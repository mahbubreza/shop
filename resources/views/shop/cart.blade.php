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
                        $isDiscounted = $product->discounted_price > 0 
                        && $now->between(Carbon::parse($product->discount_start_date), Carbon::parse($product->discount_end_date));
                        
                        $price = $isDiscounted ? $product->discounted_price : $product->price;
                       
                    @endphp
                    <tr class="border-b">
                        <td class="p-3">
                            <div class="flex items-center space-x-4">
                                <img src="{{ asset('storage/' . $product->image) }}" class="w-16 h-16 rounded">
                                <span>{{ $product->name }}</span>
                            </div>
                        </td>
                        <td class="p-3">
                            @if($isDiscounted)
                                <span class="line-through text-gray-400">${{ number_format($product->price, 2) }}</span>
                                <span class="text-red-600 font-semibold">${{ number_format($price, 2) }}</span>
                            @else
                                ${{ number_format($product->price, 2) }}
                            @endif
                        </td>
                        <td class="p-3">{{ $item->quantity }}</td>
                        <td class="p-3">
                            ${{ $price*$item->quantity }}
                        </td>
                        <td class="p-3 text-right">
                            {{-- <form action="{{ route('cart.remove', $item->id) }}" method="POST"> --}}
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
<script>
document.querySelectorAll('.remove-from-cart').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = this.dataset.id;

        fetch(`/cart/remove/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success){
                // Remove row
                this.closest('tr').remove();
                //updateCartCount(data.cart_count);
                refreshMiniCart();
                if (data.cart_count) {
                    updateCartCount(data.cart_count);
                }
            }
        })
        .catch(err => console.error(err));
    });
});

</script>
