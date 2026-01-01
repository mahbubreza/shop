@php
    $shopConfig = config('shop');
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            New Product
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <form method="POST" action="/products"  enctype="multipart/form-data">
                @csrf
                <!-- Tabs Navigation -->
                <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <button type="button"
                            class="tab-button border-indigo-500 text-indigo-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                            data-tab="general">
                            General Info
                        </button>
                        <button type="button"
                            class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                            data-tab="additional">
                            Additional Info
                        </button>
                    </nav>
                </div>

                <!-- TAB 1: General Info -->
                <div id="tab-general" class="tab-content space-y-12"> 
                    <div class="border-b border-gray-900/10 dark:border-gray-700 pb-12">
                        <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <!-- Product Title -->
                            <div class="sm:col-span-3">
                                <x-forms.form-label for="name" >Product Title</x-forms.form-label>
                                <div class="mt-2">
                                    <x-forms.form-input required id="name" type="text" name="name" placeholder="Shampoo"  />
                                    <x-forms.form-error name="name" />
                                </div>
                            </div>
                            <!-- Product Category -->
                            <div class="sm:col-span-3">
                                <x-forms.form-label for="category_id" >Category</x-forms.form-label>
                                <div class="mt-2 grid grid-cols-1">
                                    <x-forms.form-select required id="category_id" name="category_id">
                                        @isset($categories)
                                           @foreach ($categories as $category)
                                                <option value="{{$category->id}}">{{$category->name}}</option>
                                            @endforeach  
                                        @endisset
                                    </x-forms.form-select>  
                                    <x-forms.form-error name="category_id" />                                  
                                </div>
                            </div> 
                            <!-- Price -->
                            <div class="sm:col-span-3">
                                <x-forms.form-label for="price" >Price per unit (in Taka)</x-forms.form-label>
                                <div class="mt-2">
                                    <x-forms.form-input required id="price" type="text" name="price" placeholder="1800"  />
                                    <x-forms.form-error name="price" />
                                </div>
                            </div> 
                            <!-- Unit/Quantity/Stock -->
                            <div class="sm:col-span-3">
                                <x-forms.form-label for="stock" >Unit/Quantity (in pieces)</x-forms.form-label>
                                <div class="mt-2">
                                    <x-forms.form-input required id="stock" type="text" name="stock" placeholder="10"  />
                                    <x-forms.form-error name="stock" />
                                </div>
                            </div>
                            <!-- Weight -->
                            <div class="sm:col-span-3">
                                <x-forms.form-label for="weight" >Weight (in Kg)</x-forms.form-label>
                                <div class="mt-2">
                                    <x-forms.form-input id="weight" type="text" name="weight" placeholder="0.5 kg"  />
                                    <x-forms.form-error name="weight" />
                                </div>
                            </div> 
                            <!-- Tags -->
                            <div class="sm:col-span-3">
                                <x-forms.form-label for="tags" >Tags</x-forms.form-label>
                                <div class="mt-2">
                                    <x-forms.form-input id="tags" type="tags" name="tags" placeholder="health, beauty"  />
                                    <x-forms.form-error name="tags" />
                                </div>
                            </div>
                            <!-- Brand -->
                            <div class="sm:col-span-3">
                                <x-forms.form-label for="brand_id" >Brand</x-forms.form-label>
                                <div class="mt-2 grid grid-cols-1">
                                    <x-forms.form-select id="brand_id" name="brand_id">
                                        @isset($brands)
                                           @foreach ($brands as $brand)
                                                <option value="{{$brand->id}}">{{$brand->name}}</option>
                                            @endforeach  
                                        @endisset
                                    </x-forms.form-select>    
                                    <x-forms.form-error name="brand_id" />                                  
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
                            <!-- Product Description -->
                            <div class="col-span-full">
                                <x-forms.form-label for="body">Product description</x-forms.form-label>
                                <div class="mt-2">
                                    <textarea
                                        id="body"
                                        name="description"
                                        rows="8"
                                        class="w-full rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 p-3 text-gray-800 shadow-sm"
                                        placeholder="Write your post..."
                                    ></textarea>
                                    <x-forms.form-error name="description" />   
                                </div>
                            </div>
                            <!-- Thumbnail Image -->
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
                                    This image is visible in all product box. Minimum dimensions required: {{$shopConfig['product_image_width']}}px width X {{$shopConfig['product_image_height']}}px height. 
                                    Keep some blank space around main object of your image as we had to crop some edge in different 
                                    devices to make it responsive. If no thumbnail is uploaded, the product's first gallery image 
                                    will be used as the thumbnail image.
                                </p>


                                <div id="thumbnailPreview" class="mt-3 hidden w-28 h-28 border rounded-lg overflow-hidden">
                                    <!-- Preview will appear here -->
                                </div>
                                {{-- <div class="mt-4">
                                    <img id="thumbnailPreview" class="hidden w-48 h-48 object-cover rounded-lg shadow-md border border-gray-300" />
                                </div> --}}
                            </div>
                            <!-- Gallery Images -->
                            <div class="sm:col-span-3">
                                <x-forms.form-label for="images">Gallery Images</x-forms.form-label>
                                <div class="mt-2">
                                    <input 
                                        type="file" 
                                        name="images[]" 
                                        id="images" 
                                        accept="image/*" 
                                        multiple
                                        class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-indigo-200"
                                    />
                                    <x-forms.form-error name="images[]" />   
                                </div>
                                <p class="mt-1 text-sm/6 text-gray-600">
                                    These images are visible in product details page gallery. Minimum dimensions required: {{$shopConfig['product_image_width']}}px width X {{$shopConfig['product_image_height']}}px height.
                                <p>
                                <!-- Preview container -->
                                <div id="imagePreview" class="mt-4 flex flex-wrap gap-4"></div>
                            </div>   
                        </div>
                    </div>
                </div>

                <!-- TAB 2: Additional Info -->
                <div id="tab-additional" class="tab-content hidden space-y-12">
                    <div class="border-b border-gray-900/10 dark:border-gray-700 pb-12">
                        <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            
                             <!-- Vdieos -->
                            <div class="sm:col-span-3">
                                <x-forms.form-label for="videos">Upload Videos</x-forms.form-label>

                                <div class="mt-2">
                                    <input
                                        type="file" 
                                        name="videos[]" 
                                        id="videos" 
                                        accept="video/*" 
                                        multiple
                                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                        onchange="previewVideos(event)"
                                    />
                                    <x-forms.form-error name="videos[]" />   

                                </div>
                                <p class="mt-1 text-sm/6 text-gray-600">
                                    Try to upload videos under 30 seconds for better performance.                        
                                </p>
                                <!-- Video preview container -->
                                <div id="videoPreview" class="mt-4 flex flex-wrap gap-4"></div>
                            </div>
                            <div class="sm:col-span-3">
                                <x-forms.form-label for="published" >Published</x-forms.form-label>
                                <div class="mt-2 grid grid-cols-1">
                                    <x-forms.form-select id="published" name="published">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </x-forms.form-select>    
                                    <x-forms.form-error name="published" />                                  
                                </div>
                            </div>
                            <!-- Youtube Link -->
                            <div class="sm:col-span-3">
                                <x-forms.form-label for="youtube_link">Youtube video / shorts link</x-forms.form-label>
                                <div class="mt-2">
                                    <x-forms.form-input id="youtube_link" type="text" name="youtube_link" placeholder="https://www.youtube.com/watch?v=AeY15GBpjqE&t=8476s"  />
                                    <x-forms.form-error name="youtube_link" />
                                </div>
                            </div>
                            <!-- Pdf -->
                            <div class="sm:col-span-3">
                                <x-forms.form-label for="pdfs">Upload PDF Specification</x-forms.form-label>

                                <div class="mt-2">
                                    <input
                                        type="file" 
                                        name="pdfs[]" 
                                        id="pdfs" 
                                        accept="application/pdf" 
                                        multiple
                                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                        onchange="previewPDFs(event)"
                                    />
                                    <x-forms.form-error name="pdfs[]" />  
                                </div>
                                <!-- PDF preview container -->
                                <div id="pdfPreview" class="mt-4 flex flex-col gap-2"></div>
                            </div>
                            
                            <!-- Discounted Price -->
                            <div class="sm:col-span-3">
                                <x-forms.form-label for="discounted_price">Discounted Price (in Taka)</x-forms.form-label>
                                <div class="mt-2">
                                    <x-forms.form-input id="discounted_price" type="text" name="discounted_price" placeholder="1500" />
                                    <x-forms.form-error name="discounted_price" />
                                </div>
                            </div>

                            <!-- Discount Start Date -->
                            <div class="sm:col-span-3">
                                <x-forms.form-label for="discount_start_date">Discount Start Date</x-forms.form-label>
                                <div class="mt-2">
                                    <x-forms.form-input id="discount_start_date" type="text" name="discount_start_date" placeholder="YYYY-MM-DD" />
                                    <x-forms.form-error name="discount_start_date" />
                                </div>
                            </div>

                            <!-- Discount End Date -->
                            <div class="sm:col-span-3">
                                <x-forms.form-label for="discount_end_date">Discount End Date</x-forms.form-label>
                                <div class="mt-2">
                                    <x-forms.form-input id="discount_end_date" type="text" name="discount_end_date" placeholder="YYYY-MM-DD" />
                                    <x-forms.form-error name="discount_end_date" />
                                </div>
                            </div>

                            <!-- Sizes -->
                            <div class="sm:col-span-3">
                                <x-forms.form-label for="sizes">Available Sizes</x-forms.form-label>
                                <div class="mt-2 grid grid-cols-2 gap-2 border p-3 rounded-lg">
                                    @foreach ($sizes as $size)
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" name="sizes[]" value="{{ $size->id }}" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                            <span>{{ $size->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <x-forms.form-error name="sizes" />
                            </div>

                            <!-- Colors -->
                            <div class="sm:col-span-3">
                                <x-forms.form-label for="colors">Available Colors</x-forms.form-label>
                                <div class="mt-2 grid grid-cols-2 gap-2 border p-3 rounded-lg">
                                    @foreach ($colors as $color)
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" name="colors[]" value="{{ $color->id }}" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                            <span>{{ $color->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <x-forms.form-error name="colors" />
                            </div>

                        </div>      
                    </div>
                </div>
   
                {{-- Buttons --}}
                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <button type="button"
                        class="text-sm font-semibold text-gray-900 dark:text-gray-200 hover:text-indigo-500 dark:hover:text-indigo-400 transition">
                        <a href="/products">Cancel</a>
                    </button>

                    <button type="submit"
                        class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- Tab Switcher Script -->
    <script>
        const tabs = document.querySelectorAll('.tab-button');
        const contents = document.querySelectorAll('.tab-content');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('border-indigo-500', 'text-indigo-600'));
                tab.classList.add('border-indigo-500', 'text-indigo-600');

                contents.forEach(c => c.classList.add('hidden'));
                document.getElementById(`tab-${tab.dataset.tab}`).classList.remove('hidden');
            });
        });
    </script>
    <script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>
    <script>
        // 🔹 Initialize CKEditor with image upload support
        ClassicEditor.create(document.querySelector('#body'), {
            ckfinder: {
                uploadUrl: '{{ route('ckeditor.upload', ['_token' => csrf_token()]) }}'
            }
        }).catch(error => console.error(error));

        // 🔹 Image Preview
        document.getElementById('images').addEventListener('change', e => {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';
            Array.from(e.target.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = ev => {
                    const div = document.createElement('div');
                    div.className = 'relative w-28 h-28';
                    div.innerHTML = `
                        <img src="${ev.target.result}" class="w-full h-full object-cover rounded-lg border">
                        <button type="button"
                            class="absolute top-1 right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center opacity-0 group-hover:opacity-100 hover:bg-red-600 hover:scale-110 transition"
                            onclick="this.parentElement.remove()">×</button>`;
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        });

        // 🔹 Video Preview
        document.getElementById('videos').addEventListener('change', e => {
            const preview = document.getElementById('videoPreview');
            preview.innerHTML = '';
            Array.from(e.target.files).forEach(file => {
                const url = URL.createObjectURL(file);
                const div = document.createElement('div');
                div.className = 'relative';
                div.innerHTML = `
                    <video src="${url}" class="w-40 h-24 rounded-lg border" controls></video>
                    <button type="button"
                        class="absolute top-1 right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center opacity-0 group-hover:opacity-100 hover:bg-red-600 hover:scale-110 transition"
                        onclick="this.parentElement.remove()">×</button>`;
                preview.appendChild(div);
            });
        });

        // 🔹 PDF Preview with hover remove button
        document.getElementById('pdfs').addEventListener('change', e => {
            const preview = document.getElementById('pdfPreview');
            preview.innerHTML = '';
            Array.from(e.target.files).forEach(file => {
                const div = document.createElement('div');
                div.className = 'relative flex items-center justify-between p-2 border rounded-lg border-gray-300 bg-gray-50 group cursor-move';
                div.innerHTML = `
                    <div class="flex items-center space-x-2">
                        <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6 2a2 2 0 0 0-2 2v16c0 1.1.9 2 2 2h12a2 2 0 0 0 2-2V8l-6-6H6z"/>
                        </svg>
                        <span class="text-sm font-medium text-gray-700 truncate max-w-[180px]">${file.name}</span>
                    </div>
                    <button type="button"
                        class="absolute top-1 right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300 hover:bg-red-600 hover:scale-110"
                        onclick="this.parentElement.remove()">×</button>`;
                preview.appendChild(div);
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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr("#discount_start_date", {
        dateFormat: "Y-m-d",
        allowInput: true
    });
    flatpickr("#discount_end_date", {
        dateFormat: "Y-m-d",
        allowInput: true
    });
</script>
</x-app-layout>
