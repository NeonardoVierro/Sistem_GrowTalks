@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="p-6 space-y-6">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Admin</h1>
            <p class="text-gray-600 italic">Monitoring sistem layanan digital</p>
        </div>

        <div class="text-sm text-gray-500">
            {{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
        </div>
    </div>
<!-- Tabel Data Terbaru -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Podcast Terbaru -->
        <div class="card-stat bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
            <div class="flex justify-between mb-4">
                <h2 class="text-lg font-bold">Podcast Terbaru</h2>
                <a href="{{ route('admin.podcasts') }}" class="text-blue-600">Lihat Semua</a>
            </div>

            <div class="shadow-md rounded-lg overflow-hidden">
                <div class="{{ $recentPodcasts->count() > 7 ? 'max-h-72 overflow-y-auto' : '' }}">
                <table class="w-full text-sm table-fixed">
                     <thead class="bg-blue-900 text-white">
                        <tr>
                            <th class="p-3 text-left">Tanggal</th>
                            <th class="p-3 text-left">Judul</th>
                            <th class="p-3 text-left">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                    @forelse($recentPodcasts as $podcast)
                        <tr class="border-b hover:bg-gray-100 transition duration-200">
                            <td class="p-3">
                                {{ $podcast->tanggal->format('d/m/Y') }}
                            </td>

                            <td class="p-3">
                                {{ Str::limit($podcast->keterangan, 25) }}
                            </td>

                            <td class="p-3">
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
                                <span class="inline-flex items-center justify-center text-[10px] px-2 py-0.5
                                            rounded-full font-medium leading-tight text-center
                                            whitespace-normal break-words {{ $bg }}">
                                    {{ ucfirst($podcast->status_verifikasi) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-6 text-center text-gray-500">
                                Tidak ada data podcast
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                </div>
            </div>

        </div>

        <!-- Coaching Terbaru -->
       <div class="card-stat bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
            <div class="flex justify-between mb-4">
                <h2 class="text-lg font-bold">Coaching Terbaru</h2>
                <a href="{{ route('admin.coachings') }}" class="text-blue-600">Lihat Semua</a>
            </div>

            <div class="shadow-md rounded-lg overflow-hidden">
                <div class="{{ $recentCoachings->count() > 7 ? 'max-h-72 overflow-y-auto' : '' }}">
                <table class="w-full text-sm table-fixed">
                     <thead class="bg-blue-900 text-white">
                        <tr>
                            <th class="p-3 text-left">Tanggal</th>
                            <th class="p-3 text-left">Layanan</th>
                            <th class="p-3 text-left">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                    @forelse($recentCoachings as $coaching)
                        <tr class="border-b hover:bg-gray-100 transition duration-200">
                            <td class="p-3">
                                {{ $coaching->tanggal->format('d/m/Y') }}
                            </td>

                            <td class="p-3">
                                {{ $coaching->layanan }}
                            </td>
                            <td class="p-3">
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
                                <span class="inline-flex items-center justify-center text-[10px] px-2 py-0.5
                                            rounded-full font-medium leading-tight text-center
                                            whitespace-normal break-words {{ $bg }}">
                                    {{ ucfirst($coaching->status_verifikasi) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-6 text-center text-gray-500">
                                Tidak ada data coaching
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                </div>
            </div>

        </div>

    </div>

<!-- Status Statistik -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Statistik Podcast -->
       <div class="card-stat bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
            <h2 class="text-lg font-bold mb-4">Statistik Podcast</h2>

            <div class="space-y-3">

                <div class="flex justify-between items-center bg-yellow-50 p-3 rounded shadow-sm">
                    <span>Menunggu Persetujuan</span>
                    <span class="font-bold">{{ $stats['podcast_pending'] }}</span>
                </div>

                <div class="flex justify-between items-center bg-green-50 p-3 rounded shadow-sm">
                    <span>Disetujui</span>
                    <span class="font-bold">{{ $stats['podcast_approved'] }}</span>
                </div>

                <div class="flex justify-between items-center bg-red-50 p-3 rounded shadow-sm">
                    <span>Ditolak</span>
                    <span class="font-bold">{{ $stats['podcast_rejected'] }}</span>
                </div>

            </div>
        </div>

        <!-- Statistik Coaching -->
        <div class="card-stat bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
            <h2 class="text-lg font-bold mb-4">Statistik Coaching Clinic</h2>

            <div class="space-y-3">

                <div class="flex justify-between items-center bg-yellow-50 p-3 rounded shadow-sm">
                    <span>Menunggu Persetujuan</span>
                    <span class="font-bold">{{ $stats['coaching_pending'] }}</span>
                </div>

                <div class="flex justify-between items-center bg-green-50 p-3 rounded shadow-sm">
                    <span>Disetujui</span>
                    <span class="font-bold">{{ $stats['coaching_approved'] }}</span>
                </div>

                <div class="flex justify-between items-center bg-red-50 p-3 rounded shadow-sm">
                    <span>Ditolak</span>
                    <span class="font-bold">{{ $stats['coaching_rejected'] }}</span>
                </div>

            </div>
        </div>

    </div>

    <!-- Statistik Utama -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition duration-300 p-5 flex items-center">
            <div class="p-3 bg-blue-100 rounded-lg mr-4 shadow">
                <i class="fas fa-users text-blue-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total User</p>
                <p class="text-2xl font-bold">{{ $stats['total_users'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition duration-300 p-5 flex items-center">
            <div class="p-3 bg-purple-100 rounded-lg mr-4 shadow">
                <i class="fas fa-podcast text-purple-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Podcast</p>
                <p class="text-2xl font-bold">{{ $stats['total_podcasts'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition duration-300 p-5 flex items-center">
            <div class="p-3 bg-green-100 rounded-lg mr-4 shadow">
                <i class="fas fa-chalkboard-teacher text-green-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Coaching</p>
                <p class="text-2xl font-bold">{{ $stats['total_coachings'] }}</p>
            </div>
        </div>

    </div>


</div>
@endsection
