<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce</title>
    @vite('resources/css/app.css') {{-- Tailwind via Vite --}}
</head>
<body class="bg-gray-100 text-gray-900">
    <!-- Navbar -->
    <nav class="bg-white shadow mb-6">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <a href="{{ route('home') }}" class="text-xl font-bold">MyShop</a>
            <div class="space-x-4">
                <a href="{{ route('products.index') }}" class="hover:text-blue-600">Products</a>
                <a href="{{ route('categories.index') }}" class="hover:text-blue-600">Categories</a>
                <a href="{{ url('/cart') }}" class="hover:text-blue-600">Cart</a>
                @auth
                    <a href="{{ route('orders.index') }}" class="hover:text-blue-600">Orders</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="max-w-7xl mx-auto px-4">
        {{$slot}}
    </div>
</body>
</html>