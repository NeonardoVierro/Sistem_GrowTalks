@extends('layouts.admin')

@section('title', 'Laporan Coaching Clinic')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Laporan Coaching Clinic</h1>
            <p class="text-gray-600">Analisis dan statistik coaching clinic</p>
        </div>
        <div class="flex space-x-3">
            <select class="px-4 py-2 border border-gray-300 rounded focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                <option>2026</option>
                <option>2025</option>
                <option>2024</option>
            </select>
            <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                <i class="fas fa-download mr-2"></i>Export
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <div class="text-center">
                <div class="text-3xl font-bold text-green-600">{{ $coachings->count() }}</div>
                <p class="text-sm text-gray-600 mt-2">Total Coaching</p>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <div class="text-center">
                <div class="text-3xl font-bold text-green-600">
                    {{ $coachings->where('status_verifikasi', 'disetujui')->count() }}
                </div>
                <p class="text-sm text-gray-600 mt-2">Disetujui</p>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <div class="text-center">
                <div class="text-3xl font-bold text-red-600">
                    {{ $coachings->where('status_verifikasi', 'ditolak')->count() }}
                </div>
                <p class="text-sm text-gray-600 mt-2">Ditolak</p>
            </div>
        </div>
    </div>

    <!-- Report Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800">Detail Laporan Coaching Clinic</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Kode</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Tanggal</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">OPD</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Layanan</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Agenda</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Status</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Verifikator</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($coachings as $coaching)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4 font-mono text-sm">
                            CCA{{ $coaching->id_coaching }}
                        </td>
                        <td class="py-3 px-4 text-sm">
                            {{ $coaching->tanggal->format('d/m/Y') }}
                        </td>
                        <td class="py-3 px-4 text-sm">{{ $coaching->user->nama_opd }}</td>
                        <td class="py-3 px-4 text-sm">{{ $coaching->layanan }}</td>
                        <td class="py-3 px-4 text-sm">{{ Str::limit($coaching->keterangan, 40) }}</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $coaching->status_verifikasi == 'disetujui' ? 'bg-green-100 text-green-800' : 
                                   ($coaching->status_verifikasi == 'ditolak' ? 'bg-red-100 text-red-800' : 
                                   'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($coaching->status_verifikasi) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-sm">{{ $coaching->verifikasi ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-gray-500">
                            <i class="fas fa-database text-3xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500">Belum Ada Data</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection