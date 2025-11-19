<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl">Orders</h2>
            <div class="flex gap-2">
                <form id="exportForm" method="POST" action="{{ route('admin.orders.exportList') }}">
                    @csrf
                    <input type="hidden" name="status" value="{{ request('status') }}">
                    <input type="hidden" name="from" value="{{ request('from') }}">
                    <input type="hidden" name="to" value="{{ request('to') }}">
                    <button type="submit" class="px-3 py-2 bg-gray-700 text-white rounded">Export CSV</button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('admin.orders.partials._filters')

            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-3"><input id="select_all" type="checkbox"></th>
                            <th class="p-3">ID</th>
                            <th class="p-3">Customer</th>
                            <th class="p-3">Total</th>
                            <th class="p-3">Payment</th>
                            <th class="p-3">Status</th>
                            <th class="p-3">Created</th>
                            <th class="p-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr class="border-t">
                                <td class="p-3 text-center"><input class="bulk_checkbox" type="checkbox" value="{{ $order->id }}"></td>
                                <td class="p-3">#{{ $order->id }}</td>
                                <td class="p-3">{{ $order->user->name ?? 'User: '.$order->user_id }}<br><small>{{ $order->mobile_number }}</small></td>
                                <td class="p-3">{{ number_format($order->total,2) }}</td>
                                <td class="p-3">{{ $order->payment_method ?? '-' }}</td>
                                <td class="p-3">
                                    <span class="status-badge">{{ ucfirst($order->status) }}</span>
                                </td>
                                <td class="p-3">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                <td class="p-3">@include('admin.orders.partials._actions')</td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="p-6 text-center">No orders found.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="p-4">{{ $orders->links() }}</div>

                <div class="p-4 flex gap-2">
                    <select id="bulk_action" class="border px-3 py-2 rounded">
                        <option value="">Bulk action</option>
                        <option value="cancel">Cancel Selected</option>
                        <option value="complete">Mark as Complete</option>
                    </select>
                    <button id="run_bulk" class="px-3 py-2 bg-blue-600 text-white rounded">Apply</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/admin-orders.js') }}"></script>
</x-app-layout>
