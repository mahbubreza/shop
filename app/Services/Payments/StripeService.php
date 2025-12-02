<?php

namespace App\Services\Payments;

use App\Models\Order;
use Illuminate\Support\Facades\Http;

class StripeService
{
    public function checkout(Order $order)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'bdt',
                    'unit_amount' => $order->total * 100,
                    'product_data' => ['name' => "Order #{$order->id}"],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('payment.callback', ['provider' => 'stripe']),
            'cancel_url'  => route('payment.callback', ['provider' => 'stripe']),
        ]);

        return redirect($session->url);
    }

    public function verify(Request $request)
    {
        // Stripe webhooks recommended
    }
}


