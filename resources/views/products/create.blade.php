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
                <div class="space-y-12">  
                    <div class="border-b border-gray-900/10 dark:border-gray-700 pb-12">
                        <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <!-- Body (Quill Editor) -->
                            <div class="sm:col-span-3">
                                <x-forms.form-label for="name" >Product Title</x-forms.form-label>
                                <div class="mt-2">
                                    <x-forms.form-input id="name" type="text" name="name" placeholder="Shampoo"  />
                                    <x-forms.form-error name="name" />
                                </div>
                            </div>
                            <div class="sm:col-span-3">
                                <x-forms.form-label for="category_id" >Category</x-forms.form-label>
                                <div class="mt-2 grid grid-cols-1">
                                    <x-forms.form-select id="category_id" name="category_id">
                                        @isset($categories)
                                           @foreach ($categories as $category)
                                                <option value="{{$category->id}}">{{$category->name}}</option>
                                            @endforeach  
                                        @endisset
                                    </x-forms.form-select>                                    
                                </div>
                            </div> 
                            <div class="sm:col-span-3">
                                <x-forms.form-label for="price" >Price per unit (in Taka)</x-forms.form-label>
                                <div class="mt-2">
                                    <x-forms.form-input id="price" type="text" name="price" placeholder="1800"  />
                                    <x-forms.form-error name="price" />
                                </div>
                            </div> 
                            <div class="sm:col-span-3">
                                <x-forms.form-label for="stock" >Unit/Quantity (in pieces)</x-forms.form-label>
                                <div class="mt-2">
                                    <x-forms.form-input id="stock" type="text" name="stock" placeholder="10"  />
                                    <x-forms.form-error name="stock" />
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <x-forms.form-label for="weight" >Weight (in Kg)</x-forms.form-label>
                                <div class="mt-2">
                                    <x-forms.form-input id="weight" type="text" name="weight" placeholder="0.5 kg"  />
                                    <x-forms.form-error name="weight" />
                                </div>
                            </div> 
                            <div class="sm:col-span-3">
                                <x-forms.form-label for="tags" >Tags</x-forms.form-label>
                                <div class="mt-2">
                                    <x-forms.form-input id="tags" type="tags" name="tags" placeholder="health, beauty"  />
                                    <x-forms.form-error name="tags" />
                                </div>
                            </div>

                            <div class="sm:col-span-full">
                                <x-forms.form-label for="brand_id" >Brand</x-forms.form-label>
                                <div class="mt-2 grid grid-cols-1">
                                    <x-forms.form-select id="brand_id" name="brand_id">
                                        @isset($brands)
                                           @foreach ($brands as $brand)
                                                <option value="{{$brand->id}}">{{$brand->name}}</option>
                                            @endforeach  
                                        @endisset
                                    </x-forms.form-select>                                    
                                </div>
                            </div> 
                            
                            <div class="col-span-full">
                                <label for="body" class="block text-sm/6 font-medium text-gray-900">Product description</label>
                                <div class="mt-2">
                                    <textarea
                                        id="body"
                                        name="body"
                                        rows="8"
                                        class="w-full rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 p-3 text-gray-800 shadow-sm"
                                        placeholder="Write your post..."
                                    ></textarea>
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="image" class="block text-sm/6 font-medium text-gray-900">Upload Thumbnail Image</label>
                                <div class="mt-2">
                                    <input
                                        type="file"
                                        name="image"
                                        id="image"
                                        accept="image/*"
                                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                        onchange="previewImage(event)"
                                    />
                                </div>

                                <p class="mt-1 text-sm/6 text-gray-600">
                                    This image is visible in all product box. Minimum dimensions required: 195px width X 195px height. 
                                    Keep some blank space around main object of your image as we had to crop some edge in different 
                                    devices to make it responsive. If no thumbnail is uploaded, the product's first gallery image 
                                    will be used as the thumbnail image.
                                </p>


                                <!-- Preview container -->
                                <div class="mt-4">
                                    <img id="preview" class="hidden w-48 h-48 object-cover rounded-lg shadow-md border border-gray-300" />
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="images" class="block text-sm/6 font-medium text-gray-900">Gallery Images</label>
                                <div class="mt-2">
                                    <input
                                        type="file"
                                        name="images[]"
                                        id="images"
                                        accept="image/*"
                                        multiple
                                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                        onchange="previewImages(event)"
                                    />
                                </div>
                                <p class="mt-1 text-sm/6 text-gray-600">
                                    These images are visible in product details page gallery. Minimum dimensions required: 900px width X 900px height.
                                <p>
                                <!-- Preview container -->
                                <div id="preview-container" class="mt-4 flex flex-wrap gap-4"></div>
                            </div>   

                            <div class="col-span-full">
                                <label for="images" class="block text-sm/6 font-medium text-gray-900">Upload Videos</label>
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
                                </div>
                                <p class="mt-1 text-sm/6 text-gray-600">
                                    Try to upload videos under 30 seconds for better performance.                        <p>
                                <!-- Video preview container -->
                                <div id="video-preview-container" class="mt-4 flex flex-wrap gap-4"></div>
                            </div>

                            <div class="sm:col-span-3">
                                <x-forms.form-label for="youtube_link">Youtube video / shorts link</x-forms.form-label>
                                <div class="mt-2">
                                    <x-forms.form-input id="youtube_link" type="text" name="youtube_link" placeholder="https://www.youtube.com/watch?v=AeY15GBpjqE&t=8476s"  />
                                    <x-forms.form-error name="youtube_link" />
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="pdfs" class="block text-sm/6 font-medium text-gray-900">Upload PDF Specification</label>
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
                                </div>
                                <!-- PDF preview container -->
                                <div id="pdf-preview-container" class="mt-4 flex flex-col gap-2"></div>
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

    <!-- CKEditor Script -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>
    <script>
        function previewPDFs(event) {
        const container = document.getElementById('pdf-preview-container');
        container.innerHTML = ''; // Clear previous previews

        const files = event.target.files;
        if (!files.length) {
            container.innerHTML = '<p class="text-gray-500">No PDFs selected.</p>';
            return;
        }

        Array.from(files).forEach(file => {
            if (file.type !== 'application/pdf') return;

            const div = document.createElement('div');
            div.className = 'flex items-center gap-2 p-2 border rounded-lg border-gray-300';

            const icon = document.createElement('span');
            icon.textContent = '📄';
            icon.className = 'text-xl';

            const name = document.createElement('span');
            name.textContent = file.name;
            name.className = 'text-gray-800';

            div.appendChild(icon);
            div.appendChild(name);
            container.appendChild(div);
        });
    }
        function previewVideos(event) {
        const container = document.getElementById('video-preview-container');
        container.innerHTML = ''; // Clear previous previews

        const files = event.target.files;
        if (!files.length) {
            container.innerHTML = '<p class="text-gray-500">No videos selected.</p>';
            return;
        }

        Array.from(files).forEach(file => {
            if (!file.type.startsWith('video/')) return;

            const video = document.createElement('video');
            video.className = 'w-48 h-32 rounded-lg border border-gray-300 shadow-sm';
            video.controls = true; // add play/pause controls

            const source = document.createElement('source');
            source.src = URL.createObjectURL(file);
            source.type = file.type;

            video.appendChild(source);
            container.appendChild(video);
        });
    }
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('preview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }

                reader.readAsDataURL(input.files[0]);
            } else {
                preview.classList.add('hidden');
                preview.src = '';
            }
        }
        function previewImages(event) {
            const container = document.getElementById('preview-container');
            container.innerHTML = ''; // Clear previous previews

            const files = event.target.files;

            if (!files.length) {
                container.innerHTML = '<p class="text-gray-500">No images selected.</p>';
                return;
            }

            Array.from(files).forEach(file => {
                if (!file.type.startsWith('image/')) return;

                const reader = new FileReader();
                reader.onload = e => {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-32 h-32 object-cover rounded-lg border border-gray-300 shadow-sm';
                    container.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        }
        ClassicEditor.create(document.querySelector('#body'), {
                ckfinder: {
                    uploadUrl: '{{ route('ckeditor.upload').'?_token='.csrf_token() }}'
                }
            }).then(editor => {
                console.log('CKEditor initialized', editor);
            }).catch(error => {
                console.error('CKEditor error:', error);
            });
    </script>
</x-app-layout>
