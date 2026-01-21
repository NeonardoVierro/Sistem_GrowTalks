@extends('layouts.verifikator-podcast')

@section('title', 'Laporan Podcast')

@section('content')
<div class="p-8">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Laporan Podcast</h1>
        <p class="text-gray-600">Laporan dan statistik podcast</p>
    </div>

    <!-- Report Container -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800">Laporan Podcast</h2>
        </div>

        <!-- Filter -->
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                           placeholder="Cari...">
                </div>
                <div class="w-40">
                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        <option value="">Status</option>
                        <option value="pending">Pending</option>
                        <option value="disetujui">Disetujui</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>
                <div class="w-32">
                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        <option value="">Tahun</option>
                        <option value="2026">2026</option>
                        <option value="2025">2025</option>
                        <option value="2024">2024</option>
                    </select>
                </div>
                <button class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Filter
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Status</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Kode Booking</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Tanggal</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Instansi</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Judul</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Narasumber</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Host</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-700">Cover</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($podcasts as $podcast)
                    <tr class="table-row">
                        <td class="py-3 px-4">
                            <span class="status-badge status-{{ $podcast->status_verifikasi }}">
                                {{ ucfirst($podcast->status_verifikasi) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 font-mono text-sm">
                            POD{{ $podcast->id_podcast }}
                        </td>
                        <td class="py-3 px-4 text-sm">
                            {{ $podcast->tanggal->format('d/m/Y') }}
                        </td>
                        <td class="py-3 px-4 text-sm">{{ $podcast->nama_opd }}</td>
                        <td class="py-3 px-4 font-medium">{{ Str::limit($podcast->keterangan, 30) }}</td>
                        <td class="py-3 px-4 text-sm">{{ $podcast->narasumber }}</td>
                        <td class="py-3 px-4 text-sm">{{ $podcast->host ?? '-' }}</td>
                        <td class="py-3 px-4">
                            @if($podcast->cover_path)
                                <a href="{{ asset($podcast->cover_path) }}" target="_blank" 
                                   class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-link mr-1"></i>Unggah
                                </a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-16 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <i class="fas fa-database text-4xl mb-3"></i>
                                <p class="text-lg text-gray-500">Belum Ada Data</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection