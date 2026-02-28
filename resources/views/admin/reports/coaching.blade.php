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
    </div>

    <!-- Report Table -->
    <div class="bg-white rounded-xl shadow-xl hover:shadow-2xl transition duration-300 overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-bold text-gray-800">
                Detail Laporan Coaching Clinic
            </h2>
        </div>

        <!-- Filter -->
        <div class="p-6 border-b border-gray-200">
            <form method="GET" action="{{ route('admin.reports.coaching') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" 
                           name="search"
                           value="{{ request('search') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-green-500 focus:ring-1 focus:ring-green-500"
                           placeholder="Cari kategori, agenda, coach, atau instansi...">
                </div>
                <div class="w-50">
                    <select name="status"
                            class="w-full px-1 py-2 border border-gray-300 rounded-lg focus:border-green-500 focus:ring-1 focus:ring-green-500">
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
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-green-500 focus:ring-1 focus:ring-green-500">
                </div>
                <div class="w-45">
                    <input type="date" 
                           name="end_date"
                           value="{{ request('end_date') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-green-500 focus:ring-1 focus:ring-green-500">
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
                        <th class="py-3 px-4 text-left text-sm">Kategori</th>
                        <th class="py-3 px-4 text-left text-sm">Agenda</th>
                        <th class="py-3 px-4 text-left text-sm">Coach</th>
                        <th class="py-3 px-4 text-left text-sm">Dokumentasi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @foreach($coachings as $coaching)
                    <tr>
                        <td class="py-3 px-4">
                            @php
                                $status = strtolower($coaching->status_verifikasi);
                                $color = match($status) {
                                    'disetujui' => 'bg-green-100 text-green-700',
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'ditolak' => 'bg-red-100 text-red-700',
                                    'penjadwalan ulang' => 'bg-purple-100 text-purple-700',
                                    default => 'bg-gray-100 text-gray-700',
                                };
                            @endphp
                            <span class="px-2 py-1 text-xs rounded-full {{ $color }}">
                                {{ ucfirst($coaching->status_verifikasi) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 font-mono text-sm">
                            CCA-{{ date('Ymd', strtotime($coaching->tanggal)) }}{{ $coaching->id }}
                        </td>
                        <td class="py-3 px-4 text-sm">{{ $coaching->tanggal->format('d/m/Y') }}</td>
                        <td class="py-3 px-4 text-sm">{{ $coaching->nama_opd }}</td>
                        <td class="py-3 px-4 text-sm">{{ $coaching->layanan }}</td>
                        <td class="py-3 px-4 text-sm max-w-[220px] break-words">
                            {{ Str::limit($coaching->keterangan, 50) }}
                        </td>
                        <td class="py-3 px-4 text-sm">{{ $coaching->coach ?? '-' }}</td>
                        <td class="py-3 px-4 text-center">
                            @if($coaching->dokumentasi_path)
                                <a href="{{ asset('storage/'.$coaching->dokumentasi_path) }}" target="_blank"
                                class="text-green-600 hover:text-green-800">
                                <i class="fas fa-image text-lg"></i>
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
            @forelse($coachings as $coaching)

            @php
                $status = strtolower($coaching->status_verifikasi);
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
                            CCA-{{ date('Ymd', strtotime($coaching->tanggal)) }}{{ $coaching->id }}
                        </p>
                    </div>

                    <span class="px-3 py-1 text-xs rounded-full font-medium {{ $color }}">
                        {{ ucfirst($coaching->status_verifikasi) }}
                    </span>
                </div>

                <!-- Agenda -->
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">
                        Agenda Coaching
                    </p>
                    <h3 class="font-semibold text-gray-800 text-sm leading-snug">
                        {{ $coaching->keterangan }}
                    </h3>
                </div>

                <div class="border-t border-gray-100"></div>

                <!-- Detail Grid -->
                <div class="grid grid-cols-2 gap-4 text-sm">

                    <div>
                        <p class="text-gray-400 text-xs">Tanggal</p>
                        <p class="font-medium text-gray-700">
                            {{ $coaching->tanggal->format('d/m/Y') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-400 text-xs">Instansi</p>
                        <p class="font-medium text-gray-700 truncate">
                            {{ $coaching->nama_opd }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-400 text-xs">Kategori</p>
                        <p class="font-medium text-gray-700">
                            {{ $coaching->layanan }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-400 text-xs">Coach</p>
                        <p class="font-medium text-gray-700">
                            {{ $coaching->coach ?? '-' }}
                        </p>
                    </div>

                </div>

                @if($coaching->dokumentasi_path)
                <div class="pt-3">
                    <a href="{{ asset('storage/'.$coaching->dokumentasi_path) }}"
                    target="_blank"
                    class="flex items-center justify-center w-full py-2 rounded-lg
                            bg-gray-50 hover:bg-gray-100 text-gray-700 text-sm font-medium transition">
                        <i class="fas fa-image mr-2"></i> Lihat Dokumentasi
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
