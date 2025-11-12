<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    public function index()
    {
        return view('brands.index', [
            'paginatedBrands'=> Brand::paginate(10)
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('brands.create');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:brands,slug',
            'image' => 'required|image|max:5120', // 5MB thumbnail

        ]);
        $thumbnail = $request->file('image') 
            ? $request->file('image')->store('brands/thumbnails', 'public') 
            : null;
        $brand = Brand::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'logo' => $thumbnail,

            'status' => 1,
            'created_by' => Auth::user()->email,  // or Auth::id() if you prefer user ID
            'updated_by' => Auth::user()->email,
        ]);
        return redirect('/brands')->with('success', 'Brand created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        return view('brands.show', ['brand'=>$brand]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        return view('brands.edit', ['brand'=>$brand]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Brand $brand)
    {
        request()->validate([
                'name' => 'required',
                'slug' => 'required',
                'status' => 'required'
            ]);
        $thumbnail = $brand->image;
        if (request()->has('remove_thumbnail') && $brand->image) {
            Storage::disk('public')->delete($brand->image);
            $thumbnail = null;
        }

         if (request()->hasFile('image')) {
            if ($brand->image) {
                Storage::disk('public')->delete($brand->image);
            }
            $thumbnail = request()->file('image')->store('brands/thumbnails', 'public');
        }
          
        $brand->update([
            'name' => request('name'),
            'slug' => request('slug'),
            'logo' => $thumbnail,
            'status' => request('status'),
            'updated_by' => Auth::user()->email,
        ]);

    return redirect('/brands')
        ->with('success', 'Brand updated successfully!');
    }

     public function toggleStatus(Request $request, Brand $brand)
    {
        $field = $request->field;
        $value = $request->value;

        if (in_array($field, ['status'])) {
            $brand->$field = $value;
            $brand->updated_by = Auth::user()->email;
            $brand->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        return redirect('/categories')->with('success', 'Delete is not permitted!');
    }
}
