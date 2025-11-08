<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User\User;
use App\Models\User\UserStudent;
use App\Models\User\UserTeacher;
use App\Models\Academic\SchoolClass;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        User::truncate();
        UserStudent::truncate();
        UserTeacher::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // ==================== CREATE SUPER ADMIN ====================
        $superAdmin = User::create([
            'name' => 'Super Administrator',
            'username' => 'superadmin',
            'email' => 'superadmin@sekolah.id',
            'password' => Hash::make('password123'),
            'no_hp' => '081234567890',
            'gender' => 'L',
            'birth_date' => '1980-01-01',
        ]);
        $superAdmin->assignRole('super_admin');

        // ==================== CREATE ADMIN ====================
        $admin = User::create([
            'name' => 'Administrator Sekolah',
            'username' => 'admin',
            'email' => 'admin@sekolah.id',
            'password' => Hash::make('password123'),
            'no_hp' => '081234567891',
            'gender' => 'P',
            'birth_date' => '1985-05-15',
        ]);
        $admin->assignRole('admin');

        // ==================== CREATE GURU PKWU ====================
        $guruPkwu = User::create([
            'name' => 'Bu Sri - Guru PKWU',
            'username' => 'gurupkwu',
            'email' => 'pkwu@sekolah.id',
            'password' => Hash::make('password123'),
            'no_hp' => '081234567892',
            'gender' => 'P',
            'birth_date' => '1978-08-20',
        ]);
        $guruPkwu->assignRole('guru_pkwu');

        UserTeacher::create([
            'user_id' => $guruPkwu->id,
            'nip' => '196508201978082001',
            'is_pkwu' => true,
            'is_wali_kelas' => false,
        ]);

        // ==================== CREATE STUDENTS ====================
        $studentsData = [
            // Kelas X IPA 1
            ['name' => 'Ahmad Fauzi', 'username' => 'ahmad123', 'gender' => 'L', 'class_index' => 1],
            ['name' => 'Siti Rahma', 'username' => 'siti123', 'gender' => 'P', 'class_index' => 1],
            ['name' => 'Rizki Pratama', 'username' => 'rizki123', 'gender' => 'L', 'class_index' => 1],
            ['name' => 'Dewi Anggraini', 'username' => 'dewi123', 'gender' => 'P', 'class_index' => 1],
            
            // Kelas XI IPS 2
            ['name' => 'Budi Santoso', 'username' => 'budi123', 'gender' => 'L', 'class_index' => 2],
            ['name' => 'Maya Sari', 'username' => 'maya123', 'gender' => 'P', 'class_index' => 2],
            ['name' => 'Fajar Nugroho', 'username' => 'fajar123', 'gender' => 'L', 'class_index' => 2],
            ['name' => 'Citra Lestari', 'username' => 'citra123', 'gender' => 'P', 'class_index' => 2],
            
            // Kelas XII Bahasa 3
            ['name' => 'Dimas Prayoga', 'username' => 'dimas123', 'gender' => 'L', 'class_index' => 3],
            ['name' => 'Nina Utami', 'username' => 'nina123', 'gender' => 'P', 'class_index' => 3],
            ['name' => 'Hendra Kurniawan', 'username' => 'hendra123', 'gender' => 'L', 'class_index' => 3],
            ['name' => 'Rina Wulandari', 'username' => 'rina123', 'gender' => 'P', 'class_index' => 3],
        ];

        foreach ($studentsData as $index => $studentData) {
            $student = User::create([
                'name' => $studentData['name'],
                'username' => $studentData['username'],
                'email' => $studentData['username'] . '@student.sekolah.id',
                'password' => Hash::make('password123'),
                'no_hp' => '08123456' . (7000 + $index),
                'gender' => $studentData['gender'],
                'birth_date' => '200' . (6 + $studentData['class_index']) . '-' . sprintf('%02d', ($index % 12) + 1) . '-' . sprintf('%02d', ($index % 28) + 1),
  
            ]);
            $student->assignRole('student');

            // Assign to classes
            $classId = $studentData['class_index'];
            $isAdminClass = $index % 4 == 0; // Setiap siswa pertama di kelas jadi admin

            UserStudent::create([
                'user_id' => $student->id,
                'nisn' => '00' . (1000 + $index) . (1000 + $classId),
                'class_id' => $classId,
                'is_admin_class' => $isAdminClass,
            ]);
        }

        $this->command->info('Users seeded successfully!');
        $this->command->info('=== LOGIN CREDENTIALS ===');
        $this->command->info('Super Admin: superadmin / password123');
        $this->command->info('Admin: admin / password123');
        $this->command->info('Guru PKWU: gurupkwu / password123');
        $this->command->info('Wali Kelas 1: walikelas1 / password123');
        $this->command->info('Wali Kelas 2: walikelas2 / password123');
        $this->command->info('Wali Kelas 3: walikelas3 / password123');
        $this->command->info('Guru Biasa: gurubiasa / password123');
        $this->command->info('Students: ahmad123, siti123, etc / password123');
    }
}