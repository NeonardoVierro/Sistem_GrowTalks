@extends('layouts.app')

@section('title', 'Podcast')

@section('content')
<div class="min-h-screen relative p-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Podcast</h1>
        <p class="text-gray-600">Ruang berbagi gagasan dan inspirasi bersama narasumber pilihan. Jadwal podcast tersedia khusus setiap hari Jumat.</p>
    </div>
    
    <!-- CONTENT -->
        <!-- Calendar Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
            <!-- Calendar Header with Navigation -->
            <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    @php
                        $prevMonth = $month - 1;
                        $prevYear = $year;
                        if ($prevMonth < 1) {
                            $prevMonth = 12;
                            $prevYear = $year - 1;
                        }
                        
                        $nextMonth = $month + 1;
                        $nextYear = $year;
                        if ($nextMonth > 12) {
                            $nextMonth = 1;
                            $nextYear = $year + 1;
                        }
                    @endphp
                    
                    <a href="{{ route('podcast.index', ['month' => $prevMonth, 'year' => $prevYear]) }}"
                       class="p-2 hover:bg-gray-100 rounded-full">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                    <h2 class="text-xl font-bold text-gray-800">
                        {{ \Carbon\Carbon::create($year, $month, 1)->locale('id')->isoFormat('MMMM YYYY') }}
                    </h2>
                    <a href="{{ route('podcast.index', ['month' => $nextMonth, 'year' => $nextYear]) }}"
                       class="p-2 hover:bg-gray-100 rounded-full">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
                <a href="{{ route('podcast.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                    Kembali ke Bulan Ini
                </a>
            </div>

            <!-- Days Header -->
            <div class="p-4 border-b border-gray-200 grid grid-cols-7 gap-1">
                @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $day)
                    <div class="text-center font-medium text-gray-700 py-2 {{ $day == 'Jumat' ? 'text-blue-600 font-bold' : '' }}">
                        {{ $day }}
                    </div>
                @endforeach
            </div>

            <!-- Calendar Grid -->
            <div class="p-4">
                <div class="grid grid-cols-7 gap-2">
                    @php
                        use Carbon\Carbon;
                        $today = Carbon::today();
                    @endphp

                    @foreach($calendar as $week)
                        @foreach($week as $dayData)
                            @php
                                $date = $dayData['date'];
                                $dateString = $dayData['date_string'] ?? $date->format('Y-m-d');
                                $isCurrentMonth = $dayData['is_current_month'] ?? false;
                                $isFriday = $dayData['is_friday'] ?? false;
                                $isPast = $date->lt($today);
                                $isToday = $date->isToday();
                                
                                // Check if this date has bookings
                                $hasApprovedBookings = isset($approvedBookings[$dateString]) && $approvedBookings[$dateString]->isNotEmpty();
                                $myBookingsOnDate = $bookings->where('tanggal', $dateString);
                                $hasMyApprovedBooking = $myBookingsOnDate->where('status_verifikasi', 'disetujui')->isNotEmpty();
                                $hasMyPendingBooking = $myBookingsOnDate->where('status_verifikasi', 'pending')->isNotEmpty();
                                $hasMyRejectedBooking = $myBookingsOnDate->where('status_verifikasi', 'ditolak')->isNotEmpty();
                                $hasMyRescheduledBooking = $myBookingsOnDate->where('status_verifikasi', 'penjadwalan ulang')->isNotEmpty();

                                // Determine cell style and clickability
                                if ($hasMyApprovedBooking) {
                                    $bgClass = 'bg-green-100 border-green-300 cursor-pointer';
                                    $clickable = true;
                                    $type = 'my_approved';
                                } elseif ($hasMyPendingBooking) {
                                    $bgClass = 'bg-yellow-100 border-yellow-300 cursor-pointer';
                                    $clickable = true;
                                    $type = 'my_pending';
                                } elseif ($hasMyRejectedBooking) {
                                    $bgClass = 'bg-red-100 border-red-300 cursor-pointer';
                                    $clickable = true;
                                    $type = 'my_rejected';
                                } elseif ($hasMyRescheduledBooking) {
                                    $bgClass = 'bg-purple-100 border-purple-300 cursor-pointer';
                                    $clickable = true;
                                    $type = 'my_rescheduled';
                                } elseif ($hasApprovedBookings && $isCurrentMonth && $isFriday && !$isPast) {
                                    $bgClass = 'bg-blue-100 border-blue-300 cursor-pointer';
                                    $clickable = true;
                                    $type = 'booked_by_others';
                                } elseif ($isCurrentMonth && $isFriday && !$isPast) {
                                    $bgClass = 'cursor-pointer hover:bg-blue-50 border-blue-200';
                                    $clickable = true;
                                    $type = 'available';
                                } else {
                                    $bgClass = 'bg-gray-100 text-gray-400 cursor-not-allowed';
                                    $clickable = false;
                                    $type = 'unavailable';
                                }
                                
                                if ($isToday) {
                                    $bgClass .= ' border-2 border-gray-600';
                                }
                            @endphp

                            <div
                                class="h-24 p-2 text-center border rounded-lg flex flex-col justify-between {{ $bgClass }}"
                                @if($clickable)
                                    onclick="handleDateClick('{{ $dateString }}', '{{ $type }}', {{ $dayData['day'] }}, '{{ \Carbon\Carbon::parse($date)->locale('id')->isoFormat('D MMMM YYYY') }}')"
                                @endif
                            >
                                <div class="font-semibold {{ $isToday ? 'text-gray-600' : '' }}">
                                    {{ $dayData['day'] }}
                                    @if($isToday)
                                        <span class="text-xs">(Hari ini)</span>
                                    @endif
                                </div>
                                
                                @if($hasMyApprovedBooking)
                                    <div class="text-xs text-green-700 font-medium mt-1">
                                        <i class="fas fa-check-circle"></i> Disetujui
                                    </div>
                                @elseif($hasMyPendingBooking)
                                    <div class="text-xs text-yellow-700 font-medium mt-1">
                                        <i class="fas fa-clock"></i> Pending
                                    </div>
                                @elseif($hasMyRejectedBooking)
                                    <div class="text-xs text-red-700 font-medium mt-1">
                                        <i class="fas fa-times-circle"></i> Ditolak
                                    </div>
                                @elseif($hasMyRescheduledBooking)
                                    <div class="text-xs text-purple-700 font-medium mt-1">
                                        <i class="fas fa-redo"></i> Penjadwalan Ulang
                                    </div>
                                @elseif($hasApprovedBookings && $type != 'available')
                                    <div class="text-xs text-blue-700 font-medium mt-1">
                                        <i class="fas fa-users"></i> Terisi
                                    </div>
                                @elseif($type == 'available')
                                    <div class="text-xs text-green-600 mt-1">
                                        <i class="fas fa-calendar-plus"></i> Kosong
                                    </div>
                                @endif
                                
                                @if($isFriday && $isCurrentMonth)
                                    <div class="text-xs text-blue-500 mt-1">
                                        <i class="fas fa-podcast"></i>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endforeach
                </div>

                <!-- Legend -->
                <div class="mt-4 flex flex-wrap gap-4 text-sm">
                    <div class="flex items-center">
                        <div class="w-4 h-4 border-2 border-blue-500 mr-2"></div>
                        <span>Tersedia</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-green-100 border border-green-300 mr-2"></div>
                        <span>Disetujui</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-yellow-100 border border-yellow-300 mr-2"></div>
                        <span>Menunggu Verifikasi</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-blue-100 border border-blue-300 mr-2"></div>
                        <span>Sudah Terisi</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-red-100 border border-red-300 mr-2"></div>
                        <span>Ditolak</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-gray-100 border border-gray-300 mr-2"></div>
                        <span>Tidak Tersedia</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Judul -->
        <div class="flex items-center gap-3 py-5">
            <h2 class="text-lg font-bold tracking-wide text-black-700">
                ANTRIAN PENGAJUAN PODCAST
            </h2>
            <span class="ml-auto bg-gray-100 text-black-700 px-3 py-1 rounded-full text-sm font-semibold">
                Total: {{ $bookings->total() }} Pengajuan
            </span>
        </div>

        <!-- Tabel Antrian Verifikasi Podcast -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-200">
            <!-- Tabel dengan Scroll -->
            <div class="overflow-x-auto max-h-96 overflow-y-auto">
                <table class="w-full text-sm table-striped-cols">
                    <thead class="bg-blue-900 text-white">
                        <tr class="text-left">
                            <th class="px-4 py-3 text-center">Aksi</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Keterangan</th>
                            <th class="px-4 py-3">Kode Booking</th>
                            <th class="px-4 py-3">Tanggal & Waktu</th>
                            <th class="px-4 py-3">Judul</th>
                            <th class="px-4 py-3">Narasumber</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @forelse($bookings as $booking)
                        <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200">
                            <!-- aksi -->
                            <td class="px-4 py-3">
                                @if($booking->status_verifikasi === 'pending')
                                <form action="{{ route('podcast.destroy', $booking->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Batalkan pengajuan ini?')"
                                        class="text-red-600 hover:text-red-800" title="Batalkan">
                                        <i class="fas fa-times-circle"></i>
                                    </button>
                                </form>
                                @else
                                <span class="text-gray-400 cursor-not-allowed" title="Tidak dapat dibatalkan">
                                    <i class="fas fa-times-circle"></i>
                                </span>
                                @endif
                            </td>

                            <!-- status -->
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    @if($booking->status_verifikasi === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($booking->status_verifikasi === 'disetujui') bg-green-100 text-green-800
                                    @elseif($booking->status_verifikasi === 'penjadwalan ulang') bg-purple-100 text-purple-800
                                    @elseif($booking->status_verifikasi === 'ditolak') bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($booking->status_verifikasi) }}
                                </span>
                            </td>
                            <!-- keterangan -->
                            <td class="px-4 py-3 w-60 text-gray-600">
                                {{ $booking->catatan ?? '-' }}
                            </td>

                            <!-- kode booking -->
                            <td class="px-4 py-3 font-mono text-gray-800">
                                {{ date('Ymd', strtotime($booking->tanggal)) }}{{ $booking->id }}
                            </td>

                            <!-- tanggal -->
                            <td class="px-4 py-3">
                                <div class="font-medium">
                                    {{ \Carbon\Carbon::parse($booking->tanggal)->locale('id')->isoFormat('D MMMM YYYY') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    @if($booking->waktu)
                                    Waktu: {{ $booking->waktu }} <br>
                                    @endif
                                    @if($booking->host)
                                    Host: {{ $booking->host }}
                                    @endif
                                </div>
                            </td>
                            <!-- judul -->
                            <td class="px-4 py-3 font-semibold text-gray-800">
                                {{ $booking->keterangan }}
                            </td>
                            <!-- narasumber -->
                            <td class="px-4 py-3 text-gray-800">
                                {{ $booking->narasumber }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center text-gray-500">
                                Belum ada pengajuan podcast
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>    

<!-- Booking Modal -->
<div id="bookingModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg w-full max-w-md relative">
        <div class="p-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800">Form Pengajuan Podcast</h3>
            <button onclick="closeBookingModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="bookingForm" action="{{ route('podcast.submit') }}" method="POST" class="p-4">
            @csrf
            <input type="hidden" id="selectedDate" name="tanggal">

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal*</label>
                    <input type="text" id="displayDate" class="w-full px-3 py-2 border border-gray-300 rounded" readonly>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul Podcast*</label>
                    <input type="text" name="keterangan" class="w-full px-3 py-2 border border-gray-300 rounded focus:border-blue-500 focus:ring-1 focus:ring-blue-500" placeholder="Masukkan Judul" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Narasumber*</label>
                    <input type="text" name="narasumber" class="w-full px-3 py-2 border border-gray-300 rounded focus:border-blue-500 focus:ring-1 focus:ring-blue-500" placeholder="Masukkan Narasumber" required>
                </div>

                <div class="flex items-start">
                    <input type="checkbox" id="persetujuan" name="persetujuan" class="mt-1 mr-2" required>
                    <label for="persetujuan" class="text-sm text-gray-700">
                        Saya menyetujui apabila ada perubahan jadwal podcast oleh pengelola layanan
                    </label>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeBookingModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Ajukan</button>
            </div>
        </form>
    </div>
</div>

<!-- Detail Modal -->
<div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg w-full max-w-md relative">
        <div class="p-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800">Detail Podcast</h3>
            <button onclick="closeDetailModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="p-4 space-y-3">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                <div id="detailDate" class="px-3 py-2 bg-gray-50 rounded"></div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <div id="detailStatus" class="px-3 py-2 bg-gray-50 rounded"></div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Waktu</label>
                <div id="detailTime" class="px-3 py-2 bg-gray-50 rounded"></div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                <div id="detailTitle" class="px-3 py-2 bg-gray-50 rounded"></div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Narasumber</label>
                <div id="detailNarasumber" class="px-3 py-2 bg-gray-50 rounded"></div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">OPD</label>
                <div id="detailOpd" class="px-3 py-2 bg-gray-50 rounded"></div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Host</label>
                <div id="detailHost" class="px-3 py-2 bg-gray-50 rounded"></div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                <div id="detailNotes" class="px-3 py-2 bg-gray-50 rounded"></div>
            </div>
        </div>

        <div class="p-4 border-t border-gray-200 flex justify-end">
            <button onclick="closeDetailModal()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Tutup</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function handleDateClick(dateString, type, day, displayDate) {
    if (type === 'available') {
        openBookingModal(dateString, displayDate);
    } else if (type === 'my_approved' || type === 'my_pending' || type === 'my_rejected' || type === 'my_rescheduled' || type === 'booked_by_others') {
        showPodcastDetail(dateString, type);
    }
}

function openBookingModal(dateString, displayDate) {
    document.getElementById('displayDate').value = displayDate;
    document.getElementById('selectedDate').value = dateString;
    document.getElementById('bookingModal').classList.remove('hidden');
    document.getElementById('bookingModal').classList.add('flex');
}

function closeBookingModal() {
    document.getElementById('bookingModal').classList.remove('flex');
    document.getElementById('bookingModal').classList.add('hidden');
}

function showPodcastDetail(dateString, type) {
    // Prepare data for display
    document.getElementById('detailDate').textContent = new Date(dateString).toLocaleDateString('id-ID', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    
    if (type === 'booked_by_others') {
        document.getElementById('detailStatus').textContent = 'Sudah ada booking pada tanggal ini';
        document.getElementById('detailTime').textContent = 'Waktu akan ditentukan verifikator';
        document.getElementById('detailTitle').textContent = '-';
        document.getElementById('detailNarasumber').textContent = '-';
        document.getElementById('detailOpd').textContent = '-';
        document.getElementById('detailHost').textContent = '-';
        document.getElementById('detailNotes').textContent = 'Anda masih bisa mengajukan booking, verifikator akan menjadwalkan waktu yang berbeda.';
    } else {
        // For other types, would need to fetch specific booking data
        // This is a simplified version
        document.getElementById('detailStatus').textContent = type.replace('_', ' ').toUpperCase();
        document.getElementById('detailTime').textContent = '-';
        document.getElementById('detailTitle').textContent = '-';
        document.getElementById('detailNarasumber').textContent = '-';
        document.getElementById('detailOpd').textContent = '-';
        document.getElementById('detailHost').textContent = '-';
        document.getElementById('detailNotes').textContent = 'Detail lengkap dapat dilihat di tabel antrian.';
    }
    
    document.getElementById('detailModal').classList.remove('hidden');
    document.getElementById('detailModal').classList.add('flex');
}

function closeDetailModal() {
    document.getElementById('detailModal').classList.remove('flex');
    document.getElementById('detailModal').classList.add('hidden');
}

// Show success message if exists
@if(session('success'))
    alert('{{ session('success') }}');
@endif

@if(session('error'))
    alert('{{ session('error') }}');
@endif
</script>
@endpush
@endsection
[file content end]