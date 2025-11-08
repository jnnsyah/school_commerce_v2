<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Academic\SchoolClass;
use Illuminate\Support\Facades\DB;

class ClassSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        SchoolClass::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $classes = [
            // Kelas X
            [
                'class_id' => 1,
                'grade_id' => 1, // X
                'major_id' => 1, // IPA
                'section_id' => 1, // 1
                'teacher_id' => 1, // Pak Budi (wali_kelas1)
                'is_active' => true,
            ],
            [
                'class_id' => 2,
                'grade_id' => 2, // XI
                'major_id' => 2, // IPS
                'section_id' => 2, // 2
                'teacher_id' => 2, // Bu Sari (wali_kelas2)
                'is_active' => true,
            ],
            [
                'class_id' => 3,
                'grade_id' => 3, // XII
                'major_id' => 3, // Bahasa
                'section_id' => 3, // 3
                'teacher_id' => 3, // Pak Joko (wali_kelas3)
                'is_active' => true,
            ],
            // Additional classes for variety
            [
                'class_id' => 4,
                'grade_id' => 1, // X
                'major_id' => 4, // TKJ
                'section_id' => 4, // 4
                'teacher_id' => 4, // Pak Andi (guru_biasa) - as example
                'is_active' => true,
            ],
        ];

        foreach ($classes as $class) {
            SchoolClass::create($class);
        }

        $this->command->info('Classes seeded successfully!');
        $this->command->info('Created classes: X IPA 1, XI IPS 2, XII Bahasa 3, X TKJ 4');
    }
}