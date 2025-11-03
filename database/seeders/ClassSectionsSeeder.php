<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Classes\ClassSection;
use Illuminate\Support\Facades\DB;

class ClassSectionsSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            ['name' => '1'],
            ['name' => '2'],
            ['name' => '3'],
            ['name' => '4'],
            ['name' => '5'],
            ['name' => '6'],
        ];

        foreach ($sections as $section) {
            ClassSection::create($section);
        }

        $this->command->info('Class sections seeded successfully!');
    }
}