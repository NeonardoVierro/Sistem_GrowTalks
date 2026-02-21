@extends('layouts.verifikator-podcast')

@section('title', 'Dashboard Verifikator Podcast')

@section('content')
<div class="p-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard Verifikator Podcast</h1>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Podcast -->
          <div class="card-stat bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg mr-4">
                    <i class="fas fa-podcast text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Podcast</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalPodcasts }}</p>
                    <p class="text-xs text-gray-500 mt-1">Total Pengajuan</p>
                </div>
            </div>
        </div>

        <!-- Menunggu Persetujuan -->
          <div class="card-stat bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-lg mr-4">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Menunggu Persetujuan</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $pendingPodcasts }}</p>
                    <p class="text-xs text-gray-500 mt-1">Podcast Pending</p>
                </div>
            </div>
        </div>

        <!-- Disetujui -->
        <div class="card-stat bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg mr-4">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Disetujui</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $approvedPodcasts }}</p>
                    <p class="text-xs text-gray-500 mt-1">Podcast Disetujui</p>
                </div>
            </div>
        </div>

        <!-- Ditolak -->
         <div class="card-stat bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-lg mr-4">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Ditolak</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $rejectedPodcasts }}</p>
                    <p class="text-xs text-gray-500 mt-1">Podcast Ditolak</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar Section -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
        <div class="px-6 py-12 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-800">
                <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>
                Kalender Jadwal Podcast {{ \Carbon\Carbon::create($year, $month, 1)->locale('id')->isoFormat('MMMM YYYY') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('verifikator-podcast.dashboard', ['month' => $month - 1 <= 0 ? 12 : $month - 1, 'year' => $month - 1 <= 0 ? $year - 1 : $year]) }}" 
                   class="px-3 py-1 border border-gray-300 rounded-lg hover:bg-gray-50">
                    <i class="fas fa-chevron-left"></i>
                </a>
                <a href="{{ route('verifikator-podcast.dashboard', ['month' => date('m'), 'year' => date('Y')]) }}" 
                   class="px-3 py-1 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Bulan Ini
                </a>
                <a href="{{ route('verifikator-podcast.dashboard', ['month' => $month + 1 > 12 ? 1 : $month + 1, 'year' => $month + 1 > 12 ? $year + 1 : $year]) }}" 
                   class="px-3 py-1 border border-gray-300 rounded-lg hover:bg-gray-50">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </div>

        <div class="p-6">
            <!-- Days Header -->
            <div class="grid grid-cols-7 gap-2 mb-2">
                @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $day)
                    <div class="text-center font-medium text-gray-700 py-2 {{ $day == 'Jumat' ? 'text-green-600 font-bold' : '' }}">
                        {{ $day }}
                    </div>
                @endforeach
            </div>

            <!-- Calendar Grid -->
            <div class="grid grid-cols-7 gap-2">
                @php
                    $today = Carbon\Carbon::today();
                @endphp

                @foreach($calendar as $week)
                    @foreach($week as $dayData)
                        @php
                            $date = $dayData['date'];
                            $dateString = $dayData['date_string'];
                            $isCurrentMonth = $dayData['is_current_month'];
                            $isFriday = $dayData['is_friday'];
                            $isPast = $date->lt($today);
                            $isToday = $date->isToday();
                            
                            // Check if this date has bookings
                            $hasBookings = isset($bookings[$dateString]) && $bookings[$dateString]->isNotEmpty();
                            
                            // Get booking status for this date
                            $bookingStatus = null;
                            $bookingCount = 0;
                            $statusColors = [];
                            
                            if ($hasBookings) {
                                $bookingCount = $bookings[$dateString]->count();
                                
                                // Get unique statuses for this date
                                $statuses = $bookings[$dateString]->pluck('status_verifikasi')->unique();
                                
                                foreach ($statuses as $status) {
                                    switch(strtolower($status)) {
                                        case 'disetujui':
                                            $statusColors[] = 'bg-green-500';
                                            break;
                                        case 'pending':
                                            $statusColors[] = 'bg-yellow-500';
                                            break;
                                        case 'ditolak':
                                            $statusColors[] = 'bg-red-500';
                                            break;
                                        case 'penjadwalan ulang':
                                            $statusColors[] = 'bg-purple-500';
                                            break;
                                    }
                                }
                            }

                            // Determine cell style
                            if (!$isCurrentMonth) {
                                $bgClass = 'bg-gray-50 text-gray-400';
                            } elseif ($hasBookings) {
                                $bgClass = 'bg-blue-50 border-blue-200 cursor-pointer hover:bg-blue-100';
                            } elseif ($isFriday && !$isPast) {
                                $bgClass = 'bg-green-50 border-green-200';
                            } elseif ($isFriday && $isPast) {
                                // Jumat yang sudah lewat, tetap abu-abu tapi tetap bisa diklik jika ada bookings
                                $bgClass = 'bg-gray-100 text-gray-400';
                            } else {
                                // Hari selain Jumat, warna abu-abu
                                $bgClass = 'bg-gray-100 text-gray-400';
                            }
                            
                            if ($isToday) {
                                $bgClass .= ' border-2 border-blue-600';
                            }
                        @endphp

                        <div class="relative h-28 p-2 border rounded-lg {{ $bgClass }} {{ $hasBookings ? 'cursor-pointer' : '' }}"
                             @if($hasBookings)
                             onclick="showBookingsDetail('{{ $dateString }}', '{{ \Carbon\Carbon::parse($date)->locale('id')->isoFormat('D MMMM YYYY') }}', {{ json_encode($bookings[$dateString] ?? []) }})"
                             @endif>
                            
                            <div class="flex justify-between items-start">
                                <span class="font-semibold text-sm {{ $isToday ? 'text-blue-600' : '' }}">
                                    {{ $dayData['day'] }}
                                </span>
                                
                                @if($hasBookings)
                                    <span class="text-xs bg-blue-600 text-white px-1.5 py-0.5 rounded-full">
                                        {{ $bookingCount }}
                                    </span>
                                @endif
                            </div>
                            
                            @if($hasBookings)
                                <div class="mt-2 space-y-1">
                                    @foreach($bookings[$dateString]->take(2) as $booking)
                                        <div class="text-xs truncate {{ $booking->status_verifikasi == 'disetujui' ? 'text-green-700' : ($booking->status_verifikasi == 'pending' ? 'text-yellow-700' : 'text-gray-600') }}">
                                            <i class="fas fa-circle text-[8px] mr-1 
                                                {{ $booking->status_verifikasi == 'disetujui' ? 'text-green-500' : 
                                                   ($booking->status_verifikasi == 'pending' ? 'text-yellow-500' : 
                                                   ($booking->status_verifikasi == 'ditolak' ? 'text-red-500' : 'text-purple-500')) }}">
                                            </i>
                                            {{ Str::limit($booking->keterangan, 15) }}
                                        </div>
                                    @endforeach
                                    @if($bookings[$dateString]->count() > 2)
                                        <div class="text-xs text-gray-500">
                                            +{{ $bookings[$dateString]->count() - 2 }} lagi
                                        </div>
                                    @endif
                                </div>
                    
                            @elseif($isFriday && $isCurrentMonth && !$isPast)
                                <div class="mt-2 text-xs text-green-600">
                                    <i class="fas fa-calendar-plus"></i> Tersedia
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endforeach
            </div>

            <!-- Legend -->
            <div class="mt-4 flex flex-wrap gap-4 text-sm">
                <div class="flex items-center">
                    <div class="w-3 h-3 rounded-full bg-green-500 mr-1"></div>
                    <span class="mr-3">Disetujui</span>
                    <div class="w-3 h-3 rounded-full bg-yellow-500 mr-1"></div>
                    <span class="mr-3">Pending</span>
                    <div class="w-3 h-3 rounded-full bg-red-500 mr-1"></div>
                    <span class="mr-3">Ditolak</span>
                    <div class="w-3 h-3 rounded-full bg-purple-500 mr-1"></div>
                    <span>Penjadwalan Ulang</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Podcasts -->
       <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800">Podcast Terbaru</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <thead class="bg-blue-900 text-white">
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Kode</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Jadwal</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Instansi</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Judul</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Narasumber</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($recentPodcasts as $podcast)
                    <tr class="table-row">
                        <td class="py-3 px-4 font-mono text-sm">
                            POD-{{ date('Ymd', strtotime($podcast->tanggal)) }}{{ $podcast->id }}
                        </td>
                        <td class="py-3 px-4 text-sm">
                            {{ $podcast->tanggal->format('d/m/Y') }}
                        </td>
                        <td class="py-3 px-4 text-sm">{{ $podcast->nama_opd }}</td>
                        <td class="py-3 px-4">
                            <div class="font-medium text-gray-800 text-sm">{{ Str::limit($podcast->keterangan) }}</div>
                        </td>
                        <td class="py-3 px-4 text-sm">{{ $podcast->narasumber }}</td>
                        <td class="py-3 px-4">
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
                                        rounded-full font-medium leading-tight text-center whitespace-normal break-words {{ $bg }}">
                                    {{ ucfirst($podcast->status_verifikasi) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-500">
                            Belum ada data podcast
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8 mb-7">
        <!-- Approval Statistics -->
           <div class="card-stat bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Statistik Approval</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Menunggu Verifikasi</span>
                    <span class="font-bold">{{ $pendingPodcasts }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Sudah Diverifikasi</span>
                    <span class="font-bold">{{ $approvedPodcasts + $rejectedPodcasts }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Persentase Disetujui</span>
                    <span class="font-bold">
                        {{ $totalPodcasts > 0 ? round(($approvedPodcasts / $totalPodcasts) * 100, 1) : 0 }}%
                    </span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
           <div class="card-stat bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition mb-7">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Aksi Cepat</h3>
            <div class="space-y-3">
                <a href="{{ route('verifikator-podcast.approval') }}" 
                   class="block w-full text-center py-3 px-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-check-circle mr-2"></i>Verifikasi Podcast
                </a>
                <a href="{{ route('verifikator-podcast.report') }}" 
                   class="block w-full text-center py-3 px-4 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                    <i class="fas fa-file-alt mr-2"></i>Lihat Laporan
                </a>
            </div>
        </div>
    </div>

    
</div>
<!-- Detail Bookings Modal -->
<div id="bookingsDetailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg w-full max-w-2xl relative">
        <div class="p-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800" id="modalDateTitle">Detail Jadwal Podcast</h3>
            <button onclick="closeBookingsModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="p-4 max-h-96 overflow-y-auto" id="bookingsDetailContent">
            <!-- Content will be filled by JavaScript -->
        </div>

        <div class="p-4 border-t border-gray-200 flex justify-end">
            <button onclick="closeBookingsModal()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Tutup
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showBookingsDetail(dateString, displayDate, bookings) {
    const modalTitle = document.getElementById('modalDateTitle');
    const modalContent = document.getElementById('bookingsDetailContent');
    
    modalTitle.textContent = `Detail Jadwal Podcast - ${displayDate}`;
    
    let html = `
        <div class="space-y-4">
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            Total ${bookings.length} jadwal podcast pada tanggal ini
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="space-y-3">
    `;
    
    bookings.forEach((booking, index) => {
        let statusClass = '';
        let statusIcon = '';
        
        switch(booking.status_verifikasi.toLowerCase()) {
            case 'disetujui':
                statusClass = 'bg-green-100 text-green-800';
                statusIcon = 'fa-check-circle';
                break;
            case 'pending':
                statusClass = 'bg-yellow-100 text-yellow-800';
                statusIcon = 'fa-clock';
                break;
            case 'ditolak':
                statusClass = 'bg-red-100 text-red-800';
                statusIcon = 'fa-times-circle';
                break;
            case 'penjadwalan ulang':
                statusClass = 'bg-purple-100 text-purple-800';
                statusIcon = 'fa-redo';
                break;
        }
        
        html += `
            <div class="border rounded-lg p-4 hover:shadow-md transition cursor-pointer hover:bg-blue-50" onclick="window.location.href='/verifikator-podcast/approval/${booking.id}/form'">
                <div class="flex justify-between items-start mb-2">
                    <span class="font-mono text-sm bg-gray-100 px-2 py-1 rounded">
                        POD-${new Date(booking.tanggal).toISOString().slice(0,10).replace(/-/g, '')}${booking.id}
                    </span>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${statusClass}">
                        <i class="fas ${statusIcon} mr-1"></i>
                        ${booking.status_verifikasi}
                    </span>
                </div>
                
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <span class="text-gray-500">Instansi:</span>
                        <span class="font-medium ml-1">${booking.nama_opd || '-'}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Narasumber:</span>
                        <span class="font-medium ml-1">${booking.narasumber || '-'}</span>
                    </div>
                    <div class="col-span-2">
                        <span class="text-gray-500">Judul:</span>
                        <a href="/verifikator-podcast/approval/${booking.id}/form" class="font-medium ml-1 text-blue-600 hover:text-blue-800 hover:underline cursor-pointer" title="Klik untuk ke halaman verifikasi">
                            ${booking.keterangan || '-'}
                        </a>
                    </div>
                    <div>
                        <span class="text-gray-500">Waktu:</span>
                        <span class="font-medium ml-1">${booking.waktu || 'Akan ditentukan'}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Host:</span>
                        <span class="font-medium ml-1">${booking.host || 'Akan ditentukan'}</span>
                    </div>
                    ${booking.catatan ? `
                    <div class="col-span-2">
                        <span class="text-gray-500">Catatan:</span>
                        <span class="text-gray-700 ml-1">${booking.catatan}</span>
                    </div>
                    ` : ''}
                </div>
            </div>
        `;
    });
    
    html += `
            </div>
        </div>
    `;
    
    modalContent.innerHTML = html;
    
    document.getElementById('bookingsDetailModal').classList.remove('hidden');
    document.getElementById('bookingsDetailModal').classList.add('flex');
}

function closeBookingsModal() {
    document.getElementById('bookingsDetailModal').classList.remove('flex');
    document.getElementById('bookingsDetailModal').classList.add('hidden');
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('bookingsDetailModal');
    if (event.target == modal) {
        closeBookingsModal();
    }
}
</script>
@endpush
@endsection