<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $products = Product::with('category', 'brand')->paginate(2);
        return view('products.index', compact('products'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create', [
            'categories' => Category::all(),
            'brands' => Brand::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
      $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'weight' => 'nullable|string|max:50',
            'tags' => 'nullable|string|max:255',
            'youtube_link' => 'nullable|url',
            'image' => 'nullable|image|max:5120', // 5MB thumbnail
            'images.*' => 'nullable|image|max:5120',
            'videos.*' => 'nullable|mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime|max:51200', // 50MB
            'pdfs.*' => 'nullable|mimes:pdf|max:10240', // 10MB
            'description' => 'nullable|string',
        ]);

        // ✅ Handle thumbnail image
        $thumbnail = $request->file('image') 
            ? $request->file('image')->store('products/thumbnails', 'public') 
            : null;

        // ✅ Handle gallery images
        $gallery = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $gallery[] = $img->store('products/images', 'public');
            }
        }

        // ✅ Handle videos
        $videos = [];
        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $video) {
                $videos[] = $video->store('products/videos', 'public');
            }
        }

        // ✅ Handle PDFs
        $pdfs = [];
        if ($request->hasFile('pdfs')) {
            foreach ($request->file('pdfs') as $pdf) {
                $pdfs[] = $pdf->store('products/pdfs', 'public');
            }
        }

        // ✅ Create product
        $product = Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'description' => $request->description, // CKEditor content
            'price' => $request->price,
            'stock' => $request->stock,
            'weight' => $request->weight,
            'tags' => $request->tags,
            'youtube_link' => $request->youtube_link,
            'featured' => $request->featured,
            'published' => $request->published,
            'image' => $thumbnail,
            'images' => !empty($gallery) ? json_encode($gallery) : null,
            'videos' => !empty($videos) ? json_encode($videos) : null,
            'pdfs' => !empty($pdfs) ? json_encode($pdfs) : null,
            'status' => 1,
            'created_by' => Auth::user()->email,
        ]);

        return redirect('/products')->with('success', 'Product created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
       return view('products.show', [
            'product'=>$product,
            'categories' => Category::all(),
            'brands' => Brand::all()]
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('products.edit', [
            'product'=>$product,
            'categories' => Category::all(),
            'brands' => Brand::all()]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'weight' => 'nullable|string|max:50',
            'tags' => 'nullable|string|max:255',
            'youtube_link' => 'nullable|url',
            'image' => 'nullable|image|max:5120', // 5MB thumbnail
            'new_images.*' => 'nullable|image|max:5120',
            'new_videos.*' => 'nullable|mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime|max:51200', // 50MB
            'new_pdfs.*' => 'nullable|mimes:pdf|max:10240', // 10MB
            'description' => 'nullable|string',
        ]);
        
        // --- Basic fields
        $data = [
            'name' => $request->name,
            'stock' => $request->stock,
            'price' => $request->price, 
            'weight' => $request->weight, 
            'tags' => $request->tags, 
            'category_id' => $request->category_id, 
            'brand_id' => $request->brand_id, 
            'description' => $request->description,
            'featured' => $request->featured,
            'published' => $request->published,
            'updated_by' => Auth::user()->email,
        ];

        // --- Handle Thumbnail
        if ($request->has('remove_thumbnail') && $product->image) {
            Storage::disk('public')->delete($product->image);
            $data['image'] = null;
        }

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products/thumbnails', 'public');
        }

        // --- Handle Multiple Images
        $existingImages = json_decode($product->images ?? '[]', true);

        // Remove selected
        if ($request->removed_images) {
            foreach ($request->removed_images as $file) {
                Storage::disk('public')->delete($file);
                $existingImages = array_diff($existingImages, [$file]);
            }
        }

        // Add new uploads
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $file) {
                $existingImages[] = $file->store('products/images', 'public');
            }
        }

        $data['images'] = json_encode(array_values($existingImages));

        // --- Handle Videos
        $existingVideos = json_decode($product->videos ?? '[]', true);

        if ($request->removed_videos) {
            foreach ($request->removed_videos as $file) {
                Storage::disk('public')->delete($file);
                $existingVideos = array_diff($existingVideos, [$file]);
            }
        }

        if ($request->hasFile('new_videos')) {
            foreach ($request->file('new_videos') as $file) {
                $existingVideos[] = $file->store('products/videos', 'public');
            }
        }

        $data['videos'] = json_encode(array_values($existingVideos));

        // --- Handle PDFs
        $existingPdfs = json_decode($product->pdfs ?? '[]', true);

        if ($request->removed_pdfs) {
            foreach ($request->removed_pdfs as $file) {
                Storage::disk('public')->delete($file);
                $existingPdfs = array_diff($existingPdfs, [$file]);
            }
        }

        if ($request->hasFile('new_pdfs')) {
            foreach ($request->file('new_pdfs') as $file) {
                $existingPdfs[] = $file->store('products/pdfs', 'public');
            }
        }

        $data['pdfs'] = json_encode(array_values($existingPdfs));



        // ✅ Finally, perform one single update
        $product->update($data);

        return redirect('products')->with('success', 'Product updated successfully!');
    }


    public function toggleStatus(Request $request, Product $product)
    {
        $field = $request->field;
        $value = $request->value;

        if (in_array($field, ['featured', 'published'])) {
            $product->$field = $value;
            $product->updated_by = Auth::user()->email;
            $product->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 400);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        return redirect('/products')->with('success', 'Delete is not permitted!');
    }
}
