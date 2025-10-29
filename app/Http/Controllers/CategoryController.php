<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('categories.index', [
            'categories'=> Category::paginate(10)
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug',
            'hot' => 'required',
            'image' => 'nullable|image|max:5120', // 5MB thumbnail

        ]);
        $thumbnail = $request->file('image') 
            ? $request->file('image')->store('categories/thumbnails', 'public') 
            : null;
        $category = Category::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'image' => $thumbnail,

            'hot' => $validated['hot'],
            'status' => 1,
            'created_by' => Auth::user()->email,  // or Auth::id() if you prefer user ID
            'updated_by' => Auth::user()->email,
        ]);
        return redirect('/categories/create')->with('success', 'Category created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return view('categories.show', ['category'=>$category]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('categories.edit', ['category'=>$category]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Category $category)
    {
        request()->validate([
                'name' => 'required',
                'slug' => 'required',
                'status' => 'required'
            ]);
        // update the job
          
        $category->update([
        'name' => request('name'),
        'slug' => request('slug'),
        'status' => request('status'),
        'updated_by' => Auth::user()->email,
    ]);

    return redirect('/categories/' . $category->id)
        ->with('success', 'Category updated successfully!');
    }

     public function toggleStatus(Request $request, Category $category)
    {
        $field = $request->field;
        $value = $request->value;

        if (in_array($field, ['hot', 'status'])) {
            $category->$field = $value;
            $category->updated_by = Auth::user()->email;
            $category->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
