<?php

namespace App\Services;

use App\Models\Product\Product;
use App\Models\Product\ProductVariant;
use App\Models\Product\ProductExtra;
use App\Models\Inventory\StockProduct;
use App\Models\Inventory\StockProductVariant;
use App\Models\Inventory\StockProductExtra;
use App\Models\Inventory\StockReferenceType;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    public function adjustProductStock($productId, $quantity, $note, $userId)
    {
        $product = Product::findOrFail($productId);

        // Record stock movement
        StockProduct::create([
            'product_id' => $product->product_id,
            'ref_type_id' => StockReferenceType::ADJUSTMENT,
            'ref_id' => 0,
            'qty' => $quantity,
            'note' => $note,
            'user_id' => $userId,
        ]);

        // Update all variants stock
        $product->variants()->each(function($variant) use ($quantity) {
            $variant->update([
                'stock_at' => max(0, $variant->stock_at + $quantity)
            ]);
        });

        return $product;
    }

    public function adjustVariantStock($variantId, $quantity, $note, $userId)
    {
        $variant = ProductVariant::findOrFail($variantId);

        // Record stock movement
        StockProductVariant::create([
            'variant_id' => $variant->id,
            'ref_type_id' => StockReferenceType::ADJUSTMENT,
            'ref_id' => 0,
            'qty' => $quantity,
            'note' => $note,
            'user_id' => $userId,
        ]);

        // Update variant stock
        $variant->update([
            'stock_at' => max(0, $variant->stock_at + $quantity)
        ]);

        return $variant;
    }

    public function adjustExtraStock($extraId, $quantity, $note, $userId)
    {
        $extra = ProductExtra::findOrFail($extraId);

        // Record stock movement
        StockProductExtra::create([
            'extra_id' => $extra->id,
            'ref_type_id' => StockReferenceType::ADJUSTMENT,
            'ref_id' => 0,
            'qty' => $quantity,
            'note' => $note,
            'user_id' => $userId,
        ]);

        // Update extra stock
        $extra->update([
            'stock_cache' => max(0, $extra->stock_cache + $quantity)
        ]);

        return $extra;
    }

    public function getStockMovementHistory($filters = [])
    {
        $productQuery = StockProduct::with(['product', 'user', 'referenceType']);
        $variantQuery = StockProductVariant::with(['variant.product', 'user', 'referenceType']);
        $extraQuery = StockProductExtra::with(['extra.product', 'user', 'referenceType']);

        if (isset($filters['date_from'])) {
            $productQuery->where('created_at', '>=', $filters['date_from']);
            $variantQuery->where('created_at', '>=', $filters['date_from']);
            $extraQuery->where('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $productQuery->where('created_at', '<=', $filters['date_to']);
            $variantQuery->where('created_at', '<=', $filters['date_to']);
            $extraQuery->where('created_at', '<=', $filters['date_to']);
        }

        return [
            'products' => $productQuery->latest()->get(),
            'variants' => $variantQuery->latest()->get(),
            'extras' => $extraQuery->latest()->get(),
        ];
    }

    public function getLowStockAlerts($threshold = 10)
    {
        $lowStockVariants = ProductVariant::with(['product'])
            ->where('stock_at', '>', 0)
            ->where('stock_at', '<=', $threshold)
            ->get();

        $outOfStockVariants = ProductVariant::with(['product'])
            ->where('stock_at', '<=', 0)
            ->get();

        $lowStockExtras = ProductExtra::with(['product'])
            ->where('stock_cache', '>', 0)
            ->where('stock_cache', '<=', $threshold)
            ->get();

        return [
            'low_stock_variants' => $lowStockVariants,
            'out_of_stock_variants' => $outOfStockVariants,
            'low_stock_extras' => $lowStockExtras,
        ];
    }

    public function getInventorySummary()
    {
        $totalProducts = Product::where('status_id', 2)->count();
        $totalVariants = ProductVariant::count();
        $totalStockValue = ProductVariant::sum(DB::raw('price * stock_at'));
        
        $lowStockCount = ProductVariant::where('stock_at', '>', 0)
            ->where('stock_at', '<=', 10)
            ->count();

        $outOfStockCount = ProductVariant::where('stock_at', '<=', 0)->count();

        return [
            'total_products' => $totalProducts,
            'total_variants' => $totalVariants,
            'total_stock_value' => $totalStockValue,
            'low_stock_count' => $lowStockCount,
            'out_of_stock_count' => $outOfStockCount,
        ];
    }
}