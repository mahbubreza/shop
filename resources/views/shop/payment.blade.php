<x-shop.layout>
<div class="container mx-auto py-10">
    <h1 class="text-2xl font-bold mb-6">Payment</h1>

    <p>Order ID: {{ $orderId }}</p>
    <p>Choose payment result to simulate:</p>

    <div class="mt-4 flex space-x-4">
        <a href="{{ route('checkout.payment.success', $orderId) }}" class="bg-green-500 text-white px-4 py-2 rounded">Payment Success</a>
        <a href="{{ route('checkout.payment.fail', $orderId) }}" class="bg-red-500 text-white px-4 py-2 rounded">Payment Fail</a>
        <a href="{{ route('payment.start', $orderId) }}" class="btn btn-primary">
            Pay Now
        </a>
    </div>
</div>
</x-shop.layout>
