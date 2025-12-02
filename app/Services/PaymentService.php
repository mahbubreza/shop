<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PaymentService
{
    public function createBkashPayment($orderId, $amount, $callbackUrl)
    {
        // Example API request, fill with your bKash sandbox/live credentials
        $token = $this->getBkashToken();
        $response = Http::withHeaders([
            'Authorization' => "Bearer $token",
            'Content-Type' => 'application/json'
        ])->post('https://checkout.sandbox.bka.sh/v1.2.0-beta/checkout/payment/create', [
            'amount' => $amount,
            'currency' => 'BDT',
            'intent' => 'sale',
            'merchantInvoiceNumber' => $orderId,
            'callbackURL' => $callbackUrl,
        ]);

        return $response->json();
    }

    private function getBkashToken()
    {
        // Request auth token from bKash API
        $response = Http::post('https://checkout.sandbox.bka.sh/v1.2.0-beta/token/grant', [
            'app_key' => config('services.bkash.app_key'),
            'app_secret' => config('services.bkash.app_secret'),
        ]);
        return $response->json()['id_token'] ?? null;
    }

    // Similarly, implement Nagad and Rocket APIs
}
