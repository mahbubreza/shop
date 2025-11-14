@php
use Carbon\Carbon;

$now = Carbon::now();
@endphp
<x-shop.layout>
<div class="container mx-auto py-10">
    <h1 class="text-2xl font-bold mb-6">Checkout</h1>

    @if($cartItems->isEmpty())
        <p>Your cart is empty</p>
    @else
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
                @php $total = 0; @endphp
                @foreach($cartItems as $item)
                    @php
                        $product = $item->product;
                        $isDiscounted = $product->discounted_price > 0 
                            && $now->between(Carbon::parse($product->discount_start_date), Carbon::parse($product->discount_end_date));
                        $price = $isDiscounted ? $product->discounted_price : $product->price;
                        $subtotal = $price * $item->quantity;
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
                    @php $total += $subtotal; @endphp
                @endforeach
            </tbody>
        </table>

        <div class="text-right mb-6">
            <h3 class="text-xl font-semibold">Total: ${{ number_format($total, 2) }}</h3>
        </div>

        <form action="{{ route('checkout.place') }}" method="POST">
            @csrf
            <button class="bg-primary text-white px-6 py-2 rounded hover:bg-opacity-90">
                Place Order & Reserve Stock
            </button>
        </form>
    @endif
</div>
</x-shop.layout>
