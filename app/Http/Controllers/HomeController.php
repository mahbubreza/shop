<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class HomeController extends Controller
{
    public function index(Request $request)
    {      
        // Latest products (new arrivals)
        $latestProducts = Product::where('status', 1)
        ->orderBy('created_at', 'desc')
        ->take(8) // limit to 8 or any number you want
        ->get();

        // Popular products (based on views or sold_count)
        $popularProducts = Product::where('status', 1)
            ->orderBy('views', 'desc') // or 'sold_count'
            ->take(8)
            ->get();
        
        return view('home', [
            'latestProducts' => $latestProducts,
            'popularProducts' => $popularProducts,
            'categories' => Category::where('status', 1)->get(),
            'brands' => Brand::where('status', 1)->get(),
        ]);
    }
}
