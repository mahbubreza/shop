<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Product
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 dark:border-gray-700 pb-12">
                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <!-- Title -->
                        <div class="sm:col-span-3">
                            <x-forms.form-label for="name" >Product Title</x-forms.form-label>
                            <div class="mt-2">
                                <x-forms.form-input disabled id="name" type="text" name="name" value="{{ old('name', $product->name) }}"  />
                                <x-forms.form-error name="name" />
                            </div>
                        </div>
                        <!-- Category -->
                        <div class="sm:col-span-3">
                            <x-forms.form-label for="category_id" >Category</x-forms.form-label>
                            <div class="mt-2 grid grid-cols-1">
                                <x-forms.form-select disabled id="category_id" name="category_id">
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
                                <x-forms.form-input disabled id="price" type="text" name="price" value="{{ old('name', $product->price) }}"  />
                                <x-forms.form-error name="price" />
                            </div>
                        </div> 
                        <!-- Unit/Stocvk/Quantity -->
                        <div class="sm:col-span-3">
                            <x-forms.form-label for="stock" >Unit/Quantity (in pieces)</x-forms.form-label>
                            <div class="mt-2">
                                <x-forms.form-input disabled id="stock" type="text" name="stock" value="{{ old('name', $product->stock) }}"  />
                                <x-forms.form-error name="stock" />
                            </div>
                        </div>
                        <!-- Weight -->
                        <div class="sm:col-span-3">
                            <x-forms.form-label for="weight" >Weight (in Kg)</x-forms.form-label>
                            <div class="mt-2">
                                <x-forms.form-input disabled id="weight" type="text" name="weight" value="{{ old('name', $product->weight) }}"  />
                                <x-forms.form-error name="weight" />
                            </div>
                        </div> 
                        <!-- Tags -->
                        <div class="sm:col-span-3">
                            <x-forms.form-label for="tags" >Tags</x-forms.form-label>
                            <div class="mt-2">
                                <x-forms.form-input disabled id="tags" type="tags" name="tags" value="{{ old('name', $product->tags) }}"  />
                                <x-forms.form-error name="tags" />
                            </div>
                        </div>

                        <!-- Brand -->
                        <div class="sm:col-span-full">
                            <x-forms.form-label for="brand_id" >Brand</x-forms.form-label>
                            <div class="mt-2 grid grid-cols-1">
                                <x-forms.form-select disabled id="brand_id" name="brand_id">
                                    @isset($brands)
                                        @foreach ($brands as $brand)
                                            <option @selected($brand->id==$product->brand_id) value="{{$brand->id}}">{{$brand->name}}</option>
                                        @endforeach  
                                    @endisset
                                </x-forms.form-select>    
                                <x-forms.form-error name="brand_id" />                                  
                            </div>
                        </div> 
                        <!-- Product Description -->
                        <div class="col-span-full">
                            <x-forms.form-label for="body" >Product description</x-forms.form-label>

                            <div class="mt-2">
                                <textarea disabled id="editor" name="description" rows="6" class="w-full border-gray-300 rounded-md">
                                    {!! old('description', $product->description) !!}
                                </textarea>
                                <x-forms.form-error name="description" />   
                            </div>
                        </div>

                        <!-- Thumbnail Image -->
                        <div class="sm:col-span-3">
                            <x-forms.form-label for="image" >Thumbnail Image</x-forms.form-label>

                            @if ($product->image)
                                <div class="mt-2 relative inline-block">
                                    <img src="{{ asset('storage/' . $product->image) }}" class="h-24 rounded">
                                </div>
                            @endif

                        </div>

                        <!-- Multiple Images -->
                        <div class="sm:col-span-3">
                            <x-forms.form-label for="new_images" >Gallery Image</x-forms.form-label>

                            <!-- Preview container -->
                            <div id="image-list" class="flex flex-wrap gap-2 mt-2">
                                @foreach (json_decode($product->images ?? '[]', true) as $img)
                                    <div class="relative">
                                        <img src="{{ asset('storage/' . $img) }}" class="h-24 rounded">
                                        
                                    </div>
                                @endforeach
                            </div>
                        </div> 

                        <!-- Vdieos -->
                        <div class="col-span-full">
                            <x-forms.form-label for="new_videos" >Videos</x-forms.form-label>
                            <!-- Video preview container -->
                            <div id="video-list" class="flex flex-wrap gap-2 mt-2">
                                @foreach (json_decode($product->videos ?? '[]', true) as $vid)
                                    <div class="relative">
                                        <video src="{{ asset('storage/' . $vid) }}" class="h-24 rounded" controls></video>
                                        
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <!-- Youtube Link -->
                        <div class="sm:col-span-3">
                            <x-forms.form-label for="youtube_link">Youtube video / shorts link</x-forms.form-label>
                            <div class="mt-2">
                                <x-forms.form-input id="youtube_link" type="text" name="youtube_link"  value="{{ old('name', $product->youtube_link) }}" disabled  />
                                <x-forms.form-error name="youtube_link" />
                            </div>
                        </div>
                        <!-- Pdf -->
                        <div class="sm:col-span-3">
                            <x-forms.form-label for="new_pdfs" >PDF Specification</x-forms.form-label>
                            <!-- PDF preview container -->
                            <div id="pdf-list" class="space-y-2 mt-2">
                                @foreach (json_decode($product->pdfs ?? '[]', true) as $pdf)
                                    <div class="relative p-2 border rounded bg-gray-100 flex justify-between items-center">
                                        <span class="text-sm text-gray-700 truncate max-w-[200px]">
                                            {{ basename($pdf) }}
                                        </span>
                                        
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
                <x-forms.common-button 
                    href="/products"
                >
                    Cancel
                </x-forms.common-button>

                <x-forms.common-button
                    class=" bg-indigo-600 text-white hover:bg-indigo-500
                    focus-visible:outline-indigo-600"
                    href="/products/{{$product->id}}/edit"
                >
                    Edit Product
                </x-forms.common-button>

            </div>
            
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
