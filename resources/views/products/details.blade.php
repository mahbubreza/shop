@php
use Carbon\Carbon;

$now = Carbon::now();
@endphp
<x-shop.layout>
    <!-- Breadcrumbs -->
    <section id="breadcrumbs" class="pt-6 bg-gray-50">
        <div class="container mx-auto px-4">
            <ol class="list-reset flex">
                <li><a href="index.html" class="font-semibold hover:text-primary">Home</a></li>
                <li><span class="mx-2">&gt;</span></li>
                <li><a href="shop.html" class="font-semibold hover:text-primary">Shop</a></li>
                <li><span class="mx-2">&gt;</span></li>
                <li><a href="category.html" class="font-semibold hover:text-primary">{{$product->category->name}}</a></li>
                <li><span class="mx-2">&gt;</span></li>
                <li>{{$product->name}}</li>
            </ol>
        </div>
    </section>

    <!-- Product info -->
    <section id="product-info">
        <div class="container mx-auto">
            <div class="py-6">
                <div class="flex flex-col lg:flex-row gap-6">
                    <!-- Image Section -->
                    <div class="w-full lg:w-1/2">
                        <div class="grid gap-4">
                            <!-- Big Image -->
                            <div id="main-image-container">
                                <img id="main-image"
                                    class="h-auto w-full max-w-full rounded-lg object-cover object-center md:h-[480px]"
                                    
                                    src="{{ asset('storage/' . $product->image) }}"
                                    alt="Main Product Image" />
                            </div>
                            <!-- Small Images -->
                            <div class="grid grid-cols-5 gap-4">
                                @isset($product->images)
                                @foreach (json_decode($product->images ?? '[]', true) as $img)

                                    <div>
                                    <img onclick="changeImage(this)"
                                    data-full="{{ $img }}"
                                    src="{{ asset('storage/' . $img) }}"
                                    class="object-cover object-center max-h-30 max-w-full rounded-lg cursor-pointer"
                                    alt="Gallery Image" />
                                </div>    
                                @endforeach
                                
                                @endisset
        
                            </div>
                        </div>
                    </div>
                    <!-- Product Details Section -->
                    <div class="w-full lg:w-1/2 flex flex-col justify-between">
                        <div class="pb-8 border-b border-gray-line">
                            <h1 class="text-3xl font-bold mb-4">{{$product->name}}</h1>
                            {{-- <div class="flex items-center mb-8">
                                <span>★★★★★</span>
                                <span class="ml-2">(0 Reviews)</span>
                                <a href="#" class="ml-4 text-primary font-semibold">Write a review</a>
                            </div> --}}
                            <div class="mb-4 pb-4 border-b border-gray-line">
                                <p class="mb-2">Brand:<strong><a href="#" class="hover:text-primary"> {{ $product->brand->name }}</a></strong>
                                </p>
                                <p class="mb-2">Product code:<strong> {{ $product->id }}</strong></p>
                                <p class="mb-2">Availability:<strong> In Stock</strong></p>
                            </div>
                            <div class="flex items-center mb-4">
                                <span class="text-2xl font-semibold">${{$product->price}}</span>

                                @if ($product->discounted_price > 0 
                                    && $now->between(Carbon::parse($product->discount_start_date), Carbon::parse($product->discount_end_date)))
                                    <span class="text-lg line-through ml-2">${{ $product->discounted_price }}</span>
                                @endif

                            </div>

                            <div class="flex items-center mb-8">
                                <button id="decrease"
                                    class="bg-primary hover:bg-transparent border border-transparent hover:border-primary text-white hover:text-primary font-semibold w-10 h-10 rounded-full flex items-center justify-center focus:outline-none"
                                    disabled>-</button>
                                <input id="quantity" type="number" value="1"
                                    class="w-16 py-2 text-center focus:outline-none" readonly>
                                <button id="increase"
                                    class="bg-primary hover:bg-transparent border border-transparent hover:border-primary text-white hover:text-primary font-semibold  w-10 h-10 rounded-full focus:outline-none">+</button>
                            </div>
                            <button
                                class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full">Add
                                to Cart</button>
                        </div>
                        <!-- Social sharing -->
                        <div class="flex space-x-4 my-6">
                            <a href="#" class="w-4 h-4 flex items-center justify-center">
                                <img src="{{ asset('storage/images/social_icons/facebook.svg') }}" alt="Facebook"
                                    class="w-4 h-4 transition-transform transform hover:scale-110">
                            </a>
                            <a href="#" class="w-4 h-4 flex items-center justify-center">
                                <img src="{{ asset('storage/images/social_icons/instagram.svg') }}" alt="Instagram"
                                    class="w-4 h-4 transition-transform transform hover:scale-110">
                            </a>
                            <a href="#" class="w-4 h-4 flex items-center justify-center">
                                <img src="{{ asset('storage/images/social_icons/pinterest.svg') }}" alt="Pinterest"
                                    class="w-4 h-4 transition-transform transform hover:scale-110">
                            </a>
                            <a href="#" class="w-4 h-4 flex items-center justify-center">
                                <img src="{{ asset('storage/images/social_icons/twitter.svg') }}" alt="Twitter"
                                    class="w-4 h-4 transition-transform transform hover:scale-110">
                            </a>
                            <a href="#" class="w-4 h-4 flex items-center justify-center">
                                <img src="{{ asset('storage/images/social_icons/viber.svg') }}" alt="Viber"
                                    class="w-4 h-4 transition-transform transform hover:scale-110">
                            </a>
                        </div>
                        <!-- Additional Information -->
                        <div>
                                                      
                            @php
                                $videos = json_decode($product->videos ?? '[]', true);
                            @endphp

                            @if (!empty($videos) && count($videos) > 0)
                                <h3 class="text-lg font-semibold mb-2">Product Videos</h3>
                                <div class="grid grid-cols-5 gap-4">
                                    @foreach ($videos as $vid)
                                        <div>
                                            <video 
                                                src="{{ asset('storage/' . $vid) }}" 
                                                class="h-24 rounded" 
                                                controls>
                                            </video>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Product tabs description -->
    <section>
        <div class="container mx-auto">
            <div class="py-12">
                <div class="mt-10">
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Product Description</h3>
                        <p>{!! $product->description !!}</p>
                    </div>
                   
    
                </div>
            </div>
        </div>
    </section>

</x-shop.layout>