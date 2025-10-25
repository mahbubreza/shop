<x-layout>
<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <div>
        <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://via.placeholder.com/400' }}" 
             alt="{{ $product->name }}" 
             class="w-full rounded shadow">
    </div>

    <div>
        <h1 class="text-3xl font-bold mb-4">{{ $product->name }}</h1>
        <p class="text-gray-700 mb-4">{{ $product->description }}</p>
        <p class="text-2xl font-semibold text-blue-600 mb-6">${{ number_format($product->price, 2) }}</p>

        <form action="{{ url('/cart/add/'.$product->id) }}" method="POST" class="flex items-center space-x-3">
            @csrf
            <input type="number" name="quantity" value="1" min="1" 
                   class="w-16 border rounded px-2 py-1">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Add to Cart
            </button>
        </form>
    </div>
</div>
</x-layout>
