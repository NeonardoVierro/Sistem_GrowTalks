@extends('layouts.verifikator-podcast')

@section('title', 'Approval Podcast')

@section('content')
<div class="p-8">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Approval Podcast</h1>
        <p class="text-gray-600 italic">Verifikasi pengajuan podcast dari user</p>
    </div>

    <!-- Tabel Antrian Verifikasi Podcast -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div>

            <table class="w-full">

                <!-- HEADER -->
                <thead class="bg-blue-900 text-white text-left">
                    <tr>
                        <th class="py-3 px-4 text-sm">Aksi</th>
                        <th class="py-3 px-4 text-sm">Status</th>
                        <th class="py-3 px-4 text-sm">Kode Booking</th>
                        <th class="py-3 px-4 text-sm">Tanggal</th>
                        <th class="py-3 px-4 text-sm">Judul</th>
                        <th class="py-3 px-4 text-sm">Narasumber</th>
                        <th class="py-3 px-4 text-sm">Keterangan</th>
                        <th class="py-3 px-4 text-sm">Cover</th>
                    </tr>
                </thead>

                <!-- BODY -->
                <tbody class="divide-y divide-gray-200 text-sm">

                    @forelse($podcasts as $podcast)
                    <tr class="hover:bg-gray-50 transition">

                        {{-- AKSI --}}
                        <td class="py-3 px-4 flex justify-center items-center">
                            <a href="{{ route('verifikator-podcast.approval-form', $podcast->id) }}" 
                               class="text-blue-700 hover:text-blue-800" title="Verifikasi">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>

                        {{-- STATUS --}}
                        <td class="py-3 px-4 text-center w-[110px]">
                            @php
                                $status = strtolower($podcast->status_verifikasi);
                                switch($status) {
                                    case 'disetujui':
                                        $bg = 'bg-green-100 text-green-800';
                                        break;
                                    case 'pending':
                                        $bg = 'bg-yellow-100 text-yellow-800';
                                        break;
                                    case 'ditolak':
                                        $bg = 'bg-red-100 text-red-800';
                                        break;
                                    case 'penjadwalan ulang':
                                        $bg = 'bg-purple-100 text-purple-800';
                                        break;
                                    default:
                                        $bg = 'bg-gray-100 text-gray-800';
                                }
                            @endphp
                            <span class="inline-flex items-center justify-center w-fit mx-auto
                                        text-[10px] px-2 py-0.5 rounded-full font-medium
                                        whitespace-normal break-words text-center {{ $bg }}">
                                {{ ucfirst($podcast->status_verifikasi) }}
                            </span>
                        </td>

                        {{-- KODE --}}
                        <td class="py-3 px-4 font-mono text-sm">
                            POD-{{ date('Ymd', strtotime($podcast->tanggal)) }}{{ $podcast->id }}
                        </td>

                        {{-- TANGGAL --}}
                        <td class="py-3 px-4 text-sm whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($podcast->tanggal)->locale('id')->isoFormat('D MMMM YYYY') }}

                            @if($podcast->waktu)
                                <div class="text-xs text-gray-500">
                                    {{ $podcast->waktu }}
                                </div>
                            @endif

                            @if($podcast->host)
                                <div class="text-xs text-gray-500">
                                    Host: {{ $podcast->host }}
                                </div>
                            @endif
                        </td>

                        {{-- JUDUL --}}
                        <td class="py-3 px-4 text-sm max-w-[260px] break-words whitespace-normal">
                            {{ $podcast->keterangan }}
                        </td>

                        {{-- NARASUMBER --}}
                        <td class="py-3 px-4 text-sm">
                            {{ $podcast->narasumber }}
                        </td>

                        {{-- KETERANGAN --}}
                        <td class="py-3 px-4 text-xs text-gray-600 max-w-[160px] break-words whitespace-normal">
                            {{ $podcast->catatan ?? '-' }}
                        </td>
                        <td class="py-3 px-4"> 
                            @if($podcast->cover_path) 
                            <a href="{{ asset($podcast->cover_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800"> 
                                <i class="fas fa-link mr-1"></i>Unggah </a> 
                            @else 
                            <span class="text-gray-400">-</span> 
                            @endif 
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-16 text-center text-gray-400">
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