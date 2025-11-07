<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Product
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
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

                          
                        </div>
                    </div>
                </div>
                <!-- TAB 2: Additional Info -->
                <div id="tab-additional" class="tab-content hidden space-y-12">
                    <div class="border-b border-gray-900/10 dark:border-gray-700 pb-12">
                        <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
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
                            
                            <!-- Discounted Price -->
                            <div class="sm:col-span-3">
                                <x-forms.form-label for="discounted_price">Discounted Price (in Taka)</x-forms.form-label>
                                <div class="mt-2">
                                    <x-forms.form-input id="discounted_price" type="text" name="discounted_price" value="{{ old('name', $product->discounted_price) }}" />
                                    <x-forms.form-error name="discounted_price" />
                                </div>
                            </div>

                            <!-- Discount Start Date -->
                            <div class="sm:col-span-3">
                                <x-forms.form-label for="discount_start_date">Discount Start Date</x-forms.form-label>
                                <div class="mt-2">
                                    <x-forms.form-input disabled
                                    id="discount_start_date" 
                                    type="text" 
                                    name="discount_start_date" 
                                    value="{{ old('discount_start_date', $product->discount_start_date) }}"
 />
                                    <x-forms.form-error name="discount_start_date" />
                                </div>
                            </div>

                            <!-- Discount End Date -->
                            <div class="sm:col-span-3">
                                <x-forms.form-label for="discount_end_date">Discount End Date</x-forms.form-label>
                                <div class="mt-2">
                                    <x-forms.form-input disabled
                                        id="discount_end_date" 
                                        type="text" 
                                        name="discount_end_date" 
                                        value="{{ old('discount_end_date', $product->discount_end_date) }}"
                                        />
                                    <x-forms.form-error name="discount_end_date" />
                                </div>
                            </div>

                            <!-- Sizes -->
                            <div class="sm:col-span-3">
                                <x-forms.form-label for="sizes">Available Sizes</x-forms.form-label>
                                <div class="mt-2 grid grid-cols-2 gap-2 border p-3 rounded-lg">
                                    @foreach ($sizes as $size)
                                        <label class="flex items-center space-x-2">
                                            <input disabled
                                                type="checkbox" 
                                                name="sizes[]" 
                                                value="{{ $size->id }}" 
                                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                @checked(in_array($size->id, $product->sizes->pluck('id')->toArray()))

                                            >
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
                                            <input disabled
                                                type="checkbox" 
                                                name="colors[]" 
                                                value="{{ $color->id }}" 
                                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                @checked(in_array($color->id, $product->colors->pluck('id')->toArray()))

                                            >
                                            <span>{{ $color->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <x-forms.form-error name="colors" />
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <button type="button"
                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    <a href="/products">Ok</a>
                </button>
            </div>
            
        </div>
    </div>

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
