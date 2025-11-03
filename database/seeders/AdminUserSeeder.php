<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Users\User;
use App\Models\Users\UserTeacher;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Cek jika user admin sudah ada
        $existingAdmin = User::where('email', 'admin@sekolah.dev')->first();
        
        if ($existingAdmin) {
            $this->command->info('Admin user already exists!');
            return;
        }

        // Get atau create admin role
        $adminRole = Role::where('name', 'admin')->first();
        
        if (!$adminRole) {
            $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
            $this->command->info('Admin role created!');
        }

        // Create admin user
        $admin = User::create([
            'username' => 'admin',
            'name' => 'Administrator',
            'email' => 'admin@sekolah.dev',
            'password' => Hash::make('password123'),
            'no_hp' => '081234567890',
            'gender' => 'L',
            'birth_date' => '1985-01-01',
        ]);

        // Assign admin role
        $admin->assignRole($adminRole);

        // Create teacher profile untuk admin (optional)
        UserTeacher::create([
            'user_id' => $admin->id,
            'nip' => 'ADM' . $admin->id . '001',
            'is_pkwu' => true,
            'is_wali_kelas' => false,
        ]);

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@sekolah.dev');
        $this->command->info('Password: password123');
    }
}