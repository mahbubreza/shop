@php
use Carbon\Carbon;

$now = Carbon::now();
@endphp
<x-shop.layout>
  @vite(['resources/css/swiper-bundle.min.css'])
  <!-- Slider -->
  <section id="product-slider">
      <div class="main-slider swiper-container">
          <div class="swiper-wrapper">
            @isset($categories)
                @foreach ($categories->where('carousal', 1) as  $category)
                <div class="swiper-slide">
                  <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}">
                  <div class="swiper-slide-content">
                    <h2 class="text-3xl md:text-7xl font-bold text-white mb-2 md:mb-4">{{ $category->name }}</h2>
                    <p class="mb-4 text-white md:text-2xl">{{$category->description}}</p>
                      <a href="/"
                          class="bg-primary hover:bg-transparent text-white hover:text-white border border-transparent hover:border-white font-semibold px-4 py-2 rounded-full inline-block">Shop
                          now</a>
                  </div>
              </div>                   
                @endforeach             
            @endisset
          </div>
      </div>
      <!-- Slider Pagination -->
      <div class="swiper-button-prev"></div>
      <div class="swiper-button-next"></div>
  </section>

  <!-- Product banner section -->
  <section id="product-banners">
      <div class="container mx-auto py-10">
          <div class="flex flex-wrap -mx-4">
              <!-- Category 1 -->
              @isset($categories)
                @foreach ($categories->where('featured', 1) as  $category)
                <div class="w-full sm:w-1/3 px-4 mb-8">
                    <div class="category-banner relative overflow-hidden rounded-lg shadow-lg group">
                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-full h-auto">
                        <div class="absolute inset-0 bg-gray-light bg-opacity-50"></div>
                        <div
                            class="absolute inset-0 flex flex-col items-center justify-center text-center text-white p-4">
                            <h2 class="text-2xl md:text-3xl font-bold mb-4">{{ $category->name }}</h2>
                            <a href="/"
                                class="bg-primary hover:bg-transparent border border-transparent hover:border-white text-white hover:text-white font-semibold px-4 py-2 rounded-full inline-block">Shop
                                now</a>
                        </div>
                    </div>
                </div>
                @endforeach             
            @endisset
          </div>
      </div>
  </section>

  <!-- Popular product section -->
  <section id="popular-products">
      <div class="container mx-auto px-4">
          <h2 class="text-2xl font-bold mb-8">Popular products</h2>
          <div class="flex flex-wrap -mx-4">
              @isset($popularProducts)
              @foreach ($popularProducts as $product)
                  <div class="w-full sm:w-1/2 lg:w-1/4 px-4 mb-8">
                <div class="bg-white p-3 rounded-lg shadow-lg">
                  <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full object-cover mb-4 rounded-lg">
                  <a href="/products/{{ $product->id }}/details" class="text-lg font-semibold mb-2">{{ $product->name }}</a>
                  <p class="my-2">{{ $product->category->name }}</p>
                  <div class="flex items-center mb-4">
                    @php
                      $isDiscounted = $product->discounted_price > 0 
                      && $now->between(Carbon::parse($product->discount_start_date), Carbon::parse($product->discount_end_date));
                      $price = $isDiscounted ? $product->discounted_price : $product->price;                      
                    @endphp
                    <span class="text-lg font-bold text-primary">${{$price}}</span>

                    @if ($product->discounted_price > 0 
                        && $now->between(Carbon::parse($product->discount_start_date), Carbon::parse($product->discount_end_date)))
                        <span class="text-sm line-through ml-2">${{ $product->price }}</span>
                    @endif

                  </div>
                  <button 
                  data-product-id="{{ $product->id }}"
                  class="add-to-cart bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full w-full">Add to Cart</button>
                </div>
              </div>
              @endforeach
                  
              @endisset
              
              
            </div>
      </div>
  </section>

    <!-- Latest product section -->
    <section id="latest-products" class="py-10">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold mb-8">Latest products</h2>
            <div class="flex flex-wrap -mx-4">
                @isset($latestProducts)
                @foreach ($latestProducts as $product)
                <div class="w-full sm:w-1/2 lg:w-1/4 px-4 mb-8">
                  <div class="bg-white p-3 rounded-lg shadow-lg">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full object-cover mb-4 rounded-lg">
                    <a href="/products/{{ $product->id }}/details" class="text-lg font-semibold mb-2">{{ $product->name }}</a>
                    <p class=" my-2">{{ $product->category->name }}</p>
                    <div class="flex items-center mb-4">
                      @php
                        $isDiscounted = $product->discounted_price > 0 
                        && $now->between(Carbon::parse($product->discount_start_date), Carbon::parse($product->discount_end_date));
                        $price = $isDiscounted ? $product->discounted_price : $product->price;                      
                      @endphp
                      <span class="text-lg font-bold text-primary">${{$price}}</span>
                    
                        @if ($product->discounted_price > 0 
                            && $now->between(Carbon::parse($product->discount_start_date), Carbon::parse($product->discount_end_date)))
                            <span class="text-sm line-through ml-2">${{ $product->price }}</span>
                        @endif
                    </div>
                    <button 
                    data-product-id="{{ $product->id }}"  
                    class="add-to-cart bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full w-full">Add to Cart</button>
                  </div>
                </div>

                @endforeach
                  
              @endisset
            </div>
        </div>
    </section>

  <!-- Brand section -->
  <section id="brands" class="bg-white py-16 px-4">
      <div class="container mx-auto max-w-screen-xl px-4 testimonials">
        <div class="text-center mb-12 lg:mb-20">
          <h2 class="text-5xl font-bold mb-4">Discover <span class="text-primary">Our Brands</span></h2>
          <p class="my-7">Explore the top brands we feature in our store</p>
      </div>
          <div class="swiper brands-swiper-slider">
              <div class="swiper-wrapper">
                  @isset($brands)
                  @foreach ($brands as $brand)
                    <div class="swiper-slide flex-none bg-gray-200 flex items-center justify-center rounded-md">
                        <img src="{{ asset('storage/' . $brand->logo) }}" alt="Client Logo" class="max-h-full max-w-full">
                    </div>
                  @endforeach
                    
                  @endisset

                  

                  
              </div>
              <div class="swiper-button-prev"></div>
              <div class="swiper-button-next"></div>
          </div>
  </section>

  <!-- Banner section -->
  <section id="banner" class="relative my-16">
      <div class="container mx-auto px-4 py-20 rounded-lg relative bg-cover bg-center" style="background-image: url('storage/images/beauty.jpg');">
          <div class="absolute inset-0 bg-black opacity-40 rounded-lg"></div>
          <div class="relative flex flex-col items-center justify-center h-full text-center text-white py-20">
              <h2 class="text-4xl font-bold mb-4">Welcome to Our Shop</h2>
              <div class="flex space-x-4">
                  <a href="products/list" class="bg-primary hover:bg-transparent text-white hover:text-primary border border-transparent hover:border-primary font-semibold px-4 py-2 rounded-full inline-block">Shop Now</a>
                  <a href="products/list?sort=latest" class="bg-primary hover:bg-transparent text-white hover:text-primary border border-transparent hover:border-primary font-semibold px-4 py-2 rounded-full inline-block">New Arrivals</a>
                  <a href="products/list?on_sale=1" class="bg-primary hover:bg-transparent text-white hover:text-primary border border-transparent hover:border-primary font-semibold px-4 py-2 rounded-full inline-block">Sale</a>
              </div>
          </div>
      </div>
  </section>

  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

</x-shop.layout>