<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $category->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <form method="POST" action="/categories/{{ $category->id }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="space-y-12">
                    <div class="border-b border-gray-900/10 dark:border-gray-700 pb-12">
                        <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <div class="sm:col-span-3">
                                <x-forms.form-field>
                                    <x-forms.form-label for="name" >Title</x-forms.form-label>
                                    <div class="mt-2">
                                        <x-forms.form-input id="name" type="text" name="name" value="{{ $category->name }}" required />
                                        <x-forms.form-error name="name" />
                                    </div>
                                </x-forms.form-field>
                            </div>
                            <div class="sm:col-span-3">
                                <x-forms.form-field>
                                    <x-forms.form-label for="slug" >Slug</x-forms.form-label>
                                    <div class="mt-2">
                                        <x-forms.form-input id="slug" type="text" name="slug" value="{{ $category->slug }}" required/>
                                        <x-forms.form-error name="slug" />
                                    </div>
                                </x-forms.form-field>  
                            </div>

                            <!-- Category Description -->
                            <div class="col-span-full">
                                <x-forms.form-label for="description" >Category description</x-forms.form-label>

                                <div class="mt-2">
                                    <textarea id="description" name="description" rows="3" class="w-full border-gray-300 rounded-md">{!! old('description', $category->description) !!}</textarea>

                                    <x-forms.form-error name="description" />   
                                </div>
                            </div>

                            <!-- Thumbnail Image -->
                            <div class="sm:col-span-3">
                                <x-forms.form-label for="image" >Thumbnail Image</x-forms.form-label>

                                <div class="mt-2">
                                    <input
                                        type="file"
                                        name="image"
                                        id="image"
                                        accept="image/*"
                                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        
                                    />
                                    <x-forms.form-error name="image" />   
                                </div>

                                <p class="mt-1 text-sm/6 text-gray-600">
                                    This image is visible in slider. Dimensions required: 1900px width X 800px height. 
                                </p>

                                @if ($category->image)
                                    <div class="mt-2 relative inline-block">
                                        <img src="{{ asset('storage/' . $category->image) }}" class="h-24 rounded">
                                        <input type="checkbox" name="remove_thumbnail" value="1" class="absolute top-0 right-0">
                                    </div>
                                @endif

                            </div>

                            <div class="sm:col-span-3">
                                <x-forms.form-label for="hot" >Hot</x-forms.form-label>
                                <div class="mt-2 grid grid-cols-1">
                                    <x-forms.form-select id="hot" name="hot">
                                        <option @selected($category->hot == 0) value="0">No</option>
                                        <option @selected($category->hot == 1) value="1">Yes</option>
                                    </x-forms.form-select>    
                                    <x-forms.form-error name="hot" />                                  
                                </div>
                            </div>

                            
                            <div class="sm:col-span-3">
                                <x-forms.form-field>
                                    <x-forms.form-label for="status" >Status</x-forms.form-label>
                                    <div class="mt-2 grid grid-cols-1">                                  
                                        <x-forms.form-select id="status" name="status">
                                            <option value="1" @selected($category->status == 1)>Active</option>
                                            <option value="0" @selected($category->status == 0)>Inactive</option>                                     
                                        </x-forms.form-select>                                  
                                        <x-forms.form-error name="status" />
                                    </div>
                                </x-forms.form-field>  
                            </div>

                            <div class="sm:col-span-3">
                                <x-forms.form-label for="featured" >Featured</x-forms.form-label>
                                <div class="mt-2 grid grid-cols-1">
                                    <x-forms.form-select id="featured" name="featured">
                                        <option @selected($category->featured == 0) value="0">No</option>
                                        <option @selected($category->featured == 1) value="1">Yes</option>
                                    </x-forms.form-select>    
                                    <x-forms.form-error name="featured" />                                  
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <x-forms.form-label for="carousal" >Carousal</x-forms.form-label>
                                <div class="mt-2 grid grid-cols-1">
                                    <x-forms.form-select id="carousal" name="carousal">
                                        <option @selected($category->carousal == 0) value="0">No</option>
                                        <option @selected($category->carousal == 1) value="1">Yes</option>
                                    </x-forms.form-select>    
                                    <x-forms.form-error name="carousal" />                                  
                                </div>
                            </div>
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
