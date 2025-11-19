<div class="flex items-center gap-2">
    <a href="{{ route('admin.orders.show', $order->id) }}" class="px-2 py-1 bg-gray-200 rounded">View</a>
    <button class="px-2 py-1 bg-green-500 text-white rounded btn-change-status" data-id="{{ $order->id }}" data-status="shipped">Ship</button>
    <button class="px-2 py-1 bg-yellow-500 text-white rounded btn-change-status" data-id="{{ $order->id }}" data-status="completed">Complete</button>
    <button class="px-2 py-1 bg-red-500 text-white rounded btn-change-status" data-id="{{ $order->id }}" data-status="cancelled">Cancel</button>
</div>
