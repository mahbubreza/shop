<x-layout>
    <h1 class="text-2xl font-bold mb-4">Products</h1>

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    @foreach ($products as $product)
        <div class="bg-white rounded-lg shadow p-4 flex flex-col">
            <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://via.placeholder.com/200' }}" 
                 alt="{{ $product->name }}" 
                 class="h-40 w-full object-cover rounded mb-4">
            
            <h2 class="font-semibold text-lg">{{ $product->name }}</h2>
            <p class="text-sm text-gray-600 mb-2">{{ Str::limit($product->description, 50) }}</p>
            <span class="font-bold text-blue-600">${{ number_format($product->price, 2) }}</span>

            <a href="{{ route('products.show', $product) }}" 
               class="mt-auto bg-blue-500 text-white px-3 py-1 rounded text-center hover:bg-blue-600">
               View
            </a>
        </div>
    @endforeach
</div>

<div class="mt-6">
    {{ $products->links() }}
</div>
</x-layout>