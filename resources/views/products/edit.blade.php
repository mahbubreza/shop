<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Product
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <form method="POST" action="/products/{{ $product->id }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <!-- Name -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium">Name</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}"
                               class="w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <!-- CKEditor Description -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium">Description</label>
                        <textarea id="editor" name="description" rows="6" class="w-full border-gray-300 rounded-md">
                            {!! old('description', $product->description) !!}
                        </textarea>
                    </div>

                    <!-- Thumbnail -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium">Thumbnail</label>
                        <input type="file" name="image" accept="image/*" class="block w-full text-sm text-gray-600">
                        @if ($product->image)
                            <div class="mt-2 relative inline-block">
                                <img src="{{ asset('storage/' . $product->image) }}" class="h-24 rounded">
                                <input type="checkbox" name="remove_thumbnail" value="1" class="absolute top-0 right-0">
                            </div>
                        @endif
                    </div>

                    <!-- Multiple Images -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium">Images</label>
                        <input type="file" name="new_images[]" multiple accept="image/*"
                               class="block w-full text-sm text-gray-600">

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

                    <!-- Videos -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium">Videos</label>
                        <input type="file" name="new_videos[]" multiple accept="video/*"
                               class="block w-full text-sm text-gray-600">
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

                    <!-- PDFs -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium">PDFs</label>
                        <input type="file" name="new_pdfs[]" multiple accept="application/pdf"
                               class="block w-full text-sm text-gray-600">

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

                    <div class="mt-6">
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Update Product
                        </button>
                    </div>
                </form>
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
