<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Classes\ClassGrade;
use Illuminate\Support\Facades\DB;

class ClassGradesSeeder extends Seeder
{
    public function run(): void
    {
        $grades = [
            ['name' => 'X'],
            ['name' => 'XI'], 
            ['name' => 'XII'],
        ];

        foreach ($grades as $grade) {
            ClassGrade::create($grade);
        }

        $this->command->info('Class grades seeded successfully!');
    }
}