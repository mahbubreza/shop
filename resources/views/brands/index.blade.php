<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Categories
        </h2>
    </x-slot>

    <x-slot name="button">
        <a href="/brands/create" 
        class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white border border-green-600 rounded-md font-semibold text-xs uppercase tracking-widest shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
        + Add Brand
        </a>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 mb-6 flex flex-wrap gap-4">
                <div>
                    <select class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-md text-sm">
                        <option>Sort By</option>
                        <option>Name</option>
                        
                    </select>
                </div>
                <div class="ml-auto">
                    <input type="text" placeholder="Search Brand..."
                           class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-md text-sm px-3 py-2 w-56 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            <!-- Products Table -->
            <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded-lg">
                <table class="min-w-full text-sm text-gray-700 dark:text-gray-300">
                    <thead class="bg-gray-100 dark:bg-gray-700 border-b dark:border-gray-600">
                        <tr>
                            <th class="px-4 py-3 text-left">Name</th>
                            <th class="px-4 py-3 text-center">Logo</th>
                            <th class="px-4 py-3 text-center">Active</th>
                            <th class="px-4 py-3 text-center">Options</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y dark:divide-gray-700">
                        @forelse($brands as $brand)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <td class="px-4 py-3 ">
                                    <span class="font-medium text-gray-800 dark:text-gray-100">{{ $brand->name }}</span>
                                </td>

                                <td class="px-4 py-3 text-center">
                                    <img src="{{ asset('storage/'.$brand->logo) }}" alt="" class="w-10 h-10 object-cover rounded">
                                </td>

                                 
                                
                                <td class="px-4 py-3 text-center">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" 
                                            class="sr-only peer toggle-checkbox"
                                            data-id="{{ $brand->id }}" 
                                            data-field="status"
                                            {{ $brand->status == 1? 'checked' : '' }}>
                                        <div class="w-10 h-5 bg-gray-300 peer-checked:bg-yellow-500 rounded-full transition dark:bg-gray-600"></div>
                                    </label>
                                </td>

                                <td class="px-4 py-3 text-center">
                                    <div class="flex justify-center gap-3">
                                        <a href="brands/{{ $brand->id }}" class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="brands/{{ $brand->id }}/edit" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="brands/{{ $brand->id }}" method="POST" onsubmit="return confirm('Delete this brand?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-6 text-gray-500 dark:text-gray-400">No brands found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div>
                    {{ $brands->links() }}
                </div>
            </div>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.toggle-checkbox').forEach((checkbox) => {
            checkbox.addEventListener('change', function () {
                const brandId = this.dataset.id;
                const field = this.dataset.field;
                const value = this.checked ? 1 : 0;

                fetch(`/brands/${brandId}/toggle`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ field, value }),
                })
                .then(res => res.json())
                .then(data => {
                    if (!data.success) {
                        alert('Failed to update field.');
                    }
                })
                .catch(() => alert('Something went wrong!'));
            });
        });
    });
    </script>
    
</x-app-layout>