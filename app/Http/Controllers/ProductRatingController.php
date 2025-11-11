<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductRatingController extends Controller
{
    public function store(Request $request, $productId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);


        $product = Product::findOrFail($productId);

        // Prevent multiple ratings by same user
        $existing = ProductRating::where('product_id', $productId)
            ->where('user_id', Auth::id())
            ->first();

        if ($existing) {
            $existing->update([
                'rating' => $request->rating,
                'review' => $request->review,
            ]);
        } else {
            ProductRating::create([
                'product_id' => $productId,
                'user_id' => Auth::id(),
                'rating' => $request->rating,
                'review' => $request->review,
            ]);
        }

        return back()->with('success', 'Thanks for your review!');
    }
}
