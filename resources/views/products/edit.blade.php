<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Product
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <form method="POST" action="/products/{{ $product->id }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                <div class="space-y-12">
                    <div class="border-b border-gray-900/10 dark:border-gray-700 pb-12">
                        <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <!-- Title -->
                            <div class="sm:col-span-3">
                                <x-forms.form-label for="name" >Product Title</x-forms.form-label>
                                <div class="mt-2">
                                    <x-forms.form-input id="name" type="text" name="name" value="{{ old('name', $product->name) }}"  />
                                    <x-forms.form-error name="name" />
                                </div>
                            </div>
                            <!-- Category -->
                            <div class="sm:col-span-3">
                                <x-forms.form-label for="category_id" >Category</x-forms.form-label>
                                <div class="mt-2 grid grid-cols-1">
                                    <x-forms.form-select id="category_id" name="category_id">
                                        @isset($categories)
                                           @foreach ($categories as $category)
                                                <option @selected($category->id==$product->category_id) value="{{$category->id}}">{{$category->name}}</option>
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
                                    <x-forms.form-input id="price" type="text" name="price" value="{{ old('name', $product->price) }}"  />
                                    <x-forms.form-error name="price" />
                                </div>
                            </div> 
                            <!-- Unit/Stocvk/Quantity -->
                            <div class="sm:col-span-3">
                                <x-forms.form-label for="stock" >Unit/Quantity (in pieces)</x-forms.form-label>
                                <div class="mt-2">
                                    <x-forms.form-input id="stock" type="text" name="stock" value="{{ old('name', $product->stock) }}"  />
                                    <x-forms.form-error name="stock" />
                                </div>
                            </div>
                            <!-- Weight -->
                            <div class="sm:col-span-3">
                                <x-forms.form-label for="weight" >Weight (in Kg)</x-forms.form-label>
                                <div class="mt-2">
                                    <x-forms.form-input id="weight" type="text" name="weight" value="{{ old('name', $product->weight) }}"  />
                                    <x-forms.form-error name="weight" />
                                </div>
                            </div> 
                            <!-- Tags -->
                            <div class="sm:col-span-3">
                                <x-forms.form-label for="tags" >Tags</x-forms.form-label>
                                <div class="mt-2">
                                    <x-forms.form-input id="tags" type="tags" name="tags" value="{{ old('name', $product->tags) }}"  />
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
                                                <option @selected($brand->id==$product->brand_id) value="{{$brand->id}}">{{$brand->name}}</option>
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
                                        <option @selected($product->featured==0) value="0">No</option>
                                        <option @selected($product->featured==1)  value="1">Yes</option>
                                    </x-forms.form-select>    
                                    <x-forms.form-error name="featured" />                                  
                                </div>
                            </div>
                            <!-- Product Description -->
                            <div class="col-span-full">
                                <x-forms.form-label for="body" >Product description</x-forms.form-label>

                                <div class="mt-2">
                                    <textarea id="editor" name="description" rows="6" class="w-full border-gray-300 rounded-md">
                                        {!! old('description', $product->description) !!}
                                    </textarea>
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
                                    This image is visible in all product box. Minimum dimensions required: 195px width X 195px height. 
                                    Keep some blank space around main object of your image as we had to crop some edge in different 
                                    devices to make it responsive. If no thumbnail is uploaded, the product's first gallery image 
                                    will be used as the thumbnail image.
                                </p>

                                @if ($product->image)
                                    <div class="mt-2 relative inline-block">
                                        <img src="{{ asset('storage/' . $product->image) }}" class="h-24 rounded">
                                        <input type="checkbox" name="remove_thumbnail" value="1" class="absolute top-0 right-0">
                                    </div>
                                @endif

                            </div>

                            <!-- Multiple Images -->
                            <div class="sm:col-span-3">
                                <x-forms.form-label for="new_images" >Gallery Images</x-forms.form-label>

                                <div class="mt-2">
                                    <input 
                                        type="file" 
                                        name="new_images[]" 
                                        id="images" 
                                        accept="image/*" 
                                        multiple
                                        class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-indigo-200"
                                    />
                                    <x-forms.form-error name="new_images[]" />   
                                </div>
                                <p class="mt-1 text-sm/6 text-gray-600">
                                    These images are visible in product details page gallery. Minimum dimensions required: 900px width X 900px height.
                                <p>
                                <!-- Preview container -->
                                <div id="image-list" class="flex flex-wrap gap-2 mt-2">
                                    @foreach (json_decode($product->images ?? '[]', true) as $img)
                                        <div class="relative">
                                            <img src="{{ asset('storage/' . $img) }}" class="h-24 rounded">
                                            <button type="button"
                                                class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full w-5 h-5"
                                                onclick="removeFile(this, 'removed_images[]', '{{ $img }}')">×</button>
                                        </div>
                                    @endforeach
                                </div>
                            </div> 

                            <!-- Vdieos -->
                            <div class="sm:col-span-3">
                                <x-forms.form-label for="new_videos" >Upload Videos</x-forms.form-label>

                                <div class="mt-2">
                                    <input
                                        type="file" 
                                        name="new_videos[]" 
                                        id="videos" 
                                        accept="video/*" 
                                        multiple
                                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                        onchange="previewVideos(event)"
                                    />
                                    <x-forms.form-error name="new_videos[]" />   

                                </div>
                                <p class="mt-1 text-sm/6 text-gray-600">
                                    Try to upload videos under 30 seconds for better performance.                        
                                </p>
                                <!-- Video preview container -->
                                <div id="video-list" class="flex flex-wrap gap-2 mt-2">
                                    @foreach (json_decode($product->videos ?? '[]', true) as $vid)
                                        <div class="relative">
                                            <video src="{{ asset('storage/' . $vid) }}" class="h-24 rounded" controls></video>
                                            <button type="button"
                                                class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full w-5 h-5"
                                                onclick="removeFile(this, 'removed_videos[]', '{{ $vid }}')">×</button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <x-forms.form-label for="published" >Published</x-forms.form-label>
                                <div class="mt-2 grid grid-cols-1">
                                    <x-forms.form-select id="published" name="published">
                                        <option value="1" @selected($product->published==1)>Yes</option>
                                        <option value="0" @selected($product->published==0)>No</option>
                                    </x-forms.form-select>    
                                    <x-forms.form-error name="published" />                                  
                                </div>
                            </div>
                            <!-- Youtube Link -->
                            <div class="sm:col-span-3">
                                <x-forms.form-label for="youtube_link">Youtube video / shorts link</x-forms.form-label>
                                <div class="mt-2">
                                    <x-forms.form-input id="youtube_link" type="text" name="youtube_link"  value="{{ old('name', $product->youtube_link) }}"   />
                                    <x-forms.form-error name="youtube_link" />
                                </div>
                            </div>
                            <!-- Pdf -->
                            <div class="sm:col-span-3">
                                <x-forms.form-label for="new_pdfs" >Upload PDF Specification</x-forms.form-label>

                                <div class="mt-2">
                                    <input
                                        type="file" 
                                        name="new_pdfs[]" 
                                        id="pdfs" 
                                        accept="application/pdf" 
                                        multiple
                                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                      
                                    />
                                    <x-forms.form-error name="new_pdfs[]" />  
                                </div>
                                <!-- PDF preview container -->
                                <div id="pdf-list" class="space-y-2 mt-2">
                                    @foreach (json_decode($product->pdfs ?? '[]', true) as $pdf)
                                        <div class="relative p-2 border rounded bg-gray-100 flex justify-between items-center">
                                            <span class="text-sm text-gray-700 truncate max-w-[200px]">
                                                {{ basename($pdf) }}
                                            </span>
                                            <button type="button"
                                                class="bg-red-500 text-white text-xs rounded-full w-5 h-5"
                                                onclick="removeFile(this, 'removed_pdfs[]', '{{ $pdf }}')">×</button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>


                            <!-- Hidden removed files -->
                            <div id="removed-files"></div>

                            
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <button type="button"
                        class="text-sm font-semibold text-gray-900 dark:text-gray-200 hover:text-indigo-500 dark:hover:text-indigo-400 transition">
                        <a href="/products">Cancel</a>
                    </button>

                    <button type="submit"
                        class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Update
                    </button>
                </div>
            </form>
            
        </div>
    </div>

    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <script>
        ClassicEditor.create(document.querySelector('#editor'), {
            ckfinder: { uploadUrl: "{{ route('ckeditor.upload').'?_token='.csrf_token() }}" }
        });

        // Keep track of removed files
        function removeFile(button, fieldName, filePath) {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = fieldName;
            hiddenInput.value = filePath;
            document.getElementById('removed-files').appendChild(hiddenInput);

            button.parentElement.remove();
        }
    </script>
</x-app-layout>
