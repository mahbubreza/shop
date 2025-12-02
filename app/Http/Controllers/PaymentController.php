<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\Payments\BkashService;
use App\Services\Payments\NagadService;
use App\Services\Payments\PaypalService;
use App\Services\Payments\RocketService;
use App\Services\Payments\StripeService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function initialize($orderId)
    {
        $order = Order::findOrFail($orderId);

        // Redirect to proper gateway
        if ($order->payment_provider === 'bkash') {
            $service = new BkashService(app('App\Services\StockService'));
            return $service->initiate($order);
        }
        if ($order->payment_provider === 'nagad') {
            return app(NagadService::class)->initiate($order);
        }
        if ($order->payment_provider === 'rocket') {
            return app(RocketService::class)->initiate($order);
        }
        if ($order->payment_provider === 'stripe') {
            return app(StripeService::class)->checkout($order);
        }
        if ($order->payment_provider === 'paypal') {
            return app(PaypalService::class)->checkout($order);
        }

        return abort(400, "Invalid payment provider");
    }

    public function callback(Request $request, $provider)
    {
        // return app("App\\Services\\" . ucfirst($provider) . "Service")
        //     ->verify($request);
        if ($provider === 'bkash') {
            $service = new BkashService(app('App\Services\StockService'));
            return $service->verify($request);
        }

        return abort(400, "Invalid provider");
    }
}

