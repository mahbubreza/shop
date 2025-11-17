@php
use Carbon\Carbon;
$now = Carbon::now();
@endphp

<x-shop.layout>

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container mx-auto py-10">
    <h1 class="text-2xl font-bold mb-6">Checkout</h1>

    @if($cartItems->isEmpty())
        <p>Your cart is empty</p>
    @else

        @php
            $finalSubtotal = 0;
            foreach($cartItems as $item){
                $price = $item->product->discounted_price > 0
                    && $now->between(Carbon::parse($item->product->discount_start_date), Carbon::parse($item->product->discount_end_date))
                    ? $item->product->discounted_price
                    : $item->product->price;

                $finalSubtotal += $price * $item->quantity;
            }

            $shopConfig = config('shop');
        @endphp

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

        <!-- Coupon -->
        <div class="mb-4 flex items-center gap-3">
            <input id="couponCode" type="text" placeholder="Coupon code" class="border p-2 rounded" />
            <button id="applyCouponBtn" class="bg-blue-600 text-white px-4 py-2 rounded">Apply</button>
            <div id="couponMessage" class="text-sm"></div>
        </div>

        <!-- Shipping & Payment -->
        <div class="flex flex-col md:flex-row gap-6 mb-6">
            <div class="w-full md:w-1/2">
                <label class="block font-semibold mb-1">Shipping Charge</label>
                <select id="shippingChargeSelect" class="w-full border rounded px-3 py-2">
                    <option value="{{ $shopConfig['inside_dhaka_shipping_charge'] }}">Inside Dhaka - ৳{{ number_format($shopConfig['inside_dhaka_shipping_charge'],2) }}</option>
                    <option value="{{ $shopConfig['outside_dhaka_shipping_charge'] }}">Outside Dhaka - ৳{{ number_format($shopConfig['outside_dhaka_shipping_charge'],2) }}</option>
                </select>
            </div>

            <div class="w-full md:w-1/2">
                <label class="block font-semibold mb-1">Payment Method</label>
                <select id="paymentMethodSelect" class="w-full border rounded px-3 py-2">
                    <option value="cod">Cash on Delivery</option>
                    <option value="bkash">bKash</option>
                    <option value="nagad">Nagad</option>
                    <option value="rocket">Rocket</option>
                </select>
            </div>
        </div>

        <!-- Summary -->
        <div class="mt-6 text-right space-y-2">
            <h3 class="text-lg font-semibold">
                Final Subtotal: ৳<span id="finalSubtotal">{{ number_format($finalSubtotal, 2) }}</span>
            </h3>

            <h3 class="text-lg font-semibold">
                Shipping Charge: ৳<span id="shippingCharge">{{ number_format($shopConfig['inside_dhaka_shipping_charge'], 2) }}</span>
            </h3>

            @if($shopConfig['mfs_charge'])
                <h3 class="text-lg font-semibold">
                    MFS Charge ({{ $shopConfig['mfs_percentage'] }}%): ৳<span id="mfsCharge">0.00</span>
                </h3>
            @endif

            @if($shopConfig['vat_applicable'])
                <h3 class="text-lg font-semibold">
                    VAT ({{ $shopConfig['vat_percent'] }}%): ৳<span id="vatCharge">0.00</span>
                </h3>
            @endif

            <h3 class="text-lg font-semibold text-red-600">
                Coupon Discount: -৳<span id="couponDiscount">0.00</span>
            </h3>

            <h3 class="text-xl font-bold">
                Total: ৳<span id="grandTotal">0.00</span>
            </h3>
        </div>

        <!-- Submit -->
        <form action="{{ route('checkout.place') }}" method="POST" class="mt-6">
            @csrf

            <input type="hidden" name="coupon_id" id="couponIdInput">
            <input type="hidden" name="coupon_code" id="couponCodeInput">
            <input type="hidden" name="coupon_discount" id="couponDiscountInput" value="0">

            <input type="hidden" name="shipping_charge" id="shippingChargeInput" value="{{ $shopConfig['inside_dhaka_shipping_charge'] }}">
            <input type="hidden" name="payment_method" id="paymentMethodInput" value="cod">

            <button type="submit" class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full">
                Place Order
            </button>
        </form>

    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    const finalSubtotal = parseFloat("{{ $finalSubtotal }}");

    const couponDiscountText = document.getElementById('couponDiscount');
    const couponMessage = document.getElementById('couponMessage');
    const couponIdInput = document.getElementById('couponIdInput');
    const couponCodeInput = document.getElementById('couponCodeInput');
    const couponDiscountInput = document.getElementById('couponDiscountInput');

    const shippingSelect = document.getElementById('shippingChargeSelect');
    const paymentSelect = document.getElementById('paymentMethodSelect');

    const shippingText = document.getElementById('shippingCharge');
    const mfsChargeText = document.getElementById('mfsCharge');
    const vatChargeText = document.getElementById('vatCharge');
    const grandTotalText = document.getElementById('grandTotal');

    const mfsApplicable = {{ $shopConfig['mfs_charge'] ? 'true' : 'false' }};
    const mfsPercent = {{ $shopConfig['mfs_percentage'] }};
    const vatApplicable = {{ $shopConfig['vat_applicable'] ? 'true' : 'false' }};
    const vatPercent = {{ $shopConfig['vat_percent'] }};

    function calculateTotals(discount = 0) {
        const shipping = parseFloat(shippingSelect.value);
        const payment = paymentSelect.value;

        shippingText.textContent = shipping.toFixed(2);

        let mfs = 0;
        let vat = 0;

        if(mfsApplicable && payment !== 'cod'){
            mfs = (finalSubtotal + shipping - discount) * mfsPercent / 100;
        }
        mfsChargeText.textContent = mfs.toFixed(2);

        if(vatApplicable){
            vat = (finalSubtotal + shipping - discount) * vatPercent / 100;
        }
        vatChargeText.textContent = vat.toFixed(2);

        const total = finalSubtotal + shipping - discount + mfs + vat;
        grandTotalText.textContent = total.toFixed(2);
    }

    calculateTotals(0);

    shippingSelect.addEventListener('change', () => {
        document.getElementById("shippingChargeInput").value = shippingSelect.value;
        calculateTotals(parseFloat(couponDiscountInput.value || 0));
    });
    paymentSelect.addEventListener('change', () => {
        document.getElementById("paymentMethodInput").value = paymentSelect.value;
        calculateTotals(parseFloat(couponDiscountInput.value || 0));
    });

    document.getElementById('applyCouponBtn').addEventListener('click', function() {
        couponMessage.textContent = "Checking...";

        const code = document.getElementById('couponCode').value.trim();

        fetch('{{ route("checkout.applyCoupon") }}', {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector("meta[name='csrf-token']").getAttribute("content")
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
                couponMessage.textContent = data.message;
                couponMessage.className = "text-red-600";

                couponIdInput.value = "";
                couponCodeInput.value = "";
                couponDiscountText.textContent = "0.00";
                couponDiscountInput.value = 0;

                calculateTotals(0);
                return;
            }

            couponMessage.textContent = "Applied: " + data.code;
            couponMessage.className = "text-green-600";

            couponIdInput.value = data.coupon_id;
            couponCodeInput.value = data.code;
            couponDiscountText.textContent = Number(data.discount).toFixed(2);
            couponDiscountInput.value = Number(data.discount).toFixed(2);
            couponIdInput.value = data.coupon_id;


            calculateTotals(data.discount);
        });
    });

});
</script>

</x-shop.layout>
