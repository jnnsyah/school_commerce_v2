<?php

namespace Database\Seeders;

use App\Models\Classes\ClassGrade;
use App\Models\Classes\ClassMajor;
use Illuminate\Database\Seeder;
use App\Models\Users\User;
use App\Models\Users\UserTeacher;
use App\Models\Users\UserStudent;
use App\Models\Classes\ClassModel;
use App\Models\Classes\ClassSection;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SampleUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Get roles
        $teacherRole = Role::where('name', 'guru_pkwu')->where('guard_name', 'web')->first();
        $studentRole = Role::where('name', 'student')->where('guard_name', 'web')->first();

        if (!$teacherRole) {
            $teacherRole = Role::create(['name' => 'guru_pkwu', 'guard_name' => 'web']);
        }
        
        if (!$studentRole) {
            $studentRole = Role::create(['name' => 'student', 'guard_name' => 'web']);
        }

        // Create sample teachers
        $teachers = [
            [
                'username' => 'guru_pkwu',
                'name' => 'Bu Sri Wahyuni',
                'email' => 'guru_pkwu@sekolah.dev',
                'password' => Hash::make('password123'),
                'no_hp' => '081234567801',
                'gender' => 'P',
                'birth_date' => '1980-05-15',
            ],
            [
                'username' => 'wali_kelas_x_ipa1',
                'name' => 'Pak Budi Santoso',
                'email' => 'budi.santoso@sekolah.dev',
                'password' => Hash::make('password123'),
                'no_hp' => '081234567802',
                'gender' => 'L',
                'birth_date' => '1982-08-20',
            ],
        ];

        foreach ($teachers as $teacherData) {
            $user = User::create($teacherData);
            
            $user->assignRole($teacherRole->name);

            // Create teacher profile
            UserTeacher::create([
                'user_id' => $user->id,
                'nip' => (int) ('1' . $user->id . rand(100, 999)),
                'is_pkwu' => $teacherData['username'] === 'guru_pkwu',
                'is_wali_kelas' => $teacherData['username'] === 'wali_kelas_x_ipa1',
            ]);
        }

        // Handle classes - create if empty
        $classes = ClassModel::with(['grade', 'major', 'section'])->get();
        
        if ($classes->isEmpty()) {
            $this->command->warn('No classes found. Creating sample classes...');
            $classes = $this->createSampleClasses();
        }

        // Create sample students
        for ($i = 1; $i <= 20; $i++) {
            $class = $classes->random();
            
            $student = User::create([
                'username' => 'student_' . $i,
                'name' => 'Siswa ' . $i,
                'email' => 'student' . $i . '@sekolah.dev',
                'password' => Hash::make('password123'),
                'no_hp' => '08123456' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'gender' => $i % 2 == 0 ? 'L' : 'P',
                'birth_date' => '200' . rand(5, 7) . '-' . rand(1, 12) . '-' . rand(1, 28),
            ]);

            $student->assignRole($studentRole->name);

            // Create student profile
            UserStudent::create([
                'user_id' => $student->id,
                'nisn' => (int) ('2' . $student->id . str_pad($i, 3, '0', STR_PAD_LEFT)),
                'class_id' => $class->id,
                'is_admin_class' => $i == 1,
            ]);
        }

        // Assign wali kelas to a class
        $waliKelas = User::where('username', 'wali_kelas_x_ipa1')->first();
        $classXIPA1 = ClassModel::whereHas('grade', function($q) {
            $q->where('name', 'X');
        })->whereHas('major', function($q) {
            $q->where('short_name', 'IPA');
        })->whereHas('section', function($q) {
            $q->where('name', '1');
        })->first();

        if ($waliKelas && $classXIPA1) {
            $classXIPA1->update(['teacher_id' => $waliKelas->id]);
        }

        $this->command->info('Sample teachers and students seeded successfully!');
    }

    /**
     * Create sample classes if none exist
     */
    private function createSampleClasses()
    {
        // Pastikan ClassGrade, ClassMajor, section ada
        $gradeX = ClassGrade::firstOrCreate(['name' => 'X'], ['level' => 10]);
        $gradeXI = ClassGrade::firstOrCreate(['name' => 'XI'], ['level' => 11]);
        $gradeXII = ClassGrade::firstOrCreate(['name' => 'XII'], ['level' => 12]);

        $majorIPA = ClassMajor::firstOrCreate(
            ['short_name' => 'IPA'], 
            ['name' => 'Ilmu Pengetahuan Alam']
        );
        $majorIPS = ClassMajor::firstOrCreate(
            ['short_name' => 'IPS'], 
            ['name' => 'Ilmu Pengetahuan Sosial']
        );

        // Buat sections
        $sections = [];
        for ($i = 1; $i <= 3; $i++) {
            $sections[] = ClassSection::firstOrCreate(['name' => (string)$i]);
        }

        $classes = [];

        // Buat kombinasi kelas
        foreach ([$gradeX, $gradeXI, $gradeXII] as $ClassGrade) {
            foreach ([$majorIPA, $majorIPS] as $ClassMajor) {
                foreach ($sections as $section) {
                    $classes[] = ClassModel::create([
                        'grade_id' => $ClassGrade->id,
                        'major_id' => $ClassMajor->id,
                        'section_id' => $section->id,
                        'teacher_id' => null,
                    ]);
                }
            }
        }

        return collect($classes);
    }
}