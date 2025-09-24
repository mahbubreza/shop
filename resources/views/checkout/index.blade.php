@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">Checkout</h1>

<form action="{{ url('/checkout/place-order') }}" method="POST" class="bg-white shadow rounded p-6 max-w-lg">
    @csrf
    <div class="mb-4">
        <label class="block font-semibold mb-1">Shipping Address</label>
        <textarea name="shipping_address" rows="3" class="w-full border rounded px-3 py-2" required></textarea>
    </div>

    <div class="mb-4">
        <label class="block font-semibold mb-1">Payment Method</label>
        <select name="payment_method" class="w-full border rounded px-3 py-2" required>
            <option value="cod">Cash on Delivery</option>
            <option value="bkash">bKash</option>
            <option value="nagad">Nagad</option>
            <option value="rocket">Rocket</option>
        </select>
    </div>

    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
        Place Order
    </button>
</form>
@endsection
