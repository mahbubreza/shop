<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Category
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="space-y-12">
                    <div class="border-b border-gray-900/10 dark:border-gray-700 pb-12">
                        <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <x-forms.form-field>
                                <x-forms.form-label for="name" >Title</x-forms.form-label>
                                <div class="mt-2">
                                    <x-forms.form-input id="name" type="text" name="title" value="{{ $category->name}}" disabled />
                                    <x-forms.form-error name="name" />
                                </div>
                            </x-forms.form-field>

                            <x-forms.form-field>
                                <x-forms.form-label for="slug" >Slug</x-forms.form-label>
                                <div class="mt-2">
                                    <x-forms.form-input id="slug" type="text" name="slug" value="{{$category->slug}}" disabled/>
                                    <x-forms.form-error name="slug" />
                                </div>
                            </x-forms.form-field>  

                        </div>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <x-forms.common-button 
                        href="/categories"
                    >
                        Cancel
                    </x-forms.common-button>

                    <x-forms.common-button
                        class=" bg-indigo-600 text-white hover:bg-indigo-500
                        focus-visible:outline-indigo-600"
                        href="/categories/{{$category->id}}/edit"
                    >
                        Edit Job
                    </x-forms.common-button>

                </div>
        </div>
    </div>

    
</x-app-layout>
