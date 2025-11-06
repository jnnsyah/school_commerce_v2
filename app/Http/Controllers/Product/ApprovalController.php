<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use App\Models\Product\ProductStatus;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:product.approve');
    }

    public function index()
    {
        $pendingProducts = Product::with(['class', 'category', 'images'])
            ->where('status_id', ProductStatus::PENDING)
            ->latest()
            ->paginate(10);

        return view('products.approval', compact('pendingProducts'));
    }

    public function approve(Product $product)
    {
        $product->update([
            'status_id' => ProductStatus::APPROVED,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);

        return redirect()->route('products.approval.index')
            ->with('success', 'Product approved successfully.');
    }

    public function reject(Request $request, Product $product)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $product->update([
            'status_id' => ProductStatus::REJECTED,
            'rejection_reason' => $request->rejection_reason,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('products.approval.index')
            ->with('success', 'Product rejected successfully.');
    }
}