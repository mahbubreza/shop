@extends('layouts.app2')

@section('content')

<!-- Navbar -->
<nav class="bg-white dark:bg-gray-800 shadow-sm py-3 px-4 flex justify-between items-center fixed w-full top-0 z-50 transition-colors">
    <div class="flex items-center gap-4">
        <button id="menuToggle" class="lg:hidden text-gray-700 dark:text-gray-200 text-2xl"><i class="fa fa-bars"></i></button>
        <h1 class="text-2xl font-bold text-blue-600 dark:text-blue-400">MyShop</h1>
    </div>
    <div class="hidden md:flex w-1/3">
        <input type="text" placeholder="Search products..." class="w-full border dark:border-gray-600 rounded-l-lg p-2 focus:outline-none bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200">
        <button class="bg-blue-600 dark:bg-blue-500 text-white px-4 rounded-r-lg"><i class="fa fa-search"></i></button>
    </div>
    <div class="flex gap-4 text-gray-700 dark:text-gray-200">
        <i class="fa fa-user text-xl">Mahbub</i>
        <i class="fa fa-heart text-xl"></i>
        <i class="fa fa-shopping-cart text-xl">Cart</i>
        <button id="darkModeToggle" class="ml-2 text-lg"><i class="fa fa-moon"></i>Toggle</button>
    </div>
</nav>

<!-- Mobile Sidebar -->
<div id="mobileMenu" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40">
    <div class="bg-white dark:bg-gray-800 w-64 h-full p-4 transition-colors">
        <button id="closeMenu" class="text-gray-700 dark:text-gray-200 text-xl mb-4"><i class="fa fa-times"></i></button>
        <h3 class="font-semibold mb-3 text-gray-900 dark:text-gray-200">Categories</h3>
        <ul class="space-y-2">
            @foreach($categories as $category)
                <li><a href="#" class="block hover:text-blue-600 dark:hover:text-blue-400">{{ $category->name }}</a></li>
            @endforeach
        </ul>
    </div>
</div>

<!-- MAIN CONTENT -->
<div class="pt-20 max-w-7xl mx-auto px-4 py-6 grid grid-cols-12 gap-4">
    <!-- Sidebar -->
    <aside class="hidden lg:block col-span-3 bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 transition-colors">
        <h3 class="text-lg font-semibold mb-3 text-gray-900 dark:text-gray-200">Categories</h3>
        <ul class="space-y-2">
            @foreach($categories as $category)
                <li><a href="#" class="block hover:bg-blue-50 dark:hover:bg-gray-700 p-2 rounded transition">{{ $category->name }}</a></li>
            @endforeach
        </ul>
    </aside>

    <!-- Main -->
    Carousal
    <section class="col-span-12 lg:col-span-9"> 
        <!-- Carousel -->
        <div class="relative overflow-hidden rounded-lg shadow transition-colors bg-white dark:bg-gray-900">
            <div id="carousel" class="relative h-64 md:h-80 overflow-hidden">
                @foreach($banners as $index => $banner)
                    <div class="absolute inset-0 transition-opacity duration-1000 opacity-{{ $loop->first ? '100' : '0' }}">
                        <img src="{{ asset('storage/'.$banner->image) }}" alt="Banner" class="w-full h-full object-cover">
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Featured Products -->
        <div class="mt-8">
            <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-gray-200">Featured Products</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($featuredProducts as $product)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm hover:shadow-md p-3 transition-colors">
                    <img src="{{ asset('storage/'.$product->image) }}" class="w-full h-40 object-cover rounded-md">
                    <h3 class="mt-2 text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $product->name }}</h3>
                    <p class="text-blue-600 dark:text-blue-400 font-bold">${{ $product->price }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Best Selling -->
        <div class="mt-10">
            <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-gray-200">Best Selling</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($bestSelling as $product)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm hover:shadow-md transition-colors p-3">
                    <img src="{{ asset('storage/'.$product->image) }}" class="w-full h-32 object-cover rounded-md">
                    <h3 class="text-sm mt-2 text-gray-800 dark:text-gray-200">{{ $product->name }}</h3>
                    <p class="font-semibold text-blue-600 dark:text-blue-400">${{ $product->price }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <!-- TODAY'S DEALS -->
        @include('partials.todays-deals')

        <!-- TOP RATED PRODUCTS -->
        <div class="mt-10">
            <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-gray-200">Top Rated Products</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($topRated as $product)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-md p-3 transition-colors">
                    <img src="{{ asset('storage/'.$product->image) }}" class="w-full h-36 object-cover rounded-md">
                    <h3 class="mt-2 text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $product->name }}</h3>
                    <p class="text-blue-600 dark:text-blue-400 font-bold">${{ $product->price }}</p>
                    <div class="mt-1">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fa fa-star {{ $i <= $product->rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}"></i>
                        @endfor
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $topRated->links('pagination::tailwind') }}
            </div>
        </div>
    </section>
</div>

@endsection
