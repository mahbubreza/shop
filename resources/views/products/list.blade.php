<x-shop.layout>
    <!-- Shop -->
    @php
    $shopConfig = config('shop');
    @endphp
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
                        <h3 class="text-lg font-semibold mb-6">Category</h3>
                        <div class="space-y-2">
                            @isset($categories)
                            @foreach ($categories as $category)
                                <label class="flex items-center">
                                    <input type="checkbox" name="category[]" value="{{ $category->id }}"
                                        onchange="filterProducts()"
                                        {{ in_array($category->id, (array) request('category')) ? 'checked' : '' }}>
                                    <span class="ml-2">{{ $category->name }}</span>
                                </label>
                            @endforeach
                            @endisset                        
                        </div>
                    </div>
                    @if ($shopConfig['size_filtering'])
                      <!-- Size Filter -->
                    <div class="mb-6 pb-8 border-b border-gray-line">
                        <h3 class="text-lg font-semibold mb-6">Size</h3>
                        <div class="space-y-2">
                            @isset($sizes)
                            @foreach ($sizes as $size)
                                
                                <label class="flex items-center">
                                    <input type="checkbox" name="size[]" value="{{ $size->id }}"
                                        onchange="filterProducts()"
                                        {{ in_array($size->id, (array) request('size')) ? 'checked' : '' }}>
                                    <span class="ml-2">{{ $size->name }}</span>
                                </label>
                            @endforeach
                            @endisset  
                            
                        </div>
                    </div>  
                    @endif
                    @if ($shopConfig['color_filtering'])

                    <!-- Color Filter -->
                    <div class="mb-6 pb-8 border-b border-gray-line">
                        <h3 class="text-lg font-semibold mb-6">Color</h3>
                        <div class="space-y-2">
                            @isset($colors)
                            @foreach ($colors as $color)
                                <label class="flex items-center">
                                    <input type="checkbox" name="color[]" value="{{ $color->id }}"
                                        onchange="filterProducts()"
                                        {{ in_array($color->id, (array) request('color')) ? 'checked' : '' }}>
                                    <span class="ml-2">{{ $color->name }}</span>
                                </label>
                            @endforeach
                            @endisset
                            
                        </div>
                    </div>
                    @endif
                    <!-- Brand Filter -->
                    <div class="mb-6 pb-8 border-b border-gray-line">
                        <h3 class="text-lg font-semibold mb-6">Brand</h3>
                        <div class="space-y-2">
                            @isset($brands)
                            @foreach ($brands as $brand)
                                <label class="flex items-center">
                                    <input type="checkbox" name="brand[]" value="{{ $brand->id }}"
                                        onchange="filterProducts()"
                                        {{ in_array($brand->id, (array) request('brand')) ? 'checked' : '' }}>
                                    <span class="ml-2">{{ $brand->name }}</span>
                                </label>
                            @endforeach
                            @endisset 
                            
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
                            data-product-id="{{ $product->id }}"
                            class="add-to-cart bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full w-full"
                        >
                            Add to Cart
                        </button>
                        </div>
                        @endforeach
                            
                        @endisset                       
                        
                    </div>
                <!-- Pagination -->
                <div class="flex justify-center mt-8">
                    <nav aria-label="Page navigation" class="pagination-custom">
                        {{ $products->withQueryString()->links('pagination::tailwind') }}
                    </nav>
                </div>

                <style>
                .pagination-custom nav > div span[aria-current='page'] {
                    background-color: var(--color-primary, #FF0042); /* or your Tailwind primary */
                    color: #fff;
                    border: 1px solid var(--color-primary, #FF0042);
                    border-radius: 9999px;
                    padding: 0.5rem 1rem;
                    font-weight: 600;
                }

                .pagination-custom nav > div a {
                    border: 1px solid var(--color-primary, #FF0042);
                    color: var(--color-primary, #FF0042);
                    border-radius: 9999px;
                    padding: 0.5rem 1rem;
                    margin: 0 0.25rem;
                    font-weight: 600;
                    transition: all 0.2s;
                    text-decoration: none;
                }

                .pagination-custom nav > div a:hover {
                    background-color: var(--color-primary, #FF0042);
                    color: #fff;
                }

                .pagination-custom nav > div span {
                    padding: 0.5rem 1rem;
                    margin: 0 0.25rem;
                    border-radius: 9999px;
                    color: #9ca3af; /* gray-400 */
                }
                </style>


                   
                </div>
            </div>
        </div>
    </section>

    <script>
function filterProducts() {
    const url = new URL(window.location.href);
    const params = new URLSearchParams(url.search);

    // Clear all filter keys (including [] versions)
    ['category', 'brand', 'color', 'size', 'on_sale', 'sort'].forEach(k => {
        params.delete(k);
        params.delete(k + '[]');
    });

    // Add current selections (allow multiple per name)
    document.querySelectorAll('#filters input:checked').forEach(input => {
        const name = input.name.replace('[]', '');
        params.append(name + '[]', input.value);
    });

    // Update the URL
    url.search = params.toString();
    window.location.href = url.toString();
}
</script>


</x-shop.layout>

