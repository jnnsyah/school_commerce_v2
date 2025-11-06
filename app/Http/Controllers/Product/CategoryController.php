<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\ProductCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:product.manage');
    }

    public function index()
    {
        $categories = ProductCategory::latest()->paginate(10);
        return view('products.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:product_categories,name',
            'description' => 'nullable|string|max:500',
        ]);

        ProductCategory::create([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->back()->with('success', 'Category created successfully.');
    }

    public function update(Request $request, ProductCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:product_categories,name,' . $category->id,
            'description' => 'nullable|string|max:500',
        ]);

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->back()->with('success', 'Category updated successfully.');
    }

    public function destroy(ProductCategory $category)
    {
        if ($category->products()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete category that has products.');
        }

        $category->delete();
        return redirect()->back()->with('success', 'Category deleted successfully.');
    }
}