<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Categories
        </h2>
    </x-slot>

    <x-slot name="button">
        <a href="/categories/create" 
        class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white border border-green-600 rounded-md font-semibold text-xs uppercase tracking-widest shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
        + Add Category
        </a>
    </x-slot>


    <div class="space-py-2 py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-2">
        @foreach($categories as $category)
            <div class="flex justify-between items-center px-4 py-2 border border-gray-200 rounded-lg shadow-sm">
                <a href="/categories/{{ $category->id }}">
                    <div class="font-bold text-blue-500 text-sm hover:underline">
                        {{ $category->name }}
                    </div>
                </a>

                <div class="flex gap-2">
                    <a href="/categories/{{ $category->id }}" class="inline-flex items-center px-4 py-2 rounded-md font-semibold text-xs uppercase tracking-widest shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150 bg-blue-500 hover:bg-blue-600 text-white border border-blue-600  focus:ring-indigo-500 ">
                        View
                    </a>

                    <a href="/categories/{{ $category->id }}/edit" class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white border border-yellow-600 rounded-md font-semibold text-xs uppercase tracking-widest shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Edit
                    </a>

                </div>
            </div>
        @endforeach

            <div>
                {{ $categories->links() }}
            </div>
        </div>
   </div>
</x-app-layout>