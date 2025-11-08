<?php

namespace Database\Seeders;

use App\Models\Product\Product;
use App\Models\User\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            MasterDataSeeder::class,
            RolePermissionSeeder::class,
            WaliMuridSeeder::class,
            ClassSeeder::class,
            UserSeeder::class,
            ProductSeeder::class,
            OrderSeeder::class,
            CartSeeder::class,
        ]);
    }
}