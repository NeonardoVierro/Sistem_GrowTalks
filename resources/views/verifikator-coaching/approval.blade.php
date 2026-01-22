@extends('layouts.verifikator-coaching')

@section('title', 'Approval Coaching Clinic')

@section('content')
<div class="p-8">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Approval Coaching Clinic</h1>
        <p class="text-gray-600">Verifikasi pengajuan coaching clinic dari user</p>
    </div>

    <!-- Search Bar -->
    <div class="mb-6">
        <input type="text" 
               class="w-full max-w-md px-4 py-2 border border-gray-300 rounded-lg focus:border-green-500 focus:ring-1 focus:ring-green-500"
               placeholder="🔍 Judul">
    </div>

    <!-- Antrian Pengajuan Coaching -->
     <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800">Antrian Pengajuan Coaching</h2>
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
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Kategori</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Agenda</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($coachings as $coaching)
                    <tr class="table-row">
                        <td class="py-3 px-4">
                            <a href="{{ route('verifikator-coaching.approval-form', $coaching->id) }}" 
                               class="text-green-600 hover:text-green-800" title="Verifikasi">
                                <i class="fas fa-check-circle"></i>
                            </a>
                        </td>
                        <td class="py-3 px-4">
                            <span class="status-badge status-{{ $coaching->status_verifikasi }}">
                                {{ ucfirst($coaching->status_verifikasi) }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <button class="text-gray-600 hover:text-gray-800">
                                ...
                            </button>
                        </td>
                        <td class="py-3 px-4 font-mono text-sm">
                            {{ date('Ymd', strtotime($coaching->tanggal)) }}{{ $coaching->id_coaching }}
                        </td>
                        <td class="py-3 px-4">
                            <div class="text-sm">{{ $coaching->tanggal->locale('id')->isoFormat('D MMMM YYYY') }}</div>
                            @if($coaching->waktu)
                                <div class="text-xs text-gray-600">Waktu: {{ $coaching->waktu }}</div>
                            @else
                                <div class="text-xs text-gray-600">Waktu: -</div>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <div class="font-medium">{{ $coaching->layanan }}</div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="text-sm">{{ Str::limit($coaching->keterangan, 30) }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-gray-500">
                            Belum ada pengajuan coaching clinic
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection