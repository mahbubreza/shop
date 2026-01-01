document.addEventListener("DOMContentLoaded", function () {
    /* Cart hover */
    const cartIcon = document.querySelector(".cart-wrapper");
    if (cartIcon) {
        const cartDropdown = cartIcon.querySelector(".group-hover\\:block");
        if (cartDropdown) {
            cartIcon.addEventListener("mouseenter", () => {
                clearTimeout(cartIcon.__timer);
                cartDropdown.classList.remove("hidden");
            });
            cartIcon.addEventListener("mouseleave", () => {
                cartIcon.__timer = setTimeout(
                    () => cartDropdown.classList.add("hidden"),
                    1300
                );
            });
            cartDropdown.addEventListener("mouseenter", () =>
                clearTimeout(cartIcon.__timer)
            );
            cartDropdown.addEventListener("mouseleave", () => {
                cartIcon.__timer = setTimeout(
                    () => cartDropdown.classList.add("hidden"),
                    1300
                );
            });
        }
    }

    /* Mobile menu */
    const hamburgerBtn = document.getElementById("hamburger");
    const mobileMenu = document.querySelector(".mobile-menu");
    if (hamburgerBtn && mobileMenu) {
        hamburgerBtn.addEventListener("click", () =>
            mobileMenu.classList.toggle("hidden")
        );
    }

    /* Search toggle */
    const searchIcon = document.getElementById("search-icon");
    const searchField = document.getElementById("search-field");
    if (searchIcon && searchField) {
        searchIcon.addEventListener("click", () => {
            searchField.classList.toggle("hidden");
            searchField.classList.toggle("search-slide-down");
        });
    }

    /* Single product quantity */
    const decreaseButton = document.getElementById("decrease");
    const increaseButton = document.getElementById("increase");
    const quantityInput = document.getElementById("quantity");
    if (decreaseButton && increaseButton && quantityInput) {
        decreaseButton.addEventListener("click", () => {
            let qty = parseInt(quantityInput.value);
            if (qty > 1) quantityInput.value = qty - 1;
        });
        increaseButton.addEventListener("click", () => {
            quantityInput.value = parseInt(quantityInput.value) + 1;
        });
    }

    /* Tabs */
    const tabs = document.querySelectorAll(".tab");
    const contents = document.querySelectorAll(".tab-content");
    if (tabs.length && contents.length) {
        tabs.forEach((tab) => {
            tab.addEventListener("click", function () {
                tabs.forEach((t) => t.classList.remove("active"));
                contents.forEach((c) => c.classList.add("hidden"));
                tab.classList.add("active");
                document
                    .querySelector(`#${tab.id.replace("-tab", "-content")}`)
                    .classList.remove("hidden");
            });
        });
        tabs[0].click();
    }

    /* Shop page filter toggle */
    const toggleButton = document.getElementById("products-toggle-filters");
    const filters = document.getElementById("filters");
    if (toggleButton && filters) {
        toggleButton.addEventListener("click", function () {
            filters.classList.toggle("hidden");
            toggleButton.textContent = filters.classList.contains("hidden")
                ? "Show Filters"
                : "Hide Filters";
        });
    }

    /* Shop select arrow */
    const selectElement = document.querySelector("select");
    const arrowDown = document.getElementById("arrow-down");
    const arrowUp = document.getElementById("arrow-up");
    if (selectElement && arrowDown && arrowUp) {
        selectElement.addEventListener("click", () => {
            arrowDown.classList.toggle("hidden");
            arrowUp.classList.toggle("hidden");
        });
    }

    /* Cart increment/decrement */
    document.querySelectorAll(".cart-increment").forEach((btn) => {
        btn.addEventListener("click", () => {
            const quantityElement = btn.previousElementSibling;
            if (quantityElement)
                quantityElement.textContent =
                    parseInt(quantityElement.textContent) + 1;
        });
    });
    document.querySelectorAll(".cart-decrement").forEach((btn) => {
        btn.addEventListener("click", () => {
            const quantityElement = btn.nextElementSibling;
            if (quantityElement) {
                let qty = parseInt(quantityElement.textContent);
                if (qty > 1) quantityElement.textContent = qty - 1;
            }
        });
    });

    /* Swiper sliders */
    if (typeof Swiper !== "undefined") {
        const swiperContainers = document.querySelectorAll(
            ".swiper, .main-slider"
        );

        swiperContainers.forEach((swiperEl) => {
            const slideCount =
                swiperEl.querySelectorAll(".swiper-slide").length;

            // Determine default slidesPerView
            let slidesPerView = 1; // fallback
            if (swiperEl.classList.contains("main-slider")) {
                slidesPerView = 1; // main-slider shows 1 slide
            } else {
                slidesPerView = slideCount >= 2 ? 2 : 1; // other sliders default 2 if enough slides
            }

            // Only enable loop if there are enough slides
            const loop = slideCount >= slidesPerView * 2;

            new Swiper(swiperEl, {
                slidesPerView,
                loop,
                autoplay: {
                    delay: swiperEl.classList.contains("main-slider")
                        ? 5000
                        : 3000,
                },
                navigation: {
                    nextEl: swiperEl.querySelector(".swiper-button-next"),
                    prevEl: swiperEl.querySelector(".swiper-button-prev"),
                },
                breakpoints: {
                    1024: {
                        slidesPerView: swiperEl.classList.contains(
                            "main-slider"
                        )
                            ? 1
                            : slideCount >= 6
                            ? 6
                            : slideCount,
                    },
                },
            });
        });
    }
});
