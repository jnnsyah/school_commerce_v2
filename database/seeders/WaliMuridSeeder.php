<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User\User;
use App\Models\User\UserTeacher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class WaliMuridSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        User::truncate();
        UserTeacher::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // ==================== CREATE GURU BIASA ====================
        $guruBiasa = User::create([
            'name' => 'Pak Andi - Guru Matematika',
            'username' => 'gurubiasa',
            'email' => 'andi@sekolah.id',
            'password' => Hash::make('password123'),
            'no_hp' => '081234567896',
            'gender' => 'L',
            'birth_date' => '1984-09-12',
        ]);
        $guruBiasa->assignRole('guru_biasa');

        UserTeacher::create([
            'user_id' => $guruBiasa->id,
            'nip' => '198409122005011001',
            'is_pkwu' => false,
            'is_wali_kelas' => false,
        ]);

        $waliKelas = [];
        
        $waliKelas[1] = User::create([
            'name' => 'Pak Budi - Wali Kelas X IPA 1',
            'username' => 'walikelas1',
            'email' => 'budi@sekolah.id',
            'password' => Hash::make('password123'),
            'no_hp' => '081234567893',
            'gender' => 'L',
            'birth_date' => '1982-03-10',
        ]);
        $waliKelas[1]->assignRole('wali_kelas');

        $waliKelas[2] = User::create([
            'name' => 'Bu Sari - Wali Kelas XI IPS 2',
            'username' => 'walikelas2',
            'email' => 'sari@sekolah.id',
            'password' => Hash::make('password123'),
            'no_hp' => '081234567894',
            'gender' => 'P',
            'birth_date' => '1983-07-25',
        ]);
        $waliKelas[2]->assignRole('wali_kelas');

        $waliKelas[3] = User::create([
            'name' => 'Pak Joko - Wali Kelas XII Bahasa 3',
            'username' => 'walikelas3',
            'email' => 'joko@sekolah.id',
            'password' => Hash::make('password123'),
            'no_hp' => '081234567895',
            'gender' => 'L',
            'birth_date' => '1981-11-30',
        ]);
        $waliKelas[3]->assignRole('wali_kelas');

        // Create teacher records for wali kelas
        foreach ($waliKelas as $index => $wali) {
            UserTeacher::create([
                'user_id' => $wali->id,
                'nip' => '19820310' . (2000 + $index) . '001',
                'is_pkwu' => false,
                'is_wali_kelas' => true,
            ]);
        }

        $this->command->info('Wali Kelas 1: walikelas1 / password123');
        $this->command->info('Wali Kelas 2: walikelas2 / password123');
        $this->command->info('Wali Kelas 3: walikelas3 / password123');
    }
}