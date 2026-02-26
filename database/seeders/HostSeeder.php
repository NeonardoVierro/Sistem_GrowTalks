<?php

namespace Database\Seeders;

use App\Models\Staff;
use Illuminate\Database\Seeder;

class HostSeeder extends Seeder
{
    public function run(): void
    {
        $hosts = [
            [
                'nama' => 'Bambang Suryanto',
                'role' => 'host',
                'no_hp' => '081234567890',
                'bidang' => 'Teknologi dan Informatika',
            ],
            [
                'nama' => 'Siti Nurhaliza',
                'role' => 'host',
                'no_hp' => '082345678901',
                'bidang' => 'Teknologi dan Informatika',
            ],
            [
                'nama' => 'Ahmad Wijaya',
                'role' => 'host',
                'no_hp' => '083456789012',
                'bidang' => 'Teknologi dan Informatika',
            ],
        ];

        foreach ($hosts as $host) {
            Staff::firstOrCreate(
                ['nama' => $host['nama'], 'role' => 'host'],
                $host
            );
        }
    }
}
