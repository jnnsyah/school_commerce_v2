<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use App\Models\Product\ProductVariant;
use App\Models\Product\ProductExtra;
use App\Models\Inventory\StockProduct;
use App\Models\Inventory\StockProductVariant;
use App\Models\Inventory\StockProductExtra;
use App\Models\Inventory\StockReferenceType;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:product.manage');
    }

    public function index(Request $request)
    {
        $query = Product::with(['category', 'status', 'variants']);

        // Filter by category
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by status
        if ($request->has('status_id') && $request->status_id) {
            $query->where('status_id', $request->status_id);
        }

        $products = $query->where('status_id', 2) // Only approved products
            ->latest()
            ->paginate(15);

        return view('inventory.stock.index', compact('products'));
    }

    public function adjustStock(Request $request, Product $product)
    {
        $request->validate([
            'adjustment_type' => 'required|in:add,subtract',
            'quantity' => 'required|integer|min:1',
            'note' => 'required|string|max:500',
            'variant_id' => 'nullable|exists:product_variants,id',
            'extra_id' => 'nullable|exists:product_extras,id',
        ]);

        try {
            if ($request->variant_id) {
                $this->adjustVariantStock($request, $product);
            } elseif ($request->extra_id) {
                $this->adjustExtraStock($request, $product);
            } else {
                $this->adjustProductStock($request, $product);
            }

            return redirect()->back()->with('success', 'Stock adjusted successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to adjust stock: ' . $e->getMessage());
        }
    }

    private function adjustProductStock($request, $product)
    {
        $quantity = $request->adjustment_type === 'add' ? $request->quantity : -$request->quantity;

        // Create stock movement record
        StockProduct::create([
            'product_id' => $product->product_id,
            'ref_type_id' => StockReferenceType::ADJUSTMENT,
            'ref_id' => 0, // Manual adjustment
            'qty' => $quantity,
            'note' => $request->note,
            'user_id' => auth()->id(),
        ]);

        // Update variants stock if any
        foreach ($product->variants as $variant) {
            $variant->update([
                'stock_at' => max(0, $variant->stock_at + $quantity)
            ]);
        }
    }

    private function adjustVariantStock($request, $product)
    {
        $variant = ProductVariant::where('id', $request->variant_id)
            ->where('product_id', $product->product_id)
            ->firstOrFail();

        $quantity = $request->adjustment_type === 'add' ? $request->quantity : -$request->quantity;

        // Create stock movement record
        StockProductVariant::create([
            'variant_id' => $variant->id,
            'ref_type_id' => StockReferenceType::ADJUSTMENT,
            'ref_id' => 0,
            'qty' => $quantity,
            'note' => $request->note,
            'user_id' => auth()->id(),
        ]);

        // Update variant stock
        $variant->update([
            'stock_at' => max(0, $variant->stock_at + $quantity)
        ]);
    }

    private function adjustExtraStock($request, $product)
    {
        $extra = ProductExtra::where('id', $request->extra_id)
            ->where('product_id', $product->product_id)
            ->firstOrFail();

        $quantity = $request->adjustment_type === 'add' ? $request->quantity : -$request->quantity;

        // Create stock movement record
        StockProductExtra::create([
            'extra_id' => $extra->id,
            'ref_type_id' => StockReferenceType::ADJUSTMENT,
            'ref_id' => 0,
            'qty' => $quantity,
            'note' => $request->note,
            'user_id' => auth()->id(),
        ]);

        // Update extra stock
        $extra->update([
            'stock_cache' => max(0, $extra->stock_cache + $quantity)
        ]);
    }

    public function movementHistory(Request $request)
    {
        $productMovements = StockProduct::with(['product', 'user', 'referenceType'])
            ->latest()
            ->paginate(20, ['*'], 'product_page');

        $variantMovements = StockProductVariant::with(['variant.product', 'user', 'referenceType'])
            ->latest()
            ->paginate(20, ['*'], 'variant_page');

        $extraMovements = StockProductExtra::with(['extra.product', 'user', 'referenceType'])
            ->latest()
            ->paginate(20, ['*'], 'extra_page');

        return view('inventory.reports.movement', compact(
            'productMovements', 
            'variantMovements', 
            'extraMovements'
        ));
    }

    public function stockLevelReport()
    {
        $lowStockProducts = Product::with(['category', 'variants'])
            ->whereHas('variants', function($query) {
                $query->where('stock_at', '<=', 10)
                      ->where('stock_at', '>', 0);
            })
            ->orWhereHas('variants', function($query) {
                $query->where('stock_at', '<=', 0);
            })
            ->get();

        $outOfStockProducts = Product::with(['category', 'variants'])
            ->whereDoesntHave('variants', function($query) {
                $query->where('stock_at', '>', 0);
            })
            ->get();

        return view('inventory.reports.stock-level', compact(
            'lowStockProducts',
            'outOfStockProducts'
        ));
    }
}