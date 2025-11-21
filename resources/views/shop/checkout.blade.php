@php
use Carbon\Carbon;
$now = Carbon::now();
$finalSubtotal = 0;
foreach($cartItems as $item){
    $product = $item->product;
    $isDiscounted = $product->discounted_price > 0
        && $now->between(
            Carbon::parse($product->discount_start_date),
            Carbon::parse($product->discount_end_date)
        );
    $price = $isDiscounted ? $product->discounted_price : $product->price;
    $finalSubtotal += $price * $item->quantity;
}
$shopConfig = config('shop');
@endphp

<x-shop.layout>
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container mx-auto py-10">
    <h1 class="text-2xl font-bold mb-6">Checkout</h1>

    @if($cartItems->isEmpty())
        <p>Your cart is empty</p>
    @else
        <!-- CART TABLE -->
        <table class="w-full border-collapse mb-6">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-3 text-left">Product</th>
                    <th class="p-3 text-left">Price</th>
                    <th class="p-3 text-left">Qty</th>
                    <th class="p-3 text-left">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartItems as $item)
                    @php
                        $product = $item->product;
                        $isDiscounted = $product->discounted_price > 0
                            && $now->between(
                                Carbon::parse($product->discount_start_date),
                                Carbon::parse($product->discount_end_date)
                            );
                        $price = $isDiscounted ? $product->discounted_price : $product->price;
                        $subtotal = $price * $item->quantity;
                    @endphp
                    <tr class="border-b">
                        <td class="p-3">{{ $product->name }}</td>
                        <td class="p-3">৳{{ number_format($price, 2) }}</td>
                        <td class="p-3">{{ $item->quantity }}</td>
                        <td class="p-3">৳{{ number_format($subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- USER INPUTS -->
        <div class="flex flex-col md:flex-row gap-6 mb-6">
            <!-- Mobile -->
            <div class="w-full md:w-1/2">
                <label class="block font-semibold mb-1">
                    Mobile Number <span class="text-red-600">*</span>
                </label>
                <input type="text" id="mobileNumber" name="mobile_number"
                       class="w-full border rounded px-3 py-2"
                       value="{{ Auth::user()->mobile_number ?? '' }}"
                       placeholder="01XXXXXXXXX" required>
                @error('mobile_number')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Coupon -->
            <div class="w-full md:w-1/2 flex items-center gap-3">
                <label class="font-semibold">Coupon</label>
                <input id="couponCode" type="text" class="border p-2 rounded" placeholder="Coupon code">
                <button type="button" id="applyCouponBtn" class="bg-blue-600 text-white px-4 py-2 rounded">Apply</button>
                <div id="couponMessage" class="text-sm"></div>
            </div>
        </div>

        <!-- SHIPPING ADDRESS -->
        <div class="mb-6">
            <label class="block font-semibold mb-1">Shipping Address <span class="text-red-600">*</span></label>
            <textarea id="shippingAddress" rows="3" class="w-full border rounded px-3 py-2"></textarea>
            @error('shipping_address')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- SHIPPING + PAYMENT -->
        <div class="flex flex-col md:flex-row gap-6 mb-6">
            <div class="w-full md:w-1/2">
                <label class="block font-semibold mb-1">Shipping Charge</label>
                <select id="shippingChargeSelect" class="w-full border rounded px-3 py-2">
                    <option value="{{ $shopConfig['inside_dhaka_shipping_charge'] }}">
                        Inside Dhaka — ৳{{ number_format($shopConfig['inside_dhaka_shipping_charge'], 2) }}
                    </option>
                    <option value="{{ $shopConfig['outside_dhaka_shipping_charge'] }}">
                        Outside Dhaka — ৳{{ number_format($shopConfig['outside_dhaka_shipping_charge'], 2) }}
                    </option>
                </select>
            </div>
            <div class="w-full md:w-1/2">
                <label class="block font-semibold mb-1">Payment Method</label>
                <select id="paymentMethodSelect" class="w-full border rounded px-3 py-2">
                    <option value="cod">Cash on Delivery</option>
                    @if ($shopConfig['bkash_applicable'])
                    <option value="bkash">bKash</option>
                    @endif
                     @if ($shopConfig['nagad_applicable'])
                        <option value="nagad">Nagad</option>
                    @endif
                     @if ($shopConfig['rocket_applicable'])
                    <option value="rocket">Rocket</option>
                    @endif
                </select>
            </div>
        </div>

        <!-- SUMMARY -->
        <div class="mt-6 text-right space-y-2">
            <h3 class="text-lg font-semibold">Subtotal: ৳<span id="finalSubtotal">{{ number_format($finalSubtotal, 2) }}</span></h3>
            <h3 class="text-lg font-semibold">Shipping: ৳<span id="shippingCharge">0.00</span></h3>
            @if($shopConfig['mfs_charge'])
            <h3 class="text-lg font-semibold">MFS Charge: ৳<span id="mfsCharge">0.00</span></h3>
            @endif
            @if($shopConfig['vat_applicable'])
            <h3 class="text-lg font-semibold">VAT: ৳<span id="vatCharge">0.00</span></h3>
            @endif
            <h3 class="text-lg font-semibold text-red-600">
                Discount: -৳<span id="couponDiscount">0.00</span>
            </h3>
            <h3 class="text-xl font-bold">Total: ৳<span id="grandTotal">0.00</span></h3>
        </div>

        <!-- FORM SUBMIT -->
        <form action="{{ route('checkout.place') }}" method="POST" class="mt-6" id="checkoutForm">
            @csrf
            <input type="hidden" name="coupon_id" id="couponIdInput">
            <input type="hidden" name="coupon_code" id="couponCodeInput">
            <input type="hidden" name="coupon_discount" id="couponDiscountInput" value="0">
            <input type="hidden" name="shipping_charge" id="shippingChargeInput">
            <input type="hidden" name="payment_method" id="paymentMethodInput" value="cod">
            <input type="hidden" name="shipping_address" id="shippingAddressInput">
            <input type="hidden" name="mobile_number" id="mobileNumberInput">

            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-full font-semibold">
                Place Order
            </button>
        </form>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const finalSubtotal = parseFloat("{{ $finalSubtotal }}");
    const shippingSelect     = document.getElementById("shippingChargeSelect");
    const paymentSelect      = document.getElementById("paymentMethodSelect");
    const shippingText       = document.getElementById("shippingCharge");
    const mfsText            = document.getElementById("mfsCharge");
    const vatText            = document.getElementById("vatCharge");
    const couponText         = document.getElementById("couponDiscount");
    const grandText          = document.getElementById("grandTotal");
    const couponInput        = document.getElementById("couponDiscountInput");
    const mfsApplicable      = {{ $shopConfig['mfs_charge'] ? 'true' : 'false' }};
    const mfsPercent         = {{ $shopConfig['mfs_percentage'] }};
    const vatApplicable      = {{ $shopConfig['vat_applicable'] ? 'true' : 'false' }};
    const vatPercent         = {{ $shopConfig['vat_percent'] }};

    function calculateTotals(discount = 0) {
        const shipping = parseFloat(shippingSelect.value);
        const payment = paymentSelect.value;
        shippingText.textContent = shipping.toFixed(2);
        let mfs = 0;
        if(mfsApplicable && payment !== 'cod') mfs = (finalSubtotal + shipping - discount) * (mfsPercent / 100);
        mfsText.textContent = mfs.toFixed(2);
        let vat = 0;
        if(vatApplicable) vat = (finalSubtotal + shipping - discount) * (vatPercent / 100);
        vatText.textContent = vat.toFixed(2);
        const total = finalSubtotal + shipping - discount + mfs + vat;
        grandText.textContent = total.toFixed(2);
    }

    calculateTotals(0);

    shippingSelect.addEventListener("change", () => {
        document.getElementById("shippingChargeInput").value = shippingSelect.value;
        calculateTotals(parseFloat(couponInput.value || 0));
    });
    paymentSelect.addEventListener("change", () => {
        document.getElementById("paymentMethodInput").value = paymentSelect.value;
        calculateTotals(parseFloat(couponInput.value || 0));
    });

    // APPLY COUPON
    document.getElementById("applyCouponBtn").addEventListener("click", function () {
        const code = document.getElementById("couponCode").value.trim();
        const msg  = document.getElementById("couponMessage");
        msg.textContent = "Checking...";
        fetch("{{ route('checkout.applyCoupon') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector("meta[name='csrf-token']").content
            },
            body: JSON.stringify({
                code: code,
                shipping_charge: shippingSelect.value,
                payment_method: paymentSelect.value
            })
        })
        .then(r => r.json())
        .then(data => {
            if(!data.success){
                msg.textContent = data.message;
                msg.className = "text-red-600";
                couponText.textContent = "0.00";
                couponInput.value = 0;
                calculateTotals(0);
                return;
            }
            msg.textContent = "Applied: " + data.code;
            msg.className = "text-green-600";
            couponText.textContent = Number(data.discount).toFixed(2);
            couponInput.value = Number(data.discount).toFixed(2);
            document.getElementById("couponIdInput").value = data.coupon_id;
            document.getElementById("couponCodeInput").value = data.code;
            calculateTotals(data.discount);
        });
    });

    // AUTO-FILL ON SUBMIT
    document.getElementById("checkoutForm").addEventListener("submit", function () {
        document.getElementById("shippingAddressInput").value = document.getElementById("shippingAddress").value;
        document.getElementById("mobileNumberInput").value = document.getElementById("mobileNumber").value;
        document.getElementById("shippingChargeInput").value = shippingSelect.value;
        document.getElementById("paymentMethodInput").value = paymentSelect.value;
    });
});
</script>
</x-shop.layout>
