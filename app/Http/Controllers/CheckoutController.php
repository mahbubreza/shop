<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\StockService;
use Carbon\Carbon;
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

          // Coupon logic (same as applyCoupon)
        $couponId = $request->input('coupon_id');
        $couponDiscount = 0;
        $coupon = null;

        if ($couponId) {
            $coupon = Coupon::find($couponId);

            if (! $coupon || ! $coupon->isActive()) {
                return redirect()->back()->with('error', 'Coupon invalid or expired');
            }

            // global usage
            if ($coupon->max_uses && $coupon->used_count >= $coupon->max_uses) {
                return redirect()->back()->with('error', 'Coupon usage limit reached');
            }

            // min amount
            if ($finalSubtotal < $coupon->min_cart_amount) {
                return redirect()->back()->with('error', 'Cart amount does not meet coupon minimum');
            }

            $base = $finalSubtotal + $shippingCharge;

            if ($coupon->type === 'fixed') {
                $couponDiscount = min($coupon->value, $base);
            } else {
                $couponDiscount = ($base * $coupon->value) / 100.0;
            }
        }

        // MFS charge (only for digital payment)
        $mfsCharge = 0;
        if ($shopConfig['mfs_charge'] && $paymentMethod !== 'cod') {
            $mfsCharge = ($finalSubtotal + $shippingCharge - $couponDiscount) * ($shopConfig['mfs_percentage'] / 100);
        }

        // VAT (applied on subtotal + shipping)
        $vat = 0;
        if ($shopConfig['vat_applicable']) {
            $vat = ($finalSubtotal + $shippingCharge - $couponDiscount) * ($shopConfig['vat_percent'] / 100);
        }

        // Total
        $total = $finalSubtotal + $shippingCharge - $couponDiscount + $mfsCharge + $vat;

        DB::beginTransaction();

        try {
            // 2️⃣ Create order
            $order = Order::create([
                'user_id'         => $user->id,
                'sub_total'       => $finalSubtotal,
                'shipping_charge' => $shippingCharge,
                'mfs_charge'      => $mfsCharge,
                'vat'             => $vat,
                'coupon_id'       => $couponId ?? null,
                'coupon_discount' => $couponDiscount,
                'total'           => $total,
                'mobile_number'   => $request->input('mobile_number'),   // NEW
                'shipping_address'=> $request->input('shipping_address'),
                'status'          => 'pending',
            ]);

            // 3️⃣ Reserve stock and create order items
            $this->stockService->reserveStock($orderItems, $user->id, $order->id);

            // Update coupon usage count
            if ($coupon) {
                $coupon->increment('used_count');

                $existing = DB::table('coupon_user')->where([
                    'coupon_id' => $coupon->id,
                    'user_id'   => $user->id
                ])->first();

                if ($existing) {
                    DB::table('coupon_user')->where('id', $existing->id)
                        ->update(['uses' => $existing->uses + 1]);
                } else {
                    DB::table('coupon_user')->insert([
                        'coupon_id' => $coupon->id,
                        'user_id'   => $user->id,
                        'uses'      => 1,
                        'created_at'=> now(),
                        'updated_at'=> now(),
                    ]);
                }
            }

            // 4️⃣ Clear cart
            CartItem::where('user_id', $user->id)->delete();

            DB::commit();

            return redirect()->route('checkout.payment', $order->id)
                ->with('success', 'Order placed. Proceed to payment.');
        } catch (\Exception $e) {
            DB::rollBack();
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
    // public function applyCoupon(Request $request){

    
    //     $request->validate(['code' => 'required|string']);


    //     $code = strtoupper(trim($request->input('code')));
    //     $user = Auth::user();
    //     $coupon = Coupon::where('code', $code)->first();


    //     if (! $coupon) {
    //         return response()->json(['success' => false, 'message' => 'Invalid coupon code']);
    //     }


    //     if (! $coupon->isActive()) {
    //         return response()->json(['success' => false, 'message' => 'Coupon is not active or expired']);
    //     }


    //     // check global uses
    //     if ($coupon->max_uses && $coupon->used_count >= $coupon->max_uses) {
    //         return response()->json(['success' => false, 'message' => 'Coupon usage limit reached']);
    //     }


    //     // check per-user usage
    //     $userUses = DB::table('coupon_user')->where(['coupon_id' => $coupon->id, 'user_id' => $user->id])->value('uses') ?? 0;
    //     if ($coupon->max_uses_per_user && $userUses >= $coupon->max_uses_per_user) {
    //         return response()->json(['success' => false, 'message' => 'You have already used this coupon the maximum allowed times']);
    //     }


    //     // compute discount against the incoming cart subtotal
    //     $cartItems = CartItem::with('product')->where('user_id', $user->id)->get();
    //     $now = Carbon::now();
    //     $finalSubtotal = 0;
    //     foreach ($cartItems as $item) {
    //         $product = $item->product;
    //         $isDiscounted = $product->discounted_price > 0
    //         && $now->between(Carbon::parse($product->discount_start_date), Carbon::parse($product->discount_end_date));
    //         $price = $isDiscounted ? $product->discounted_price : $product->price;
    //         $finalSubtotal += $price * $item->quantity;
    //     }


    //     if ($finalSubtotal < $coupon->min_cart_amount) {
    //         return response()->json(['success' => false, 'message' => "Minimum cart amount not reached for this coupon (minimum: {$coupon->min_cart_amount})"]);
    //     }


    //     $shipping = (float) $request->input('shipping_charge', config('shop.inside_dhaka_shipping_charge'));


    //     $base = $finalSubtotal + $shipping;
    //     if ($coupon->type === 'fixed') {
    //         $discount = min($coupon->value, $base);
    //     } else {
    //         $discount = ($base * $coupon->value) / 100.0;
    //     }


    //     // return discount and recalculated totals
    //     $mfsCharge = 0;
    //     if (config('shop.mfs_charge') && $request->input('payment_method') !== 'cod') {
    //         $mfsCharge = ($finalSubtotal + $shipping - $discount) * (config('shop.mfs_percentage') / 100);
    //     }


    //     $vat = 0;
    //     if (config('shop.vat_applicable')) {
    //         $vat = ($finalSubtotal + $shipping - $discount) * (config('shop.vat_percent') / 100);
    //     }


    //     $grand = $finalSubtotal + $shipping - $discount + $mfsCharge + $vat;


    //     return response()->json([
    //         'success' => true,
    //         'discount' => round($discount,2),
    //         'finalSubtotal' => round($finalSubtotal,2),
    //         'shipping' => round($shipping,2),
    //         'mfs' => round($mfsCharge,2),
    //         'vat' => round($vat,2),
    //         'grand' => round($grand,2),
    //         'coupon_id' => $coupon->id,
    //         'code' => $coupon->code,
    //     ]);
    // }
    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $code = strtoupper(trim($request->input('code')));
        $user = Auth::user();

        $coupon = Coupon::where('code', $code)->first();

        if (! $coupon) {
            return response()->json(['success' => false, 'message' => 'Invalid coupon code']);
        }

        if (! $coupon->isActive()) {
            return response()->json(['success' => false, 'message' => 'Coupon is not active or expired']);
        }

        // check global uses
        if ($coupon->max_uses && $coupon->used_count >= $coupon->max_uses) {
            return response()->json(['success' => false, 'message' => 'Coupon usage limit reached']);
        }

        // check per-user usage
        $userUses = DB::table('coupon_user')
            ->where(['coupon_id' => $coupon->id, 'user_id' => $user->id])
            ->value('uses') ?? 0;

        if ($coupon->max_uses_per_user && $userUses >= $coupon->max_uses_per_user) {
            return response()->json(['success' => false, 'message' => 'You have already used this coupon the maximum allowed times']);
        }

        // Get cart subtotal
        $cartItems = CartItem::with('product')->where('user_id', $user->id)->get();
        $now = Carbon::now();
        $finalSubtotal = 0;

        foreach ($cartItems as $item) {
            $product = $item->product;
            $isDiscounted = $product->discounted_price > 0
                && $now->between(Carbon::parse($product->discount_start_date), Carbon::parse($product->discount_end_date));

            $price = $isDiscounted ? $product->discounted_price : $product->price;
            $finalSubtotal += $price * $item->quantity;
        }

        // min amount required?
        if ($finalSubtotal < $coupon->min_cart_amount) {
            return response()->json(['success' => false, 'message' => "Minimum cart amount not reached for this coupon"]);
        }

        // Receive shipping and payment from request
        $shipping = (float) $request->input('shipping_charge', config('shop.inside_dhaka_shipping_charge'));
        $paymentMethod = $request->input('payment_method', 'cod');

        // Calculate coupon discount (same logic used in placeOrder)
        $base = $finalSubtotal + $shipping;

        if ($coupon->type === 'fixed') {
            $discount = min($coupon->value, $base);
        } else {
            $discount = ($base * $coupon->value) / 100.0;
        }

        // MFS CHARGE
        $mfsCharge = 0;
        if (config('shop.mfs_charge') && $paymentMethod !== 'cod') {
            $mfsCharge = ($finalSubtotal + $shipping - $discount) * (config('shop.mfs_percentage') / 100);
        }

        // VAT
        $vat = 0;
        if (config('shop.vat_applicable')) {
            $vat = ($finalSubtotal + $shipping - $discount) * (config('shop.vat_percent') / 100);
        }

        $grand = $finalSubtotal + $shipping - $discount + $mfsCharge + $vat;

        return response()->json([
            'success'      => true,
            'discount'     => round($discount,2),
            'finalSubtotal'=> round($finalSubtotal,2),
            'shipping'     => round($shipping,2),
            'mfs'          => round($mfsCharge,2),
            'vat'          => round($vat,2),
            'grand'        => round($grand,2),
            'coupon_id'    => $coupon->id,
            'code'         => $coupon->code,
        ]);
    }

    
}
