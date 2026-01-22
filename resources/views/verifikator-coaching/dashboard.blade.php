@extends('layouts.verifikator-coaching')

@section('title', 'Dashboard Verifikator Coaching')

@section('content')
<div class="p-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard Verifikator Coaching Clinic</h1>
        <div class="flex space-x-4 mt-2">
            <span class="text-green-600 font-medium">Dashboard</span>
            <span class="text-gray-400">|</span>
            <a href="{{ route('verifikator-coaching.approval') }}" class="text-gray-600 hover:text-green-600">Approval Coaching</a>
            <span class="text-gray-400">|</span>
            <a href="{{ route('verifikator-coaching.report') }}" class="text-gray-600 hover:text-green-600">Laporan Coaching</a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Total Coaching -->
     <div class="card-stat bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg mr-4">
                    <i class="fas fa-chalkboard-teacher text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Coaching</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalCoachings }}</p>
                    <p class="text-xs text-gray-500 mt-1">Pengajuan Coaching</p>
                </div>
            </div>
        </div>

        <!-- Coaching Menunggu -->
       <div class="card-stat bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-lg mr-4">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Coaching Menunggu</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $pendingCoachings }}</p>
                    <p class="text-xs text-gray-500 mt-1">Menunggu Persetujuan</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Coachings -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800">Coaching Terbaru</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                     <thead class="bg-blue-900 text-white">
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Kode</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Tanggal</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Instansi</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Kategori</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Agenda</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($recentCoachings as $coaching)
                    <tr class="table-row">
                        <td class="py-3 px-4 font-mono text-sm">
                            CCA{{ $coaching->id_coaching }}
                        </td>
                        <td class="py-3 px-4 text-sm">
                            {{ $coaching->tanggal->format('d/m/Y') }}
                        </td>
                        <td class="py-3 px-4 text-sm">{{ $coaching->user->nama_opd }}</td>
                        <td class="py-3 px-4">
                            <div class="font-medium text-gray-800">{{ $coaching->layanan }}</div>
                        </td>
                        <td class="py-3 px-4 text-sm">{{ Str::limit($coaching->keterangan, 25) }}</td>
                        <td class="py-3 px-4">
                            <span class="status-badge status-{{ $coaching->status_verifikasi }}">
                                {{ ucfirst($coaching->status_verifikasi) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-500">
                            Belum ada data coaching
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Statistics -->
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Statistik</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Disetujui</span>
                    <span class="font-bold text-green-600">{{ $approvedCoachings }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Ditolak</span>
                    <span class="font-bold text-red-600">{{ $rejectedCoachings }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Pending</span>
                    <span class="font-bold text-yellow-600">{{ $pendingCoachings }}</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
         <div class="card-stat bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
            <h3 class="text-lg font-bold text-gray-800 mb-3">Aksi Cepat</h3>
            <div class="space-y-4">
                <a href="{{ route('verifikator-coaching.approval') }}" 
                   class="block w-full text-center py-3 px-4 bg-green-600 text-white rounded-lg hover:bg-green-600">
                    <i class="fas fa-check-circle mr-2"></i>Verifikasi Coaching
                </a>
                <a href="{{ route('verifikator-coaching.report') }}" 
                   class="block w-full text-center py-3 px-4 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                    <i class="fas fa-file-alt mr-2"></i>Lihat Laporan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection