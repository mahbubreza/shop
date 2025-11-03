<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Favicon -->
    <link rel="icon" type="icon" href="storage/images/favicon.png" />
    <title>Home page</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">    
    @vite(['resources/css/app.css', 'resources/css/styles.css',  'resources/css/custom.css', 'resources/js/app.js'])
   <!-- Swiper CSS -->
{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" /> --}}
<!-- Swiper CSS -->

</head>

<body>
    <!-- Header -->
    <header class="bg-gray-dark sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center py-4">
            <!-- Left section: Logo -->
            <a href="/" class="flex items-center">
              <div>
                <img src="{{ asset('storage/images/walstyle.png') }}" class="h-24 rounded" width="100">
              </div>
            </a>
            <!-- Hamburger menu (for mobile) -->
            <div class="flex lg:hidden">
                <button id="hamburger" class="text-white focus:outline-none">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>

            <!-- Center section: Menu -->
            <nav class="hidden lg:flex md:flex-grow justify-center">
              <ul class="flex justify-center space-x-4 text-white">
                  <li><a href="index.html" class="hover:text-secondary font-semibold">Home</a></li>
                  <li><a href="single-product-page.html" class="hover:text-secondary font-semibold">Products</a></li>
                  <!-- Category Dropdown -->
                  <li class="relative group" x-data="{ open: false }">
                      <a href="shop.html" @mouseover="open = true" @mouseleave="open = false" href="#" class="hover:text-secondary font-semibold flex items-center">
                          Categories
                          <i :class="open ? 'fas fa-chevron-up ml-1 text-xs' : 'fas fa-chevron-down ml-1 text-xs'"></i>
                      </a>
                      <ul 
                          x-show="open"
                          @mouseover="open = true"
                          @mouseleave="open = false"
                          class="absolute left-0 bg-white text-black space-y-2 mt-1 p-2 rounded shadow-lg"
                          x-transition:enter="transition ease-out duration-100"
                          x-transition:enter-start="opacity-0 scale-90"
                          x-transition:enter-end="opacity-100 scale-100"
                          x-transition:leave="transition ease-in duration-100"
                          x-transition:leave-start="opacity-100 scale-100"
                          x-transition:leave-end="opacity-0 scale-90"
                      >
                          <li><a href="shop.html" class="min-w-40 block px-4 py-2 hover:bg-primary hover:text-white rounded">Category Item 1</a></li>
                          <li><a href="shop.html" class="min-w-40 block px-4 py-2 hover:bg-primary hover:text-white rounded">Category Item 2</a></li>
                          <li><a href="shop.html" class="min-w-40 block px-4 py-2 hover:bg-primary hover:text-white rounded">Category Item 3</a></li>
                      </ul>
                  </li>

                  <!-- Brand Dropdown -->
                  <li class="relative group" x-data="{ open: false }">
                      <a href="shop.html" @mouseover="open = true" @mouseleave="open = false" href="#" class="hover:text-secondary font-semibold flex items-center">
                          Brands
                          <i :class="open ? 'fas fa-chevron-up ml-1 text-xs' : 'fas fa-chevron-down ml-1 text-xs'"></i>
                      </a>
                      <ul
                          x-show="open"
                          @mouseover="open = true"
                          @mouseleave="open = false"
                          class="absolute left-0 bg-white text-black space-y-2 mt-1 p-2 rounded shadow-lg"
                          x-transition:enter="transition ease-out duration-100"
                          x-transition:enter-start="opacity-0 scale-90"
                          x-transition:enter-end="opacity-100 scale-100"
                          x-transition:leave="transition ease-in duration-100"
                          x-transition:leave-start="opacity-100 scale-100"
                          x-transition:leave-end="opacity-0 scale-90"
                      >
                          <li><a href="shop.html" class="min-w-40 block px-4 py-2 hover:bg-primary hover:text-white rounded">Brand Item 1</a></li>
                          <li><a href="shop.html" class="min-w-40 block px-4 py-2 hover:bg-primary hover:text-white rounded">Brand Item 2</a></li>
                          <li><a href="shop.html" class="min-w-40 block px-4 py-2 hover:bg-primary hover:text-white rounded">Brand Item 3</a></li>
                      </ul>
                  </li>
                  
                  <li><a href="checkout.html" class="hover:text-secondary font-semibold">Contact Us</a></li>
              </ul>
            </nav>

            <!-- Right section: Buttons (for desktop) -->
            <div class="hidden lg:flex items-center space-x-4 relative">
              <a href="/register"
                  class="bg-primary border border-primary hover:bg-transparent text-white hover:text-primary font-semibold px-4 py-2 rounded-full inline-block">Register</a>
              <a href="/login"
                  class="bg-primary border border-primary hover:bg-transparent text-white hover:text-primary font-semibold px-4 py-2 rounded-full inline-block">Login</a>
              <div class="relative group cart-wrapper">
                  <a href="/cart.html" >
                      <img src="{{ asset('storage/images/cart-shopping.svg.png') }}" alt="Cart" class="h-6 w-6 group-hover:scale-120">
                  </a>
                  <!-- Cart dropdown -->
                  <div class="absolute right-0 mt-1 w-80 bg-white shadow-lg p-4 rounded hidden group-hover:block">
                      <div class="space-y-4">
                          <!-- product item -->
                          <div class="flex items-center justify-between pb-4 border-b border-gray-line">
                              <div class="flex items-center">
                                  <img  src="{{ asset('storage/images/single-product/1.jpg"') }}" alt="Product" class="h-12 w-12 object-cover rounded mr-2">
                                  <div>
                                      <p class="font-semibold">Summer black dress</p>
                                      <p class="text-sm">Quantity: 1</p>
                                  </div>
                              </div>
                              <p class="font-semibold">$25.00</p>
                          </div>
                          <!-- product item -->
                          <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <img src="{{ asset('storage/images/single-product/2.jpg"') }}"  alt="Product" class="h-12 w-12 object-cover rounded mr-2">
                                <div>
                                    <p class="font-semibold">Black suit</p>
                                    <p class="text-sm">Quantity: 1</p>
                                </div>
                            </div>
                            <p class="font-semibold">$125.00</p>
                        </div>
                      </div>
                      <a href="/cart.html" class="block text-center mt-4 border border-primary bg-primary hover:bg-transparent text-white hover:text-primary py-2 rounded-full font-semibold">Go to Cart</a>
                  </div>
              </div>
              <a id="search-icon" href="javascript:void(0);" class="text-white hover:text-secondary group">
                  <img src="{{ asset('storage/images/search-icon.svg"') }}"  alt="Search"
                      class="h-6 w-6 transition-transform transform group-hover:scale-120">
              </a>
              <!-- Search field -->
              <div id="search-field"
                  class="hidden absolute top-full right-0 mt-2 w-full bg-white shadow-lg p-2 rounded">
                  <input type="text" class="w-full p-2 border border-gray-300 rounded"
                      placeholder="Search for products...">
              </div>
          </div>
        </div>
    </header>

    <!-- Mobile menu -->
    <nav id="mobile-menu-placeholder" class="mobile-menu hidden flex flex-col items-center space-y-8 lg:hidden">
      <ul class="w-full">
          <li><a href="index.html" class="hover:text-secondary font-bold block py-2">Home</a></li>
          <li><a href="single-product-page.html" class="hover:text-secondary font-bold block py-2">Products</a></li>
          <!-- Men Dropdown -->
          <li class="relative group" x-data="{ open: false }">
              <a @click="open = !open; $event.preventDefault()" class="hover:text-secondary font-bold block py-2 flex justify-center items-center cursor-pointer">
                <span>Categories</span>
                <span @click.stop="open = !open">
                    <i :class="open ? 'fas fa-chevron-up text-xs ml-2' : 'fas fa-chevron-down text-xs ml-2'"></i>
                </span>
              </a>
              <ul class="mobile-dropdown-menu" x-show="open" x-transition class="space-y-2">
                  <li><a href="shop.html" class="hover:text-secondary font-bold block pt-2 pb-3">Shop Category</a></li>
                  <li><a href="single-product-page.html" class="hover:text-secondary font-bold block py-2">Category subitem 1</a></li>
                  <li><a href="single-product-page.html" class="hover:text-secondary font-bold block py-2">Category subitem 2</a></li>
                  <li><a href="single-product-page.html" class="hover:text-secondary font-bold block py-2">Category subitem 3</a></li>
              </ul>
          </li>

          <!-- Women Dropdown -->
          <li class="relative group" x-data="{ open: false }">
              <a @click="open = !open; $event.preventDefault()" class="hover:text-secondary font-bold block py-2 flex justify-center items-center cursor-pointer">
                    <span>Brands</span>
                    <span @click.stop="open = !open">
                        <i :class="open ? 'fas fa-chevron-up text-xs ml-2' : 'fas fa-chevron-down text-xs ml-2'"></i>
                    </span>
              </a>
              <ul class="mobile-dropdown-menu" x-show="open" x-transition class="pl-4 space-y-2">
                  <li><a href="shop.html" class="hover:text-secondary font-bold block py-2">Shop Brand</a></li>
                  <li><a href="single-product-page.html" class="hover:text-secondary font-bold block py-2">Brand item 1</a></li>
                  <li><a href="single-product-page.html" class="hover:text-secondary font-bold block py-2">Brand item 2</a></li>
                  <li><a href="single-product-page.html" class="hover:text-secondary font-bold block py-2">Brand item 3</a></li>
              </ul>
          </li>

          <li><a href="checkout.html" class="hover:text-secondary font-bold block py-2">Contact Us</a></li>
      </ul>
      <div class="flex flex-col mt-6 space-y-2 items-center">
          <a href="register.html"
              class="bg-primary hover:bg-transparent text-white hover:text-primary border border-primary font-semibold px-4 py-2 rounded-full inline-block flex items-center justify-center min-w-[110px]">Register</a>
          <a href="register.html"
              class="bg-primary hover:bg-transparent text-white hover:text-primary border border-primary font-semibold px-4 py-2 rounded-full inline-block flex items-center justify-center min-w-[110px]">Login</a>
          <a href="register.html"
              class="bg-primary hover:bg-transparent text-white hover:text-primary border border-primary font-semibold px-4 py-2 rounded-full inline-block flex items-center justify-center min-w-[110px]">Cart -&nbsp;<span>5</span>&nbsp;items</a>
      </div>
      <!-- Search field -->
      <div 
          class="  top-full right-0 mt-2 w-full bg-white shadow-lg p-2 rounded">
          <input type="text" class="w-full p-2 border border-gray-300 rounded"
              placeholder="Search for products...">
      </div>
    </nav>

    {{ $slot }}

    <!-- Subscribe section -->
    <section id="subscribe" class="py-6 lg:py-24 bg-white border-t border-gray-line">
      <div class="container mx-auto">
          <div class="flex flex-col items-center rounded-lg p-4 sm:p-0 ">
              <div class="mb-8">
                  <h2 class="text-center text-xl font-bold sm:text-2xl lg:text-left lg:text-3xl">Join our newsletter 
                    <!--and <span class="text-primary">get $50 discount</span> for your first order-->
                  </h2>
              </div>
              <div class="flex flex-col items-center w-96 ">
                  <form class="flex w-full gap-2">
                      <input placeholder="Enter your email address"
                             class="w-full flex-1 rounded-full px-3 py-2 border border-gray-300 text-gray-700 placeholder-gray-500 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary" />
                      <button
                          class="bg-primary border border-primary hover:bg-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full">Subscribe</button>
                  </form>
              </div>
          </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-gray-line">
        <!-- Top part -->
        <div class="container mx-auto px-4 py-10">
          <div class="flex flex-wrap -mx-4">
            <!-- Menu 1 -->
            <div class="w-full sm:w-1/6 px-4 mb-8">
              <h3 class="text-lg font-semibold mb-4">Shop</h3>
              <ul>
                <li><a href="/shop.html" class="hover:text-primary">Shop</a></li>
                <li><a href="/single-product-page.html" class="hover:text-primary">Products</a></li>
                <li><a href="/shop.html" class="hover:text-primary">Categories</a></li>
                <li><a href="/single-product-page.html" class="hover:text-primary">Brands</a></li>
              </ul>
            </div>
            <!-- Menu 2 -->
            <div class="w-full sm:w-1/6 px-4 mb-8">
              <h3 class="text-lg font-semibold mb-4">Pages</h3>
              <ul>
                <li><a href="/shop.html" class="hover:text-primary">Shop</a></li>
                <li><a href="/single-product-page.html" class="hover:text-primary">Product</a></li>
                <li><a href="/checkout.html" class="hover:text-primary">Checkout</a></li>
                <li><a href="/404.html" class="hover:text-primary">404</a></li>
              </ul>
            </div>
            <!-- Menu 3 -->
            <div class="w-full sm:w-1/6 px-4 mb-8">
              <h3 class="text-lg font-semibold mb-4">Account</h3>
              <ul>
                <li><a href="/cart.html" class="hover:text-primary">Cart</a></li>
                <li><a href="/register.html" class="hover:text-primary">Registration</a></li>
                <li><a href="/register.html" class="hover:text-primary">Login</a></li>
              </ul>
            </div>
            <!-- Social Media -->
            <div class="w-full sm:w-1/6 px-4 mb-8">
              <h3 class="text-lg font-semibold mb-4">Follow Us</h3>
              <ul>
                <li class="flex items-center mb-2">
                  <img src="{{ asset('storage/images/social_icons/facebook.svg') }}" alt="Facebook" class="w-4 h-4 transition-transform transform hover:scale-110 mr-2">
                  <a href="#" class="hover:text-primary">Facebook</a>
                </li>
                <li class="flex items-center mb-2">
                  <img src="{{ asset('storage/images/social_icons/twitter.svg') }}" alt="Twitter" class="w-4 h-4 transition-transform transform hover:scale-110 mr-2">
                  <a href="#" class="hover:text-primary">Twitter</a>
                </li>
                <li class="flex items-center mb-2">
                  <img src="{{ asset('storage/images/social_icons/instagram.svg') }}" alt="Instagram" class="w-4 h-4 transition-transform transform hover:scale-110 mr-2">
                  <a href="#" class="hover:text-primary">Instagram</a>
                </li>
                <li class="flex items-center mb-2">
                  <img src="{{ asset('storage/images/social_icons/pinterest.svg') }}"   alt="Instagram" class="w-4 h-4 transition-transform transform hover:scale-110 mr-2">
                  <a href="#" class="hover:text-primary">Pinterest</a>
                </li>
                <li class="flex items-center mb-2">
                  <img src="{{ asset('storage/images/social_icons/youtube.svg') }}"  alt="Instagram" class="w-4 h-4 transition-transform transform hover:scale-110 mr-2">
                  <a href="#" class="hover:text-primary">YouTube</a>
                </li>
              </ul>
            </div>
            <!-- Contact Information -->
            <div class="w-full sm:w-2/6 px-4 mb-8">
              <h3 class="text-lg font-semibold mb-4">Contact Us</h3>
              <p><img  src="{{ asset('storage/images/walstyle.png') }}" alt="Logo" class="h-[60px] mb-4"></p>
              <p>123 Street Name, Seoul, South Korea</p>
              <p class="text-xl font-bold my-4">Phone: (123) 456-7890</p>
              <a href="mailto:info@company.com" class="underline">Email: walstyle@gmail.com</a>
            </div>
          </div>
        </div>
      
        <!-- Bottom part -->
        <div class="py-6 border-t border-gray-line">
          <div class="container mx-auto px-4 flex flex-wrap justify-between items-center">
            <!-- Copyright and Links -->
            <div class="w-full lg:w-3/4 text-center lg:text-left mb-4 lg:mb-0">
              <p class="mb-2 font-bold">&copy; 2024 Your Company. All rights reserved.</p>
              <ul class="flex justify-center lg:justify-start space-x-4 mb-4 lg:mb-0">
                <li><a href="#" class="hover:text-primary">Privacy Policy</a></li>
                <li><a href="#" class="hover:text-primary">Terms of Service</a></li>
                <li><a href="#" class="hover:text-primary">FAQ</a></li>
              </ul>
              <p class="text-sm mt-4">Your shop's description goes here. This is a brief introduction to your shop and what you offer.</p>
            </div>
            <!-- Payment Icons -->
            <div class="w-full lg:w-1/4 text-center lg:text-right">
              <img src="storage/images/social_icons/paypal.svg" alt="PayPal" class="inline-block h-8 mr-2">
              <img src="storage/images/social_icons/stripe.svg" alt="Stripe" class="inline-block h-8 mr-2">
              <img src="storage/images/social_icons/visa.svg" alt="Visa" class="inline-block h-8">
            </div>
          </div>
        </div>
    </footer>

    @vite(['resources/js/script.js'])


<!-- Swiper JS -->
  

</body>
</html>