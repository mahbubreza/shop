<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CKEditorController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductRatingController;
use App\Http\Controllers\ProfileController;
use App\Models\Category;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/ckeditor/upload', [CKEditorController::class, 'upload'])->name('ckeditor.upload');

Route::get('/products/list', [ProductController::class, 'list'])->name('products.list');


Route::get('/products/{product}/details', [ProductController::class, 'details']);

Route::middleware(['auth', 'admin'])->group(function() {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/brands', [BrandController::class, 'index'])->name('brands');
    Route::get('/brands/create', [BrandController::class, 'create']);
    Route::get('/brands/{brand}', [BrandController::class, 'show']);
    Route::post('/brands', [BrandController::class, 'store']);
    Route::get('/brands/{brand}/edit', [BrandController::class, 'edit']);
    Route::patch('/brands/{brand}', [BrandController::class, 'update']);
    Route::delete('/brands/{brand}', [BrandController::class, 'destroy']);
    Route::post('/brands/{brand}/toggle', [BrandController::class, 'toggleStatus'])->name('brands.toggle');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
    Route::get('/categories/create', [CategoryController::class, 'create']);
    Route::get('/categories/{category}', [CategoryController::class, 'show']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit']);
    Route::patch('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
    Route::post('/categories/{category}/toggle', [CategoryController::class, 'toggleStatus'])->name('categories.toggle');


    Route::get('/products', [ProductController::class, 'index'])->name('products');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');;
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/products/{product}/edit', [ProductController::class, 'edit']);
    Route::get('/products/{product}', [ProductController::class, 'show']);

    Route::patch('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);
    Route::post('/products/{product}/toggle', [ProductController::class, 'toggleStatus'])->name('products.toggle');

    Route::get('/coupons', [CouponController::class,'index'])->name('admin.coupons.index');
    Route::get('/coupons/create', [CouponController::class,'create'])->name('admin.coupons.create');
    Route::post('/coupons', [CouponController::class,'store'])->name('admin.coupons.store');
    Route::get('/coupons/{coupon}/edit', [CouponController::class,'edit'])->name('admin.coupons.edit');
    Route::patch('/coupons/{coupon}', [CouponController::class,'update'])->name('admin.coupons.update');
    Route::delete('/coupons/{coupon}', [CouponController::class,'destroy'])->name('admin.coupons.destroy');
    Route::post('/coupons/{coupon}/toggle', [CouponController::class,'toggle'])->name('coupons.toggle');

    Route::post('/checkout/apply-coupon', [CheckoutController::class, 'applyCoupon'])->name('checkout.applyCoupon');

});


Route::get('/cart/mini', [CartController::class, 'miniCart'])->name('cart.mini');

Route::middleware('auth')->group(function () {
   Route::post('/products/{product}/rate', [ProductRatingController::class, 'store'])->name('products.rate');

   Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
   Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');

   Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
   Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');


    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/place', [CheckoutController::class, 'placeOrder'])->name('checkout.place');

    Route::get('/checkout/payment/{orderId}', [CheckoutController::class, 'payment'])->name('checkout.payment');
    Route::get('/checkout/payment-success/{orderId}', [CheckoutController::class, 'paymentSuccess'])->name('checkout.payment.success');
    Route::get('/checkout/payment-fail/{orderId}', [CheckoutController::class, 'paymentFail'])->name('checkout.payment.fail');

    

});

Route::post('/newsletter', [NewsletterController::class, 'store'])->name('newsletter.store');
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');




Route::get('/samples/create', function(){
    return view('samples.create');

});

require __DIR__.'/auth.php';
