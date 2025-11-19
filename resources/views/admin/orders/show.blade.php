<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl">Order #{{ $order->id }}</h2>
            <div class="flex gap-2 ml-5">
                <form method="POST" action="{{ route('admin.orders.exportInvoice', $order->id) }}">
                    @csrf
                    <button class="px-3 py-2 bg-gray-700 text-white rounded">Export CSV</button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 bg-white shadow p-4">
            <div class="flex justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Customer</h3>
                    <div>{{ $order->user->name ?? 'User '.$order->user_id }}</div>
                    <div>{{ $order->mobile_number }}</div>
                    <div>{{ $order->shipping_address }}</div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold">Status</h3>
                    <div id="order_status">{{ ucfirst($order->status) }}</div>
                    <div class="mt-2">
                        <input id="tracking_number" placeholder="Tracking #" value="{{ $order->tracking_number }}" class="border px-2 py-1 rounded">
                        <button id="save_tracking" data-id="{{ $order->id }}" class="px-3 py-1 bg-indigo-600 text-white rounded">Save</button>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <h4 class="font-semibold">Items</h4>
            <table class="min-w-full text-sm mt-2 text-center">
                <thead><tr><th>Product</th><th>Qty</th><th>Price</th></tr></thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product->name ?? $item->product_id }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->price,2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                <div>Subtotal: {{ number_format($order->sub_total,2) }}</div>
                <div>Shipping: {{ number_format($order->shipping_charge,2) }}</div>
                <div>MFS: {{ number_format($order->mfs_charge,2) }}</div>
                <div>VAT: {{ number_format($order->vat,2) }}</div>
                <div class="text-lg font-bold">Total: {{ number_format($order->total,2) }}</div>
            </div>

            <hr class="my-4">

            <div>
                <h4 class="font-semibold">Admin Note</h4>
                <textarea id="admin_note" rows="4" class="w-full border p-2">{{ $order->admin_note }}</textarea>
                <button id="save_note" data-id="{{ $order->id }}" class="mt-2 px-3 py-2 bg-blue-600 text-white rounded">Save Note</button>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/admin-orders.js') }}"></script>
</x-app-layout>
