// resources/js/cart.js
// Clean, defensive cart script. Requires: Toastify (loaded in layout), CSRF meta tag, endpoints:
// GET  /cart/mini
// POST /cart/add      -> { product_id, quantity }
// POST /cart/update/{id} -> { quantity }
// POST /cart/remove/{id}

(function () {
    "use strict";

    // helper: read csrf
    function getCsrf() {
        const m = document.querySelector('meta[name="csrf-token"]');
        return m ? m.getAttribute("content") : "";
    }

    // Toast helper
    function showToast(msg, type = "success") {
        const bg =
            type === "success"
                ? "linear-gradient(to right,#4CAF50,#45A049)"
                : "linear-gradient(to right,#f44336,#e53935)";

        if (typeof Toastify === "function") {
            Toastify({
                text: msg,
                duration: 3500,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: bg,
                stopOnFocus: true,
            }).showToast();
        } else {
            console.log(`[${type}] ${msg}`);
        }
    }

    // Update both desktop & mobile counts
    function updateCartCount(count) {
        const d = document.getElementById("cart-count-desktop");
        const m = document.getElementById("cart-count-mobile");
        if (d) d.textContent = count;
        if (m) m.textContent = count;
    }

    // Build item element (used when refreshing mini cart)
    function buildMiniCartItem(item) {
        // item should have: id, name, image, price, quantity
        const li = document.createElement("li");
        li.className = "flex justify-between items-center";
        li.dataset.id = item.id;

        li.innerHTML = `
            <div class="flex items-center space-x-2">
                <img src="${
                    item.image
                }" class="w-12 h-12 object-cover rounded" alt="${escapeHtml(
            item.name
        )}">
                <div>
                    <p class="text-sm">${escapeHtml(item.name)}</p>
                    <div class="flex items-center mt-1 space-x-1">
                        <button class="decrease px-2 py-1 bg-gray-200 rounded">-</button>
                        <span class="quantity px-2">${item.quantity}</span>
                        <button class="increase px-2 py-1 bg-gray-200 rounded">+</button>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-sm font-semibold subtotal">$${(
                    item.price * item.quantity
                ).toFixed(2)}</span>
                <button class="remove text-red-500 ml-2">🗑️</button>
            </div>
        `;

        return li;
    }

    // escape HTML
    function escapeHtml(text) {
        if (!text) return "";
        return text.replace(/[&<>"'`=\/]/g, function (s) {
            return {
                "&": "&amp;",
                "<": "&lt;",
                ">": "&gt;",
                '"': "&quot;",
                "'": "&#39;",
                "/": "&#x2F;",
                "`": "&#x60;",
                "=": "&#x3D;",
            }[s];
        });
    }

    // Refresh mini cart from server
    async function refreshMiniCart() {
        try {
            const res = await fetch("/cart/mini", {
                credentials: "same-origin",
            });
            if (!res.ok) throw new Error("Failed to load cart");
            const data = await res.json();

            const itemsContainer = document.getElementById("mini-cart-items");
            const subtotalEl = document.getElementById("mini-cart-subtotal");

            if (!itemsContainer || !subtotalEl) return;

            itemsContainer.innerHTML = "";

            data.items.forEach((it) => {
                // ensure image URL present
                if (!it.image) {
                    it.image = "/storage/images/default.png";
                }
                itemsContainer.appendChild(buildMiniCartItem(it));
            });

            subtotalEl.textContent = `$${Number(data.subtotal || 0).toFixed(
                2
            )}`;
            updateCartCount(data.total_count || 0);

            attachMiniCartEvents(); // attach events to the newly created nodes
        } catch (err) {
            console.error("refreshMiniCart error", err);
        }
    }

    // Attach increment/decrement/remove handlers inside mini cart
    function attachMiniCartEvents() {
        const items = document.querySelectorAll("#mini-cart-items > li");
        items.forEach((li) => {
            const id = li.dataset.id;
            const decreaseBtn = li.querySelector(".decrease");
            const increaseBtn = li.querySelector(".increase");
            const removeBtn = li.querySelector(".remove");

            // Defensive checks
            if (decreaseBtn) {
                decreaseBtn.removeEventListener("click", handleDecrease);
                decreaseBtn.addEventListener("click", handleDecrease);
            }
            if (increaseBtn) {
                increaseBtn.removeEventListener("click", handleIncrease);
                increaseBtn.addEventListener("click", handleIncrease);
            }
            if (removeBtn) {
                removeBtn.removeEventListener("click", handleRemove);
                removeBtn.addEventListener("click", handleRemove);
            }

            // handlers reference
            function handleDecrease(e) {
                e.preventDefault();
                const qSpan = li.querySelector(".quantity");
                const qty = parseInt(qSpan.textContent || "0", 10);
                if (qty > 1) {
                    updateCartItem(id, qty - 1);
                }
            }
            function handleIncrease(e) {
                e.preventDefault();
                const qSpan = li.querySelector(".quantity");
                const qty = parseInt(qSpan.textContent || "0", 10);
                updateCartItem(id, qty + 1);
            }
            function handleRemove(e) {
                e.preventDefault();
                removeCartItem(id);
            }
        });
    }

    // Update cart item quantity (POST)
    async function updateCartItem(cartItemId, quantity) {
        try {
            const res = await fetch(`/cart/update/${cartItemId}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": getCsrf(),
                },
                body: JSON.stringify({ quantity }),
            });
            const data = await res.json();
            if (!res.ok || data.success === false) {
                showToast(data.message || "Could not update quantity", "error");
                return;
            }
            await refreshMiniCart();
            showToast("Cart updated", "success");
        } catch (err) {
            console.error(err);
            showToast("Server error", "error");
        }
    }

    // Remove cart item
    async function removeCartItem(cartItemId) {
        try {
            const res = await fetch(`/cart/remove/${cartItemId}`, {
                method: "POST",
                headers: { "X-CSRF-TOKEN": getCsrf() },
            });
            const data = await res.json();
            if (!res.ok || data.success === false) {
                showToast(data.message || "Could not remove item", "error");
                return;
            }
            await refreshMiniCart();
            showToast("Item removed", "success");
        } catch (err) {
            console.error(err);
            showToast("Server error", "error");
        }
    }

    // Add to cart handler (delegated)
    async function handleAddToCart(evt) {
        const btn = evt.currentTarget;
        const productId = btn.dataset.productId;
        const qty = Number(btn.dataset.quantity || 1);

        if (!productId) return;

        try {
            const res = await fetch("/cart/add", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": getCsrf(),
                },
                body: JSON.stringify({ product_id: productId, quantity: qty }),
            });
            const data = await res.json();
            if (!res.ok || data.success === false) {
                showToast(data.message || "Failed to add to cart", "error");
                return;
            }
            showToast(data.message || "Added to cart", "success");

            // Update mini cart & count
            if (typeof refreshMiniCart === "function") await refreshMiniCart();
            if (data.cart_count) updateCartCount(data.cart_count);
        } catch (err) {
            console.error(err);
            showToast("Server error", "error");
        }
    }

    // Pin / hover behaviour
    function setupMiniCartToggle() {
        const wrapper = document.getElementById("cart-wrapper");
        const mini = document.getElementById("mini-cart");
        const btn = document.getElementById("cart-button");
        if (!wrapper || !mini || !btn) return;

        let pinned = false;
        let hideTimeout = null;

        // show
        function show() {
            if (hideTimeout) {
                clearTimeout(hideTimeout);
                hideTimeout = null;
            }
            mini.classList.remove("hidden");
        }
        // hide
        function hide() {
            if (pinned) return;
            mini.classList.add("hidden");
        }

        // hover behaviour
        wrapper.addEventListener("mouseenter", () => show());
        wrapper.addEventListener("mouseleave", () => {
            hideTimeout = setTimeout(() => hide(), 200);
        });

        // click toggles pin state and visibility
        btn.addEventListener("click", (e) => {
            e.preventDefault();
            pinned = !pinned;
            if (pinned) {
                show();
            } else {
                // if we're not pinned, allow it to hide on next mouseleave
                hideTimeout = setTimeout(() => hide(), 200);
            }
        });

        // close when clicking outside (only if not pinned)
        document.addEventListener("click", (e) => {
            if (pinned) return;
            if (!wrapper.contains(e.target)) {
                hide();
            }
        });
    }

    // Escape hatch to show blade session flash (window.__FLASH)
    function showBladeFlash() {
        if (window.__FLASH) {
            if (window.__FLASH.success)
                showToast(window.__FLASH.success, "success");
            if (window.__FLASH.error) showToast(window.__FLASH.error, "error");
            window.__FLASH = null;
        }
    }

    // Utility: attach add-to-cart buttons (exists on product cards)
    function attachAddToCartButtons() {
        // find buttons with class 'add-to-cart' and dataset.productId
        const btns = document.querySelectorAll(".add-to-cart[data-product-id]");
        btns.forEach((b) => {
            // remove previous listeners to avoid double-binding
            b.removeEventListener("click", handleAddToCart);
            b.addEventListener("click", handleAddToCart);
        });
    }

    // DOM ready
    document.addEventListener("DOMContentLoaded", () => {
        try {
            attachAddToCartButtons();
            setupMiniCartToggle();
            refreshMiniCart();
            showBladeFlash();
        } catch (err) {
            console.error("cart.js init error", err);
        }
    });

    // also expose refreshMiniCart globally in case other scripts want to call it
    window.refreshMiniCart = refreshMiniCart;
    window.updateCartCount = updateCartCount;
})();
