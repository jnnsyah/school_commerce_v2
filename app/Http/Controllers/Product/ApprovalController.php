<?php
// app/Http/Controllers/Product/ApprovalController.php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use App\Models\Product\ProductStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApprovalController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:product.approve');
    }

    /**
     * Display approval listing page
     */
    public function index(Request $request)
    {
        $query = Product::with(['class', 'category', 'images', 'status']);

        // Filter by status
        if ($request->has('status') && $request->status) {
            switch ($request->status) {
                case 'pending':
                    $query->where('status_id', ProductStatus::PENDING);
                    break;
                case 'approved':
                    $query->where('status_id', ProductStatus::APPROVED);
                    break;
                case 'rejected':
                    $query->where('status_id', ProductStatus::REJECTED);
                    break;
            }
        }

        // Get counts for stats
        $pendingCount = Product::where('status_id', ProductStatus::PENDING)->count();
        $approvedCount = Product::where('status_id', ProductStatus::APPROVED)->count();
        $rejectedCount = Product::where('status_id', ProductStatus::REJECTED)->count();
        $totalCount = Product::count();

        $products = $query->latest()->paginate(15);

        return view('admin.products.approvals.index', compact(
            'products',
            'pendingCount',
            'approvedCount', 
            'rejectedCount',
            'totalCount'
        ));
    }

    /**
     * Approve a product
     */
    public function approve(Product $product, Request $request)
    {
        try {
            DB::transaction(function () use ($product) {
                $product->update([
                    'status_id' => ProductStatus::APPROVED,
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                    'rejection_reason' => null, // Clear rejection reason if any
                ]);

                // You can add notification logic here
                // Notification::send($product->class->teacher, new ProductApprovedNotification($product));
            });

            return redirect()->route('admin.products.approval.index')
                ->with('success', 'Produk berhasil disetujui dan sekarang tersedia untuk dijual.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menyetujui produk: ' . $e->getMessage());
        }
    }

    /**
     * Reject a product
     */
    public function reject(Product $product, Request $request)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        try {
            DB::transaction(function () use ($product, $request) {
                $product->update([
                    'status_id' => ProductStatus::REJECTED,
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                    'rejection_reason' => $request->rejection_reason,
                ]);

                // You can add notification logic here
                // Notification::send($product->class->teacher, new ProductRejectedNotification($product, $request->rejection_reason));
            });

            return redirect()->route('admin.products.approval.index')
                ->with('success', 'Produk berhasil ditolak dengan alasan yang diberikan.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menolak produk: ' . $e->getMessage());
        }
    }

    /**
     * Bulk approve products
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,product_id',
        ]);

        try {
            DB::transaction(function () use ($request) {
                Product::whereIn('product_id', $request->product_ids)
                    ->update([
                        'status_id' => ProductStatus::APPROVED,
                        'approved_by' => auth()->id(),
                        'approved_at' => now(),
                        'rejection_reason' => null,
                    ]);
            });

            return response()->json([
                'success' => true,
                'message' => count($request->product_ids) . ' produk berhasil disetujui.',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyetujui produk: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get approval statistics for dashboard
     */
    public function getApprovalStats()
    {
        $stats = [
            'pending' => Product::where('status_id', ProductStatus::PENDING)->count(),
            'approved' => Product::where('status_id', ProductStatus::APPROVED)->count(),
            'rejected' => Product::where('status_id', ProductStatus::REJECTED)->count(),
            'total' => Product::count(),
        ];

        return response()->json($stats);
    }
}