<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="icon" href="{{ asset('storage/images/favicon.png') }}" />
    <title>Home page</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    @vite([
        'resources/css/app.css',
        'resources/css/styles.css',
        'resources/css/custom.css',
        'resources/js/app.js',
        'resources/js/script.js'
    ])
</head>

<body x-data="{ mobileMenuOpen: false }">

    <!-- Header -->
    <header class="bg-gray-dark sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center py-4">
            <a href="/" class="flex items-center">
                <img src="{{ asset('storage/images/walstyle.png') }}" class="h-24 rounded" width="100">
            </a>

            <!-- Hamburger menu -->
            <div class="flex lg:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-white focus:outline-none">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>

            <!-- Desktop Menu -->
            <nav class="hidden lg:flex md:flex-grow justify-center">
                <ul class="flex justify-center space-x-4 text-white">
                    <li><a href="/" class="hover:text-secondary font-semibold">Home</a></li>
                    <li><a href="/products/list" class="hover:text-secondary font-semibold">Products</a></li>
                    <li><a href="#brands" class="hover:text-secondary font-semibold">Brands</a></li>

                    <!-- Category Dropdown -->
                    <li class="relative group" x-data="{ open: false }">
                        <a href="#" @mouseover="open = true" @mouseleave="open = false"
                            class="hover:text-secondary font-semibold flex items-center">
                            Categories
                            <i :class="open ? 'fas fa-chevron-up ml-1 text-xs' : 'fas fa-chevron-down ml-1 text-xs'"></i>
                        </a>
                        <ul x-show="open" @mouseover="open = true" @mouseleave="open = false"
                            class="absolute left-0 bg-white text-black space-y-2 mt-1 p-2 rounded shadow-lg"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 scale-90"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-90">
                            @isset($categories)
                                @foreach ($categories as $category)
                                    <li><a href="products/list?category={{$category->id}}" class="min-w-56 block px-4 py-2 whitespace-nowrap hover:bg-primary hover:text-white rounded">{{$category->name}}</a></li>
                                @endforeach
                            @endisset
                        </ul>
                    </li>
                    <li><a href="/contact" class="hover:text-secondary font-semibold">Contact Us</a></li>
                </ul>
            </nav>

            <!-- Right Buttons -->
            <div class="hidden lg:flex items-center space-x-4 relative">
                @guest
                    <a href="/register"
                        class="bg-primary border border-primary hover:bg-transparent text-white hover:text-primary font-semibold px-4 py-2 rounded-full">
                        Register
                    </a>
                    <a href="/login"
                        class="bg-primary border border-primary hover:bg-transparent text-white hover:text-primary font-semibold px-4 py-2 rounded-full">
                        Login
                    </a>
                @endguest
                @auth
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit"
                            class="bg-primary border border-primary hover:bg-transparent text-white hover:text-primary font-semibold px-4 py-2 rounded-full">
                            Logout
                        </button>
                    </form>
                @endauth

                <div class="relative group cart-wrapper">
                    <a href="/cart.html">
                        <img src="{{ asset('storage/images/cart-shopping.svg') }}" alt="Cart" class="h-6 w-6 group-hover:scale-120">
                    </a>
                    <div class="absolute right-0 mt-1 w-80 bg-white shadow-lg p-4 rounded hidden group-hover:block">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between pb-4 border-b border-gray-line">
                                <div class="flex items-center">
                                    <img src="{{ asset('storage/images/single-product/1.jpg') }}" alt="Product"
                                        class="h-12 w-12 object-cover rounded mr-2">
                                    <div>
                                        <p class="font-semibold">Summer black dress</p>
                                        <p class="text-sm">Quantity: 1</p>
                                    </div>
                                </div>
                                <p class="font-semibold">$25.00</p>
                            </div>
                        </div>
                        <a href="/cart.html" class="block text-center mt-4 border border-primary bg-primary hover:bg-transparent text-white hover:text-primary py-2 rounded-full font-semibold">Go to Cart</a>
                    </div>
                </div>

                <a id="search-icon-desktop" href="javascript:void(0);" class="text-white hover:text-secondary group">
                    <img src="{{ asset('storage/images/search-icon.svg') }}" alt="Search" class="h-6 w-6 transition-transform transform group-hover:scale-120">
                </a>

                <div id="search-field-desktop" class="hidden absolute top-full right-0 mt-2 w-72 bg-white shadow-lg p-2 rounded">
                    <form action="{{ route('products.list') }}" method="GET" class="flex">
                        <input type="text" name="search" value="{{ request('search') }}" class="w-full p-2 border border-gray-300 rounded-l-full focus:outline-none" placeholder="Search for products...">
                        <button type="submit" class="bg-primary text-white px-4 rounded-r-full hover:bg-transparent hover:text-primary border border-primary transition">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </header>

    <!-- Mobile Menu -->
    <nav x-show="mobileMenuOpen" class="lg:hidden flex flex-col items-center space-y-8">
        <ul class="w-full text-center">
            <li><a href="/" class="hover:text-secondary font-bold block py-2">Home</a></li>
            <li><a href="/products/list" class="hover:text-secondary font-bold block py-2">Products</a></li>
            <li><a href="#brands" class="hover:text-secondary font-bold block py-2">Brands</a></li>
            <li class="relative" x-data="{ open: false }">
                <a @click.prevent="open = !open" class="hover:text-secondary font-bold block py-2 flex justify-center items-center cursor-pointer">
                    Categories
                    <i :class="open ? 'fas fa-chevron-up ml-2 text-xs' : 'fas fa-chevron-down ml-2 text-xs'"></i>
                </a>
                <ul x-show="open" class="space-y-2">
                    @isset($categories)
                        @foreach ($categories as $category)
                            <li><a href="products/list?category={{$category->id}}" class="hover:text-secondary font-bold block py-2">{{$category->name}}</a></li>
                        @endforeach
                    @endisset
                </ul>
            </li>
            <li><a href="/contact" class="hover:text-secondary font-bold block py-2">Contact Us</a></li>
        </ul>

        <div class="flex flex-col mt-6 space-y-2 items-center">
            <a href="/register" class="bg-primary hover:bg-transparent text-white hover:text-primary border border-primary font-semibold px-4 py-2 rounded-full min-w-[110px]">Register</a>
            <a href="/login" class="bg-primary hover:bg-transparent text-white hover:text-primary border border-primary font-semibold px-4 py-2 rounded-full min-w-[110px]">Login</a>
        </div>
    </nav>

    {{ $slot }}

    <!-- Footer -->
    <footer class="border-t border-gray-line">
        <div class="container mx-auto px-4 py-10">
            <div class="flex flex-wrap -mx-4">
                <!-- Menu sections here (same as your original layout) -->
            </div>
        </div>
        <div class="py-6 border-t border-gray-line">
            <div class="container mx-auto px-4 flex flex-wrap justify-between items-center">
                <p class="mb-2 font-bold">&copy; {{ date('Y') }} Your Company. All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>
</html>
