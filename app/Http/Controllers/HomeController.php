<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // $categories = collect([
        //     (object)['name' => 'Electronics'],
        //     (object)['name' => 'Fashion'],
        //     (object)['name' => 'Home & Kitchen'],
        //     (object)['name' => 'Beauty & Health'],
        //     (object)['name' => 'Sports & Outdoors'],
        // ]);
        $categories = Category::all();

        $banners = collect([
            (object)['image' => 'banners/banner1.jpg'],
            (object)['image' => 'banners/banner2.jpg'],
            (object)['image' => 'banners/banner3.jpg'],
        ]);

        $featuredProducts = collect([
            (object)['name' => 'Smartphone X200', 'price' => 499, 'image' => 'products/phone.jpg'],
            (object)['name' => 'Wireless Earbuds', 'price' => 59, 'image' => 'products/earbuds.jpg'],
            (object)['name' => 'Gaming Mouse', 'price' => 35, 'image' => 'products/mouse.jpg'],
            (object)['name' => 'Smart Watch', 'price' => 99, 'image' => 'products/watch.jpg'],
        ]);

        $bestSelling = collect([
            (object)['name' => 'Bluetooth Speaker', 'price' => 45, 'image' => 'products/speaker.jpg'],
            (object)['name' => 'Laptop Bag', 'price' => 25, 'image' => 'products/laptop-bag.jpg'],
            (object)['name' => 'Keyboard', 'price' => 40, 'image' => 'products/keyboard.jpg'],
            (object)['name' => 'Power Bank', 'price' => 30, 'image' => 'products/power-bank.jpg'],
        ]);

        $todaysDeals = collect([
            (object)['name' => 'Fitness Tracker', 'price' => 80, 'old_price' => 120, 'image' => 'products/tracker.jpg'],
            (object)['name' => 'Wireless Earbuds', 'price' => 30, 'old_price' => 50, 'image' => 'products/earbuds.jpg'],
            (object)['name' => 'Smartphone', 'price' => 499, 'old_price' => 600, 'image' => 'products/phone.jpg'],
            (object)['name' => 'Laptop Bag', 'price' => 25, 'old_price' => 40, 'image' => 'products/laptop-bag.jpg'],
            (object)['name' => 'Bluetooth Speaker', 'price' => 45, 'old_price' => 70, 'image' => 'products/speaker.jpg'],
        ]);

        $topRatedCollection = collect([
            (object)['name' => '4K TV', 'price' => 799, 'rating' => 5, 'image' => 'products/tv.jpg'],
            (object)['name' => 'Wireless Headphones', 'price' => 120, 'rating' => 4, 'image' => 'products/headphones.jpg'],
            (object)['name' => 'Digital Camera', 'price' => 250, 'rating' => 4, 'image' => 'products/camera.jpg'],
            (object)['name' => 'Smart Lamp', 'price' => 40, 'rating' => 5, 'image' => 'products/lamp.jpg'],
            (object)['name' => 'Bluetooth Keyboard', 'price' => 60, 'rating' => 3, 'image' => 'products/keyboard.jpg'],
            (object)['name' => 'Tablet Pro', 'price' => 350, 'rating' => 4, 'image' => 'products/tablet.jpg'],
            (object)['name' => 'Drone X', 'price' => 999, 'rating' => 5, 'image' => 'products/drone.jpg'],
        ]);

        $perPage = 4;
        $page = $request->get('page', 1);
        $topRated = new LengthAwarePaginator(
            $topRatedCollection->forPage($page, $perPage),
            $topRatedCollection->count(),
            $perPage,
            $page,
            ['path' => route('home')]
        );

        return view('home', compact('categories', 'banners', 'featuredProducts', 'bestSelling', 'todaysDeals', 'topRated'));
    }
}
