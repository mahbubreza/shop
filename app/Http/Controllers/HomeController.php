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
        
        return view('home', [
            'products' => Product::where('status', 1)->get(),
            'categories' => Category::where('status', 1)->get(),
            'brands' => Brand::where('status', 1)->get(),
        ]);
    }
}
