<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:product.manage');
    }

    public function index()
    {
        $categories = ProductCategory::withCount(['products as products_count'])
            ->latest()
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:product_categories,name',
            'is_active' => 'required|boolean',
        ]);

        try {
            ProductCategory::create([
                'name' => $request->name,
                'is_active' => $request->is_active,
            ]);

            return redirect()->route('admin.categories.index')
                ->with('success', 'Kategori berhasil ditambahkan.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan kategori: ' . $e->getMessage());
        }
    }

    public function update(Request $request, ProductCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:product_categories,name,' . $category->id,
            'is_active' => 'required|boolean',
        ]);

        try {
            $category->update([
                'name' => $request->name,
                'is_active' => $request->is_active,
            ]);

            return redirect()->route('admin.categories.index')
                ->with('success', 'Kategori berhasil diperbarui.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui kategori: ' . $e->getMessage());
        }
    }

    public function destroy(ProductCategory $category)
    {
        // Check if category has products
        if ($category->products()->exists()) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus kategori yang masih memiliki produk.');
        }

        try {
            $category->delete();

            return redirect()->route('admin.categories.index')
                ->with('success', 'Kategori berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus kategori: ' . $e->getMessage());
        }
    }

    public function toggleStatus(ProductCategory $category)
    {
        try {
            $category->update([
                'is_active' => !$category->is_active
            ]);

            $status = $category->is_active ? 'diaktifkan' : 'dinonaktifkan';
            
            return redirect()->route('admin.categories.index')
                ->with('success', "Kategori berhasil $status.");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengubah status kategori: ' . $e->getMessage());
        }
    }
}