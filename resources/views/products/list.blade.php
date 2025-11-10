@php
use Carbon\Carbon;

$now = Carbon::now();
@endphp
<x-shop.layout>
    <!-- Shop -->
    <section id="shop">
        <div class="container mx-auto">
            <!-- Top Filter -->
            <div class="flex flex-col md:flex-row justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <button
                        class="bg-primary text-white hover:bg-transparent hover:text-primary border hover:border-primary py-2 px-4 rounded-full focus:outline-none">Show
                        On
                        Sale</button>
                    {{-- <button
                        class="bg-primary text-white hover:bg-transparent hover:text-primary border hover:border-primary py-2 px-4 rounded-full focus:outline-none">List
                        View</button>
                    <button
                        class="bg-primary text-white hover:bg-transparent hover:text-primary border hover:border-primary py-2 px-4 rounded-full focus:outline-none">Grid
                        View</button> --}}
                </div>
                <div class="flex mt-5 md:mt-0 space-x-4">
                    <div class="relative">
                        <select
                            class="block appearance-none w-full bg-white border  hover:border-primary px-4 py-2 pr-8 rounded-full shadow leading-tight focus:outline-none focus:shadow-outline">
                            <option>Sort by Latest</option>
                            <option>Sort by Popularity</option>
                            <option>Sort by A-Z</option>
                        </select>
                        <div
                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center justify-center px-2">
                            <img id="arrow-down" class="h-4 w-4"
                             src="{{ asset('storage/images/filter-down-arrow.svg') }}"
                                alt="filter arrow">
                            <img id="arrow-up" class="h-4 w-4 hidden" 
                            src="{{ asset('storage/images/filter-up-arrow.svg') }}"

                                alt="filter arrow">
                        </div>
                    </div>
                </div>
            </div>
            <!-- Filter Toggle Button for Mobile -->
            <div class="block md:hidden text-center mb-4">
                <button id="products-toggle-filters"
                    class="bg-primary text-white py-2 px-4 rounded-full focus:outline-none">Show Filters</button>
            </div>
            <div class="flex flex-col md:flex-row">
                <!-- Filters -->
                <div id="filters" class="w-full md:w-1/4 p-4 hidden md:block">
                    <!-- Category Filter -->
                    <div class="mb-6 pb-8 border-b border-gray-line">
                        <h3 class="text-lg font-semibold mb-6">Category</h3>
                        <div class="space-y-2">
                            @isset($categories)
                            @foreach ($categories as $category)
                                <label class="flex items-center">
                                    <input type="checkbox" class="form-checkbox custom-checkbox">
                                    <span class="ml-2">{{$category->name}}</span>
                                </label>
                            @endforeach
                            @endisset                        
                        </div>
                    </div>
                    <!-- Size Filter -->
                    <div class="mb-6 pb-8 border-b border-gray-line">
                        <h3 class="text-lg font-semibold mb-6">Size</h3>
                        <div class="space-y-2">
                            @isset($sizes)
                            @foreach ($sizes as $size)
                                <label class="flex items-center">
                                    <input type="checkbox" class="form-checkbox custom-checkbox">
                                    <span class="ml-2">{{$size->name}}</span>
                                </label>
                            @endforeach
                            @endisset  
                            
                        </div>
                    </div>
                    <!-- Color Filter -->
                    <div class="mb-6 pb-8 border-b border-gray-line">
                        <h3 class="text-lg font-semibold mb-6">Color</h3>
                        <div class="space-y-2">
                            @isset($colors)
                            @foreach ($colors as $color)
                                <label class="flex items-center custom-color-checkbox" 
                                data-color="#ff0000">
                                    <input type="checkbox" class="form-checkbox custom-checkbox">
                                    <span class="ml-2">{{$color->name}}</span>
                                </label>
                            @endforeach
                            @endisset
                            
                        </div>
                    </div>
                    <!-- Brand Filter -->
                    <div class="mb-6 pb-8 border-b border-gray-line">
                        <h3 class="text-lg font-semibold mb-6">Brand</h3>
                        <div class="space-y-2">
                            @isset($brands)
                            @foreach ($brands as $brand)
                                <label class="flex items-center">
                                    <input type="checkbox" class="form-checkbox custom-checkbox">
                                    <span class="ml-2">{{$brand->name}}</span>
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
                            <span class="text-lg font-bold text-primary">${{$product->price}}</span>
                            @if ($product->discounted_price > 0 
                                    && $now->between(Carbon::parse($product->discount_start_date), Carbon::parse($product->discount_end_date)))
                                    <span class="text-sm line-through ml-2">${{ $product->discounted_price }}</span>
                                @endif
                        </div>
                        <button
                            class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full w-full"
                        >
                            Addto Cart
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

    <!-- Shop category description -->
    <section id="shop-category-description" class="py-8">
        <div class="container mx-auto">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-bold mb-4">{{$product->category->name}} Category</h2>
                <p class="mb-4">
                    {{$product->category->description}}
                </p>
                
            </div>
        </div>
    </section>
</x-shop.layout>