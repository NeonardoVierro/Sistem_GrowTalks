@extends('layouts.verifikator-coaching')

@section('title', 'Laporan Coaching Clinic')

@section('content')
<div class="p-8">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Laporan Coaching Clinic</h1>
        <p class="text-gray-600 italic">Laporan dan statistik coaching clinic</p>
    </div>

    <!-- Report Container -->
   <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800">Laporan Coaching Clinic</h2>
        </div>

        <!-- Filter -->
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-green-500 focus:ring-1 focus:ring-green-500"
                           placeholder="Cari...">
                </div>
                <div class="w-40">
                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-green-500 focus:ring-1 focus:ring-green-500">
                        <option value="">Status</option>
                        <option value="pending">Pending</option>
                        <option value="disetujui">Disetujui</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>
                <div class="w-32">
                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-green-500 focus:ring-1 focus:ring-green-500">
                        <option value="">Tahun</option>
                        <option value="2026">2026</option>
                        <option value="2025">2025</option>
                        <option value="2024">2024</option>
                    </select>
                </div>
                <button class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Filter
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <thead class="bg-blue-900 text-white">
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
                <td class="py-3 px-4">
                        @if($coaching->dokumentasi_path)
                            <div class="flex items-center gap-2">
                                <a href="{{ asset('storage/'.$coaching->dokumentasi_path) }}" 
                                target="_blank"
                                class="text-green-600 hover:text-green-800 text-sm">
                                    <i class="fas fa-image mr-1"></i>Lihat
                                </a>

                                <form action="{{ route('verifikator-coaching.upload', $coaching->id) }}"
                                    method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <label class="cursor-pointer text-blue-600 hover:text-blue-800 text-sm">
                                        <i class="fas fa-upload mr-1"></i>Ganti
                                        <input type="file"
                                            name="dokumentasi"
                                            accept="image/*"
                                            class="hidden"
                                            onchange="this.form.submit()">
                                    </label>
                                </form>
                            </div>
                        @else
                            <form action="{{ route('verifikator-coaching.upload', $coaching->id) }}"
                                method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <label class="cursor-pointer text-green-600 hover:text-green-800 text-sm">
                                    <i class="fas fa-upload mr-1"></i>Unggah
                                    <input type="file"
                                        name="dokumentasi"
                                        accept="image/*"
                                        class="hidden"
                                        onchange="this.form.submit()">
                                </label>
                            </form>
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