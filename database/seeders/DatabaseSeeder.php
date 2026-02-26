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
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Call other seeders
        $this->call(HostSeeder::class);
        $this->call(CoachSeeder::class);

        // Create roles (use firstOrCreate to avoid duplicate key errors)
        $roleUser = Role::firstOrCreate(
            ['kode_role' => 'user'],
            ['nama_role' => 'User']
        );
        $roleAdmin = Role::firstOrCreate(
            ['kode_role' => 'admin'],
            ['nama_role' => 'Admin']
        );
        $roleVerifikatorPodcast = Role::firstOrCreate(
            ['kode_role' => 'verifikator_podcast'],
            ['nama_role' => 'Verifikator Podcast']
        );
        $roleVerifikatorCoaching = Role::firstOrCreate(
            ['kode_role' => 'verifikator_coaching'],
            ['nama_role' => 'Verifikator Coaching']
        );

        // Akun admin user (use firstOrCreate to avoid duplicates)
        InternalUser::firstOrCreate(
            ['email' => 'admin@growtalks.com'],
            [
                'id_role' => $roleAdmin->id_role ?? ($roleAdmin->id ?? null),
                'nama_user' => 'Administrator',
                'password' => Hash::make('admingrowtalks'),
                'jabatan' => 'Super Admin',
                'no_hp' => '081234567890',
                'status' => 'aktif',
            ]
        );

        // Akun verifikator podcast
        InternalUser::firstOrCreate(
            ['email' => 'verifikator.podcast@growtalks.com'],
            [
                'id_role' => $roleVerifikatorPodcast->id_role ?? ($roleVerifikatorPodcast->id ?? null),
                'nama_user' => 'Verifikator Podcast',
                'password' => Hash::make('verifpodcast99'),
                'jabatan' => 'Verifikator Podcast',
                'no_hp' => '081234567891',
                'status' => 'aktif',
            ]
        );

        // Akun verifikator coaching
        InternalUser::firstOrCreate(
            ['email' => 'verifikator.coaching@growtalks.com'],
            [
                'id_role' => $roleVerifikatorCoaching->id_role ?? ($roleVerifikatorCoaching->id ?? null),
                'nama_user' => 'Verifikator Coaching',
                'password' => Hash::make('verifcoaching99'),
                'jabatan' => 'Verifikator Coaching',
                'no_hp' => '081234567892',
                'status' => 'aktif',
            ]
        );

        // Data instansi lengkap
        $instansiData = [
            'DAFTAR DPRD' => [
                'Dewan Perwakilan Rakyat Daerah' => 'Budi Santoso'
            ],
            'DAFTAR SEKRETARIAT DAERAH' => [
                'Bagian Administrasi Pembangunan Setda' => 'Ahmad Fauzi',
                'Bagian Hukum Setda' => 'Sri Wahyuni',
                'Bagian Kesejahteraan Rakyat Setda' => 'Rina Marlina',
                'Bagian Layanan Pengadaan Barang/Jasa Setda' => 'Dedi Setiawan',
                'Bagian Organisasi Setda' => 'Eko Prasetyo',
                'Bagian Perekonomian dan Sumber Daya Alam Setda' => 'Fitri Handayani',
                'Bagian Protokol, Komunikasi dan Administrasi Pimpinan' => 'Gunawan Wibowo',
                'Bagian Tata Pemerintahan Setda' => 'Hesti Kusuma',
                'Bagian Umum Setda' => 'Irfan Maulana'
            ],
            'DAFTAR INSPEKTORAT' => [
                'Inspektorat' => 'Joko Widodo'
            ],
            'DAFTAR DINAS' => [
                'Dinas Administrasi Kependudukan dan Pencatatan Sipil' => 'Kartika Sari',
                'Dinas Kebudayaan dan Pariwisata' => 'Lukman Hakim',
                'Dinas Kepemudaan dan Olah Raga' => 'Maya Indah',
                'Dinas Kesehatan' => 'Nugroho Pratama',
                'Dinas Ketahanan Pangan dan Pertanian' => 'Oki Setiawan',
                'Dinas Komunikasi Informatika Statistik dan Persandian' => 'Putri Anggraini',
                'Dinas Koperasi, UKM dan Perindustrian' => 'Rahmat Hidayat',
                'Dinas Lingkungan Hidup' => 'Sari Dewi',
                'Dinas Pekerjaan Umum dan Penataan Ruang' => 'Tono Wijaya',
                'Dinas Pemadam Kebakaran' => 'Umar Said',
                'DP3AP2KB ' => 'Vina Melati',
                'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu' => 'Wahyu Ramadhan',
                'Dinas Pendidikan' => 'Xena Putri',
                'Dinas Perdagangan' => 'Yoga Pratama',
                'Dinas Perhubungan' => 'Zainal Abidin',
                'Dinas Perpustakaan dan Kearsipan' => 'Aisyah Nur',
                'Dinas Perumahan, Kawasan Permukiman dan Pertanahan' => 'Bambang Sutejo',
                'Dinas Sosial' => 'Cindy Laura',
                'Dinas Tenaga Kerja' => 'Doni Setiawan',
                'Satuan Polisi Pamong Praja' => 'Eka Wulandari'
            ],
            'DAFTAR BADAN' => [
                'Badan Kepegawaian dan Pengembangan Sumberdaya Manusia' => 'Fajar Nugroho',
                'Badan Kesatuan Bangsa dan Politik' => 'Gita Maharani',
                'Badan Penanggulangan Bencana Daerah' => 'Hendra Kurnia',
                'Badan Pendapatan Daerah' => 'Intan Permata',
                'Badan Pengelolaan Keuangan dan Aset Daerah' => 'Jefri Maulana',
                'Badan Perencanaan Pembangunan Daerah' => 'Kiki Amelia',
                'Badan Riset dan Inovasi Daerah' => 'Lutfi Hamdani'
            ],
            'DAFTAR KECAMATAN' => [
                'Kecamatan Banjarsari' => 'M. Ridwan',
                'Kecamatan Jebres' => 'Nia Kurnia',
                'Kecamatan Laweyan' => 'Oscar Pratama',
                'Kecamatan Pasar Kliwon' => 'Putra Mahardika',
                'Kecamatan Serengan' => 'Qori Aulia'
            ],
            'DAFTAR RSUD' => [
                'Rumah Sakit Umum Daerah Bung Karno' => 'Dr. Rina Setiawati',
                'Rumah Sakit Umum Daerah Ibu Fatmawati' => 'Dr. Surya Wijaya'
            ],
            'DAFTAR BUMD' => [
                'Perumda BPR Bank Solo' => 'Teguh Santoso',
                'Perumda PAU Pedaringan' => 'Umi Kulsum',
                'Perumda Perusahaan Daerah Air Minum' => 'Vicky Chandra',
                'Perumda Taman Satwa Taru Jurug' => 'Wulan Sari'
            ],
            'DAFTAR KELURAHAN' => [
                'Kelurahan Banjarsari' => 'Ari Wibowo',
                'Kelurahan Banyuanyar' => 'Bella Citra',
                'Kelurahan Gilingan' => 'Cahyo Pratomo',
                'Kelurahan Joglo' => 'Dina Marlina',
                'Kelurahan Kadipiro' => 'Eko Susanto',
                'Kelurahan Keprabon' => 'Fanny Putri',
                'Kelurahan Kestalan' => 'Ganda Setiawan',
                'Kelurahan Ketelan' => 'Hani Fitri',
                'Kelurahan Manahan' => 'Iwan Kurnia',
                'Kelurahan Mangkubumen' => 'Jeni Kartika',
                'Kelurahan Nusukan' => 'Krisna Aditya',
                'Kelurahan Punggawan' => 'Lia Anggraeni',
                'Kelurahan Setabelan' => 'Maman Sujaman',
                'Kelurahan Sumber' => 'Nova Indah',
                'Kelurahan Timuran' => 'Oki Fernando',
                'Kelurahan Gandekan' => 'Putri Anjani',
                'Kelurahan Jagalan' => 'Rizki Maulana',
                'Kelurahan Jebres' => 'Sari Dewanti',
                'Kelurahan Kepatihan Kulon' => 'Taufik Hidayat',
                'Kelurahan Kepatihan Wetan' => 'Uci Santi',
                'Kelurahan Mojosongo' => 'Vino Marcel',
                'Kelurahan Pucang Sawit' => 'Winda Puspita',
                'Kelurahan Purwodiningratan' => 'Yudi Hartono',
                'Kelurahan Sewu' => 'Zahra Fitriani',
                'Kelurahan Sudiroprajan' => 'Agus Salim',
                'Kelurahan Tegalharjo' => 'Bunga Mawar',
                'Kelurahan Bumi' => 'Candra Wijaya',
                'Kelurahan Jajar' => 'Dewi Sartika',
                'Kelurahan Karangasem' => 'Edo Pratama',
                'Kelurahan Kerten' => 'Fira Andini',
                'Kelurahan Laweyan' => 'Gilang Ramadhan',
                'Kelurahan Pajang' => 'Hesti Wulandari',
                'Kelurahan Panularan' => 'Irwan Setiawan',
                'Kelurahan Penumping' => 'Jihan Karina',
                'Kelurahan Purwosari' => 'Koko Kurniawan',
                'Kelurahan Sondakan' => 'Laras Ati',
                'Kelurahan Sriwedari' => 'Miko Andrian',
                'Kelurahan Baluwarti' => 'Nadia Putri',
                'Kelurahan Gajahan' => 'Omar Said',
                'Kelurahan Joyosuran' => 'Prita Widya',
                'Kelurahan Kampung Baru' => 'Qory Sandioriva',
                'Kelurahan Kauman' => 'Reno Febrian',
                'Kelurahan Kedunglumbu' => 'Siska Melati',
                'Kelurahan Mojo' => 'Toni Gunawan',
                'Kelurahan Pasar Kliwon' => 'Umi Salamah',
                'Kelurahan Sangkrah' => 'Vega Antares',
                'Kelurahan Semanggi' => 'Wahid Hasyim',
                'Kelurahan Danukusuman' => 'Xavier Nathan',
                'Kelurahan Jayengan' => 'Yuni Shara',
                'Kelurahan Joyotakan' => 'Zaki Alamsyah',
                'Kelurahan Kemlayan' => 'Ade Rai',
                'Kelurahan Kratonan' => 'Bima Sakti',
                'Kelurahan Serengan' => 'Cinta Laura',
                'Kelurahan Tipes' => 'Daffa Putra'
            ]
        ];

        // Create users for all instansi
        $counter = 1;
        foreach ($instansiData as $kategori => $instansiList) {
            foreach ($instansiList as $instansi => $namaPIC) {
                // Generate email from instansi name
                $email = $this->generateEmail($instansi);
                
                User::firstOrCreate(
                    ['email' => $email],
                    [
                        'kategori_instansi' => $kategori,
                        'instansi' => $instansi,
                        'nama_pic' => $namaPIC,
                        'kontak_pic' => '0812' . str_pad($counter, 8, '0', STR_PAD_LEFT),
                        'password' => Hash::make('instansi99'),
                        'status' => $counter % 10 == 0 ? 'nonaktif' : 'aktif', // Setiap user ke-10 nonaktif
                    ]
                );
                
                $counter++;
            }
        }
    }

    private function generateEmail($instansi)
    {
        // Format: dinaskesehatan@solo.go.id
        $email = strtolower($instansi);
        $email = preg_replace('/[^\w\s]/', '', $email); // Remove special chars
        $email = preg_replace('/\s+/', '', $email); // Remove spaces
        $email = str_replace(['kecamatan', 'kelurahan', 'dinas', 'badan', 'bagian', 'rumahsakitumumdaerah'], '', $email);
        $email = $email . '@solo.go.id';
        
        // Ensure uniqueness - insert counter before @solo.go.id
        $originalEmail = $email;
        $counter = 1;
        
        while (User::where('email', $email)->exists()) {
            // Insert number before @solo.go.id instead of appending
            $email = str_replace('@solo.go.id', $counter . '@solo.go.id', $originalEmail);
            $counter++;
        }
        
        return $email;
    }
}
