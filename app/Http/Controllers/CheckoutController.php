<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Services\StockService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        $cartItems = CartItem::with('product')
            ->where('user_id', $user->id)
            ->get()
            ->map(function($item) {
                return [
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'price'      => $item->product->price,
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
        return view('shop.payment', compact('orderId'));
    }

    /**
     * Simulate payment success callback
     */
    public function paymentSuccess($orderId)
    {
        try {
            $order = $this->stockService->confirmStock($orderId);

            return redirect()->route('shop.index')
                ->with('success', "Payment successful. Order #{$order->id} confirmed.");
        } catch (\Exception $e) {
            return redirect()->route('shop.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Simulate payment failure
     */
    public function paymentFail($orderId)
    {
        try {
            $order = $this->stockService->releaseStock($orderId);

            return redirect()->route('shop.index')
                ->with('error', "Payment failed. Order #{$order->id} cancelled.");
        } catch (\Exception $e) {
            return redirect()->route('shop.index')
                ->with('error', $e->getMessage());
        }
    }
}
