<?php

namespace App\Services\Payments;

use App\Models\Order;
use App\Models\Payment;
use App\Services\StockService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class BkashService
{
    protected $baseUrl;
    protected $appKey;
    protected $appSecret;
    protected $username;
    protected $password;

    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;

        $this->baseUrl = env('BKASH_BASE_URL');
        $this->appKey = env('BKASH_APP_KEY');
        $this->appSecret = env('BKASH_APP_SECRET');
        $this->username = env('BKASH_USERNAME');
        $this->password = env('BKASH_PASSWORD');
    }

    /** Get bKash token */
    public function getToken()
    {
        $response = Http::post("{$this->baseUrl}/token/grant", [
            'app_key' => $this->appKey,
            'app_secret' => $this->appSecret,
        ]);

        if ($response->failed()) throw new Exception("bKash token request failed");

        return $response->json()['id_token'] ?? null;
    }

    /** Initiate payment */
    public function initiate(Order $order)
    {
        $token = $this->getToken();
        $callbackUrl = route('payment.callback', ['provider' => 'bkash']);

        $response = Http::withHeaders([
            'Authorization' => "Bearer $token",
            'Content-Type' => 'application/json'
        ])->post("{$this->baseUrl}/checkout/payment/create", [
            'amount' => $order->total,
            'currency' => 'BDT',
            'intent' => 'sale',
            'merchantInvoiceNumber' => $order->id,
            'callbackURL' => $callbackUrl,
        ]);

        $data = $response->json();

        // Save payment attempt
        Payment::create([
            'order_id' => $order->id,
            'provider' => 'bkash',
            'transaction_id' => $data['paymentID'] ?? null,
            'status' => 'pending',
            'raw_response' => json_encode($data),
        ]);

        if (!isset($data['bkashURL'])) {
            throw new Exception("Failed to initiate bKash payment");
        }

        // Redirect user to bKash payment page
        return redirect($data['bkashURL']);
    }

    /** Verify payment callback */
    public function verify($request)
    {
        $paymentID = $request->input('paymentID');
        $orderID = $request->input('merchantInvoiceNumber');

        $order = Order::findOrFail($orderID);
        $payment = Payment::where('transaction_id', $paymentID)->first();

        if (!$payment) {
            throw new Exception("Payment record not found");
        }

        $token = $this->getToken();

        $response = Http::withHeaders([
            'Authorization' => "Bearer $token",
            'Content-Type' => 'application/json'
        ])->post("{$this->baseUrl}/checkout/payment/execute", [
            'paymentID' => $paymentID
        ]);

        $data = $response->json();
        $status = strtolower($data['status'] ?? 'failed');

        $payment->status = $status;
        $payment->raw_response = json_encode($data);
        $payment->save();

        if ($status === 'success') {
            $this->stockService->confirmStock($order->id);
            $order->status = 'completed';
            $order->save();
            return redirect()->route('home')->with('success', "Payment successful. Order #{$order->id} confirmed.");
        } else {
            $this->stockService->releaseStock($order->id);
            $order->status = 'cancelled';
            $order->save();
            return redirect()->route('home')->with('error', "Payment failed. Order #{$order->id} cancelled.");
        }
    }
}
