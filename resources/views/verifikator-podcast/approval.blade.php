@extends('layouts.verifikator-podcast')

@section('title', 'Approval Podcast')

@section('content')
<div class="p-8">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Approval Podcast</h1>
        <p class="text-gray-600">Verifikasi pengajuan podcast dari user</p>
    </div>

    <!-- Antrian Pengajuan Podcast -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800">Antrian Pengajuan Podcast</h2>
        </div>
        
        <!-- Tabs -->
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <button class="py-3 px-6 border-b-2 border-blue-500 text-blue-600 font-medium">
                    Semua ({{ $podcasts->count() }})
                </button>
                <button class="py-3 px-6 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    Pending ({{ $podcasts->where('status_verifikasi', 'pending')->count() }})
                </button>
                <button class="py-3 px-6 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    Disetujui ({{ $podcasts->where('status_verifikasi', 'disetujui')->count() }})
                </button>
                <button class="py-3 px-6 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    Ditolak ({{ $podcasts->where('status_verifikasi', 'ditolak')->count() }})
                </button>
            </nav>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <thead class="bg-blue-900 text-white">
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Aksi</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Status</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Keterangan</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Kode Booking</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Tanggal</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Judul</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Narasumber</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Cover</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($podcasts as $podcast)
                    <tr class="table-row">
                        <td class="py-3 px-4">
                            <a href="{{ route('verifikator-podcast.approval-form', $podcast->id) }}" 
                               class="text-blue-600 hover:text-blue-800" title="Verifikasi">
                                <i class="fas fa-file-alt"></i>
                            </a>
                        </td>
                        <td class="py-3 px-4">
                            <span class="status-badge status-{{ $podcast->status_verifikasi }}">
                                {{ ucfirst($podcast->status_verifikasi) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-sm">
                            <button class="text-gray-600 hover:text-gray-800">
                                {{ $podcast->catatan ?? '-' }}
                            </button>
                        </td>
                        <td class="py-3 px-4 font-mono text-sm">
                            {{ date('Ymd', strtotime($podcast->tanggal)) }}{{ $podcast->id_podcast }}
                        </td>
                        <td class="py-3 px-4">
                            <div class="text-sm">{{ $podcast->tanggal->locale('id')->isoFormat('D MMMM YYYY') }}</div>
                            @if($podcast->waktu)
                                <div class="text-xs text-gray-600">Waktu: {{ $podcast->waktu }}</div>
                            @else
                                <div class="text-xs text-gray-600">Waktu: -</div>
                            @endif
                            @if($podcast->host)
                                <div class="text-xs text-gray-600">Host: {{ $podcast->host }}</div>
                            @else
                                <div class="text-xs text-gray-600">Host: -</div>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-xs max-w-[180px] whitespace-normal break-words">
                            {{ $podcast->keterangan }}
                        </td>
                        <td class="py-3 px-4 text-sm">{{ $podcast->narasumber }}</td>
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
                        <td colspan="8" class="py-8 text-center text-gray-500">
                            Belum ada pengajuan podcast
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection