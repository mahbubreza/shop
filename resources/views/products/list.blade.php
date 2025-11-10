<x-shop.layout>
    <!-- Shop -->
    <section id="shop">
        <div class="container mx-auto">
            <!-- Top Filter -->
            <div class="flex flex-col md:flex-row justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('products.list', array_merge(request()->query(), ['on_sale' => 1])) }}"
                        class="bg-primary text-white hover:bg-transparent hover:text-primary border hover:border-primary py-2 px-4 rounded-full focus:outline-none">
                        Show On Sale
                    </a>
                    {{-- <button
                        class="bg-primary text-white hover:bg-transparent hover:text-primary border hover:border-primary py-2 px-4 rounded-full focus:outline-none">List
                        View</button>
                    <button
                        class="bg-primary text-white hover:bg-transparent hover:text-primary border hover:border-primary py-2 px-4 rounded-full focus:outline-none">Grid
                        View</button> --}}
                </div>
                <div class="flex mt-5 md:mt-0 space-x-4">
                    <form method="GET" action="{{ route('products.list') }}">
                        <select name="sort" onchange="this.form.submit()"
                            class="block appearance-none w-full bg-white border hover:border-primary px-4 py-2 pr-8 rounded-full shadow leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Sort by</option>
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Sort by Latest</option>
                            <option value="popularity" {{ request('sort') == 'popularity' ? 'selected' : '' }}>Sort by Popularity</option>
                            <option value="az" {{ request('sort') == 'az' ? 'selected' : '' }}>Sort by A-Z</option>
                        </select>
                    </form>
                </div>
            </div>
            <!-- Filter Toggle Button for Mobile -->
            <div class="block md:hidden text-center mb-4">
                <button id="products-toggle-filters"
                    class="bg-primary text-white py-2 px-4 rounded-full focus:outline-none">Show Filters</button>
            </div>
            <div class="flex flex-col md:flex-row">
                
                <form id="filterForm" method="GET" action="{{ route('products.list') }}">
                    <!-- dynamic filters via JS -->
                </form>
                
                <!-- Filters -->
                <div id="filters" class="w-full md:w-1/4 p-4 hidden md:block">
                    <!-- Category Filter -->
                    <div class="mb-6 pb-8 border-b border-gray-line">
                        <h3 class="text-lg font-semibold mb-6">Category l2l</h3>
                        <div class="space-y-2">
                            @foreach ($categories as $category)
                                <label class="flex items-center">
                                    <input type="radio" name="category" value="{{ $category->id }}"
                                        onchange="filterProducts()"
                                        {{ request('category') == $category->id ? 'checked' : '' }}>
                                    <span class="ml-2">{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Size Filter -->
                    <div class="mb-6 pb-8 border-b border-gray-line">
                        <h3 class="text-lg font-semibold mb-6">Size</h3>
                        <div class="space-y-2">
                            @foreach ($sizes as $size)
                                <label class="flex items-center">
                                    <input type="radio" name="size" value="{{ $size->id }}"
                                        onchange="filterProducts()"
                                        {{ request('size') == $size->id ? 'checked' : '' }}>
                                    <span class="ml-2">{{ $size->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Color Filter -->
                    <div class="mb-6 pb-8 border-b border-gray-line">
                        <h3 class="text-lg font-semibold mb-6">Color</h3>
                        <div class="space-y-2">
                            @foreach ($colors as $color)
                                <label class="flex items-center">
                                    <input type="radio" name="color" value="{{ $color->id }}"
                                        onchange="filterProducts()"
                                        {{ request('color') == $color->id ? 'checked' : '' }}>
                                    <span class="ml-2">{{ $color->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Brand Filter -->
                    <div class="mb-6 pb-8 border-b border-gray-line">
                        <h3 class="text-lg font-semibold mb-6">Brand</h3>
                        <div class="space-y-2">
                            @foreach ($brands as $brand)
                                <label class="flex items-center">
                                    <input type="radio" name="brand" value="{{ $brand->id }}"
                                        onchange="filterProducts()"
                                        {{ request('brand') == $brand->id ? 'checked' : '' }}>
                                    <span class="ml-2">{{ $brand->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Rating Filter -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-6">Rating</h3>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" class="form-checkbox custom-checkbox">
                                <span class="ml-2">★★★★★</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="form-checkbox custom-checkbox">
                                <span class="ml-2">★★★★☆</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="form-checkbox custom-checkbox">
                                <span class="ml-2">★★★☆☆</span>
                            </label>
                        </div>
                    </div>
                </div>
                <!-- Products List -->
                <div class="w-full md:w-3/4 p-4">
                    <!-- Products grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Product 1 -->
                        @isset($products)
                        @foreach ($products as $product)
                        <div class="bg-white p-4 rounded-lg shadow">
                        <img  src="{{ asset('storage/' . $product->image) }}" alt="{{$product->name}}"
                            class="w-full object-cover mb-4 rounded-lg">
                        <a href="/products/{{$product->id}}/details" class="text-lg font-semibold mb-2">{{$product->name}}</a>
                        <p class=" my-2">{{$product->category->name}}</p>
                        <div class="flex items-center mb-4">
                            @php
                                $hasDiscount = $product->discounted_price > 0 &&
                                            $now->between(Carbon\Carbon::parse($product->discount_start_date), Carbon\Carbon::parse($product->discount_end_date));
                            @endphp

                            @if ($hasDiscount)
                                <span class="text-lg font-bold text-primary">${{ $product->discounted_price }}</span>
                                <span class="text-sm line-through ml-2 text-gray-500">${{ $product->price }}</span>
                            @else
                                <span class="text-lg font-bold text-primary">${{ $product->price }}</span>
                            @endif
                        </div>

                        <button
                            class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full w-full"
                        >
                            Add to Cart
                        </button>
                        </div>
                        @endforeach
                            
                        @endisset
                        
                        
                    </div>
                    <!-- Pagination -->
                    <div class="flex justify-center mt-8">
                        <nav aria-label="Page navigation">
                            <ul class="inline-flex space-x-2">
                                <li>
                                    <a href="#"
                                        class="bg-primary text-white w-10 h-10 flex items-center justify-center rounded-full">1</a>
                                </li>
                                <li>
                                    <a href="#"
                                        class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-primary hover:text-white">2</a>
                                </li>
                                <li>
                                    <a href="#"
                                        class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-primary hover:text-white">3</a>
                                </li>
                                <li>
                                    <a href="#" class="w-10 h-10 flex items-center justify-center rounded-full">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
function filterProducts() {
    const params = new URLSearchParams(window.location.search);

    // Reset all filter keys to avoid stacking
    ['category', 'brand', 'color', 'size', 'on_sale', 'sort'].forEach(k => params.delete(k));

    document.querySelectorAll('#filters input:checked').forEach(input => {
        params.set(input.name, input.value);
    });
    window.location.search = params.toString();
}
</script>
</x-shop.layout>

