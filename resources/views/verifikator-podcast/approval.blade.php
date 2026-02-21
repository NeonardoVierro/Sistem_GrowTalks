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
        <!-- Filter -->
        <div class="p-6 border-b border-gray-200">
            <form method="GET" action="{{ route('verifikator-podcast.approval') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" 
                           name="search"
                           value="{{ request('search') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                           placeholder="Cari judul, narasumber, host, atau instansi...">
                </div>
                <div class="w-50">
                    <select name="status"
                            class="w-full px-1 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        <option value="penjadwalan ulang" {{ request('status') == 'penjadwalan ulang' ? 'selected' : '' }}>Penjadwalan Ulang</option>
                    </select>
                </div>
                <div class="w-43">
                    <input type="date" 
                           name="start_date"
                           value="{{ request('start_date') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>
                <div class="w-43">
                    <input type="date" 
                           name="end_date"
                           value="{{ request('end_date') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Filter
                </button>
            </form>
        </div>

        <div class="{{ $podcasts->count() > 10 ? 'overflow-y-auto max-h-96' : '' }}">

            <table class="w-full">

                <!-- HEADER -->
                <thead class="bg-blue-900 text-white text-left sticky top-0 z-10">
                    <tr>
                        <th class="py-3 px-4 text-sm">Aksi</th>
                        <th class="py-3 px-4 text-sm">Status</th>
                        <th class="py-3 px-4 text-sm">Kode Booking</th>
                        <th class="py-3 px-4 text-sm">Jadwal</th>
                        <th class="py-3 px-4 text-sm">Judul</th>
                        <th class="py-3 px-4 text-sm">Narasumber</th>
                        <th class="py-3 px-4 text-sm">Keterangan</th>
                        <th class="py-3 px-4 text-sm">Host</th>
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

                        {{-- HOST --}}
                        <td class="py-3 px-4 text-sm">
                            {{ $podcast->host ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-16 text-center text-gray-400">
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