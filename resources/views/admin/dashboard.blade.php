@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Admin</h1>
            <p class="text-gray-600">Ringkasan statistik dan monitoring sistem</p>
        </div>
        <div class="text-sm text-gray-500">
            {{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
        </div>
    </div>

    <!-- Statistik Umum -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total User -->
        <div class="stat-card bg-white p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg mr-4">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total User</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total_users'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">User Terdaftar</p>
                </div>
            </div>
        </div>

        <!-- Total Podcast -->
        <div class="stat-card bg-white p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg mr-4">
                    <i class="fas fa-podcast text-purple-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Podcast</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total_podcasts'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Total Pengajuan</p>
                </div>
            </div>
        </div>

        <!-- Total Coaching -->
        <div class="stat-card bg-white p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg mr-4">
                    <i class="fas fa-chalkboard-teacher text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Coaching</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total_coachings'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Total Pengajuan</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Podcast & Coaching Clinic -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Statistik Podcast -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Statistik Podcast</h2>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-clock text-yellow-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Menunggu Persetujuan</p>
                            <p class="text-xl font-bold text-gray-800">{{ $stats['podcast_pending'] }}</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500">Podcast Pending</p>
                </div>
                
                <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-check text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Disetujui</p>
                            <p class="text-xl font-bold text-gray-800">{{ $stats['podcast_approved'] }}</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500">Podcast Disetujui</p>
                </div>
                
                <div class="flex justify-between items-center p-3 bg-red-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-times text-red-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Ditolak</p>
                            <p class="text-xl font-bold text-gray-800">{{ $stats['podcast_rejected'] }}</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500">Podcast Ditolak</p>
                </div>
            </div>
        </div>

        <!-- Statistik Coaching Clinic -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Statistik Coaching Clinic</h2>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-clock text-yellow-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Menunggu Persetujuan</p>
                            <p class="text-xl font-bold text-gray-800">{{ $stats['coaching_pending'] }}</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500">Coaching Clinic Pending</p>
                </div>
                
                <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-check text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Disetujui</p>
                            <p class="text-xl font-bold text-gray-800">{{ $stats['coaching_approved'] }}</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500">Coaching Clinic Disetujui</p>
                </div>
                
                <div class="flex justify-between items-center p-3 bg-red-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-times text-red-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Ditolak</p>
                            <p class="text-xl font-bold text-gray-800">{{ $stats['coaching_rejected'] }}</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500">Coaching Clinic Ditolak</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities & Manajemen User -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Podcast -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold text-gray-800">Podcast Terbaru</h2>
                <a href="{{ route('admin.podcasts') }}" class="text-sm text-blue-600 hover:text-blue-800">
                    Lihat Semua →
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="py-2 px-3 text-left text-sm font-medium text-gray-700">Tanggal</th>
                            <th class="py-2 px-3 text-left text-sm font-medium text-gray-700">Judul</th>
                            <th class="py-2 px-3 text-left text-sm font-medium text-gray-700">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($recentPodcasts as $podcast)
                        <tr class="hover:bg-gray-50">
                            <td class="py-2 px-3 text-sm">
                                {{ $podcast->tanggal->format('d/m/Y') }}
                            </td>
                            <td class="py-2 px-3 text-sm font-medium">
                                {{ Str::limit($podcast->keterangan, 30) }}
                            </td>
                            <td class="py-2 px-3">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    {{ $podcast->status_verifikasi == 'disetujui' ? 'bg-green-100 text-green-800' : 
                                       ($podcast->status_verifikasi == 'ditolak' ? 'bg-red-100 text-red-800' : 
                                       'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($podcast->status_verifikasi) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-4 text-center text-gray-500 text-sm">
                                Belum ada data podcast
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Coaching -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold text-gray-800">Coaching Clinic Terbaru</h2>
                <a href="{{ route('admin.coachings') }}" class="text-sm text-blue-600 hover:text-blue-800">
                    Lihat Semua →
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="py-2 px-3 text-left text-sm font-medium text-gray-700">Tanggal</th>
                            <th class="py-2 px-3 text-left text-sm font-medium text-gray-700">Layanan</th>
                            <th class="py-2 px-3 text-left text-sm font-medium text-gray-700">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($recentCoachings as $coaching)
                        <tr class="hover:bg-gray-50">
                            <td class="py-2 px-3 text-sm">
                                {{ $coaching->tanggal->format('d/m/Y') }}
                            </td>
                            <td class="py-2 px-3 text-sm font-medium">
                                {{ $coaching->layanan }}
                            </td>
                            <td class="py-2 px-3">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    {{ $coaching->status_verifikasi == 'disetujui' ? 'bg-green-100 text-green-800' : 
                                       ($coaching->status_verifikasi == 'ditolak' ? 'bg-red-100 text-red-800' : 
                                       'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($coaching->status_verifikasi) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-4 text-center text-gray-500 text-sm">
                                Belum ada data coaching clinic
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection