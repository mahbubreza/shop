<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            All Products
        </h2>
    </x-slot>

    <x-slot name="button">
        <a href="/products/create" 
        class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white border border-green-600 rounded-md font-semibold text-xs uppercase tracking-widest shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
        + Add Product
        </a>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 mb-6 flex flex-wrap gap-4">
                <div>
                    <select class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-md text-sm">
                        <option>Sort By</option>
                        <option>Name</option>
                        <option>Price</option>
                        <option>Stock</option>
                    </select>
                </div>
                <div class="ml-auto">
                    <input type="text" placeholder="Search products..."
                           class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-md text-sm px-3 py-2 w-56 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            <!-- Products Table -->
            <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded-lg">
                <table class="min-w-full text-sm text-gray-700 dark:text-gray-300">
                    <thead class="bg-gray-100 dark:bg-gray-700 border-b dark:border-gray-600">
                        <tr>
                            <th class="px-4 py-3 text-left">Name</th>
                            <th class="px-4 py-3 text-left">Category</th>
                            <th class="px-4 py-3 text-left">Brand</th>
                            <th class="px-4 py-3 text-left">Price</th>
                            <th class="px-4 py-3 text-left">Total Stock</th>
                            <th class="px-4 py-3 text-center">Published</th>
                            <th class="px-4 py-3 text-center">Featured</th>
                            <th class="px-4 py-3 text-center">Options</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y dark:divide-gray-700">
                        @forelse($products as $product)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <td class="px-4 py-3 flex items-center gap-3">
                                    <img src="{{ asset('storage/'.$product->image) }}" alt="" class="w-10 h-10 object-cover rounded">
                                    <span class="font-medium text-gray-800 dark:text-gray-100">{{ $product->name }}</span>
                                </td>

                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $product->category->name }}</td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $product->brand->name }}</td>

                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                                    <div>Num of Sale: <span class="font-semibold">{{ rand(1,20) }} times</span></div>
                                    <div>Base Price: <span class="font-semibold">${{ number_format($product->price, 2) }}</span></div>
                                    <div>Rating: <span class="font-semibold">{{ rand(3,5) }}</span></div>
                                </td>

                                <td class="px-4 py-3">
                                    @if($product->stock > 10)
                                        <span class="text-xs px-2 py-1 rounded bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300">In Stock</span>
                                    @elseif($product->stock > 0)
                                        <span class="text-xs px-2 py-1 rounded bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-300">Low</span>
                                    @else
                                        <span class="text-xs px-2 py-1 rounded bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300">Out</span>
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-center">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" 
                                            class="sr-only peer toggle-checkbox"
                                            data-id="{{ $product->id }}" 
                                            data-field="published"
                                            {{ $product->published == 1 ? 'checked' : '' }}>
                                        <div class="w-10 h-5 bg-gray-300 peer-checked:bg-green-500 rounded-full transition dark:bg-gray-600"></div>
                                    </label>
                                </td>

                                <td class="px-4 py-3 text-center">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" 
                                            class="sr-only peer toggle-checkbox"
                                            data-id="{{ $product->id }}" 
                                            data-field="featured"
                                            {{ $product->featured == 1? 'checked' : '' }}>
                                        <div class="w-10 h-5 bg-gray-300 peer-checked:bg-indigo-500 rounded-full transition dark:bg-gray-600"></div>
                                    </label>
                                </td>

                                <td class="px-4 py-3 text-center">
                                    <div class="flex justify-center gap-3">
                                        <a href="products/{{ $product->id }}" class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="products/{{ $product->id }}/edit" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="products/{{ $product->id }}" method="POST" onsubmit="return confirm('Delete this product?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-6 text-gray-500 dark:text-gray-400">No products found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div>
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Toggle script -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.toggle-checkbox').forEach((checkbox) => {
            checkbox.addEventListener('change', function () {
                const productId = this.dataset.id;
                const field = this.dataset.field;
                const value = this.checked ? 1 : 0;

                fetch(`/products/${productId}/toggle`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ field, value }),
                })
                .then(res => res.json())
                .then(data => {
                    if (!data.success) {
                        alert('Failed to update field.');
                    }
                })
                .catch(() => alert('Something went wrong!'));
            });
        });
    });
    </script>
</x-app-layout>
