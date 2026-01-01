<!DOCTYPE html>
<html lang="en" x-data="{ mobileMenuOpen: false }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('storage/images/favicon.png') }}">
    <title>@yield('title', 'Home page')</title>

    <!-- Fonts and icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <!-- Tailwind + Custom CSS -->
    @vite([
        'resources/css/app.css',
        'resources/css/styles.css',
        'resources/css/custom.css',
    ])
</head>
<body class="bg-white">

@php
use Carbon\Carbon;
$shopConfig = config('shop');

function getFinalPrice($product) {
    $now = Carbon::now();
    $isDiscounted = $product->discounted_price > 0
        && $now->between(Carbon::parse($product->discount_start_date), Carbon::parse($product->discount_end_date));
    return $isDiscounted ? $product->discounted_price : $product->price;
}
@endphp

<!-- Header -->
<header class="bg-gray-dark sticky top-0 z-50">
    <div class="container mx-auto flex justify-between items-center py-4">
        <!-- Logo -->
        <a href="/" class="flex items-center">
            <img src="{{ asset('storage/images/logo.png') }}" class="h-24 rounded" width="100">
        </a>

        <!-- Mobile Hamburger -->
        <div class="flex lg:hidden">
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-white focus:outline-none">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
                </svg>
            </button>
        </div>

        <!-- Desktop Menu -->
        <nav class="hidden lg:flex md:flex-grow justify-center">
            <ul class="flex justify-center space-x-4 text-white">
                <li><a href="/" class="hover:text-secondary font-semibold">Home</a></li>
                <li><a href="/products/list" class="hover:text-secondary font-semibold">Products</a></li>
                <li><a href="#brands" class="hover:text-secondary font-semibold">Brands</a></li>

                <!-- Desktop Categories Dropdown -->
                <li class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" x-cloak>
                    <a href="#" class="hover:text-secondary font-semibold flex items-center">
                        Categories
                        <i :class="open ? 'fas fa-chevron-up ml-1 text-xs' : 'fas fa-chevron-down ml-1 text-xs'"></i>
                    </a>
                    <ul x-show="open" x-transition class="absolute left-0 mt-1 p-2 min-w-[200px] bg-white text-black rounded shadow-lg space-y-2">
                        @isset($categories)
                            @foreach($categories as $category)
                                <li>
                                    <a href="/products/list?category={{$category->id}}" class="block px-4 py-2 hover:bg-primary hover:text-white rounded whitespace-nowrap">
                                        {{$category->name}}
                                    </a>
                                </li>
                            @endforeach
                        @endisset
                    </ul>
                </li>

                <li>
                    <a href="/cart" class="hover:text-secondary font-semibold flex items-center">
                        Cart
                        <i class="fa-solid fa-cart-shopping ml-1 text-xs"></i>
                        <span id="cart-count-menu" class="ml-1 bg-red-500 text-white rounded-full w-5 h-5 text-xs flex items-center justify-center">
                            {{-- {{ Auth::check() ? Auth::user()->cartItems()->count() : 0 }} --}}
                            {{ $cartCount }}
                        </span>    
                    </a>                    
                </li>
                <li><a href="/contact" class="hover:text-secondary font-semibold">Contact Us</a></li>
            </ul>
        </nav>

        <!-- Right Buttons -->
        <div class="hidden lg:flex items-center space-x-4 relative">

            <!-- Auth Buttons -->
            @guest
                <a href="/register" class="bg-primary border border-primary hover:bg-transparent text-white hover:text-primary font-semibold px-4 py-2 rounded-full">Register</a>
                <a href="/login" class="bg-primary border border-primary hover:bg-transparent text-white hover:text-primary font-semibold px-4 py-2 rounded-full">Login</a>
            @endguest
            @auth
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit" class="bg-primary border border-primary hover:bg-transparent text-white hover:text-primary font-semibold px-4 py-2 rounded-full">Logout</button>
                </form>
            @endauth

            <!-- Cart -->
            <div class="relative cart-wrapper" x-data="{ open: false }">
                <a href="/cart" @click.prevent="open = !open">
                    <img src="{{ asset('storage/images/cart-shopping.svg') }}" alt="Cart" class="h-6 w-6">
                    <span id="cart-count-desktop" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 text-xs flex items-center justify-center">
                        {{-- {{ Auth::check() ? Auth::user()->cartItems()->count() : 0 }} --}}
                        {{ $cartCount }}
                    </span>
                </a>

                <!-- Mini Cart -->
                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-80 bg-white shadow-lg rounded-lg z-50 p-4">
                    <h3 class="text-lg font-semibold mb-4">Your Cart</h3>
                    <ul id="mini-cart-items" class="space-y-3"></ul>
                    <div class="mt-4 flex justify-between items-center font-semibold">
                        <span>Subtotal:</span>
                        <span id="mini-cart-subtotal">$0.00</span>
                    </div>
                    <a href="/cart" class="mt-4 block text-center bg-primary text-white py-2 rounded hover:bg-opacity-90">Checkout</a>
                </div>
            </div>

            <!-- Search -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="text-white hover:text-secondary">
                    <img src="{{ asset('storage/images/search-icon.svg') }}" alt="Search" class="h-6 w-6">
                </button>
                <div x-show="open" x-transition @click.away="open = false" class="absolute top-full right-0 mt-2 w-72 bg-white shadow-lg p-2 rounded">
                    <form action="{{ route('products.list') }}" method="GET" class="flex">
                        <input type="text" name="search" value="{{ request('search') }}" class="w-full p-2 border border-gray-300 rounded-l-full focus:outline-none" placeholder="Search products...">
                        <button type="submit" class="bg-primary text-white px-4 rounded-r-full hover:bg-transparent hover:text-primary border border-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Mobile Menu -->
<nav x-show="mobileMenuOpen" x-transition class="lg:hidden flex flex-col items-center space-y-8 bg-white p-4" x-cloak>
    <ul class="w-full text-center">
        <li><a href="/" class="hover:text-secondary font-bold block py-2">Home</a></li>
        <li><a href="/products/list" class="hover:text-secondary font-bold block py-2">Products</a></li>
        <li><a href="#brands" class="hover:text-secondary font-bold block py-2">Brands</a></li>

        <!-- Mobile Category Dropdown -->
        <li x-data="{ open: false }" class="relative">
            <a @click.prevent="open = !open" class="hover:text-secondary font-bold block py-2 flex justify-center items-center cursor-pointer">
                Categories
                <i :class="open ? 'fas fa-chevron-up ml-2 text-xs' : 'fas fa-chevron-down ml-2 text-xs'"></i>
            </a>
            <ul x-show="open" x-transition class="space-y-2">
                @isset($categories)
                    @foreach($categories as $category)
                        <li><a href="/products/list?category={{$category->id}}" class="hover:text-secondary font-bold block py-2">{{$category->name}}</a></li>
                    @endforeach
                @endisset
            </ul>
        </li>

        <li><a href="/contact" class="hover:text-secondary font-bold block py-2">Contact Us</a></li>
    </ul>

    <div class="flex flex-col mt-6 space-y-2 items-center">
        <a href="/register" class="bg-primary hover:bg-transparent text-white hover:text-primary border border-primary font-semibold px-4 py-2 rounded-full min-w-[110px]">Register</a>
        <a href="/login" class="bg-primary hover:bg-transparent text-white hover:text-primary border border-primary font-semibold px-4 py-2 rounded-full min-w-[110px]">Login</a>
        <a href="/cart" class="bg-primary hover:bg-transparent text-white hover:text-primary border border-primary font-semibold px-4 py-2 rounded-full min-w-[110px]">
            Cart - <span id="cart-count-mobile">
                {{-- {{ Auth::check() ? Auth::user()->cartItems()->count() : 0 }} --}}
                {{ $cartCount }}
            </span> items
        </a>
    </div>
</nav>

<main>{{ $slot }}</main>



<!-- Footer -->
<footer class="border-t border-gray-line">
    <div class="container mx-auto px-4 py-10">
        <!-- Optional Footer Content -->
                <!-- Top part -->
        <div class="container mx-auto px-4 py-10">
          <div class="flex flex-wrap -mx-4">
            <!-- Menu 1 -->
            <div class="w-full sm:w-1/6 px-4 mb-8">
              <h3 class="text-lg font-semibold mb-4">Shop</h3>
              <ul>
                <li><a href="/cart" class="hover:text-primary">Cart</a></li>
                <li><a href="/products/list" class="hover:text-primary">Products</a></li>
                <li><a href="#brands" class="hover:text-primary">Brands</a></li>
              </ul>
            </div>
            <!-- Menu 2 -->
            <div class="w-full sm:w-1/6 px-4 mb-8">
              <h3 class="text-lg font-semibold mb-4">Pages</h3>
              <ul>
                <li><a href="/shop.html" class="hover:text-primary">Shop</a></li>
                <li><a href="/product/list" class="hover:text-primary">Product</a></li>
                <li><a href="/checkout" class="hover:text-primary">Checkout</a></li>
                <li><a href="/contact" class="hover:text-primary">Contact Us</a></li>
              </ul>
            </div>
            <!-- Menu 3 -->
            <div class="w-full sm:w-1/6 px-4 mb-8">
              <h3 class="text-lg font-semibold mb-4">Account</h3>
              <ul>
                <li><a href="/cart" class="hover:text-primary">Cart</a></li>
                <li><a href="/register" class="hover:text-primary">Registration</a></li>
                <li><a href="/login" class="hover:text-primary">Login</a></li>
              </ul>
            </div>
            <!-- Social Media -->
            <div class="w-full sm:w-1/6 px-4 mb-8">
              <h3 class="text-lg font-semibold mb-4">Follow Us</h3>
              <ul>
                <li class="flex items-center mb-2">
                  <img src="{{ asset('storage/images/social_icons/facebook.svg') }}" alt="Facebook" class="w-4 h-4 transition-transform transform hover:scale-110 mr-2">
                    <a href="{{ !empty($shopConfig['fb_link']) ? $shopConfig['fb_link'] : '/' }}" target="_blank"
                    class="hover:text-primary">
                        Facebook
                    </a>
                </li>
                <li class="flex items-center mb-2">
                  <img src="{{ asset('storage/images/social_icons/twitter.svg') }}" alt="Twitter" class="w-4 h-4 transition-transform transform hover:scale-110 mr-2">
                  <a href="{{ !empty($shopConfig['twitter_link']) ? $shopConfig['twitter_link'] : '/' }}" target="_blank" class="hover:text-primary">Twitter</a>
                </li>
                <li class="flex items-center mb-2">
                  <img src="{{ asset('storage/images/social_icons/instagram.svg') }}" alt="Instagram" class="w-4 h-4 transition-transform transform hover:scale-110 mr-2">
                  <a href="{{ !empty($shopConfig['instagram_link']) ? $shopConfig['instagram_link'] : '/' }}" target="_blank" class="hover:text-primary">Instagram</a>
                </li>
                <li class="flex items-center mb-2">
                  <img src="{{ asset('storage/images/social_icons/pinterest.svg') }}"   alt="Instagram" class="w-4 h-4 transition-transform transform hover:scale-110 mr-2">
                  <a href="{{ !empty($shopConfig['pinterest_link']) ? $shopConfig['pinterest_link'] : '/' }}" target="_blank" class="hover:text-primary">Pinterest</a>
                </li>
                <li class="flex items-center mb-2">
                  <img src="{{ asset('storage/images/social_icons/youtube.svg') }}"  alt="Instagram" class="w-4 h-4 transition-transform transform hover:scale-110 mr-2">
                  <a href="{{ !empty($shopConfig['youtube_link']) ? $shopConfig['youtube_link'] : '/' }}" target="_blank" class="hover:text-primary">YouTube</a>
                </li>
              </ul>
            </div>
            <!-- Contact Information -->
            <div class="w-full sm:w-2/6 px-4 mb-8">
              <h3 class="text-lg font-semibold mb-4">Contact Us</h3>
              <p><img  src="{{ asset('storage/images/logo.png') }}" alt="Logo" class="h-[60px] mb-4"></p>
              <p>{{$shopConfig['business_contact_address']}}</p>
              <p class="text-xl font-bold my-4">Phone: {{$shopConfig['business_phone']}}</p>
              <a href="mailto:info@company.com" class="underline">Email: {{$shopConfig['business_contact_email']}}</a>
            </div>
          </div>
        </div>
      
        <!-- Bottom part -->
    </div>
    <div class="py-6 border-t border-gray-line">
        <div class="container mx-auto px-4 flex flex-wrap justify-between items-center">
                    <!-- Copyright and Links -->
        <div class="w-full lg:w-3/4 text-center lg:text-left mb-4 lg:mb-0">
            <p class="mb-2 font-bold">&copy; {{ date('Y') }} {{$shopConfig['business_name']}}. All rights reserved.</p>
            <ul class="flex justify-center lg:justify-start space-x-4 mb-4 lg:mb-0">
            <li><a href="#" class="hover:text-primary">Privacy Policy</a></li>
            <li><a href="#" class="hover:text-primary">Terms of Service</a></li>
            <li><a href="#" class="hover:text-primary">FAQ</a></li>
            </ul>
            <p class="text-sm mt-4">Your shop's description goes here. This is a brief introduction to your shop and what you offer.</p>
        </div>
        <!-- Payment Icons -->
        <div class="w-full lg:w-1/4 text-center lg:text-right">
            <img src="{{ asset('storage/images/social_icons/paypal.svg') }}" alt="PayPal" class="inline-block h-8 mr-2">
            <img src="{{ asset('storage/images/social_icons/stripe.svg') }}"   alt="Stripe" class="inline-block h-8 mr-2">
            <img src="{{ asset('storage/images/social_icons/visa.svg') }}"  alt="Visa" class="inline-block h-8">
        </div>
        </div>

    </div>
</footer>

<!-- Alpine.js -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@vite([
        'resources/js/script.js',
    ])
<!-- Toastify JS -->
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<!-- Inline JS for Cart and Toasts -->
<script>
// window.isLoggedIn = @json(auth()->check());
window.isLoggedIn = @json(auth()->check() && auth()->user()->hasVerifiedEmail());
document.addEventListener('DOMContentLoaded', function () {
    // Toast notifications
    @if(session('success'))
        Toastify({text:"{{ session('success') }}", duration:5000, close:true, gravity:"top", position:"right", backgroundColor:"linear-gradient(to right, #4CAF50, #45A049)"}).showToast();
    @endif
    @if(session('error'))
        Toastify({text:"{{ session('error') }}", duration:5000, close:true, gravity:"top", position:"right", backgroundColor:"linear-gradient(to right, #f44336, #e53935)"}).showToast();
    @endif

    const updateCartCount = count => {
        const desktop = document.getElementById('cart-count-desktop');
        const mobile = document.getElementById('cart-count-mobile');
        const menu = document.getElementById('cart-count-menu'); // <-- new

        if(desktop) desktop.textContent = count;
        if(mobile) mobile.textContent = count;
        if(menu) menu.textContent = count; // <-- update the menu count too

    };

    const refreshMiniCart = () => {
        fetch("{{ route('cart.mini') }}").then(res => res.json()).then(data => {
            const itemsContainer = document.getElementById('mini-cart-items');
            const subtotalEl = document.getElementById('mini-cart-subtotal');
            if(!itemsContainer || !subtotalEl) return;
            itemsContainer.innerHTML = '';
            data.items.forEach(item => {
                const li = document.createElement('li');
                li.dataset.id = item.id;
                li.className = 'flex justify-between items-center';
                li.innerHTML = `
                    <div class="flex items-center space-x-2">
                        <img src="${item.image}" class="w-12 h-12 object-cover rounded">
                        <div>
                            <p class="text-sm">${item.name}</p>
                            <div class="flex items-center mt-1 space-x-1">
                                <button class="decrease px-2 py-1 bg-gray-200 rounded">-</button>
                                <span class="quantity px-2">${item.quantity}</span>
                                <button class="increase px-2 py-1 bg-gray-200 rounded">+</button>
                            </div>
                        </div>
                    </div>
                    <span class="text-sm font-semibold subtotal">$${(item.price*item.quantity).toFixed(2)}</span>
                    <button class="remove text-red-500 ml-2">🗑️</button>
                `;
                itemsContainer.appendChild(li);
            });
            subtotalEl.textContent = `$${data.subtotal}`;
            updateCartCount(data.total_count);
            attachMiniCartEvents();
        });
    };

    const attachMiniCartEvents = () => {
        document.querySelectorAll('#mini-cart-items li').forEach(li => {
            const id = li.dataset.id;
            const decreaseBtn = li.querySelector('.decrease');
            const increaseBtn = li.querySelector('.increase');
            const removeBtn = li.querySelector('.remove');
            const qtySpan = li.querySelector('.quantity');

            decreaseBtn?.addEventListener('click', () => {
                let qty = parseInt(qtySpan.textContent);
                if(qty > 1) updateCartItem(id, qty - 1);
            });
            increaseBtn?.addEventListener('click', () => {
                let qty = parseInt(qtySpan.textContent);
                updateCartItem(id, qty + 1);
            });
            removeBtn?.addEventListener('click', () => {
                fetch(`/cart/remove/${id}`, {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}})
                    .then(res=>res.json()).then(data=>{ if(data.success) refreshMiniCart(); });
            });
        });
    };

    const updateCartItem = (id, qty) => {
        fetch(`/cart/update/${id}`, {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'}, body:JSON.stringify({quantity: qty})})
            .then(res=>res.json()).then(data=>{ if(data.success) refreshMiniCart(); else alert(data.message||'Error updating cart'); });
    };

    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', () => {
            if(!window.isLoggedIn){
                Toastify({
                    text: "Please login to add items to cart",
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "linear-gradient(to right,#f44336,#e53935)"
                }).showToast();

                setTimeout(() => {
                    window.location.href = "/login";
                }, 1200);
                return;
            }
            const productId = btn.dataset.productId;
            fetch('/cart/add',{method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'}, body:JSON.stringify({product_id:productId,quantity:1})})
            .then(res=>res.json())
            .then(data=>{
                if(data.success){
                    refreshMiniCart();
                    Toastify({text:"Added to cart", duration:3000, gravity:"top", position:"right", backgroundColor:"linear-gradient(to right,#4CAF50,#45A049)"}).showToast();
                    if(data.cart_count) updateCartCount(data.cart_count);
                } else {
                    Toastify({text:data.message, duration:3000, gravity:"top", position:"right", backgroundColor:"linear-gradient(to right,#f44336,#e53935)"}).showToast();
                }
            });
        });
    });

    refreshMiniCart();
});
</script>

</body>
</html>
