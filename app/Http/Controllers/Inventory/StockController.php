<?php
// app/Http/Controllers/Inventory/StockController.php - UPDATE

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\StockAdjustmentRequest;
use App\Models\Product\Product;
use App\Models\Product\ProductVariant;
use App\Models\Product\ProductExtra;
use App\Models\Inventory\StockProduct;
use App\Models\Inventory\StockProductVariant;
use App\Models\Inventory\StockProductExtra;
use App\Services\InventoryService;
use Illuminate\Http\Request;

class StockController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function index()
    {
        $inventorySummary = $this->inventoryService->getInventorySummary();
        $lowStockAlerts = $this->inventoryService->getLowStockAlerts();
        
        return view('admin.inventory.index', compact('inventorySummary', 'lowStockAlerts'));
    }

    public function variants(Request $request)
    {
        $query = ProductVariant::with(['product.images', 'product.class']);
        
        // Filter low stock
        if ($request->has('filter') && $request->filter == 'low_stock') {
            $query->where('stock_at', '>', 0)
                  ->where('stock_at', '<=', 10);
        }
        
        // Filter out of stock
        if ($request->has('filter') && $request->filter == 'out_of_stock') {
            $query->where('stock_at', '<=', 0);
        }

        $variants = $query->orderBy('stock_at', 'asc')->paginate(20);
        
        return view('admin.inventory.variants', compact('variants'));
    }

    public function extras(Request $request)
    {
        $query = ProductExtra::with(['product']);
        
        if ($request->has('filter')) {
            if ($request->filter == 'low_stock') {
                $query->where('stock_cache', '>', 0)
                      ->where('stock_cache', '<=', 10);
            } elseif ($request->filter == 'out_of_stock') {
                $query->where('stock_cache', '<=', 0);
            }
        }

        $extras = $query->orderBy('stock_cache', 'asc')->paginate(20);
        
        return view('admin.inventory.extras', compact('extras'));
    }

    public function adjustments()
    {
        return view('admin.inventory.adjustments');
    }

    public function adjustVariantStock(StockAdjustmentRequest $request, ProductVariant $variant)
    {
        try {
            $this->inventoryService->adjustVariantStock(
                $variant->id,
                $request->quantity,
                $request->note,
                auth()->id()
            );

            return redirect()->back()->with('success', 'Stok varian berhasil disesuaikan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyesuaikan stok: ' . $e->getMessage());
        }
    }

    public function adjustExtraStock(StockAdjustmentRequest $request, ProductExtra $extra)
    {
        try {
            $this->inventoryService->adjustExtraStock(
                $extra->id,
                $request->quantity,
                $request->note,
                auth()->id()
            );

            return redirect()->back()->with('success', 'Stok extra berhasil disesuaikan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyesuaikan stok: ' . $e->getMessage());
        }
    }

    public function movementHistory(Request $request)
    {
        $filters = $request->only(['date_from', 'date_to', 'type']);
        $movementHistory = $this->inventoryService->getStockMovementHistory($filters);
        
        return view('admin.inventory.movement-history', compact('movementHistory', 'filters'));
    }
}