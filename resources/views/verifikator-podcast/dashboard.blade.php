@extends('layouts.verifikator-podcast')

@section('title', 'Dashboard Verifikator Podcast')

@section('content')
<div class="p-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard Verifikator Podcast</h1>
        <div class="flex space-x-4 mt-2">
            <span class="text-blue-600 font-medium">Dashboard</span>
            <span class="text-gray-400">|</span>
            <a href="{{ route('verifikator-podcast.approval') }}" class="text-gray-600 hover:text-blue-600">Approval Podcast</a>
            <span class="text-gray-400">|</span>
            <a href="{{ route('verifikator-podcast.report') }}" class="text-gray-600 hover:text-blue-600">Laporan Podcast</a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Podcast -->
          <div class="card-stat bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg mr-4">
                    <i class="fas fa-podcast text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Podcast</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalPodcasts }}</p>
                    <p class="text-xs text-gray-500 mt-1">Total Pengajuan</p>
                </div>
            </div>
        </div>

        <!-- Menunggu Persetujuan -->
          <div class="card-stat bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-lg mr-4">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Menunggu Persetujuan</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $pendingPodcasts }}</p>
                    <p class="text-xs text-gray-500 mt-1">Podcast Pending</p>
                </div>
            </div>
        </div>

        <!-- Disetujui -->
        <div class="card-stat bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg mr-4">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Disetujui</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $approvedPodcasts }}</p>
                    <p class="text-xs text-gray-500 mt-1">Podcast Disetujui</p>
                </div>
            </div>
        </div>

        <!-- Ditolak -->
         <div class="card-stat bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-lg mr-4">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Ditolak</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $rejectedPodcasts }}</p>
                    <p class="text-xs text-gray-500 mt-1">Podcast Ditolak</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Podcasts -->
       <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800">Podcast Terbaru</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <thead class="bg-blue-900 text-white">
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Kode</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Tanggal</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Instansi</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Judul</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Narasumber</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($recentPodcasts as $podcast)
                    <tr class="table-row">
                        <td class="py-3 px-4 font-mono text-sm">
                            POD-{{ date('Ymd', strtotime($podcast->tanggal)) }}{{ $podcast->id }}
                        </td>
                        <td class="py-3 px-4 text-sm">
                            {{ $podcast->tanggal->format('d/m/Y') }}
                        </td>
                        <td class="py-3 px-4 text-sm">{{ $podcast->nama_opd }}</td>
                        <td class="py-3 px-4">
                            <div class="font-medium text-gray-800">{{ Str::limit($podcast->keterangan, 30) }}</div>
                        </td>
                        <td class="py-3 px-4 text-sm">{{ $podcast->narasumber }}</td>
                        <td class="py-3 px-4">
                            <span class="status-badge status-{{ $podcast->status_verifikasi }}">
                                {{ ucfirst($podcast->status_verifikasi) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-500">
                            Belum ada data podcast
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
        <!-- Approval Statistics -->
           <div class="card-stat bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Statistik Approval</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Menunggu Verifikasi</span>
                    <span class="font-bold">{{ $pendingPodcasts }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Sudah Diverifikasi</span>
                    <span class="font-bold">{{ $approvedPodcasts + $rejectedPodcasts }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Persentase Disetujui</span>
                    <span class="font-bold">
                        {{ $totalPodcasts > 0 ? round(($approvedPodcasts / $totalPodcasts) * 100, 1) : 0 }}%
                    </span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
           <div class="card-stat bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Aksi Cepat</h3>
            <div class="space-y-3">
                <a href="{{ route('verifikator-podcast.approval') }}" 
                   class="block w-full text-center py-3 px-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-check-circle mr-2"></i>Verifikasi Podcast
                </a>
                <a href="{{ route('verifikator-podcast.report') }}" 
                   class="block w-full text-center py-3 px-4 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                    <i class="fas fa-file-alt mr-2"></i>Lihat Laporan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection