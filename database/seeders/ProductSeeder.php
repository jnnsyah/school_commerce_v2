<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product\Product;
use App\Models\Product\ProductVariant;
use App\Models\Product\ProductImage;
use App\Models\Product\ProductExtra;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Product::truncate();
        ProductVariant::truncate();
        ProductImage::truncate();
        ProductExtra::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $products = [
            // Produk dari Kelas X IPA 1 (Pending & Approved)
            [
                'class_id' => 1,
                'name' => 'Es Jeruk Segar',
                'description' => 'Es jeruk segar dari jeruk lokal, cocok untuk cuaca panas',
                'price' => 8000,
                'category_id' => 1, // Makanan & Minuman
                'status_id' => 2, // Approved
                'variants' => [
                    ['name' => 'Regular', 'price' => 8000, 'stock_at' => 50],
                    ['name' => 'Jumbo', 'price' => 12000, 'stock_at' => 30],
                ],
                'extras' => [
                    ['name' => 'Extra Jeruk', 'price' => 2000, 'max_qty' => 2, 'stock_cache' => 20],
                    ['name' => 'Tambahan Gula', 'price' => 1000, 'max_qty' => 1, 'stock_cache' => 50],
                ]
            ],
            [
                'class_id' => 1,
                'name' => 'Keripik Pisang',
                'description' => 'Keripik pisang renyah dengan berbagai rasa',
                'price' => 15000,
                'category_id' => 1, // Makanan & Minuman
                'status_id' => 1, // Pending
                'variants' => [
                    ['name' => 'Original', 'price' => 15000, 'stock_at' => 25],
                    ['name' => 'Rasa Keju', 'price' => 17000, 'stock_at' => 20],
                    ['name' => 'Rasa BBQ', 'price' => 17000, 'stock_at' => 15],
                ]
            ],

            // Produk dari Kelas XI IPS 2 (Approved)
            [
                'class_id' => 2,
                'name' => 'Gelang Tali',
                'description' => 'Gelang tali handmade dengan berbagai warna',
                'price' => 12000,
                'category_id' => 2, // Kerajinan Tangan
                'status_id' => 2, // Approved
                'variants' => [
                    ['name' => 'Merah', 'price' => 12000, 'stock_at' => 40],
                    ['name' => 'Biru', 'price' => 12000, 'stock_at' => 35],
                    ['name' => 'Hijau', 'price' => 12000, 'stock_at' => 30],
                ]
            ],
            [
                'class_id' => 2,
                'name' => 'Sabun Herbal',
                'description' => 'Sabun herbal alami untuk perawatan kulit',
                'price' => 25000,
                'category_id' => 2, // Kerajinan Tangan
                'status_id' => 2, // Approved
                'variants' => [
                    ['name' => 'Lavender', 'price' => 25000, 'stock_at' => 20],
                    ['name' => 'Tea Tree', 'price' => 28000, 'stock_at' => 15],
                ],
                'extras' => [
                    ['name' => 'Kotak Hadiah', 'price' => 5000, 'max_qty' => 1, 'stock_cache' => 10],
                ]
            ],

            // Produk dari Kelas XII Bahasa 3 (Approved & Rejected)
            [
                'class_id' => 3,
                'name' => 'Buku Catatan Custom',
                'description' => 'Buku catatan dengan cover custom design',
                'price' => 20000,
                'category_id' => 2, // Kerajinan Tangan
                'status_id' => 2, // Approved
                'variants' => [
                    ['name' => 'A5 - Polos', 'price' => 20000, 'stock_at' => 30],
                    ['name' => 'A5 - Design', 'price' => 25000, 'stock_at' => 25],
                    ['name' => 'A6 - Polos', 'price' => 15000, 'stock_at' => 40],
                ]
            ],
            [
                'class_id' => 3,
                'name' => 'Sticker Pack',
                'description' => 'Pack sticker dengan berbagai design lucu',
                'price' => 8000,
                'category_id' => 2, // Kerajinan Tangan
                'status_id' => 3, // Rejected
                'rejection_reason' => 'Design sticker tidak sesuai dengan nilai-nilai sekolah',
                'variants' => [
                    ['name' => 'Pack 10', 'price' => 8000, 'stock_at' => 0],
                ]
            ],

            // Produk dari Kelas X TKJ 4 (Approved)
            [
                'class_id' => 4,
                'name' => 'USB Flash Drive Custom',
                'description' => 'USB flash drive dengan label custom kelas',
                'price' => 75000,
                'category_id' => 4, // Teknologi
                'status_id' => 2, // Approved
                'variants' => [
                    ['name' => '16GB', 'price' => 75000, 'stock_at' => 15],
                    ['name' => '32GB', 'price' => 95000, 'stock_at' => 10],
                ]
            ],
        ];

        foreach ($products as $productData) {
            $sku = 'PROD-' . Str::upper(Str::random(8));
            
            $product = Product::create([
                'class_id' => $productData['class_id'],
                'name' => $productData['name'],
                'sku' => $sku,
                'description' => $productData['description'],
                'price' => $productData['price'],
                'category_id' => $productData['category_id'],
                'status_id' => $productData['status_id'],
                'rejection_reason' => $productData['rejection_reason'] ?? null,
                'approved_by' => $productData['status_id'] == 2 ? 3 : null, // Guru PKWU
                'approved_at' => $productData['status_id'] == 2 ? now() : null,
            ]);

            // Create variants
            if (isset($productData['variants'])) {
                foreach ($productData['variants'] as $variantData) {
                    $variant = ProductVariant::create([
                        'product_id' => $product->product_id,
                        'sku' => $sku . '-' . Str::upper(Str::random(3)),
                        'name' => $variantData['name'],
                        'price' => $variantData['price'],
                        'status_id' => $productData['status_id'],
                        'stock_at' => $variantData['stock_at'],
                    ]);
                }
            }

            // Create extras
            if (isset($productData['extras'])) {
                foreach ($productData['extras'] as $extraData) {
                    ProductExtra::create([
                        'product_id' => $product->product_id,
                        'name' => $extraData['name'],
                        'price' => $extraData['price'],
                        'max_qty' => $extraData['max_qty'],
                        'stock_cache' => $extraData['stock_cache'],
                        'is_active' => true,
                    ]);
                }
            }

            // Create placeholder image record
            ProductImage::create([
                'product_id' => $product->product_id,
                'file_path' => 'products/placeholder.jpg',
                'is_primary' => true,
            ]);
        }

        $this->command->info('Products seeded successfully!');
        $this->command->info('Created: 7 products with variants and extras');
        $this->command->info('Status: 5 Approved, 1 Pending, 1 Rejected');
    }
}