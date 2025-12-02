@extends('layouts.app')

@section('content')
<div class="container text-center">
    <h2>Redirecting to bKash...</h2>
    <p>If not redirected, <a href="{{ $paymentData['bkashURL'] }}">click here</a></p>
</div>
@endsection
