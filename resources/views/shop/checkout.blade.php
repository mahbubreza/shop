@php
use Carbon\Carbon;

$now = Carbon::now();
$shopConfig = config('shop');
@endphp

<x-shop.layout>
<div class="container mx-auto py-10">
    <h1 class="text-2xl font-bold mb-6">Checkout</h1>

    @if($cartItems->isEmpty())
        <p>Your cart is empty</p>
    @else
        @php
            $finalSubtotal = 0;
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
                        $finalSubtotal += $subtotal;
                    @endphp
                    <tr class="border-b">
                        <td class="p-3">{{ $product->name }}</td>
                        <td class="p-3">
                            @if($isDiscounted)
                                <span class="line-through text-gray-400">${{ number_format($product->price, 2) }}</span>
                                <span class="text-red-600 font-semibold">${{ number_format($price, 2) }}</span>
                            @else
                                ${{ number_format($price, 2) }}
                            @endif
                        </td>
                        <td class="p-3">{{ $item->quantity }}</td>
                        <td class="p-3">${{ number_format($subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Checkout Form -->
        <form action="{{ route('checkout.place') }}" method="POST" 
              class="bg-white shadow rounded p-6 max-w-6xl mx-auto">
            @csrf

            <div class="flex flex-col md:flex-row gap-6">

                <!-- LEFT: Shipping Address -->
                <div class="w-full md:w-1/2">
                    <label class="block font-semibold mb-1">Shipping Address</label>
                    <textarea name="shipping_address" rows="6"
                              class="w-full border rounded px-3 py-2" required></textarea>
                </div>

                <!-- RIGHT: Payment Method + Delivery -->
                <div class="w-full md:w-1/2 space-y-4">

                    <div>
                        <label class="block font-semibold mb-1">Payment Method</label>
                        <select name="payment_method" id="paymentMethodSelect"
                                class="w-full border rounded px-3 py-2" required>
                            <option value="cod">Cash on Delivery</option>
                            <option value="bkash">bKash</option>
                            <option value="nagad">Nagad</option>
                            <option value="rocket">Rocket</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold mb-1">Delivery in Dhaka?</label>
                        <select name="shipping_charge" id="shippingChargeSelect"
                                class="w-full border rounded px-3 py-2" required>
                            <option value="{{ $shopConfig['inside_dhaka_shipping_charge'] }}">Inside Dhaka</option>
                            <option value="{{ $shopConfig['outside_dhaka_shipping_charge'] }}">Outside Dhaka</option>
                        </select>
                    </div>

                </div>

            </div>

            <!-- Summary -->
            <div class="mt-6 text-right space-y-2">
                <h3 class="text-lg font-semibold">
                    Final Subtotal: $<span id="finalSubtotal">{{ number_format($finalSubtotal, 2) }}</span>
                </h3>
                <h3 class="text-lg font-semibold">
                    Shipping Charge: $<span id="shippingCharge">{{ number_format($shopConfig['inside_dhaka_shipping_charge'], 2) }}</span>
                </h3>

                @if($shopConfig['mfs_charge'])
                    <h3 class="text-lg font-semibold">
                        MFS Charge ({{ $shopConfig['mfs_percentage'] }}%): $<span id="mfsCharge">0.00</span>
                    </h3>
                @endif

                @if($shopConfig['vat_applicable'])
                    <h3 class="text-lg font-semibold">
                        VAT ({{ $shopConfig['vat_percent'] }}%): $<span id="vatCharge">0.00</span>
                    </h3>
                @endif

                <h3 class="text-xl font-bold">
                    Total: $<span id="grandTotal">0.00</span>
                </h3>
            </div>

            <div class="mt-6">
                <button type="submit"
                        class="bg-primary text-white px-6 py-2 rounded hover:bg-opacity-90">
                    Place Order
                </button>
            </div>
        </form>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    const finalSubtotal = parseFloat("{{ $finalSubtotal }}");
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

    function calculateTotal() {
        const shipping = parseFloat(shippingSelect.value);
        shippingText.textContent = shipping.toFixed(2);

        const paymentMethod = paymentSelect.value;

        let mfs = 0;
        let vat = 0;

        // MFS only applies for digital payment
        if(mfsApplicable && paymentMethod !== 'cod') {
            mfs = (finalSubtotal + shipping) * mfsPercent / 100;
            mfsChargeText.textContent = mfs.toFixed(2);
        } else {
            mfsChargeText.textContent = '0.00';
        }

        if(vatApplicable) {
            vat = (finalSubtotal + shipping) * vatPercent / 100;
            vatChargeText.textContent = vat.toFixed(2);
        }

        const grandTotal = finalSubtotal + shipping + mfs + vat;
        grandTotalText.textContent = grandTotal.toFixed(2);
    }

    // Initial calculation
    calculateTotal();

    // Update on shipping or payment change
    shippingSelect.addEventListener('change', calculateTotal);
    paymentSelect.addEventListener('change', calculateTotal);
});
</script>

</x-shop.layout>
