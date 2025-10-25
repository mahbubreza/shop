<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            New Category
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <form method="POST" action="/categories">
                @csrf
                <div class="space-y-12">
                    <div class="border-b border-gray-900/10 dark:border-gray-700 pb-12">
                        <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                            {{-- Name Field --}}
                            <div class="sm:col-span-4">
                                <label for="name" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                                    Title
                                </label>
                                <div class="mt-2">
                                    <div
                                        class="flex items-center rounded-md bg-white dark:bg-gray-800 outline-1 -outline-offset-1 outline-gray-300 dark:outline-gray-700 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
                                        <input id="name" type="text" name="name" placeholder="Baby Shampoo"
                                            class="block min-w-0 grow bg-transparent py-1.5 pr-3 text-base text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:outline-none sm:text-sm" />
                                    </div>
                                </div>
                            </div>

                            {{-- Slug Field --}}
                            <div class="sm:col-span-4">
                                <label for="slug" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                                    Slug
                                </label>
                                <div class="mt-2">
                                    <div
                                        class="flex items-center rounded-md bg-white dark:bg-gray-800 outline-1 -outline-offset-1 outline-gray-300 dark:outline-gray-700 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
                                        <input id="slug" type="text" name="slug" placeholder="baby-shampoo"
                                            class="block min-w-0 grow bg-transparent py-1.5 pr-3 text-base text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:outline-none sm:text-sm" />
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <button type="button"
                        class="text-sm font-semibold text-gray-900 dark:text-gray-200 hover:text-indigo-500 dark:hover:text-indigo-400 transition">
                        Cancel
                    </button>

                    <button type="submit"
                        class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ✅ Auto-generate slug script --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const nameInput = document.getElementById('name');
            const slugInput = document.getElementById('slug');

            nameInput.addEventListener('input', () => {
                let slug = nameInput.value
                    .toLowerCase()
                    .replace(/[^a-z0-9\s-]/g, '') // remove special chars
                    .trim()
                    .replace(/\s+/g, '-'); // replace spaces with hyphens

                slugInput.value = slug;
            });
        });
    </script>
</x-app-layout>
