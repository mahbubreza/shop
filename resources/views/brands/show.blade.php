<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $brand->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
         
                @csrf
                @method('PATCH')
                <div class="space-y-12">
                    <div class="border-b border-gray-900/10 dark:border-gray-700 pb-12">
                        <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <div class="sm:col-span-3">
                                <x-forms.form-field>
                                    <x-forms.form-label for="name" >Title</x-forms.form-label>
                                    <div class="mt-2">
                                        <x-forms.form-input disabled id="name" type="text" name="name" value="{{ $brand->name }}" required />
                                        <x-forms.form-error name="name" />
                                    </div>
                                </x-forms.form-field>
                            </div>
                            <div class="sm:col-span-3">
                                <x-forms.form-field>
                                    <x-forms.form-label for="slug" >Slug</x-forms.form-label>
                                    <div class="mt-2">
                                        <x-forms.form-input disabled id="slug" type="text" name="slug" value="{{ $brand->slug }}" required/>
                                        <x-forms.form-error name="slug" />
                                    </div>
                                </x-forms.form-field>  
                            </div>

                            <!-- Thumbnail Image -->
                            <div class="sm:col-span-3">
                                <x-forms.form-label for="image" >Logo</x-forms.form-label>

                               

                                @if ($brand->logo)
                                    <div class="mt-2 relative inline-block">
                                        <img src="{{ asset('storage/' . $brand->logo) }}" class="h-24 rounded">
                                    </div>
                                @endif

                            </div>

                            

                            
                            <div class="sm:col-span-3">
                                <x-forms.form-field>
                                    <x-forms.form-label for="status" >Status</x-forms.form-label>
                                    <div class="mt-2 grid grid-cols-1">                                  
                                        <x-forms.form-select disabled id="status" name="status">
                                            <option value="1" @selected($brand->status == 1)>Active</option>
                                            <option value="0" @selected($brand->status == 0)>Inactive</option>                                     
                                        </x-forms.form-select>                                  
                                        <x-forms.form-error name="status" />
                                    </div>
                                </x-forms.form-field>  
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <button type="button"
                        class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        <a href="/brands">Ok</a>
                    </button>

                    
                </div>
        </div>
    </div>

   
</x-app-layout>
