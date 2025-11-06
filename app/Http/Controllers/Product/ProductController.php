<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductRequest;
use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
use App\Models\Product\ProductStatus;
use App\Models\Academic\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:product.view')->only('index', 'show');
        $this->middleware('permission:product.create')->only('create', 'store');
        $this->middleware('permission:product.edit')->only('edit', 'update');
        $this->middleware('permission:product.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = Product::with(['class', 'category', 'status', 'images']);

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status_id', $request->status);
        }

        // Filter by category
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by class
        if ($request->has('class_id') && $request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        // For wali_kelas, only show their class products
        if (auth()->user()->hasRole('wali_kelas')) {
            $class = SchoolClass::where('teacher_id', auth()->id())->first();
            if ($class) {
                $query->where('class_id', $class->class_id);
            }
        }

        // For students and teachers, only show approved products
        if (auth()->user()->hasAnyRole(['student', 'guru_biasa'])) {
            $query->where('status_id', ProductStatus::APPROVED);
        }

        $products = $query->latest()->paginate(12);
        $categories = ProductCategory::where('is_active', true)->get();
        $statuses = ProductStatus::all();
        $classes = SchoolClass::where('is_active', true)->get();

        return view('products.index', compact('products', 'categories', 'statuses', 'classes'));
    }

    public function create()
    {
        // Only wali_kelas can create products
        if (!auth()->user()->hasRole('wali_kelas')) {
            abort(403, 'Only class teachers can create products.');
        }

        $class = SchoolClass::where('teacher_id', auth()->id())->first();
        
        if (!$class) {
            return redirect()->back()
                ->with('error', 'You are not assigned as a class teacher.');
        }

        $categories = ProductCategory::where('is_active', true)->get();

        return view('products.create', compact('class', 'categories'));
    }

    public function store(ProductRequest $request)
    {
        // Only wali_kelas can create products
        if (!auth()->user()->hasRole('wali_kelas')) {
            abort(403, 'Only class teachers can create products.');
        }

        $class = SchoolClass::where('teacher_id', auth()->id())->first();
        
        if (!$class) {
            return redirect()->back()
                ->with('error', 'You are not assigned as a class teacher.');
        }

        // Generate SKU
        $sku = 'PROD-' . strtoupper(uniqid());

        // Create product
        $product = Product::create([
            'class_id' => $class->class_id,
            'name' => $request->name,
            'sku' => $sku,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'status_id' => ProductStatus::PENDING, // Always start as pending
        ]);

        // Handle image upload
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                
                $product->images()->create([
                    'file_path' => $path,
                    'is_primary' => $index === 0, // First image as primary
                ]);
            }
        }

        return redirect()->route('products.show', $product)
            ->with('success', 'Product created successfully and submitted for approval.');
    }

    public function show(Product $product)
    {
        $product->load(['class', 'category', 'status', 'images', 'variants.variantValues', 'extras']);

        // Check if user can view this product
        if ($product->isPending() && !auth()->user()->can('product.approve') && 
            $product->class->teacher_id !== auth()->id()) {
            abort(403, 'This product is pending approval.');
        }

        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        // Check permission - only owner or admin can edit
        if ($product->class->teacher_id !== auth()->id() && !auth()->user()->hasRole(['super_admin', 'admin'])) {
            abort(403, 'You can only edit your own class products.');
        }

        // Cannot edit approved products without special permission
        if ($product->isApproved() && !auth()->user()->can('product.approve')) {
            return redirect()->back()
                ->with('error', 'Approved products cannot be edited. Please contact administrator.');
        }

        $categories = ProductCategory::where('is_active', true)->get();
        $product->load(['images', 'variants', 'extras']);

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        // Check permission
        if ($product->class->teacher_id !== auth()->id() && !auth()->user()->hasRole(['super_admin', 'admin'])) {
            abort(403, 'You can only edit your own class products.');
        }

        // If product was approved and edited by non-admin, set back to pending
        $newStatus = $product->status_id;
        if ($product->isApproved() && !auth()->user()->can('product.approve')) {
            $newStatus = ProductStatus::PENDING;
        }

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'status_id' => $newStatus,
        ]);

        // Handle new image upload
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                
                $product->images()->create([
                    'file_path' => $path,
                    'is_primary' => false,
                ]);
            }
        }

        return redirect()->route('products.show', $product)
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        // Check permission
        if ($product->class->teacher_id !== auth()->id() && !auth()->user()->hasRole(['super_admin', 'admin'])) {
            abort(403, 'You can only delete your own class products.');
        }

        // Delete associated images from storage
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->file_path);
            $image->delete();
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}