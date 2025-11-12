<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            All Products
        </h2>
    </x-slot>

    <x-slot name="button">
        <a href="{{ route('products.create') }}"
           class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white border border-green-600 rounded-md font-semibold text-xs uppercase tracking-widest shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
            + Add Product
        </a>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters -->
            <form method="GET" action="{{ route('products') }}">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 mb-6 flex flex-wrap gap-4">
                    <div>
                        <select name="sort_by" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-md text-sm">
                            <option value="">Sort By</option>
                            <option value="name" {{ request('sort_by')=='name' ? 'selected' : '' }}>Name</option>
                            <option value="price" {{ request('sort_by')=='price' ? 'selected' : '' }}>Price</option>
                            <option value="stock" {{ request('sort_by')=='stock' ? 'selected' : '' }}>Stock</option>
                        </select>
                    </div>
                    <div>
                        <select name="category" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-md text-sm">
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category')==$category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select name="brand" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-md text-sm">
                            <option value="">-- Select Brand --</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ request('brand')==$brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="ml-auto">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..."
                            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-md text-sm px-3 py-2 w-56 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 text-xs font-semibold">
                            Apply
                        </button>
                        <a href="{{ route('products') }}"
                           class="px-4 py-2 bg-gray-300 dark:bg-gray-700 dark:text-gray-200 rounded text-xs font-semibold">
                            Reset
                        </a>
                    </div>
                </div>
            </form>

            <!-- Products Table -->
            <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded-lg">
                <table class="min-w-full text-sm text-gray-700 dark:text-gray-300">
                    <thead class="bg-gray-100 dark:bg-gray-700 border-b dark:border-gray-600">
                        <tr>
                            <th class="px-4 py-3 text-left">Name</th>
                            <th class="px-4 py-3 text-left">Category</th>
                            <th class="px-4 py-3 text-left">Brand</th>
                            <th class="px-4 py-3 text-left">Product Details</th>
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

                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $product->category->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $product->brand->name ?? '-' }}</td>

                                <!-- ✅ Combined Column -->
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300 leading-6">
                                    <div>Num of Sale: <span class="font-semibold">{{ $product->num_of_sale ?? rand(5,20) }} times</span></div>
                                    <div>Base Price: <span class="font-semibold">${{ number_format($product->price, 2) }}</span></div>
                                    <div>Rating:
                                        @php
                                            $avgRating = round($product->ratings_avg_rating ?? 0, 1);
                                        @endphp

                                        @if($avgRating > 0)
                                            <span class="font-semibold">{{ $avgRating }}</span>
                                            <span class="text-yellow-400 ml-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fa{{ $i <= $avgRating ? 's' : 'r' }} fa-star"></i>
                                                @endfor
                                            </span>
                                        @else
                                            <span class="text-gray-400">No Rating</span>
                                        @endif
                                    </div>

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
                                            {{ $product->published ? 'checked' : '' }}>
                                        <div class="w-10 h-5 bg-gray-300 peer-checked:bg-green-500 rounded-full transition dark:bg-gray-600"></div>
                                    </label>
                                </td>

                                <td class="px-4 py-3 text-center">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" 
                                            class="sr-only peer toggle-checkbox"
                                            data-id="{{ $product->id }}" 
                                            data-field="featured"
                                            {{ $product->featured ? 'checked' : '' }}>
                                        <div class="w-10 h-5 bg-gray-300 peer-checked:bg-indigo-500 rounded-full transition dark:bg-gray-600"></div>
                                    </label>
                                </td>

                                <td class="px-4 py-3 text-center">
                                    <div class="flex justify-center gap-3">
                                        <a href="/products/{{$product->id}}" class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300"><i class="fas fa-eye"></i></a>
                                        <a href="/products/{{$product->id}}/edit" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300"><i class="fas fa-edit"></i></a>
                                        <form  method="POST" onsubmit="return confirm('Delete this product?')" class="inline">
                                            @csrf @method('DELETE')
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

                <div class="px-4 py-3">
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
                        if (!data.success) alert('Failed to update field.');
                    })
                    .catch(() => alert('Something went wrong!'));
                });
            });
        });
    </script>
</x-app-layout>
