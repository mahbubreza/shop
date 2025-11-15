@php
use Carbon\Carbon;
$now = Carbon::now();
@endphp

<x-shop.layout>
<div class="container mx-auto py-10">
    <h1 class="text-2xl font-bold mb-6">Your Cart</h1>

    @if($cartItems->isEmpty())
        <p>Your cart is empty.</p>
    @else
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-3 text-left">Product</th>
                    <th class="p-3 text-left">Price</th>
                    <th class="p-3 text-left">Qty</th>
                    <th class="p-3 text-left">Subtotal</th>
                    <th class="p-3"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartItems as $item)
                    @php
                        $product = $item->product;

                        $isDiscounted =
                            $product->discounted_price > 0 &&
                            $product->discount_start_date &&
                            $product->discount_end_date &&
                            $now->between(
                                Carbon::parse($product->discount_start_date),
                                Carbon::parse($product->discount_end_date)
                            );

                        $price = $isDiscounted ? $product->discounted_price : $product->price;
                    @endphp

                    <tr class="border-b cart-row" data-id="{{ $item->id }}">
                        <td class="p-3">
                            <div class="flex items-center space-x-4">
                                <img src="{{ asset('storage/' . $product->image) }}" class="w-16 h-16 rounded">
                                <span>{{ $product->name }}</span>
                            </div>
                        </td>

                        <td class="p-3">
                            @if($isDiscounted)
                                <span class="line-through text-gray-400">${{ number_format($product->price, 2) }}</span>
                                <span class="text-red-600 font-semibold final-price"
                                    data-price="{{ $price }}">
                                    ${{ number_format($price, 2) }}
                                </span>
                            @else
                                <span class="final-price" data-price="{{ $product->price }}">
                                    ${{ number_format($product->price, 2) }}
                                </span>
                            @endif
                        </td>

                        {{-- Quantity buttons --}}
                        <td class="p-3">
                            <div class="flex items-center space-x-2">
                                <button class="decrease bg-gray-300 px-2 rounded">-</button>
                                <input 
                                    type="text"
                                    class="quantity-input w-12 text-center border rounded"
                                    value="{{ $item->quantity }}"
                                    readonly>
                                <button class="increase bg-gray-300 px-2 rounded">+</button>
                            </div>
                        </td>

                        {{-- Subtotal --}}
                        <td class="p-3">
                            <span class="row-subtotal font-semibold">
                                ${{ number_format($price * $item->quantity, 2) }}
                            </span>
                        </td>

                        {{-- Remove --}}
                        <td class="p-3 text-right">
                            <form class="remove-from-cart" data-id="{{ $item->id }}">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 border border-red-600 px-3 py-1 rounded hover:bg-red-600 hover:text-white">
                                    Remove
                                </button>
                            </form>
                        </td>
                    </tr>

                @endforeach
            </tbody>
        </table>

        <div class="mt-6 text-right">
            <a href="{{ route('checkout.index') }}" class="bg-primary text-white px-6 py-2 rounded hover:bg-opacity-90">
                Proceed to Checkout
            </a>
        </div>

    @endif
</div>
</x-shop.layout>


{{-- ======================
     JAVASCRIPT SECTION
======================= --}}
<script>
document.addEventListener("DOMContentLoaded", function () {

    /* -----------------------------
        UPDATE CART QUANTITY (AJAX)
    ------------------------------*/
    document.querySelectorAll(".cart-row").forEach(row => {
        const cartId = row.dataset.id;
        const decreaseBtn = row.querySelector(".decrease");
        const increaseBtn = row.querySelector(".increase");
        const qtyInput = row.querySelector(".quantity-input");
        const priceEl = row.querySelector(".final-price");
        const subtotalEl = row.querySelector(".row-subtotal");

        const price = parseFloat(priceEl.dataset.price);

        function updateSubtotal(qty) {
            subtotalEl.textContent = "$" + (price * qty).toFixed(2);
        }

        function updateCart(qty) {
            fetch(`/cart/update/${cartId}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ quantity: qty })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    updateSubtotal(qty);
                    refreshMiniCart();
                } else {
                    alert(data.message);
                }
            });
        }

        increaseBtn.addEventListener("click", function () {
            let qty = parseInt(qtyInput.value);
            qty++;
            qtyInput.value = qty;
            updateCart(qty);
        });

        decreaseBtn.addEventListener("click", function () {
            let qty = parseInt(qtyInput.value);
            if (qty > 1) {
                qty--;
                qtyInput.value = qty;
                updateCart(qty);
            }
        });
    });

    /* -----------------------------
        REMOVE FROM CART (AJAX)
    ------------------------------*/
    document.querySelectorAll('.remove-from-cart').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const id = this.dataset.id;

            fetch(`/cart/remove/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    this.closest('tr').remove();
                    refreshMiniCart();
                    updateCartCount(data.cart_count);
                }
            });
        });
    });

});
</script>
