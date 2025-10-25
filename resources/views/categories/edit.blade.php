<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $category->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <form method="POST" action="/categories/{{ $category->id }}">
                @csrf
                @method('PATCH')
                <div class="space-y-12">
                    <div class="border-b border-gray-900/10 dark:border-gray-700 pb-12">
                        <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <x-forms.form-field>
                                <x-forms.form-label for="name" >Title</x-forms.form-label>
                                <div class="mt-2">
                                    <x-forms.form-input id="name" type="text" name="title" value="{{ $category->name }}" required />
                                    <x-forms.form-error name="name" />
                                </div>
                            </x-forms.form-field>


                            <x-forms.form-field>
                                <x-forms.form-label for="slug" >Slug</x-forms.form-label>
                                <div class="mt-2">
                                    <x-forms.form-input id="slug" type="text" name="slug" value="{{ $category->slug }}" required/>
                                    <x-forms.form-error name="slug" />
                                </div>
                            </x-forms.form-field>                            

                        </div>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <button type="button"
                        class="text-sm font-semibold text-gray-900 dark:text-gray-200 hover:text-indigo-500 dark:hover:text-indigo-400 transition">
                        <a href="/categories">Cancel</a>
                    </button>

                    <button type="submit"
                        class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Update
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
