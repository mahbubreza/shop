<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            New Category
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <form method="POST" action="/categories" enctype="multipart/form-data">
                @csrf
                <div class="space-y-12">
                    <div class="border-b border-gray-900/10 dark:border-gray-700 pb-12">
                        <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <div class="sm:col-span-3">
                                <x-forms.form-field>
                                    <x-forms.form-label for="name" >Title</x-forms.form-label>
                                    <div class="mt-2">
                                        <x-forms.form-input id="name" type="text" name="name" placeholder="Baby Shampoo" required />
                                        <x-forms.form-error name="name" />
                                    </div>
                                </x-forms.form-field>
                            </div>
                            <div class="sm:col-span-3">
                                <x-forms.form-field>
                                    <x-forms.form-label for="slug" >Slug</x-forms.form-label>
                                    <div class="mt-2">
                                        <x-forms.form-input id="slug" type="text" name="slug" placeholder="slug-shampoo" required/>
                                        <x-forms.form-error name="slug" />
                                    </div>
                                </x-forms.form-field>     
                            </div>
                            <div class="sm:col-span-3">
                                <x-forms.form-label for="image">Upload Thumbnail Image</x-forms.form-label>

                                <div class="mt-2">
                                    <input
                                        type="file"
                                        name="image"
                                        id="image"
                                        accept="image/*"
                                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                        onchange="previewThumbnail(event)"
                                    />
                                    <x-forms.form-error name="image" />   
                                </div>

                                <p class="mt-1 text-sm/6 text-gray-600">
                                    This image is visible in slider. Dimensions required: 1900px width X 800px height. 
                                </p>

                                <div id="thumbnailPreview" class="mt-3 hidden w-28 h-28 border rounded-lg overflow-hidden">
                                    <!-- Preview will appear here -->
                                </div>
            
                            </div>

                            <div class="sm:col-span-3">
                                <x-forms.form-label for="hot" >Hot</x-forms.form-label>
                                <div class="mt-2 grid grid-cols-1">
                                    <x-forms.form-select id="hot" name="hot">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </x-forms.form-select>    
                                    <x-forms.form-error name="hot" />                                  
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <x-forms.form-label for="featured" >Featured</x-forms.form-label>
                                <div class="mt-2 grid grid-cols-1">
                                    <x-forms.form-select id="featured" name="featured">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </x-forms.form-select>    
                                    <x-forms.form-error name="featured" />                                  
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
    <script>
        function previewThumbnail(event) {
        const preview = document.getElementById('thumbnailPreview');
        const file = event.target.files[0];

        if (file) {
            preview.innerHTML = '';
            preview.classList.remove('hidden'); // show preview
            const reader = new FileReader();
            reader.onload = e => {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'w-full h-full object-cover rounded-lg border'; // match gallery
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        } else {
            preview.innerHTML = '';
            preview.classList.add('hidden'); // hide if no file
        }
    }

    </script>
</x-app-layout>
