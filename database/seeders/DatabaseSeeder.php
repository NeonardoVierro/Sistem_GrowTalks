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
        Role::create(['nama_role' => 'User', 'kode_role' => 'user']);
        Role::create(['nama_role' => 'Admin', 'kode_role' => 'admin']);
        Role::create(['nama_role' => 'Verifikator Podcast', 'kode_role' => 'verifikator_podcast']);
        Role::create(['nama_role' => 'Verifikator Coaching', 'kode_role' => 'verifikator_coaching']);

        // Akun admin user
        InternalUser::create([
            'id_role' => 2, 
            'nama_user' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'jabatan' => 'Super Admin',
            'no_hp' => '081234567890',
            'status' => 'aktif',
        ]);

        // Akun verifikator podcast
        InternalUser::create([
            'id_role' => 3, 
            'nama_user' => 'Verifikator Podcast',
            'email' => 'verifikator.podcast@example.com',
            'password' => Hash::make('verifikator123'),
            'jabatan' => 'Verifikator Podcast',
            'no_hp' => '081234567891',
            'status' => 'aktif',
        ]);

        // Akun verifikator coaching
        InternalUser::create([
            'id_role' => 4,
            'nama_user' => 'Verifikator Coaching',
            'email' => 'verifikator.coaching@example.com',
            'password' => Hash::make('verifikator123'),
            'jabatan' => 'Verifikator Coaching',
            'no_hp' => '081234567892',
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
        $podcastData = [
            [
                'id_user' => 1,
                'tanggal' => '2026-01-16',
                'nama_opd' => 'Dinas 1',
                'nama_pic' => 'PIC 1',
                'keterangan' => 'Selamatkan Karir dan Keluarga Dengan Kenali, Cegah, dan Lawan Stroke',
                'narasumber' => 'Ahmad Basuki',
                'status_verifikasi' => 'pending',
            ],
            [
                'id_user' => 2,
                'tanggal' => '2026-01-09',
                'nama_opd' => 'Dinas 2',
                'nama_pic' => 'PIC 2',
                'keterangan' => 'Selamatkan Karir dan Keluarga Dengan Kenali, Cegah, dan Lawan Stroke',
                'narasumber' => 'Ahmad Basuki',
                'verifikasi' => 'Verifikator Podcast',
                'status_verifikasi' => 'disetujui',
                'host' => 'Widiyoko',
                'waktu' => '13.00-16.00',
            ]
        ];

        foreach ($podcastData as $data) {
            PodcastBooking::create($data);
        }

        // Create coaching clinic bookings
        $coachingData = [
            [
                'id_user' => 3,
                'tanggal' => '2026-01-21',
                'layanan' => 'Website & Aplikasi',
                'keterangan' => 'Konsultasi Webinar',
                'pic' => 'PIC 3',
                'no_telp' => '081200000003',
                'status_verifikasi' => 'disetujui',
            ],
            [
                'id_user' => 4,
                'tanggal' => '2026-01-14',
                'layanan' => 'TTL Design',
                'keterangan' => 'Konsultasi Website',
                'pic' => 'PIC 4',
                'no_telp' => '081200000004',
                'verifikasi' => 'Verifikator Coaching',
                'status_verifikasi' => 'disetujui',
                'waktu' => '13.00-16.00',
            ]
        ];

        foreach ($coachingData as $data) {
            CoachingBooking::create($data);
        }
    }
}