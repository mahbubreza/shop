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
                    <tr class="border-b">
                        <td class="p-3">
                            <div class="flex items-center space-x-4">
                                <img src="{{ asset('storage/' . $item->product->image) }}" class="w-16 h-16 rounded">
                                <span>{{ $item->product->name }}</span>
                            </div>
                        </td>
                        <td class="p-3">${{ $item->product->price }}</td>
                        <td class="p-3">{{ $item->quantity }}</td>
                        <td class="p-3">${{ $item->product->price * $item->quantity }}</td>
                        <td class="p-3 text-right">
                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:text-red-800">Remove</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-6 text-right">
            <a href="{{ route('checkout') }}" class="bg-primary text-white px-6 py-2 rounded hover:bg-opacity-90">
                Proceed to Checkout
            </a>
        </div>
    @endif
</div>
</x-shop.layout>
