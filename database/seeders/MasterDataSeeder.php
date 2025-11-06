<?php
// database/seeders/MasterDataSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // Class Grades
        DB::table('class_grades')->insert([
            ['name' => 'X'],
            ['name' => 'XI'],
            ['name' => 'XII'],
        ]);

        // Class Majors
        DB::table('class_majors')->insert([
            ['name' => 'Ilmu Pengetahuan Alam', 'short_name' => 'IPA'],
            ['name' => 'Ilmu Pengetahuan Sosial', 'short_name' => 'IPS'],
            ['name' => 'Bahasa dan Budaya', 'short_name' => 'Bahasa'],
            ['name' => 'Teknik Komputer dan Jaringan', 'short_name' => 'TKJ'],
        ]);

        // Class Sections
        DB::table('class_sections')->insert([
            ['name' => 'A'],
            ['name' => 'B'],
            ['name' => 'C'],
            ['name' => 'D'],
        ]);

        // Product Statuses
        DB::table('product_statuses')->insert([
            ['name' => 'pending'],
            ['name' => 'approved'],
            ['name' => 'rejected'],
        ]);

        // Order Statuses
        DB::table('order_statuses')->insert([
            ['name' => 'pending'],
            ['name' => 'paid'],
            ['name' => 'processing'],
            ['name' => 'completed'],
            ['name' => 'cancelled'],
        ]);

        // Stock Reference Types
        DB::table('stock_references_types')->insert([
            ['type_id' => 1, 'code' => 'order', 'description' => 'Stock reduction from order'],
            ['type_id' => 2, 'code' => 'adjustment', 'description' => 'Manual stock adjustment'],
            ['type_id' => 3, 'code' => 'return', 'description' => 'Stock return from cancelled order'],
            ['type_id' => 4, 'code' => 'initial', 'description' => 'Initial stock setup'],
        ]);

        // Invoice Statuses
        DB::table('invoice_statuses')->insert([
            ['name' => 'pending'],
            ['name' => 'paid'],
            ['name' => 'overdue'],
            ['name' => 'cancelled'],
        ]);

        // Payment Methods
        DB::table('payment_methods')->insert([
            ['name' => 'QRIS', 'provider' => 'Midtrans', 'is_active' => true],
            ['name' => 'Cash', 'provider' => 'Manual', 'is_active' => true],
            ['name' => 'Transfer Bank', 'provider' => 'Manual', 'is_active' => true],
        ]);

        // Payment Statuses
        DB::table('payment_statuses')->insert([
            ['name' => 'pending'],
            ['name' => 'paid'],
            ['name' => 'failed'],
            ['name' => 'expired'],
        ]);
    }
}