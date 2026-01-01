<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Cache latest products for 10 minutes
        $latestProducts = Cache::remember('latest_products', 600, function () {
            return Product::where('status', 1)
                ->where('published', 1)
                ->orderByDesc('created_at')
                ->take(8)
                ->get();
        });

        // Cache popular products for 10 minutes
        $popularProducts = Cache::remember('popular_products', 600, function () {
            return Product::where('status', 1)
                ->where('published', 1)
                ->orderByDesc('views')
                ->take(8)
                ->get();
        });

        // Cache categories & brands for 1 hour
        $categories = Cache::remember('categories_active', 3600, function () {
            return Category::where('status', 1)->get();
        });

        $brands = Cache::remember('brands_active', 3600, function () {
            return Brand::where('status', 1)->get();
        });

        return view('home', compact(
            'latestProducts',
            'popularProducts',
            'categories',
            'brands'
        ));
    }

}
