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
                            <div class="mt-4">
                                <h3 class="text-lg font-semibold mb-2">Customer Ratings</h3>
                                @php
                                    $avg = round($product->averageRating(), 1);
                                @endphp

                                <div class="flex items-center space-x-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $avg)
                                            <span class="text-yellow-400 text-xl">&#9733;</span>
                                        @else
                                            <span class="text-gray-300 text-xl">&#9733;</span>
                                        @endif
                                    @endfor
                                    <span class="text-gray-600 text-sm">({{ $avg ?? 0 }}/5 from {{ $product->ratingCount() }} reviews)</span>
                                </div>
                            </div>
                                
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
                                    class="w-16 py-2 m-1 text-center focus:outline-none" readonly>
                                <button id="increase"
                                    class="bg-primary hover:bg-transparent border border-transparent hover:border-primary text-white hover:text-primary font-semibold  w-10 h-10 rounded-full focus:outline-none">+</button>
                            </div>
                            <button
                                class="bg-primary border border-transparent hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full">Add
                                to Cart</button>

                        </div>
                        @if (Auth::check())
                        <form action="{{ route('products.rate', $product->id) }}" method="POST" class="mt-6">
                            @csrf
                            <label for="rating" class="block text-sm font-medium text-gray-700">Your Rating</label>
                            <div class="flex space-x-1 my-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <label>
                                        <input type="radio" name="rating" value="{{ $i }}" class="hidden peer">
                                        <span class="text-2xl cursor-pointer peer-checked:text-yellow-400">&#9733;</span>
                                    </label>
                                @endfor
                            </div>

                            <textarea name="review" rows="3" class="w-full border-gray-300 rounded-md" placeholder="Write your review (optional)"></textarea>

                            <button type="submit" class="mt-3 bg-primary text-white px-4 py-2 rounded hover:bg-opacity-90">
                                Submit Rating
                            </button>
                        </form>
                            @else
                        <p class="mt-4 text-sm text-gray-500">
                            <a href="{{ route('login') }}" class="text-primary hover:underline">Login</a> to rate this product.
                        </p>
                        @endif
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
                    <div class="flex space-x-4" role="tablist">
                        <button id="description-tab" role="tab" aria-controls="description-content" aria-selected="true"
                            class="tab active">Description</button>
                        <button id="additional-info-tab" role="tab" aria-controls="additional-info-content"
                            aria-selected="false" class="tab">Videos</button>
                        <button id="size-shape-tab" role="tab" aria-controls="size-shape-content" aria-selected="false"
                            class="tab">Documents</button>
                        <button id="reviews-tab" role="tab" aria-controls="reviews-content" aria-selected="false"
                            class="tab">Reviews ({{count($reviews)}})</button>
                    </div>
                    <div class="mt-8">
                        <div id="description-content" role="tabpanel" aria-labelledby="description-tab"
                            class="tab-content">
                            <div class="flex flex-col lg:flex-row lg:space-x-8">
                                <div>
                                <h3 class="text-lg font-semibold mb-2">Product Description</h3>
                                <p>{!! $product->description !!}</p>
                            </div>
                            </div>
                        </div>
                        <div id="additional-info-content" role="tabpanel" aria-labelledby="additional-info-tab"
                            class="tab-content hidden">
                            <p>Videos about the product.</p>
                            <div class="flex flex-col space-y-8">
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
                        <div id="size-shape-content" role="tabpanel" aria-labelledby="size-shape-tab"
                            class="tab-content hidden">


                        </div>
                        <div id="reviews-content" role="tabpanel" aria-labelledby="reviews-tab"
                            class="tab-content hidden">
                            <!-- Reviews List -->
                            <div class="space-y-6">
                            <h3 class="text-lg font-semibold mb-4">Customer Reviews</h3>

                            <div id="reviews-list">
                                @forelse ($reviews as $review)
                                    <div class="py-4 border-b border-gray-200">
                                        <div class="flex items-center mb-2">
                                            <span class="text-lg font-semibold text-gray-700">
                                                {{ $review->user->name ?? 'Anonymous' }}
                                            </span>

                                            {{-- Display stars --}}
                                            <span class="ml-2 text-primary">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $review->rating)
                                                        ★
                                                    @else
                                                        ☆
                                                    @endif
                                                @endfor
                                            </span>
                                        </div>

                                        {{-- Comment --}}
                                        <p class="text-gray-600">{{ $review->review }}</p>

                                        {{-- Optional: show date --}}
                                        <small class="text-gray-400">
                                            Reviewed on {{ $review->created_at->format('M d, Y') }}
                                        </small>
                                    </div>
                                @empty
                                    <p class="text-gray-500">No reviews yet. Be the first to review this product!</p>
                                @endforelse
                            </div>
                        </div>


                            <!-- Submit Review Form -->
                            <div class="mt-8">
                                <h3 class="text-lg font-semibold mb-4">Write a Review</h3>
                                @guest
                                <h4 class="text-red-500">Please login to write a review.</h4>
                                <form  class="space-y-4">

                                @endguest
                                @auth
                                    <form action="{{ route('products.rate', $product->id) }}" method="POST" class="space-y-4">

                                @endauth
                                    @csrf
                                    <div class="space-y-4 md:flex md:space-x-4 md:space-y-0">
                                        <div class="md:flex-1">
                                            <label for="review-name"
                                                class="block text-sm font-medium text-gray-700">Name</label>
                                            <input type="text" id="review-name" name="name"
                                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                                        </div>
                                        <div class="md:flex-1">
                                            <label for="review-email"
                                                class="block text-sm font-medium text-gray-700">Email</label>
                                            <input type="email" id="review-email" name="email"
                                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                                        </div>
                                        <div class="md:flex-1">
                                            <label for="review-rating"
                                                class="block text-sm font-medium text-gray-700">Rating</label>
                                            <select id="rating" name="rating"
                                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                                                <option value="5">★★★★★</option>
                                                <option value="4">★★★★☆</option>
                                                <option value="3">★★★☆☆</option>
                                                <option value="2">★★☆☆☆</option>
                                                <option value="1">★☆☆☆☆</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="review-text"
                                            class="block text-sm font-medium text-gray-700">Review</label>
                                        <textarea id="review-text" name="review" rows="4"
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"></textarea>
                                    </div>
                                    @auth
                                      <div>
                                        <button type="submit"
                                            class="bg-primary hover:bg-transparent border border-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full focus:outline-none">Submit
                                            Review</button>
                                    </div>  
                                    @endauth
                                    
                                </form>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-shop.layout>