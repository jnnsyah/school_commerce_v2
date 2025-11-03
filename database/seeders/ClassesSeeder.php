<?php

namespace Database\Seeders;

use App\Models\Classes\ClassGrade;
use App\Models\Classes\ClassMajor;
use Illuminate\Database\Seeder;
use App\Models\Classes\ClassModel;
use App\Models\Classes\ClassSection;

class ClassesSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan data grade, major, dan section sudah ada
        $grades = ClassGrade::all();
        $majors = ClassMajor::all();
        $sections = ClassSection::all();

        if ($grades->isEmpty() || $majors->isEmpty() || $sections->isEmpty()) {
            $this->command->error('Please seed grades, majors, and sections first!');
            return;
        }

        $classes = [];

        // Buat kombinasi kelas
        foreach ($grades as $grade) {
            foreach ($majors as $major) {
                foreach ($sections as $section) {
                    $classes[] = [
                        'grade_id' => $grade->id,
                        'major_id' => $major->id,
                        'section_id' => $section->id,
                        'teacher_id' => null, // Akan diisi nanti
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        // Insert dengan chunk untuk menghindari error too many placeholders
        foreach (array_chunk($classes, 50) as $chunk) {
            ClassModel::insert($chunk);
        }

        $this->command->info('Classes seeded successfully!');
    }
}