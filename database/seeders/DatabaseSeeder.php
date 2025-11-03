<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ClassGradesSeeder::class,
            ClassMajorsSeeder::class, 
            ClassSectionsSeeder::class,
            ClassesSeeder::class,
            SampleUsersSeeder::class,
        ]);
    }
}