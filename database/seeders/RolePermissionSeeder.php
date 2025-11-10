<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Users\User;

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

            'access.merchant',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }

        // ==================== CREATE ROLES ====================
        
        // SUPER ADMIN - Full access
        $superAdmin = Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
        $superAdmin->givePermissionTo(Permission::all());

        // ADMIN - Almost full access
        $admin = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $admin->givePermissionTo([
            'user.view', 'user.create', 'user.edit', 'user.delete', 'user.roles',
            'product.view', 'product.create', 'product.edit', 'product.delete', 'product.approve', 'product.manage',
            'order.view', 'order.create', 'order.edit', 'order.delete', 'order.confirm_payment', 'order.manage',
            'class.view', 'class.create', 'class.edit', 'class.delete', 'class.manage',
            'report.sales', 'report.products', 'report.students', 'report.financial', 'access.merchant',
        ]);

        // GURU PKWU - Supervisor role
        $guruPkwu = Role::create(['name' => 'guru_pkwu', 'guard_name' => 'web']);
        $guruPkwu->givePermissionTo([
            'user.view',
            'product.view', 'product.approve', 'product.manage',
            'order.view', 'order.manage', 'order.confirm_payment',
            'class.view', 'class.manage',
            'report.sales', 'report.products', 'report.students', 'access.merchant',
        ]);

        // WALI KELAS - Class manager
        $waliKelas = Role::create(['name' => 'wali_kelas', 'guard_name' => 'web']);
        $waliKelas->givePermissionTo([
            'user.view',
            'product.view', 'product.create', 'product.edit', 'product.delete',
            'order.view', 'order.create', 'order.edit', 'order.confirm_payment',
            'class.view', 'class.edit',
            'report.sales', 'report.products', 'access.merchant',
        ]);

        // GURU BIASA - Basic access
        $guruBiasa = Role::create(['name' => 'guru_biasa', 'guard_name' => 'web']);
        $guruBiasa->givePermissionTo([
            'user.view',
            'product.view',
            'order.view', 'order.create',
        ]);

        // STUDENT - Basic user
        $student = Role::create(['name' => 'student', 'guard_name' => 'web']);
        $student->givePermissionTo([
            'product.view',
            'order.view', 'order.create',
        ]);

        $this->command->info('Roles and permissions seeded successfully!');
        $this->command->info('Roles created: super_admin, admin, guru_pkwu, wali_kelas, guru_biasa, student');
    }
}