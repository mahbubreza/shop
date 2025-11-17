<x-app-layout>
<div class="p-6">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Coupons
        </h2>
    </x-slot>

    <x-slot name="button">
        <a href="/coupons/create" 
        class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white border border-green-600 rounded-md font-semibold text-xs uppercase tracking-widest shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
        + Add Coupons
        </a>
    </x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded-lg">
                <table class="min-w-full text-sm text-gray-700 dark:text-gray-300">
                    <thead class="bg-gray-100 dark:bg-gray-700 border-b dark:border-gray-600">
                        <tr>
                            <th class="px-4 py-3 text-center">Code</th>
                            <th class="px-4 py-3 text-center">Type</th>
                            <th class="px-4 py-3 text-center">Value</th>
                            <th class="px-4 py-3 text-center">Min Cart</th>
                            <th class="px-4 py-3 text-center">Uses</th>
                            <th class="px-4 py-3 text-center">Validity</th>
                            <th class="px-4 py-3 text-center">Active</th>
                            <th class="px-4 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y dark:divide-gray-700">
                        @foreach($coupons as $coupon)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <td class="px-4 py-3 text-center">{{ $coupon->code }}</td>
                                <td class="px-4 py-3 text-center">{{ strtoupper($coupon->type) }}</td>
                                <td class="px-4 py-3 text-center">
                                    @if($coupon->type === 'percent')
                                        {{ $coupon->value }}%
                                    @else
                                        ৳{{ number_format($coupon->value,2) }}
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">৳{{ number_format($coupon->min_cart_amount,2) }}</td>
                                <td class="px-4 py-3 text-center">{{ $coupon->used_count }}{{ $coupon->max_uses ? '/'.$coupon->max_uses : '' }}</td>
                                <td class="px-4 py-3 text-center">
                                    {{ $coupon->starts_at ? $coupon->starts_at->format('Y-m-d') : '—' }} to
                                    {{ $coupon->ends_at ? $coupon->ends_at->format('Y-m-d') : '—' }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($coupon->active)
                                        <span class="text-green-600">Active</span>
                                    @else
                                        <span class="text-red-600">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="text-blue-600 mr-2">Edit</a>

                                    <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600" onclick="return confirm('Delete coupon?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    

    <div class="mt-4">
        {{ $coupons->links() }}
    </div>
</div>
</x-app-layout>
