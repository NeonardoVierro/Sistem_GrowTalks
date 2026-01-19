<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\InternalUser;
use App\Models\Kalender;
use App\Models\PodcastBooking;
use App\Models\CoachingBooking;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        Role::create(['nama_role' => 'user']);
        Role::create(['nama_role' => 'admin']);

        // Create admin user
        InternalUser::create([
            'id_role' => 2, // admin
            'nama_user' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'jabatan' => 'Super Admin',
            'no_hp' => '081234567890',
            'status' => 'aktif',
        ]);

        // Create regular user
        $user = User::create([
            'id_role' => 1,
            'email' => 'user@example.com',
            'nama_opd' => 'Dinas Kesehatan',
            'nama_pic' => 'Budi Santoso',
            'kontak_pic' => '081234567890',
            'password' => Hash::make('password'),
        ]);

        // Create more users for demo
        for ($i = 1; $i <= 18; $i++) {
            User::create([
                'id_role' => 1,
                'email' => "user{$i}@example.com",
                'nama_opd' => "OPD {$i}",
                'nama_pic' => "PIC {$i}",
                'kontak_pic' => '0812' . str_pad($i, 8, '0', STR_PAD_LEFT),
                'password' => Hash::make('password'),
            ]);
        }

        // Create podcast bookings
        PodcastBooking::create([
            'id_user' => $user->id,
            'tanggal' => '2026-01-09',
            'nama_opd' => $user->nama_opd,
            'nama_pic' => $user->nama_pic,
            'keterangan' => 'Selamatkan Karir dan Keluarga Dengan Kenali, Cegah, dan Lawan Stroke',
            'narasumber' => 'Ahmad Basuki',
            'verifikasi' => 'Administrator',
            'status_verifikasi' => 'disetujui',
            'host' => 'Widyoko',
        ]);

        for ($i = 2; $i <= 5; $i++) {
            PodcastBooking::create([
                'id_user' => $i,
                'tanggal' => "2026-01-{$i}",
                'nama_opd' => "OPD {$i}",
                'nama_pic' => "PIC {$i}",
                'keterangan' => "Podcast {$i} tentang kesehatan mental",
                'narasumber' => "Narasumber {$i}",
                'verifikasi' => 'Administrator',
                'status_verifikasi' => $i % 2 == 0 ? 'disetujui' : 'ditolak',
            ]);
        }

        // Create coaching clinic bookings
        for ($i = 1; $i <= 8; $i++) {
            CoachingBooking::create([
                'id_user' => $i,
                'tanggal' => "2026-01-{$i}",
                'layanan' => $i % 3 == 0 ? 'Design Grafis Canva' : ($i % 3 == 1 ? 'TTL Design' : 'Website & Aplikasi'),
                'keterangan' => "Konsultasi tentang {$i}",
                'pic' => "PIC {$i}",
                'no_telp' => '0812' . str_pad($i, 8, '0', STR_PAD_LEFT),
                'verifikasi' => 'Administrator',
                'status_verifikasi' => 'disetujui',
            ]);
        }
    }
}