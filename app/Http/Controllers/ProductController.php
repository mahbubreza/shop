<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            'categories' => Category::where('status', 1)->get(),
            'brands' => Brand::where('status', 1)->get(),
            'sizes' => Size::where('status', 1)->get(),
            'colors' => Color::where('status', 1)->get()
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
            'discounted_price' => 'nullable|numeric|min:0',
            'discount_start_date' => 'nullable|date',
            'discount_end_date' => 'nullable|date|after_or_equal:discount_start_date',
            'sizes' => 'nullable|array',
            'sizes.*' => 'exists:sizes,id',
            'colors' => 'nullable|array',
            'colors.*' => 'exists:colors,id',
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
       $discounted_price = $request->discounted_price ?? 0;
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
            'discounted_price' => $discounted_price,
            'discount_start_date' => $request->discount_start_date,
            'discount_end_date' => $request->discount_end_date,
            'status' => 1,
            'created_by' => Auth::user()->email,
        ]);

            // 🔹 Attach sizes
        if ($request->has('sizes')) {
            foreach ($request->sizes as $sizeId) {
                DB::table('product_size')->insert([
                    'product_id' => $product->id,
                    'size_id' => $sizeId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 🔹 Attach colors
        if ($request->has('colors')) {
            foreach ($request->colors as $colorId) {
                DB::table('product_color')->insert([
                    'product_id' => $product->id,
                    'color_id' => $colorId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect('/products')->with('success', 'Product created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load(['sizes', 'colors']);
        return view('products.show', [
            'product'=>$product,
            'categories' => Category::where('status', 1)->get(),
            'brands' => Brand::where('status', 1)->get(),
            'sizes' => Size::where('status', 1)->get(),
            'colors' => Color::where('status', 1)->get()]
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $product->load(['sizes', 'colors']);
        return view('products.edit', [
            'product'=>$product,
            'categories' => Category::where('status', 1)->get(),
            'brands' => Brand::where('status', 1)->get(),
            'sizes' => Size::where('status', 1)->get(),
            'colors' => Color::where('status', 1)->get()]
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
            'discounted_price' => 'nullable|numeric|min:0',
            'discount_start_date' => 'nullable|date',
            'discount_end_date' => 'nullable|date|after_or_equal:discount_start_date',
            'sizes' => 'nullable|array',
            'colors' => 'nullable|array',
        ]);
        
        // --- Basic fields
               

        $data = [
            'name' => $request->name,
            'stock' => $request->stock,
            'price' => $request->price, 
            'discounted_price' => $request->discounted_price ?? 0,
            'discount_start_date' => $request->discount_start_date,
            'discount_end_date' => $request->discount_end_date,
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
        // ✅ Update Sizes
        DB::table('product_size')->where('product_id', $product->id)->update(['status' => 0]);
        if ($request->has('sizes')) {
            foreach ($request->sizes as $sizeId) {
                DB::table('product_size')->updateOrInsert(
                    ['product_id' => $product->id, 'size_id' => $sizeId],
                    ['status' => 1]
                );
            }
        }

        // ✅ Update Colors
        DB::table('product_color')->where('product_id', $product->id)->update(['status' => 0]);
        if ($request->has('colors')) {
            foreach ($request->colors as $colorId) {
                DB::table('product_color')->updateOrInsert(
                    ['product_id' => $product->id, 'color_id' => $colorId],
                    ['status' => 1]
                );
            }
        }

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

    public function details(Product $product)
    {
        return view('products.details', [
            'product'=>$product]
        );
    }

    public function list()
    {
       
        return view('products.list', [
                'products'=> Product::all(),
                'categories' => Category::all(),
                'brands' => Brand::all()
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        return redirect('/products')->with('success', 'Delete is not permitted!');
    }
}
