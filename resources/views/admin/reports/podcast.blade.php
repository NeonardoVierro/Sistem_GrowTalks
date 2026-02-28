@extends('layouts.admin')

@section('title', 'Laporan Podcast')

@section('content')

<div class="space-y-6">

    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Laporan Podcast</h1>
            <p class="text-gray-600">Analisis dan statistik podcast</p>
        </div>
    </div>

    <!-- Report Table -->
    <div class="bg-white rounded-xl shadow-xl hover:shadow-2xl transition duration-300 overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-bold text-gray-800">
                Detail Laporan Podcast
            </h2>
        </div>

        <!-- Filter -->
        <div class="p-6 border-b border-gray-200">
            <form method="GET" action="{{ route('admin.reports.podcast') }}" class="flex flex-wrap gap-4">
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
                <div class="w-45">
                    <input type="date" 
                           name="start_date"
                           value="{{ request('start_date') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>
                <div class="w-45">
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

        <!-- DESKTOP TABLE -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full">
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

                <tbody class="divide-y divide-gray-200">
                    @foreach($podcasts as $podcast)
                    <tr>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 text-xs rounded-full {{ 
                                $podcast->status_verifikasi == 'disetujui' ? 'bg-green-100 text-green-800' :
                                ($podcast->status_verifikasi == 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                ($podcast->status_verifikasi == 'ditolak' ? 'bg-red-100 text-red-800' :
                                'bg-purple-100 text-purple-800')) }}">
                                {{ ucfirst($podcast->status_verifikasi) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-sm font-mono">
                            POD-{{ date('Ymd', strtotime($podcast->tanggal)) }}{{ $podcast->id }}
                        </td>
                        <td class="py-3 px-4 text-sm">{{ $podcast->tanggal->format('d/m/Y') }}</td>
                        <td class="py-3 px-4 text-sm">{{ $podcast->nama_opd }}</td>
                        <td class="py-3 px-4 text-sm max-w-[220px] break-words">{{ $podcast->keterangan }}</td>
                        <td class="py-3 px-4 text-sm">{{ $podcast->narasumber }}</td>
                        <td class="py-3 px-4 text-sm">{{ $podcast->host ?? '-' }}</td>
                        <td class="py-3 px-4">
                            @if($podcast->cover_path)
                                <a href="{{ asset('storage/'.$podcast->cover_path) }}" target="_blank"
                                class="text-green-600">
                                <i class="fas fa-image"></i>
                                </a>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- MOBILE PREMIUM CARD -->
        <div class="md:hidden space-y-5">
            @forelse($podcasts as $podcast)

            @php
                $status = strtolower($podcast->status_verifikasi);
                $color = match($status) {
                    'disetujui' => 'bg-green-100 text-green-700',
                    'pending' => 'bg-yellow-100 text-yellow-700',
                    'ditolak' => 'bg-red-100 text-red-700',
                    'penjadwalan ulang' => 'bg-purple-100 text-purple-700',
                    default => 'bg-gray-100 text-gray-700',
                };
            @endphp

            <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-5 space-y-4">

                <!-- Header -->
                <div class="flex justify-between items-start">

                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">
                            Kode Booking
                        </p>
                        <p class="font-mono text-sm text-gray-700">
                            POD-{{ date('Ymd', strtotime($podcast->tanggal)) }}{{ $podcast->id }}
                        </p>
                    </div>

                    <span class="px-3 py-1 text-xs rounded-full font-medium {{ $color }}">
                        {{ ucfirst($podcast->status_verifikasi) }}
                    </span>
                </div>

                <!-- Judul -->
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">
                        Judul Podcast
                    </p>
                    <h3 class="font-semibold text-gray-800 text-sm leading-snug">
                        {{ $podcast->keterangan }}
                    </h3>
                </div>

                <!-- Divider -->
                <div class="border-t border-gray-100"></div>

                <!-- Detail Grid -->
                <div class="grid grid-cols-2 gap-4 text-sm">

                    <div>
                        <p class="text-gray-400 text-xs">Tanggal</p>
                        <p class="font-medium text-gray-700">
                            {{ $podcast->tanggal->format('d/m/Y') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-400 text-xs">Instansi</p>
                        <p class="font-medium text-gray-700 truncate">
                            {{ $podcast->nama_opd }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-400 text-xs">Narasumber</p>
                        <p class="font-medium text-gray-700 truncate">
                            {{ $podcast->narasumber }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-400 text-xs">Host</p>
                        <p class="font-medium text-gray-700">
                            {{ $podcast->host ?? '-' }}
                        </p>
                    </div>

                </div>

                @if($podcast->cover_path)
                <div class="pt-3">
                    <a href="{{ asset('storage/'.$podcast->cover_path) }}"
                    target="_blank"
                    class="flex items-center justify-center w-full py-2 rounded-lg
                            bg-gray-50 hover:bg-gray-100 text-gray-700 text-sm font-medium transition">
                        <i class="fas fa-image mr-2"></i> Lihat Cover
                    </a>
                </div>
                @endif

            </div>

            @empty
            <div class="text-center text-gray-400 py-12">
                <i class="fas fa-database text-3xl mb-3"></i>
                <p>Belum ada data</p>
            </div>
            @endforelse
        </div>
    </div>

</div>

@endsection
