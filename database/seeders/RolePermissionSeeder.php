<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ==================== CREATE PERMISSIONS ====================
        $permissions = [
            // User Management
            'user.view',
            'user.create',
            'user.edit',
            'user.delete',
            'user.roles',

            // Product Management
            'product.view',
            'product.create',
            'product.edit',
            'product.delete',
            'product.approve',
            'product.manage',

            // Order Management
            'order.view',
            'order.create',
            'order.edit',
            'order.delete',
            'order.confirm_payment',
            'order.manage',

            // Class Management
            'class.view',
            'class.create',
            'class.edit',
            'class.delete',
            'class.manage',

            // Report Management
            'report.sales',
            'report.products',
            'report.students',
            'report.financial',

            // System Management
            'system.settings',
            'system.backup',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // ==================== CREATE ROLES ====================
        
        // SUPER ADMIN - Full access
        $superAdmin = Role::create(['name' => 'super_admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // ADMIN - Almost full access
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo([
            'user.view', 'user.create', 'user.edit', 'user.delete', 'user.roles',
            'product.view', 'product.create', 'product.edit', 'product.delete', 'product.approve', 'product.manage',
            'order.view', 'order.create', 'order.edit', 'order.delete', 'order.confirm_payment', 'order.manage',
            'class.view', 'class.create', 'class.edit', 'class.delete', 'class.manage',
            'report.sales', 'report.products', 'report.students', 'report.financial',
        ]);

        // GURU PKWU - Supervisor role
        $guruPkwu = Role::create(['name' => 'guru_pkwu']);
        $guruPkwu->givePermissionTo([
            'user.view',
            'product.view', 'product.approve', 'product.manage',
            'order.view', 'order.manage', 'order.confirm_payment',
            'class.view', 'class.manage',
            'report.sales', 'report.products', 'report.students',
        ]);

        // WALI KELAS - Class manager
        $waliKelas = Role::create(['name' => 'wali_kelas']);
        $waliKelas->givePermissionTo([
            'user.view',
            'product.view', 'product.create', 'product.edit', 'product.delete',
            'order.view', 'order.create', 'order.edit', 'order.confirm_payment',
            'class.view', 'class.edit',
            'report.sales', 'report.products',
        ]);

        // GURU BIASA - Basic access
        $guruBiasa = Role::create(['name' => 'guru_biasa']);
        $guruBiasa->givePermissionTo([
            'user.view',
            'product.view',
            'order.view', 'order.create',
        ]);

        // STUDENT - Basic user
        $student = Role::create(['name' => 'student']);
        $student->givePermissionTo([
            'product.view',
            'order.view', 'order.create',
        ]);

        // ==================== ASSIGN ROLES TO USERS ====================
        
        // Assign super_admin to first user (you)
        $firstUser = User::first();
        if ($firstUser) {
            $firstUser->assignRole('super_admin');
        }

        // Create demo users for each role (optional)
        $this->createDemoUsers();
    }

    private function createDemoUsers(): void
    {
        // Demo Admin
        $admin = User::create([
            'username' => 'admin_demo',
            'name' => 'Admin Demo',
            'email' => 'admin@demo.com',
            'password' => bcrypt('password123'),
            'no_hp' => '081234567890',
            'gender' => 'L',
            'birth_date' => '1980-01-01',
        ]);
        $admin->assignRole('admin');

        // Demo Guru PKWU
        $guruPkwu = User::create([
            'username' => 'gurupkwu_demo',
            'name' => 'Guru PKWU Demo',
            'email' => 'gurupkwu@demo.com',
            'password' => bcrypt('password123'),
            'no_hp' => '081234567891',
            'gender' => 'P',
            'birth_date' => '1985-05-15',
        ]);
        $guruPkwu->assignRole('guru_pkwu');

        // Demo Student
        $student = User::create([
            'username' => 'student_demo',
            'name' => 'Student Demo',
            'email' => 'student@demo.com',
            'password' => bcrypt('password123'),
            'no_hp' => '081234567892',
            'gender' => 'L',
            'birth_date' => '2005-08-20',
        ]);
        $student->assignRole('student');
    }
}