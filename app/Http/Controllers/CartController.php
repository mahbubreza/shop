<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Add product to cart
     */
    public function addToCart(Request $request)
    {        
        // If user is not logged in → send JSON response for JS
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'auth_required' => true,
                'message' => 'Please login to add items to your cart.'
            ]);
        }
        $user = Auth::user();
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        $qty = $request->quantity ?? 1;
        $availableStock = $product->stock - $product->reserved_stock;
        // Check if cart already has quantity
        $existingCart = CartItem::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        $currentQtyInCart = $existingCart ? $existingCart->quantity : 0;
        if($currentQtyInCart + $qty > $availableStock) {    
            return response()->json([
                'success' => false,
                'message' => "Cannot add {$qty} items. Only {$availableStock} left in stock."
            ]);
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
        return response()->json([
            'success' => true,
            'message' => 'Added to cart successfully.',
            'cart_count' => CartItem::where('user_id', $user->id)->count()
        ]);
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
    // 
    public function remove($id)
    {
        $item = CartItem::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        $item->delete();

        return response()->json([
            'success' => true,
            'cart_count' => Auth::user()->cartItems()->count()
        ]);
    }

    public function miniCart()
    {
        if (!auth()->check()) {
            return response()->json([
                'items' => [],
                'subtotal' => 0,
                'total_count' => 0,

            ]);
        }

        $cartItems = auth()->user()->cartItems()->with('product')->get();

        $now = Carbon::now();

        $items = $cartItems->map(function ($item) use ($now) {

            // Discount logic
            $isDiscounted =
                $item->product->discounted_price > 0 &&
                $item->product->discount_start_date &&
                $item->product->discount_end_date &&
                $now->between(
                    Carbon::parse($item->product->discount_start_date),
                    Carbon::parse($item->product->discount_end_date)
                );

            $finalPrice = $isDiscounted
                ? $item->product->discounted_price
                : $item->product->price;

            return [
                'id' => $item->id,
                'name' => $item->product->name,
                'image' => asset('storage/' . $item->product->image),
                'price' => $finalPrice,
                'quantity' => $item->quantity,
            ];
        });

        // Subtotal calculation using discounted price
        $subtotal = $items->sum(function ($i) {
            return $i['price'] * $i['quantity'];
        });

        return response()->json([
            'items' => $items,
            'subtotal' => $subtotal,
            'total_count' => $cartItems->sum('quantity'),

        ]);
    }

    // public function miniCart()
    // {
    //     $cartItems = Auth::user()->cartItems()->with('product')->get();
    //     $now = Carbon::now();

    //     $isDiscounted = $item->product->discounted_price > 0 &&
    //         $now->between(
    //             Carbon::parse($item->product->discount_start_date),
    //             Carbon::parse($item->product->discount_end_date)
    //         );

    //     $finalPrice = $isDiscounted ? $item->product->discounted_price : $item->product->price;


    //     $items = $cartItems->map(fn($item) => [
    //         'id' => $item->id,
    //         'name' => $item->product->name,
    //         'price' => $item->product->price,
    //         'quantity' => $item->quantity,
    //         'image' => asset('storage/' . $item->product->image)
    //     ]);

    //     return response()->json([
    //         'items' => $items,
    //         'subtotal' => $cartItems->sum(fn($i) => $i->product->price * $i->quantity),
    //     ]);
    // }
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = CartItem::where('user_id', auth()->id())->findOrFail($id);
        $product = $cartItem->product;

        $availableStock = $product->stock - $product->reserved_stock;
        if($request->quantity > $availableStock){
            return response()->json([
                'success' => false,
                'message' => "Only $availableStock units of {$product->name} available."
            ]);
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return response()->json(['success' => true]);
    }


}
