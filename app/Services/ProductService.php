<?php

namespace App\Services;

use App\Models\Product\Product;
use App\Models\Product\ProductVariant;
use App\Models\Product\ProductImage;
use App\Models\Product\ProductExtra;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function createProduct($data, $images = [])
    {
        $product = Product::create($data);

        // Handle image upload
        if (!empty($images)) {
            $this->uploadProductImages($product, $images);
        }

        return $product;
    }

    public function updateProduct(Product $product, $data, $images = [])
    {
        $product->update($data);

        // Handle new image upload
        if (!empty($images)) {
            $this->uploadProductImages($product, $images);
        }

        return $product;
    }

    public function deleteProduct(Product $product)
    {
        // Delete associated images from storage
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->file_path);
            $image->delete();
        }

        // Delete variants and extras
        $product->variants()->delete();
        $product->extras()->delete();

        $product->delete();
    }

    private function uploadProductImages(Product $product, $images)
    {
        foreach ($images as $index => $image) {
            $path = $image->store('products', 'public');
            
            $product->images()->create([
                'file_path' => $path,
                'is_primary' => $index === 0,
            ]);
        }
    }

    public function createVariant(Product $product, $variantData, $options = [])
    {
        $variant = $product->variants()->create($variantData);

        // Create variant values
        foreach ($options as $option) {
            $variant->variantValues()->create([
                'option_name' => $option['name'],
                'option_value' => $option['value'],
            ]);
        }

        return $variant;
    }

    public function updateVariantStock(ProductVariant $variant, $quantity)
    {
        $variant->update(['stock_at' => $quantity]);
        return $variant;
    }

    public function getProductStats(Product $product)
    {
        return [
            'total_sold' => $product->getTotalSold(),
            'current_stock' => $product->getStockQuantity(),
            'total_revenue' => $product->orderItems()->sum('subtotal'),
            'approval_status' => $product->status->name,
        ];
    }

    public function getProductsByClass($classId, $filters = [])
    {
        $query = Product::with(['category', 'status', 'images'])
            ->where('class_id', $classId);

        if (isset($filters['status'])) {
            $query->where('status_id', $filters['status']);
        }

        if (isset($filters['category'])) {
            $query->where('category_id', $filters['category']);
        }

        return $query->latest()->get();
    }
}