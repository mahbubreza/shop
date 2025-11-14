<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\StockService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Show checkout page
     */
    public function index()
    {
        $cartItems = CartItem::with('product')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        return view('shop.checkout', compact('cartItems'));
    }

    /**
     * Place order (reserve stock)
     */
    public function placeOrder(Request $request)
    {
        $user = Auth::user();

        $now = Carbon::now();
        $cartItems = CartItem::with('product')
        ->where('user_id', $user->id)
        ->get()
        ->map(function($item) use ($now) {
            $product = $item->product;

            // Check if discounted price applies
            $isDiscounted = $product->discounted_price > 0
                && $now->between(
                    \Carbon\Carbon::parse($product->discount_start_date),
                    \Carbon\Carbon::parse($product->discount_end_date)
                );

            $price = $isDiscounted ? $product->discounted_price : $product->price;

            return [
                'product_id' => $product->id,
                'quantity'   => $item->quantity,
                'price'      => $price,
            ];
        })
        ->toArray();

        if (empty($cartItems)) {
            return redirect()->back()->with('error', 'Your cart is empty');
        }

        try {
            // Reserve stock and create pending order
            $order = $this->stockService->reserveStock($cartItems, $user->id);

            // Clear cart after reservation
            CartItem::where('user_id', $user->id)->delete();

            // Redirect to payment page (example)
            return redirect()->route('checkout.payment', $order->id)
                ->with('success', 'Order placed. Proceed to payment.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Simulate payment page
     */
    public function payment($orderId)
    {
        $order = Order::findOrFail($orderId);

        if ($order->status === 'completed') {
            return redirect()->route('home')
                ->with('error', "This payment is already completed.");
        }

        return view('shop.payment', compact('orderId'));
    }


    /**
     * Payment success handler
     */
    public function paymentSuccess($orderId)
    {
        try {
            $order = $this->stockService->confirmStock($orderId);

            return redirect()->route('home')
                ->with('success', "Payment successful. Order #{$order->id} confirmed.");
        } catch (\Exception $e) {
            // Handle already completed orders gracefully
            return redirect()->route('home')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Payment failure / cancel simulation
     */
    public function paymentFail($orderId)
    {
        try {
            $order = $this->stockService->releaseStock($orderId);

            return redirect()->route('home')
                ->with('error', "Payment failed. Order #{$order->id} cancelled.");
        } catch (\Exception $e) {
            // Handle orders that cannot be cancelled (already completed)
            return redirect()->route('home')
                ->with('error', $e->getMessage());
        }
    }
}
