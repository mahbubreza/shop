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
                    @php $subtotal = $item->product->price * $item->quantity; @endphp
                    <tr class="border-b">
                        <td class="p-3">{{ $item->product->name }}</td>
                        <td class="p-3">${{ $item->product->price }}</td>
                        <td class="p-3">{{ $item->quantity }}</td>
                        <td class="p-3">${{ $subtotal }}</td>
                    </tr>
                    @php $total += $subtotal; @endphp
                @endforeach
            </tbody>
        </table>

        <div class="text-right mb-6">
            <h3 class="text-xl font-semibold">Total: ${{ $total }}</h3>
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
