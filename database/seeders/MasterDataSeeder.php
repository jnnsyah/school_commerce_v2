<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Class Grades
        DB::table('class_grades')->insert([
            ['name' => 'X', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'XI', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'XII', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Class Majors
        DB::table('class_majors')->insert([
            ['name' => 'Ilmu Pengetahuan Alam', 'short_name' => 'IPA', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Ilmu Pengetahuan Sosial', 'short_name' => 'IPS', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Bahasa dan Budaya', 'short_name' => 'Bahasa', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Teknik Komputer dan Jaringan', 'short_name' => 'TKJ', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Class Sections
        DB::table('class_sections')->insert([
            ['name' => '1', 'created_at' => $now, 'updated_at' => $now],
            ['name' => '2', 'created_at' => $now, 'updated_at' => $now],
            ['name' => '3', 'created_at' => $now, 'updated_at' => $now],
            ['name' => '4', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Product Categories
        DB::table('product_categories')->insert([
            ['name' => 'Makanan & Minuman', 'description' => 'Produk makanan dan minuman hasil kewirausahaan', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Kerajinan Tangan', 'description' => 'Produk kerajinan tangan siswa', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Pertanian', 'description' => 'Hasil pertanian dan perkebunan', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Teknologi', 'description' => 'Produk teknologi dan digital', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Fashion', 'description' => 'Produk fashion dan aksesoris', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Product Statuses
        DB::table('product_statuses')->insert([
            ['name' => 'pending', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'approved', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'rejected', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Order Statuses
        DB::table('order_statuses')->insert([
            ['name' => 'pending', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'paid', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'processing', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'completed', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'cancelled', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Stock Reference Types
        DB::table('stock_references_types')->insert([
            ['type_id' => 1, 'code' => 'order', 'description' => 'Stock reduction from order', 'created_at' => $now, 'updated_at' => $now],
            ['type_id' => 2, 'code' => 'adjustment', 'description' => 'Manual stock adjustment', 'created_at' => $now, 'updated_at' => $now],
            ['type_id' => 3, 'code' => 'return', 'description' => 'Stock return from cancelled order', 'created_at' => $now, 'updated_at' => $now],
            ['type_id' => 4, 'code' => 'initial', 'description' => 'Initial stock setup', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Invoice Statuses
        DB::table('invoice_statuses')->insert([
            ['name' => 'pending', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'paid', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'overdue', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'cancelled', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Payment Methods
        DB::table('payment_methods')->insert([
            ['name' => 'QRIS', 'provider' => 'Midtrans', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Cash', 'provider' => 'Manual', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Transfer Bank', 'provider' => 'Manual', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Payment Statuses
        DB::table('payment_statuses')->insert([
            ['name' => 'pending', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'paid', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'failed', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'expired', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $this->command->info('Master data seeded successfully!');
    }
}