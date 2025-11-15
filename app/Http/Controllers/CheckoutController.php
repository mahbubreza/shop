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
        $shopConfig = config('shop');
        $now = Carbon::now();

        $cartItems = CartItem::with('product')
            ->where('user_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Your cart is empty');
        }

        $finalSubtotal = 0;
        $orderItems = [];

        // 1️⃣ Check availability and calculate subtotal
        foreach ($cartItems as $item) {
            $product = $item->product;

            // Check stock availability
            $available = $product->stock - $product->reserved_stock;
            if ($item->quantity > $available) {
                return redirect()->back()->with('error', "Not enough stock for {$product->name}. Available: {$available}");
            }

            // Discount logic
            $isDiscounted = $product->discounted_price > 0
                && $now->between(
                    Carbon::parse($product->discount_start_date),
                    Carbon::parse($product->discount_end_date)
                );
            $price = $isDiscounted ? $product->discounted_price : $product->price;
            $subtotal = $price * $item->quantity;
            $finalSubtotal += $subtotal;

            $orderItems[] = [
                'product_id' => $product->id,
                'quantity'   => $item->quantity,
                'price'      => $price,
            ];
        }

        // Shipping charge
        $shippingCharge = $request->input('shipping_charge', $shopConfig['inside_dhaka_shipping_charge']);

        // Payment method
        $paymentMethod = $request->input('payment_method', 'cod');

        // MFS charge (only for digital payment)
        $mfsCharge = 0;
        if ($shopConfig['mfs_charge'] && $paymentMethod !== 'cod') {
            $mfsCharge = ($finalSubtotal + $shippingCharge) * ($shopConfig['mfs_percentage'] / 100);
        }

        // VAT (applied on subtotal + shipping)
        $vat = 0;
        if ($shopConfig['vat_applicable']) {
            $vat = ($finalSubtotal + $shippingCharge) * ($shopConfig['vat_percent'] / 100);
        }

        // Total
        $total = $finalSubtotal + $shippingCharge + $mfsCharge + $vat;

        try {
            // 2️⃣ Create order
            $order = Order::create([
                'user_id' => $user->id,
                'sub_total' => $finalSubtotal,
                'shipping_charge' => $shippingCharge,
                'mfs_charge' => $mfsCharge,
                'vat' => $vat,
                'total' => $total,
                'shipping_address' => $request->input('shipping_address'),
                'status' => 'pending',
            ]);

            // 3️⃣ Reserve stock and create order items
            $this->stockService->reserveStock($orderItems, $user->id, $order->id);

            // 4️⃣ Clear cart
            CartItem::where('user_id', $user->id)->delete();

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
