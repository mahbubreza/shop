document.addEventListener("DOMContentLoaded", function () {
    const cs = window.cs || {};

    const applyBtn = document.getElementById("applyCouponBtn");
    const couponInput = document.getElementById("couponCode");
    const couponMsg = document.getElementById("couponMessage");
    const couponIdInput = document.getElementById("couponIdInput");

    applyBtn &&
        applyBtn.addEventListener("click", function (e) {
            e.preventDefault();
            couponMsg.textContent = "Checking...";
            const token = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");

            fetch("/checkout/apply-coupon", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": token,
                },
                body: JSON.stringify({
                    code: couponInput.value,
                    shipping_charge: document.getElementById(
                        "shippingChargeSelect"
                    ).value,
                    payment_method: document.getElementById(
                        "paymentMethodSelect"
                    ).value,
                }),
            })
                .then((r) => r.json())
                .then((data) => {
                    if (!data.success) {
                        couponMsg.textContent =
                            data.message || "Invalid coupon";
                        couponIdInput.value = "";
                        document.getElementById("mfsCharge").textContent =
                            (0).toFixed(2);
                        document.getElementById("vatCharge").textContent =
                            (0).toFixed(2);
                        document.getElementById("finalSubtotal").textContent =
                            data.finalSubtotal
                                ? Number(data.finalSubtotal).toFixed(2)
                                : document.getElementById("finalSubtotal")
                                      .textContent;
                        document.getElementById("grandTotal").textContent =
                            document.getElementById("grandTotal").textContent; // keep
                    } else {
                        couponMsg.textContent =
                            "Applied: " +
                            data.code +
                            " (discount: $" +
                            Number(data.discount).toFixed(2) +
                            ")";
                        couponIdInput.value = data.coupon_id;
                        document.getElementById("finalSubtotal").textContent =
                            Number(data.finalSubtotal).toFixed(2);
                        document.getElementById("shippingCharge").textContent =
                            Number(data.shipping).toFixed(2);
                        document.getElementById("mfsCharge").textContent =
                            Number(data.mfs).toFixed(2);
                        document.getElementById("vatCharge").textContent =
                            Number(data.vat).toFixed(2);
                        document.getElementById("grandTotal").textContent =
                            Number(data.grand).toFixed(2);
                    }
                })
                .catch((err) => {
                    couponMsg.textContent = "Error validating coupon";
                    couponIdInput.value = "";
                });
        });
});
