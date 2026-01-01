<?php

namespace App\Providers;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        
        // View::composer('*', function ($view) {
        //     $view->with('categories', Category::where('status', 1)->get());
        //     $view->with('brands', Brand::where('status', 1)->get());
        // });
        View::composer('*', function ($view) {
        $categories = Cache::remember('categories_active', 3600, function () {
            return Category::where('status', 1)->get();
        });

        $brands = Cache::remember('brands_active', 3600, function () {
            return Brand::where('status', 1)->get();
        });

        // Cart count (for logged-in users)

        $cartCount = Auth::check() ? Auth::user()->cartItems->count(): 0;

        $view->with(compact('categories', 'brands', 'cartCount'));
    });
    }
}
