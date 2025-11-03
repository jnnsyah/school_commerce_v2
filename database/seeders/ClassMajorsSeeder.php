<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Classes\ClassMajor as ModelsClassMajor;
use Illuminate\Support\Facades\DB;

class ClassMajorsSeeder extends Seeder
{
    public function run(): void
    {
        $majors = [
            ['name' => 'Ilmu Pengetahuan Alam', 'short_name' => 'IPA'],
            ['name' => 'Ilmu Pengetahuan Sosial', 'short_name' => 'IPS'],
            ['name' => 'Bahasa dan Budaya', 'short_name' => 'Bahasa'],
            ['name' => 'Teknik Komputer dan Jaringan', 'short_name' => 'TKJ'],
            ['name' => 'Multimedia', 'short_name' => 'MM'],
        ];

        foreach ($majors as $major) {
            ModelsClassMajor::create($major);
        }

        $this->command->info('Class majors seeded successfully!');
    }
}