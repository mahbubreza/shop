<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Add product to cart
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1'
        ]);

        $user = Auth::user();
        $product = Product::findOrFail($request->product_id);

        // Check stock
        if ($product->stock - $product->reserved_stock < $request->quantity) {
            return response()->json(['success' => false, 'message' => 'Not enough stock available.']);
        }

        // If product already exists in cart, update quantity
        $cartItem = CartItem::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $request->quantity);
        } else {
            CartItem::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Added to cart successfully.']);
    }

    /**
     * Show cart
     */
    public function index()
    {
        $cartItems = CartItem::with('product')->where('user_id', Auth::id())->get();
        return view('shop.cart', compact('cartItems'));
    }

    /**
     * Remove from cart
     */
    public function remove($id)
    {
        CartItem::where('id', $id)->where('user_id', Auth::id())->delete();
        return redirect()->back()->with('success', 'Item removed from cart');
    }
}
