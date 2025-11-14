@extends('layouts.app')

@section('content')
@php
use Carbon\Carbon;
$now = Carbon::now();
@endphp

<h1 class="text-2xl font-bold mb-4">Shopping Cart</h1>

@if(count($cartItems) > 0)
    <table class="w-full bg-white shadow rounded">
        <thead>
            <tr class="bg-gray-100 text-left">
                <th class="p-3">Product</th>
                <th class="p-3">Price</th>
                <th class="p-3">Quantity</th>
                <th class="p-3">Total</th>
                <th class="p-3"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cartItems as $item)
            @php
                $product = $item->product;
                $isDiscounted = $product->discounted_price > 0 
                    && $now->between(Carbon::parse($product->discount_start_date), Carbon::parse($product->discount_end_date));
                $price = $isDiscounted ? $product->discounted_price : $product->price;
            @endphp
            <tr>
                <td class="p-3">{{ $product->name }}</td>
                <td class="p-3">
                    ${{ number_format($price, 2) }}
                    @if($isDiscounted)
                        <span class="text-sm line-through text-gray-500 ml-2">${{ number_format($product->price, 2) }}</span>
                    @endif
                </td>
                <td class="p-3">{{ $item->quantity }}</td>
                <td class="p-3">${{ number_format($price * $item->quantity, 2) }}</td>
                <td class="p-3">
                    <form action="{{ url('/cart/remove/'.$item->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-500 hover:underline">Remove</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-6 text-right">
        <a href="{{ url('/checkout') }}" 
           class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
           Proceed to Checkout
        </a>
    </div>
@else
    <p>Your cart is empty.</p>
@endif
@endsection
