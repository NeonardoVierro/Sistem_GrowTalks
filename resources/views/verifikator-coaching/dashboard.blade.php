@extends('layouts.verifikator-coaching')

@section('title', 'Dashboard Verifikator Coaching')

@section('content')
<div class="p-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard Verifikator Coaching Clinic</h1>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Coaching -->
    <div class="card-stat bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg mr-4">
                    <i class="fas fa-chalkboard-teacher text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Coaching</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalCoachings }}</p>
                    <p class="text-xs text-gray-500 mt-1">Pengajuan Coaching</p>
                </div>
            </div>
        </div>

        <!-- Coaching Menunggu -->
       <div class="card-stat bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-lg mr-4">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Coaching Menunggu</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $pendingCoachings }}</p>
                    <p class="text-xs text-gray-500 mt-1">Menunggu Persetujuan</p>
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
                    <p class="text-2xl font-bold text-gray-800">{{ $approvedCoachings }}</p>
                    <p class="text-xs text-gray-500 mt-1">Coaching Disetujui</p>
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
                    <p class="text-2xl font-bold text-gray-800">{{ $rejectedCoachings }}</p>
                    <p class="text-xs text-gray-500 mt-1">Caoching Ditolak</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar Section -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-800">
                <i class="fas fa-calendar-alt mr-2 text-green-600"></i>
                Kalender Jadwal Coaching Clinic {{ \Carbon\Carbon::create($year, $month, 1)->locale('id')->isoFormat('MMMM YYYY') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('verifikator-coaching.dashboard', ['month' => $month - 1 <= 0 ? 12 : $month - 1, 'year' => $month - 1 <= 0 ? $year - 1 : $year]) }}" 
                   class="px-3 py-1 border border-gray-300 rounded-lg hover:bg-gray-50">
                    <i class="fas fa-chevron-left"></i>
                </a>
                <a href="{{ route('verifikator-coaching.dashboard', ['month' => date('m'), 'year' => date('Y')]) }}" 
                   class="px-3 py-1 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Bulan Ini
                </a>
                <a href="{{ route('verifikator-coaching.dashboard', ['month' => $month + 1 > 12 ? 1 : $month + 1, 'year' => $month + 1 > 12 ? $year + 1 : $year]) }}" 
                   class="px-3 py-1 border border-gray-300 rounded-lg hover:bg-gray-50">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </div>

        <div class="p-6">
            <!-- Days Header -->
            <div class="grid grid-cols-7 gap-2 mb-2">
                @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $day)
                    <div class="text-center font-medium text-gray-700 py-2 {{ in_array($day, ['Rabu', 'Jumat']) ? 'text-green-600 font-bold' : '' }}">
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
                            $isAvailableDay = $dayData['is_available_day'] ?? false;
                            $isPast = $date->lt($today);
                            $isToday = $date->isToday();
                            
                            // Check if this date has bookings
                            $hasBookings = isset($bookings[$dateString]) && $bookings[$dateString]->isNotEmpty();
                            
                            // Get booking status for this date
                            $bookingCount = 0;
                            $statusColors = [];
                            $bookingPreview = [];
                            
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
                                
                                // Get first 2 bookings for preview
                                $bookingPreview = $bookings[$dateString]->take(2);
                            }

                            // Determine cell style
                            if (!$isCurrentMonth) {
                                $bgClass = 'bg-gray-50 text-gray-400';
                            } elseif ($hasBookings) {
                                // Any date with bookings: show as scheduled (clickable)
                                $bgClass = 'bg-green-50 border-green-200 cursor-pointer hover:bg-green-100';
                            } elseif ($isAvailableDay) {
                                // Available days (Rabu/Jumat)
                                if (!$isPast) {
                                    // Upcoming available day
                                    $bgClass = 'bg-blue-50 border-blue-200';
                                } else {
                                    // Past available day without bookings: show muted but bookings (if any) already handled
                                    $bgClass = 'bg-gray-100 text-gray-400';
                                }
                            } else {
                                // Non-available days: always muted gray
                                $bgClass = 'bg-gray-100 text-gray-400';
                            }

                            if ($isToday) {
                                $bgClass .= ' border-2 border-blue-600';
                            }
                        @endphp

                        <div class="h-28 p-2 border rounded-lg {{ $bgClass }} {{ $hasBookings ? 'cursor-pointer' : '' }}"
                             @if($hasBookings)
                             onclick="showBookingsDetail('{{ $dateString }}', '{{ Carbon\Carbon::parse($date)->locale('id')->isoFormat('D MMMM YYYY') }}')"
                             @endif>
                            
                            <div class="flex justify-between items-start">
                                <span class="font-semibold text-sm {{ $isToday ? 'text-blue-600' : '' }}">
                                    {{ $dayData['day'] }}
                                </span>
                                
                                @if($hasBookings)
                                    <span class="text-xs bg-green-600 text-white px-1.5 py-0.5 rounded-full">
                                        {{ $bookingCount }}
                                    </span>
                                @endif
                            </div>
                            
                            @if($hasBookings)
                                <div class="mt-2 space-y-1">
                                    @foreach($bookingPreview as $booking)
                                        <div class="text-xs truncate {{ $booking->status_verifikasi == 'disetujui' ? 'text-green-700' : ($booking->status_verifikasi == 'pending' ? 'text-yellow-700' : 'text-gray-600') }}">
                                            <i class="fas fa-circle text-[8px] mr-1 
                                                {{ $booking->status_verifikasi == 'disetujui' ? 'text-green-500' : 
                                                   ($booking->status_verifikasi == 'pending' ? 'text-yellow-500' : 
                                                   ($booking->status_verifikasi == 'ditolak' ? 'text-red-500' : 'text-purple-500')) }}">
                                            </i>
                                            {{ Str::limit($booking->instansi ?? $booking->nama_opd ?? 'Instansi', 12) }}
                                        </div>
                                    @endforeach
                                    @if($bookingCount > 2)
                                        <div class="text-xs text-gray-500">
                                            +{{ $bookingCount - 2 }} lagi
                                        </div>
                                    @endif
                                </div>
                            @elseif($isAvailableDay && $isCurrentMonth && !$isPast)
                                <div class="mt-2 text-xs text-blue-600">
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

    <!-- Recent Coachings -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800">Coaching Terbaru</h2>
        </div>
        <div class="p-4">
            <div class="{{ $recentCoachings->count() > 7 ? 'max-h-72 overflow-y-auto' : '' }}">
                <table class="w-full table-fixed">
                <thead>
                     <thead class="bg-blue-900 text-white">
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Kode</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Jadwal</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Instansi</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Kategori</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Agenda</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Coach</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-white-700">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($recentCoachings as $coaching)
                    <tr class="table-row">
                        <tr class="hover:bg-gray-50 cursor-pointer"
                            onclick="window.location='{{ route('verifikator-coaching.approval-form', $coaching->id) }}'">
                            <td class="py-3 px-4 font-mono text-sm">
                                CCA-{{ date('Ymd', strtotime($coaching->tanggal)) }}{{ $coaching->id }}
                            </td>
                            <td class="py-3 px-4 text-sm">
                                {{ $coaching->tanggal->format('d/m/Y') }}
                            </td>
                            <td class="py-3 px-4 text-sm">{{ $coaching->nama_opd }}</td>
                            <td class="py-3 px-4">
                                <div class="font-medium text-gray-800">{{ $coaching->layanan }}</div>
                            </td>
                            <td class="py-3 px-4 text-sm">{{ Str::limit($coaching->keterangan, 25) }}</td>
                            <td class="py-3 px-4 text-sm">{{ $coaching->coach }}</td>
                            <td class="py-3 px-4 text-sm">
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
                                <span class="px-2 py-1 rounded-full text-sm font-medium {{ $bg }}">
                                    {{ ucfirst($coaching->status_verifikasi) }}
                                </span>
                            </td>
                        </tr>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-500">
                            Belum ada data coaching
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Statistics -->
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Statistik</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Disetujui</span>
                    <span class="font-bold text-green-600">{{ $approvedCoachings }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Ditolak</span>
                    <span class="font-bold text-red-600">{{ $rejectedCoachings }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Pending</span>
                    <span class="font-bold text-yellow-600">{{ $pendingCoachings }}</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
         <div class="card-stat bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
            <h3 class="text-lg font-bold text-gray-800 mb-3">Aksi Cepat</h3>
            <div class="space-y-4">
                <a href="{{ route('verifikator-coaching.approval') }}" 
                   class="block w-full text-center py-3 px-4 bg-green-600 text-white rounded-lg hover:bg-green-600">
                    <i class="fas fa-check-circle mr-2"></i>Verifikasi Coaching
                </a>
                <a href="{{ route('verifikator-coaching.report') }}" 
                   class="block w-full text-center py-3 px-4 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                    <i class="fas fa-file-alt mr-2"></i>Lihat Laporan
                </a>
            </div>
        </div>
    </div>
</div>
<!-- Detail Bookings Modal -->
<div id="bookingsDetailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg w-full max-w-3xl relative">
        <div class="p-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800" id="modalDateTitle">Detail Jadwal Coaching Clinic</h3>
            <button onclick="closeBookingsModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="p-4 max-h-96 overflow-y-auto" id="bookingsDetailContent">
            <!-- Content will be filled by JavaScript -->
        </div>

        <div class="p-4 border-t border-gray-200 flex justify-end">
            <button onclick="closeBookingsModal()" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                Tutup
            </button>
        </div>
    </div>
</div>
@push('scripts')
<script>
function showBookingsDetail(dateString, displayDate) {
    // Fetch booking details via AJAX
    fetch(`/verifikator-coaching/get-bookings-by-date?date=${dateString}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const modalTitle = document.getElementById('modalDateTitle');
                const modalContent = document.getElementById('bookingsDetailContent');
                
                modalTitle.textContent = `Detail Jadwal Coaching Clinic - ${displayDate}`;
                
                if (data.bookings.length === 0) {
                    modalContent.innerHTML = `
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-calendar-times text-4xl mb-3 text-gray-400"></i>
                            <p>Tidak ada jadwal coaching pada tanggal ini</p>
                        </div>
                    `;
                } else {
                    let html = `
                        <div class="space-y-4">
                            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-info-circle text-green-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-green-700">
                                            Total ${data.bookings.length} jadwal coaching clinic pada tanggal ini
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="space-y-3">
                    `;
                    
                    data.bookings.forEach((booking, index) => {
                        let statusClass = '';
                        let statusIcon = '';
                        
                        switch(booking.status.toLowerCase()) {
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
                            default:
                                statusClass = 'bg-gray-100 text-gray-800';
                                statusIcon = 'fa-question-circle';
                        }
                        
                        html += `
                            <div class="border rounded-lg p-4 hover:shadow-md transition cursor-pointer hover:bg-green-50" onclick="window.location.href='/verifikator-coaching/approval/${booking.id}/form'">
                                <div class="flex justify-between items-start mb-3">
                                    <span class="font-mono text-sm bg-gray-100 px-2 py-1 rounded">
                                        ${booking.kode}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${statusClass}">
                                        <i class="fas ${statusIcon} mr-1"></i>
                                        ${booking.status}
                                    </span>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-3 text-sm">
                                    <div>
                                        <span class="text-gray-500">Instansi:</span>
                                        <span class="font-medium ml-1">${booking.instansi}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Layanan:</span>
                                        <span class="font-medium ml-1">${booking.layanan}</span>
                                    </div>
                                    <div class="col-span-2">
                                        <span class="text-gray-500">Agenda:</span>
                                        <a href="/verifikator-coaching/approval/${booking.id}/form" class="font-medium ml-1 text-green-600 hover:text-green-800 hover:underline cursor-pointer" title="Klik untuk ke halaman verifikasi">
                                            ${booking.agenda}
                                        </a>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">PIC:</span>
                                        <span class="font-medium ml-1">${booking.pic}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">No. Telp:</span>
                                        <span class="font-medium ml-1">${booking.no_telp}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Waktu:</span>
                                        <span class="font-medium ml-1">${booking.waktu}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Coach:</span>
                                        <span class="font-medium ml-1">${booking.coach}</span>
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
                }
                
                document.getElementById('bookingsDetailModal').classList.remove('hidden');
                document.getElementById('bookingsDetailModal').classList.add('flex');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengambil data booking');
        });
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
