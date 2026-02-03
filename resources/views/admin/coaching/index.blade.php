@extends('layouts.admin')

@section('title', 'Laporan Coaching Clinic')

@php use Illuminate\Support\Str; @endphp

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Laporan Coaching Clinic</h1>
        <p class="text-gray-600 italic">Daftar laporan dan monitoring coaching clinic</p>
    </div>

    {{-- TABLE CARD --}}
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">

        <div class="px-6 py-4 border-b bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-800">
                Laporan Pengajuan Coaching Clinic
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">

                {{-- HEADER --}}
                <thead class="bg-blue-900 text-white">
                    <tr>
                        <th class="py-3 px-4 text-left text-sm">Status</th>
                        <th class="py-3 px-4 text-left text-sm">Kode Booking</th>
                        <th class="py-3 px-4 text-left text-sm">Tanggal</th>
                        <th class="py-3 px-4 text-left text-sm">Instansi</th>
                        <th class="py-3 px-4 text-left text-sm">Kategori</th>
                        <th class="py-3 px-4 text-left text-sm">Agenda</th>
                        <th class="py-3 px-4 text-left text-sm">Coach</th>
                        <th class="py-3 px-4 text-left text-sm">Dokumentasi</th>
                    </tr>
                </thead>

                {{-- BODY --}}
                <tbody class="divide-y divide-gray-200">

                @forelse($coachings as $coaching)
                    <tr class="hover:bg-gray-50 transition">

                        {{-- STATUS --}}
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 text-xs rounded-full
                                @if($coaching->status_verifikasi=='disetujui')
                                    bg-green-100 text-green-800
                                @elseif($coaching->status_verifikasi=='ditolak')
                                    bg-red-100 text-red-800
                                @else
                                    bg-yellow-100 text-yellow-800
                                @endif">
                                {{ ucfirst($coaching->status_verifikasi) }}
                            </span>
                        </td>

                        {{-- KODE --}}
                        <td class="py-3 px-4 font-mono text-sm">
                            CCA-{{ $coaching->tanggal->format('Ymd') }}{{ $coaching->id }}
                        </td>

                        {{-- TANGGAL --}}
                        <td class="py-3 px-4 text-sm">
                            {{ $coaching->tanggal->format('d/m/Y') }}
                        </td>

                        {{-- INSTANSI --}}
                        <td class="py-3 px-4 text-sm">
                            {{ $coaching->user->nama_opd }}
                        </td>

                        {{-- KATEGORI --}}
                        <td class="py-3 px-4 text-sm">
                            {{ $coaching->layanan }}
                        </td>

                        {{-- AGENDA --}}
                        <td class="py-3 px-4 text-sm max-w-xs break-words whitespace-normal">
                            {{ Str::limit($coaching->keterangan, 60) }}
                        </td>

                        {{-- COACH --}}
                        <td class="py-3 px-4 text-sm">
                            {{ $coaching->coach ?? '-' }}
                        </td>

                        {{-- DOKUMENTASI --}}
                        <td class="py-3 px-4 text-sm">
                            @if($coaching->dokumentasi_path)
                                <a href="{{ asset($coaching->dokumentasi_path) }}" target="_blank"
                                   class="text-blue-600 hover:text-blue-800">
                                   Lihat
                                </a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-16 text-center text-gray-400">
                            Belum ada data coaching clinic
                        </td>
                    </tr>
                @endforelse

                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
