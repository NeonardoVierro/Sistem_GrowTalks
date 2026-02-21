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

        <div class="p-4">
            <div class="{{ $coachings->count() > 10 ? 'max-h-96 overflow-y-auto' : '' }}">
            <table class="w-full table-fixed">

                <thead class="bg-blue-900 text-white sticky top-0 z-10">
                    <tr>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Status</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Kode Booking</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Tanggal</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Instansi</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Kategori</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Agenda</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Coach</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Dokumentasi</th>
                    </tr>
                </thead>


                <tbody class="divide-y divide-gray-200">
                    @forelse($coachings as $coaching)
                    <tr class="table-row">
                        <td class="py-3 px-4">
                            @php
                                $status = strtolower($coaching->status_verifikasi);
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
                                {{ ucfirst($coaching->status_verifikasi) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 font-mono text-sm">
                             CCA-{{ date('Ymd', strtotime($coaching->tanggal)) }}{{ $coaching->id }}
                        </td>
                        <td class="py-3 px-4 text-sm">
                            {{ $coaching->tanggal->format('d/m/Y') }}
                        </td>
                        <td class="py-3 px-4 text-sm">{{ $coaching->nama_opd }}</td>
                        <td class="py-3 px-4 text-sm">{{ $coaching->layanan }}</td>
                        <td class="py-3 px-4 text-sm">{{ Str::limit($coaching->keterangan) }}</td>
                        <td class="py-3 px-4 text-sm">{{ $coaching->coach ?? '-' }}</td>
                        <td class="py-3 px-4 text-center">
                                @if($coaching->dokumentasi_path)
                                        <a href="{{ asset('storage/'.$coaching->dokumentasi_path) }}" 
                                        target="_blank"
                                        class="inline-flex items-center justify-center text-green-600 hover:text-green-800">
                                            <i class="fas fa-image text-lg"></i>
                                        </a>
                                @else
                                    <span class="text-gray-500 text-sm">-</span>
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
