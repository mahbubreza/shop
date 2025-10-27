<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $products = Product::with('category')->paginate(12);
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
        
        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'category_id' => 'required|exists:categories,id',
        //     'brand_id' => 'nullable|exists:brands,id',
        //     'price' => 'required|numeric',
        //     'stock' => 'required|integer',
        //     'weight' => 'nullable|string|max:50',
        //     'tags' => 'nullable|string|max:255',
        //     'youtube_link' => 'nullable|url',
        //     'image' => 'nullable|image|max:5120', // 5MB
        //     'images.*' => 'nullable|image|max:5120',
        //     'videos.*' => 'nullable|mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime|max:51200', // 50MB
        //     'pdfs.*' => 'nullable|mimes:pdf|max:10240', // 10MB
        // ]);

       
        // Handle thumbnail image
        $thumbnail = $request->file('image') ? $request->file('image')->store('products/thumbnails', 'public') : null;

        // Handle gallery images
        $gallery = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $gallery[] = $img->store('products/images', 'public');
            }
        }

        // Handle videos
        $videos = [];
        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $video) {
                $videos[] = $video->store('products/videos', 'public');
            }
        }

        // Handle PDFs
        $pdfs = [];
        if ($request->hasFile('pdfs')) {
            foreach ($request->file('pdfs') as $pdf) {
                $pdfs[] = $pdf->store('products/pdfs', 'public');
            }
        }

        // Create product
        $product = Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'description' => $request->body, // CKEditor content
            'price' => $request->price,
            'stock' => $request->stock,
            'weight' => $request->weight,
            'tags' => $request->tags,
            'youtube_link' => $request->youtube_link,
            'image' => $thumbnail,
            'images' => $gallery ? json_encode($gallery) : null,
            'videos' => $videos ? json_encode($videos) : null,
            'pdfs' => $pdfs ? json_encode($pdfs) : null,
        ]);

        return redirect('/products')->with('success', 'Product created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
       //return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
