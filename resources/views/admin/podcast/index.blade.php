@extends('layouts.admin')

@section('title', 'Manajemen Podcast')

@php use Illuminate\Support\Str; @endphp

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Laporan Podcast</h1>
        <p class="text-gray-600 italic">Daftar laporan dan verifikasi pengajuan podcast</p>
    </div>

    {{-- TABLE CARD --}}
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">

        <div class="px-6 py-4 border-b bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-800">
                Laporan Pengajuan Podcast
            </h2>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full">

                {{-- ================= HEADER ================= --}}
                <thead class="bg-blue-900 text-white">
                    <tr>
                        <th class="py-3 px-4 text-left text-sm">Status</th>
                        <th class="py-3 px-4 text-left text-sm">Kode Booking</th>
                        <th class="py-3 px-4 text-left text-sm">Tanggal</th>
                        <th class="py-3 px-4 text-left text-sm">Instansi</th>
                        <th class="py-3 px-4 text-left text-sm">Judul</th>
                        <th class="py-3 px-4 text-left text-sm">Narasumber</th>
                        <th class="py-3 px-4 text-left text-sm">Host</th>
                        <th class="py-3 px-4 text-left text-sm">Cover</th>
                    </tr>
                </thead>

                {{-- ================= BODY ================= --}}
                <tbody class="divide-y divide-gray-200">

                    @forelse($podcasts as $podcast)

                    <tr class="hover:bg-gray-50 transition">

                        {{-- STATUS --}}
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 text-xs rounded-full
                                @if($podcast->status_verifikasi=='disetujui')
                                bg-green-100 text-green-800
                                @elseif($podcast->status_verifikasi=='ditolak')
                                bg-red-100 text-red-800
                                @else
                                bg-yellow-100 text-yellow-800
                                @endif">
                                {{ ucfirst($podcast->status_verifikasi) }}
                            </span>
                        </td>

                        {{-- KODE --}}
                        <td class="py-3 px-4 font-mono text-sm">
                            POD-{{ date('Ymd', strtotime($podcast->tanggal)) }}{{ $podcast->id }}
                        </td>

                        {{-- TANGGAL --}}
                        <td class="py-3 px-4 text-sm">
                            {{ optional($podcast->tanggal)->format('d/m/Y') }}

                            @if($podcast->kalender && $podcast->kalender->waktu)
                            <br>
                            <span class="text-xs text-gray-500">
                            {{ $podcast->kalender->waktu }}
                            </span>
                        @endif
                        </td>

                        {{-- OPD --}}
                        <td class="py-3 px-4 text-sm">
                            {{ $podcast->nama_opd }}
                        </td>

                        {{-- JUDUL --}}
                        <td class="py-3 px-4 text-sm max-w-xs break-words whitespace-normal">
                            {{ Str::limit($podcast->keterangan, 60) }}
                        </td>

                        {{-- NARASUMBER --}}
                        <td class="py-3 px-4 text-sm">
                            {{ $podcast->narasumber }}
                        </td>

                        {{-- HOST --}}
                        <td class="py-3 px-4 text-sm">
                            {{ $podcast->host ?? '-' }}
                        </td>

                        {{-- COVER --}}
                        <td class="py-3 px-4 text-sm">
                            @if($podcast->cover_path)
                                <a href="{{ asset($podcast->cover_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800">Lihat</a>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="py-16 text-center text-gray-400">Belum ada data podcast</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
