<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\PodcastBooking;
use App\Models\Kalender;
use App\Models\CoachingBooking;
use App\Models\InternalUser;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:admin');
    // }

    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_podcasts' => PodcastBooking::count(),
            'total_coachings' => CoachingBooking::count(),
            'podcast_pending' => PodcastBooking::where('status_verifikasi', 'pending')->count(),
            'podcast_approved' => PodcastBooking::where('status_verifikasi', 'disetujui')->count(),
            'podcast_rejected' => PodcastBooking::where('status_verifikasi', 'ditolak')->count(),
            'coaching_pending' => CoachingBooking::where('status_verifikasi', 'pending')->count(),
            'coaching_approved' => CoachingBooking::where('status_verifikasi', 'disetujui')->count(),
            'coaching_rejected' => CoachingBooking::where('status_verifikasi', 'ditolak')->count(),
        ];

        $recentPodcasts = PodcastBooking::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentCoachings = CoachingBooking::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentPodcasts', 'recentCoachings'));
    }

    public function users(Request $request)
    {
        $perPage = $request->get('per_page', 10); // Default 10 per page
        
        $users = User::query()

            ->when($request->filled('kategori'), function ($query) use ($request) {
                $query->where('kategori_instansi', $request->kategori);
            })
            ->when($request->has('search'), function($query) use ($request) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nama_pic', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%")
                      ->orWhere('instansi', 'like', "%$search%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
            
        $kategoriInstansi = $this->getKategoriInstansi();
        return view('admin.users.index', compact('users', 'kategoriInstansi'));
    }

    public function createUser()
    {
        // Data kategori dan instansi untuk dropdown
        $kategoriInstansi = $this->getKategoriInstansi();
        return view('admin.users.create', compact('kategoriInstansi'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'kategori_instansi' => 'required|string|max:100',
            'instansi' => 'required|string|max:200',
            'nama_pic' => 'required|string|max:100',
            'kontak_pic' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        // Validasi domain email harus @solo.go.id
        if (!str_ends_with($request->email, '@solo.go.id')) {
            return back()->withErrors(['email' => 'Email harus menggunakan domain @solo.go.id'])->withInput();
        }

        $user = User::create([
            'email' => $request->email,
            'kategori_instansi' => $request->kategori_instansi,
            'instansi' => $request->instansi,
            'nama_pic' => $request->nama_pic,
            'kontak_pic' => $request->kontak_pic,
            'password' => Hash::make($request->password),
            'status' => $request->status,
        ]);

        return redirect()->route('admin.users')
            ->with('success', 'User berhasil ditambahkan.');
    }
    
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        $kategoriInstansi = $this->getKategoriInstansi();
        $instansiByKategori = $this->getInstansiByKategori($user->kategori_instansi);
        
        return view('admin.users.edit', compact('user', 'kategoriInstansi', 'instansiByKategori'));
    }

    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'kategori_instansi' => 'required|string|max:100',
            'instansi' => 'required|string|max:200',
            'nama_pic' => 'required|string|max:100',
            'kontak_pic' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        // Validasi domain email harus @solo.go.id
        if (!str_ends_with($request->email, '@solo.go.id')) {
            return back()->withErrors(['email' => 'Email harus menggunakan domain @solo.go.id'])->withInput();
        }

        $user = User::findOrFail($id);
        
        $updateData = [
            'kategori_instansi' => $request->kategori_instansi,
            'instansi' => $request->instansi,
            'nama_pic' => $request->nama_pic,
            'kontak_pic' => $request->kontak_pic,
            'email' => $request->email,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()->route('admin.users')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users')
            ->with('success', 'User berhasil dihapus.');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->status = $user->status == 'aktif' ? 'nonaktif' : 'aktif';
        $user->save();

        $statusText = $user->status == 'aktif' ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('admin.users')
            ->with('success', "User berhasil $statusText.");
    }

    public function getInstansiByKategori($kategori)
    {
        $list = $this->getInstansiList()[$kategori] ?? [];

        // Flatten nested arrays (e.g. kelurahan grouped by kecamatan)
        $flat = [];
        array_walk_recursive($list, function ($value) use (&$flat) {
            $flat[] = $value;
        });

        return $flat;
    }

    private function generateEmail($instansi)
    {
        // Format: dinaskesehatan@solo.go.id
        $instansiSlug = Str::slug($instansi, '');
        $instansiSlug = strtolower(preg_replace('/[^A-Za-z0-9]/', '', $instansiSlug));
        return $instansiSlug . '@solo.go.id';
    }

    private function getKategoriInstansi()
    {
        return [
            'DAFTAR DPRD',
            'DAFTAR SEKRETARIAT DAERAH',
            'DAFTAR INSPEKTORAT',
            'DAFTAR DINAS',
            'DAFTAR BADAN',
            'DAFTAR KECAMATAN',
            'DAFTAR RSUD',
            'DAFTAR BUMD',
            'DAFTAR KELURAHAN'
        ];
    }

    public function getInstansiList()
    {
        return [
            'DAFTAR DPRD' => ['Dewan Perwakilan Rakyat Daerah'],
            'DAFTAR SEKRETARIAT DAERAH' => [
                'Bagian Administrasi Pembangunan Setda',
                'Bagian Hukum Setda',
                'Bagian Kesejahteraan Rakyat Setda',
                'Bagian Layanan Pengadaan Barang/Jasa Setda',
                'Bagian Organisasi Setda',
                'Bagian Perekonomian dan Sumber Daya Alam Setda',
                'Bagian Protokol, Komunikasi dan Administrasi Pimpinan',
                'Bagian Tata Pemerintahan Setda',
                'Bagian Umum Setda'
            ],
            'DAFTAR INSPEKTORAT' => ['Inspektorat'],
            'DAFTAR DINAS' => [
                'Dinas Administrasi Kependudukan dan Pencatatan Sipil',
                'Dinas Kebudayaan dan Pariwisata',
                'Dinas Kepemudaan dan Olah Raga',
                'Dinas Kesehatan',
                'Dinas Ketahanan Pangan dan Pertanian',
                'Dinas Komunikasi Informatika Statistik dan Persandian',
                'Dinas Koperasi, UKM dan Perindustrian',
                'Dinas Lingkungan Hidup',
                'Dinas Pekerjaan Umum dan Penataan Ruang',
                'Dinas Pemadam Kebakaran',
                'DP3AP2KB',
                'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu',
                'Dinas Pendidikan',
                'Dinas Perdagangan',
                'Dinas Perhubungan',
                'Dinas Perpustakaan dan Kearsipan',
                'Dinas Perumahan, Kawasan Permukiman dan Pertanahan',
                'Dinas Sosial',
                'Dinas Tenaga Kerja',
                'Satuan Polisi Pamong Praja'
            ],
            'DAFTAR BADAN' => [
                'Badan Kepegawaian dan Pengembangan Sumberdaya Manusia',
                'Badan Kesatuan Bangsa dan Politik',
                'Badan Penanggulangan Bencana Daerah',
                'Badan Pendapatan Daerah',
                'Badan Pengelolaan Keuangan dan Aset Daerah',
                'Badan Perencanaan Pembangunan Daerah',
                'Badan Riset dan Inovasi Daerah'
            ],
            'DAFTAR KECAMATAN' => [
                'Kecamatan Banjarsari',
                'Kecamatan Jebres',
                'Kecamatan Laweyan',
                'Kecamatan Pasar Kliwon',
                'Kecamatan Serengan'
            ],
            'DAFTAR RSUD' => [
                'Rumah Sakit Umum Daerah Bung Karno',
                'Rumah Sakit Umum Daerah Ibu Fatmawati'
            ],
            'DAFTAR BUMD' => [
                'Perumda BPR Bank Solo',
                'Perumda PAU Pedaringan',
                'Perumda Perusahaan Daerah Air Minum',
                'Perumda Taman Satwa Taru Jurug'
            ],
            'DAFTAR KELURAHAN' => [
                'Kecamatan Banjarsari' => [
                    'Kelurahan Banjarsari',
                    'Kelurahan Banyuanyar',
                    'Kelurahan Gilingan',
                    'Kelurahan Joglo',
                    'Kelurahan Kadipiro',
                    'Kelurahan Keprabon',
                    'Kelurahan Kestalan',
                    'Kelurahan Ketelan',
                    'Kelurahan Manahan',
                    'Kelurahan Mangkubumen',
                    'Kelurahan Nusukan',
                    'Kelurahan Punggawan',
                    'Kelurahan Setabelan',
                    'Kelurahan Sumber',
                    'Kelurahan Timuran'
                ],
                'Kecamatan Jebres' => [
                    'Kelurahan Gandekan',
                    'Kelurahan Jagalan',
                    'Kelurahan Jebres',
                    'Kelurahan Kepatihan Kulon',
                    'Kelurahan Kepatihan Wetan',
                    'Kelurahan Mojosongo',
                    'Kelurahan Pucang Sawit',
                    'Kelurahan Purwodiningratan',
                    'Kelurahan Sewu',
                    'Kelurahan Sudiroprajan',
                    'Kelurahan Tegalharjo'
                ],
                'Kecamatan Laweyan' => [
                    'Kelurahan Bumi',
                    'Kelurahan Jajar',
                    'Kelurahan Karangasem',
                    'Kelurahan Kerten',
                    'Kelurahan Laweyan',
                    'Kelurahan Pajang',
                    'Kelurahan Panularan',
                    'Kelurahan Penumping',
                    'Kelurahan Purwosari',
                    'Kelurahan Sondakan',
                    'Kelurahan Sriwedari'
                ],
                'Kecamatan Pasar Kliwon' => [
                    'Kelurahan Baluwarti',
                    'Kelurahan Gajahan',
                    'Kelurahan Joyosuran',
                    'Kelurahan Kampung Baru',
                    'Kelurahan Kauman',
                    'Kelurahan Kedunglumbu',
                    'Kelurahan Mojo',
                    'Kelurahan Pasar Kliwon',
                    'Kelurahan Sangkrah',
                    'Kelurahan Semanggi'
                ],
                'Kecamatan Serengan' => [
                    'Kelurahan Danukusuman',
                    'Kelurahan Jayengan',
                    'Kelurahan Joyotakan',
                    'Kelurahan Kemlayan',
                    'Kelurahan Kratonan',
                    'Kelurahan Serengan',
                    'Kelurahan Tipes'
                ]
            ]
        ];
    }

    public function podcasts()
    {
        $podcasts = PodcastBooking::with(['user','kalender'])
            ->orderBy('tanggal','desc')
            ->get();

        return view('admin.podcast.index', compact('podcasts'));
    }

    public function showPodcast($id)
    {
        $podcast = PodcastBooking::with(['user','kalender'])
            ->findOrFail($id);

        return view('admin.podcast.show', compact('podcast'));
    }

    public function coachings()
    {
        $coachings = CoachingBooking::with(['user', 'kalender'])
            ->orderBy('tanggal', 'desc')
            ->get();
        return view('admin.coaching.index', compact('coachings'));
    }

    public function updatePodcastStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,disetujui,ditolak,penjadwalan ulang',
            'host' => 'nullable|string|max:100',
            'waktu' => 'nullable|date_format:H:i',
        ]);

        $podcast = PodcastBooking::findOrFail($id);

        $status = $request->input('status');

        $podcast->update([
            'status_verifikasi' => $status,
            'host' => $request->host,
            'verifikasi' => auth()->guard('admin')->user()->nama_user,
        ]);

        // If approved, ensure there's a kalender entry and mark it booked
        if ($status === 'disetujui') {
            $waktu = $request->waktu ?? $podcast->waktu;

            if ($podcast->kalender) {
                $podcast->kalender->update([
                    'waktu' => $waktu,
                    'sudah_dibooking' => true,
                    'jenis_agenda' => 'podcast',
                    'id_agenda' => $podcast->id,
                ]);
            } else {
                $kal = Kalender::create([
                    'tanggal_kalender' => $podcast->tanggal,
                    'waktu' => $waktu,
                    'sudah_dibooking' => true,
                    'jenis_agenda' => 'podcast',
                    'id_agenda' => $podcast->id,
                ]);
                $podcast->update(['id_kalender' => $kal->id]);
            }
        } else {
            // If not approved, free any associated kalender slot
            if ($podcast->kalender) {
                $podcast->kalender->update([
                    'sudah_dibooking' => false,
                    'jenis_agenda' => null,
                    'id_agenda' => null,
                ]);
                $podcast->update(['id_kalender' => null]);
            }
        }

        return back()->with('success', 'Status podcast berhasil diperbarui.');
    }

    public function updateCoachingStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,disetujui,ditolak',
        ]);

        $coaching = CoachingBooking::findOrFail($id);
        
        $coaching->update([
            'status_verifikasi' => $request->status,
            'verifikasi' => auth()->guard('admin')->user()->nama_user,
        ]);

        return back()->with('success', 'Status coaching clinic berhasil diperbarui.');
    }

    public function reportPodcast()
    {
        $podcasts = PodcastBooking::with('user')
            ->orderBy('tanggal', 'desc')
            ->get();
        return view('admin.reports.podcast', compact('podcasts'));
    }

    public function reportCoaching()
    {
        $coachings = CoachingBooking::with('user')
            ->orderBy('tanggal', 'desc')
            ->get();
        return view('admin.reports.coaching', compact('coachings'));
    }
}