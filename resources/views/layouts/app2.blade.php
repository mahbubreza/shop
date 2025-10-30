<!DOCTYPE html>
<html lang="en" class="transition-colors">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MyShop</title>
    <script src="https://kit.fontawesome.com/a2e0e6ad18.js" crossorigin="anonymous"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/carousel.js'])
</head>
<body class="bg-gray-100 dark:bg-gray-900 transition-colors">
    
    <main>
        @yield('content')
    </main>
</body>
</html>
