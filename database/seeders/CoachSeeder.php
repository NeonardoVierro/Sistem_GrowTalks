<?php

namespace Database\Seeders;

use App\Models\Staff;
use Illuminate\Database\Seeder;

class CoachSeeder extends Seeder
{
    public function run(): void
    {
        $coaches = [
            [
                'nama' => 'Dr. Hendra Permana',
                'role' => 'coach',
                'no_hp' => '081111111111',
                'bidang' => 'Teknologi dan Informatika',
            ],
            [
                'nama' => 'Linda Santosa, M.Psi',
                'role' => 'coach',
                'no_hp' => '082222222222',
                'bidang' => 'Teknologi dan Informatika',
            ],
            [
                'nama' => 'Agus Hermawan',
                'role' => 'coach',
                'no_hp' => '083333333333',
                'bidang' => 'Teknologi dan Informatika',
            ],
        ];

        foreach ($coaches as $coach) {
            Staff::firstOrCreate(
                ['nama' => $coach['nama'], 'role' => 'coach'],
                $coach
            );
        }
    }
}
