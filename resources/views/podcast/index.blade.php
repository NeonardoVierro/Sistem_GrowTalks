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
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">
                        {{ \Carbon\Carbon::create($year, $month, 1)->locale('id')->isoFormat('MMMM YYYY') }}
                    </h2>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('podcast.index', ['month' => $month - 1 <= 0 ? 12 : $month - 1, 'year' => $month - 1 <= 0 ? $year - 1 : $year]) }}" 
                    class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                    <a href="{{ route('podcast.index', ['month' => date('m'), 'year' => date('Y')]) }}" 
                    class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Bulan Ini
                    </a>
                    <a href="{{ route('podcast.index', ['month' => $month + 1 > 12 ? 1 : $month + 1, 'year' => $month + 1 > 12 ? $year + 1 : $year]) }}" 
                    class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>
        </div>

            <!-- Days Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
            <div class="p-4 border-b border-gray-200 grid grid-cols-7 gap-1">
                @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $day)
                    <div class="text-center font-medium text-gray-700 py-2 {{ $day == 'Jumat' ? 'text-green-600 font-bold' : '' }}">
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
                                
                                // Check if this date has bookings (pending or approved) and user's bookings
                                $hasBookings = isset($allBookings[$dateString]) && $allBookings[$dateString]->isNotEmpty();
                                $myBookingsOnDate = $userBookings[$dateString] ?? collect();
                                $hasMyApprovedBooking = $myBookingsOnDate->where('status_verifikasi', 'disetujui')->isNotEmpty();
                                $hasMyPendingBooking = $myBookingsOnDate->where('status_verifikasi', 'pending')->isNotEmpty();
                                $hasMyRejectedBooking = $myBookingsOnDate->where('status_verifikasi', 'ditolak')->isNotEmpty();
                                $hasMyRescheduledBooking = $myBookingsOnDate->where('status_verifikasi', 'penjadwalan ulang')->isNotEmpty();

                                // Determine cell style and clickability
                                if ($hasMyApprovedBooking) {
                                    $bgClass = 'bg-green-100 border-green-300 cursor-pointer hover:bg-green-200';
                                    $clickable = true;
                                    $type = 'my_approved';
                                    $bookingData = $myBookingsOnDate->where('status_verifikasi', 'disetujui')->first();
                                } elseif ($hasMyPendingBooking) {
                                    $bgClass = 'bg-yellow-100 border-yellow-300 cursor-pointer hover:bg-yellow-200';
                                    $clickable = true;
                                    $type = 'my_pending';
                                    $bookingData = $myBookingsOnDate->where('status_verifikasi', 'pending')->first();
                                } elseif ($hasMyRejectedBooking) {
                                    $bgClass = 'bg-red-100 border-red-300 cursor-pointer hover:bg-red-200';
                                    $clickable = true;
                                    $type = 'my_rejected';
                                    $bookingData = $myBookingsOnDate->where('status_verifikasi', 'ditolak')->first();
                                } elseif ($hasMyRescheduledBooking) {
                                    $bgClass = 'bg-purple-100 border-purple-300 cursor-pointer hover:bg-purple-200';
                                    $clickable = true;
                                    $type = 'my_rescheduled';
                                    $bookingData = $myBookingsOnDate->where('status_verifikasi', 'penjadwalan ulang')->first();
                                } elseif ($hasBookings && $isCurrentMonth && $isFriday && !$isPast) {
                                    $bgClass = 'bg-blue-100 border-blue-300 cursor-pointer hover:bg-blue-200';
                                    $clickable = true;
                                    $type = 'booked_by_others';
                                    // Get first booking by other user (pending or approved) for display
                                    $bookingData = collect($allBookings[$dateString])->firstWhere('id_user', '!=', Auth::id());
                                } elseif ($isCurrentMonth && $isFriday && !$isPast) {
                                    $bgClass = 'cursor-pointer hover:bg-blue-50 border-blue-200';
                                    $clickable = true;
                                    $type = 'available';
                                    $bookingData = null;
                                } else {
                                    $bgClass = 'bg-gray-100 text-gray-400 cursor-not-allowed';
                                    $clickable = false;
                                    $type = 'unavailable';
                                    $bookingData = null;
                                }
                                
                                if ($isToday) {
                                    $bgClass .= ' border-2 border-gray-600';
                                }
                            @endphp

                            <div
                                class="h-24 p-2 text-center border rounded-lg flex flex-col justify-between {{ $bgClass }}"
                                @if($clickable && $type == 'booked_by_others')
                                    onclick="handleDateClick('{{ $dateString }}', '{{ $type }}', {{ $dayData['day'] }}, '{{ \Carbon\Carbon::parse($date)->locale('id')->isoFormat('D MMMM YYYY') }}',
                                        {{ json_encode($bookingData ? [
                                            'kode' => date('Ymd', strtotime($bookingData->tanggal)) . $bookingData->id,
                                            'keterangan' => $bookingData->keterangan,
                                            'narasumber' => $bookingData->narasumber,
                                            'nama_opd' => $bookingData->nama_opd ?? $bookingData->instansi ?? '-',
                                            'host' => $bookingData->host,
                                            'waktu' => $bookingData->waktu,
                                            'status' => $bookingData->status_verifikasi ?? $bookingData->status ?? null,
                                            'catatan' => $bookingData->catatan,
                                            'verifikasi' => $bookingData->verifikasi,
                                            'is_other_user' => true,
                                            'other_user_message' => 'Tanggal ini sudah dibooking oleh ' . ($bookingData->nama_opd ?? $bookingData->instansi ?? 'user lain') . '. Anda masih bisa mengajukan booking untuk tanggal yang sama, verifikator akan menjadwalkan waktu yang berbeda jika memungkinkan.'
                                        ] : null) }}
                                    )"
                                @elseif($clickable)
                                    onclick="handleDateClick('{{ $dateString }}', '{{ $type }}', {{ $dayData['day'] }}, '{{ \Carbon\Carbon::parse($date)->locale('id')->isoFormat('D MMMM YYYY') }}',
                                        {{ json_encode($bookingData ? [
                                            'kode' => date('Ymd', strtotime($bookingData->tanggal)) . $bookingData->id,
                                            'keterangan' => $bookingData->keterangan,
                                            'narasumber' => $bookingData->narasumber,
                                            'nama_opd' => $bookingData->nama_opd ?? $bookingData->instansi ?? '-',
                                            'host' => $bookingData->host,
                                            'waktu' => $bookingData->waktu,
                                            'status' => $bookingData->status_verifikasi ?? $bookingData->status ?? null,
                                            'catatan' => $bookingData->catatan,
                                            'verifikasi' => $bookingData->verifikasi,
                                        ] : null) }}
                                    )"
                                @endif
                            >
                            
                                <div class="font-semibold text-sm {{ $isToday ? 'text-gray-600' : '' }}">
                                    {{ $dayData['day'] }}
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
                                @elseif(isset($hasBookings) && $hasBookings && $type != 'available')
                                    <div class="text-xs text-blue-700 font-medium mt-1">
                                        <i class="fas fa-users"></i> Terisi
                                    </div>
                                @elseif($type == 'available')
                                    <div class="text-xs text-green-600 mt-1">
                                        <i class="fas fa-calendar-plus"></i> Tersedia
                                    </div>
                                @endif
                                @if($isFriday && $isCurrentMonth)
                                    <div class="text-xs text-blue-500 mt-1">
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
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto max-h-96 overflow-y-auto">
                <table class="w-full text-sm border-collapse">
                    <thead class="bg-blue-900 text-white">
                        <tr>
                            <th class="px-4 py-3 text-center">Aksi</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Kode Booking</th>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Judul</th>
                            <th class="px-4 py-3">Narasumber</th>
                            <th class="px-4 py-3">Keterangan</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($bookings as $booking)
                        <tr class="border-t hover:bg-gray-100 transition">

                            {{-- AKSI --}}
                            <td class="px-4 py-3 text-center">
                                @if($booking->status_verifikasi === 'pending')
                                <form action="{{ route('podcast.destroy', $booking->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Batalkan pengajuan ini?')"
                                        class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-times-circle"></i>
                                    </button>
                                </form>
                                @else
                                <span class="text-gray-400 cursor-not-allowed">
                                    <i class="fas fa-times-circle"></i>
                                </span>
                                @endif
                            </td>

                            {{-- STATUS --}}
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-xs font-semibold
                                    @if($booking->status_verifikasi === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($booking->status_verifikasi === 'disetujui') bg-green-100 text-green-800
                                    @elseif($booking->status_verifikasi === 'penjadwalan ulang') bg-purple-100 text-purple-800
                                    @elseif($booking->status_verifikasi === 'ditolak') bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($booking->status_verifikasi) }}
                                </span>
                            </td>

                            {{-- KODE --}}
                            <td class="px-4 py-3 font-mono">
                                P{{ date('Ymd', strtotime($booking->tanggal)) }}{{ $booking->id }}
                            </td>
                            <td class="py-3 px-4 text-sm">
                                {{-- Tanggal --}}
                                <div class="text-sm">
                                    {{ \Carbon\Carbon::parse($booking->tanggal)->locale('id')->isoFormat('D MMMM YYYY') }}
                                </div>
                                {{-- Waktu --}}
                                @if($booking->waktu)
                                <div class="text-xs text-gray-500">
                                    Waktu: {{ $booking->waktu }}
                                </div>
                                @endif
                                {{-- Host --}}
                                @if($booking->host)
                                <div class="text-xs text-gray-500">
                                    Host: {{ $booking->host }}
                                </div>
                                @endif
                            </td>
                            {{-- JUDUL --}}
                            <td class="py-3 px-4 text-sm max-w-xs break-words whitespace-normal">
                                {{ $booking->keterangan }}
                            </td>

                            {{-- NARASUMBER --}}
                            <td class="px-4 py-3">
                                {{ $booking->narasumber }}
                            </td>

                            {{-- CATATAN --}}
                            <td class="px-4 py-3 text-gray-600 max-w-xs break-words">
                                {{ $booking->catatan ?? '-' }}
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
            <h3 class="text-lg font-bold text-gray-800" id="detailModalTitle">Detail Podcast</h3>
            <button onclick="closeDetailModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="p-4 space-y-3" id="detailContent">
            <!-- Content akan diisi oleh JavaScript -->
        </div>

        <div class="p-4 border-t border-gray-200 flex justify-end">
            <button onclick="closeDetailModal()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Tutup</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function handleDateClick(dateString, type, day, displayDate, bookingData = null) {
    // bookingData may be passed from the onclick in the calendar cell; default to null
    if (type === 'available') {
        openBookingModal(dateString, displayDate);
    } else {
        showPodcastDetail(dateString, type, bookingData);
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
    currentBookingData = null;
}

function showPodcastDetail(dateString, type, bookingData) {
    const detailContent = document.getElementById('detailContent');
    const modalTitle = document.getElementById('detailModalTitle');
    
    if (type === 'my_approved') {
        modalTitle.textContent = 'Detail Podcast Anda (Disetujui)';
        detailContent.innerHTML = `
            <div class="space-y-4">
                <div class="bg-green-50 border-l-4 border-green-500 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">
                                Podcast Anda sudah disetujui untuk tanggal ini. Berikut detailnya:
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <div class="px-3 py-2 bg-gray-50 rounded">${new Date(dateString).toLocaleDateString('id-ID', {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        })}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode Booking</label>
                        <div class="px-3 py-2 bg-gray-50 rounded font-mono">${bookingData.kode}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                        <div class="px-3 py-2 bg-gray-50 rounded">${bookingData.keterangan || '-'}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Narasumber</label>
                        <div class="px-3 py-2 bg-gray-50 rounded">${bookingData.narasumber || '-'}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Instansi/OPD</label>
                        <div class="px-3 py-2 bg-gray-50 rounded">${bookingData.nama_opd || '-'}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Waktu</label>
                        <div class="px-3 py-2 bg-gray-50 rounded">${bookingData.waktu || 'Akan ditentukan'}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Host</label>
                        <div class="px-3 py-2 bg-gray-50 rounded">${bookingData.host || 'Akan ditentukan'}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Verifikator</label>
                        <div class="px-3 py-2 bg-gray-50 rounded">${bookingData.verifikasi || '-'}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                        <div class="px-3 py-2 bg-gray-50 rounded">${bookingData.catatan || 'Tidak ada catatan'}</div>
                    </div>
                </div>
            </div>
        `;
    } else if (type === 'my_pending') {
        modalTitle.textContent = 'Detail Pengajuan Podcast (Pending)';
        detailContent.innerHTML = `
            <div class="space-y-4">
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                Pengajuan podcast Anda sedang menunggu verifikasi.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <div class="px-3 py-2 bg-gray-50 rounded">${new Date(dateString).toLocaleDateString('id-ID', {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        })}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                        <div class="px-3 py-2 bg-gray-50 rounded">${bookingData.keterangan || '-'}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Narasumber</label>
                        <div class="px-3 py-2 bg-gray-50 rounded">${bookingData.narasumber || '-'}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <div class="px-3 py-2 bg-yellow-100 text-yellow-800 rounded font-medium">Menunggu Verifikasi</div>
                    </div>
                </div>
            </div>
        `;
    } else if (type === 'booked_by_others') {
        modalTitle.textContent = 'Informasi Tanggal Podcast (Terisi)';
        detailContent.innerHTML = `
            <div class="space-y-4">
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                Tanggal ini sudah dibooking oleh instansi lain. Berikut detail booking:
                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <div class="px-3 py-2 bg-gray-50 rounded">${new Date(dateString).toLocaleDateString('id-ID', {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        })}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode Booking</label>
                        <div class="px-3 py-2 bg-gray-50 rounded font-mono">${bookingData?.kode || '-'}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                        <div class="px-3 py-2 bg-gray-50 rounded">${bookingData?.keterangan || '-'}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Narasumber</label>
                        <div class="px-3 py-2 bg-gray-50 rounded">${bookingData?.narasumber || '-'}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Instansi/OPD</label>
                        <div class="px-3 py-2 bg-gray-50 rounded">${bookingData?.nama_opd || '-'}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Waktu</label>
                        <div class="px-3 py-2 bg-gray-50 rounded">${bookingData?.waktu || 'Akan ditentukan'}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Host</label>
                        <div class="px-3 py-2 bg-gray-50 rounded">${bookingData?.host || 'Akan ditentukan'}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Verifikator</label>
                        <div class="px-3 py-2 bg-gray-50 rounded">${bookingData?.verifikasi || '-'}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                        <div class="px-3 py-2 bg-gray-50 rounded">${bookingData?.catatan || 'Tidak ada catatan'}</div>
                    </div>
                </div>
            </div>
        `;
    } else if (type === 'my_rejected') {
        modalTitle.textContent = 'Detail Podcast (Ditolak)';
        detailContent.innerHTML = `
            <div class="space-y-4">
                <div class="bg-red-50 border-l-4 border-red-500 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-times-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">
                                Pengajuan podcast Anda ditolak. Anda bisa mengajukan ulang untuk tanggal lain.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <div class="px-3 py-2 bg-gray-50 rounded">${new Date(dateString).toLocaleDateString('id-ID', {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        })}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                        <div class="px-3 py-2 bg-gray-50 rounded">${bookingData.keterangan || '-'}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Narasumber</label>
                        <div class="px-3 py-2 bg-gray-50 rounded">${bookingData.narasumber || '-'}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alasan Ditolak</label>
                        <div class="px-3 py-2 bg-red-50 text-red-800 rounded">${bookingData.catatan || 'Tidak ada catatan'}</div>
                    </div>
                </div>
            </div>
        `;
    } else if (type === 'my_rescheduled') {
        modalTitle.textContent = 'Detail Podcast (Penjadwalan Ulang)';
        detailContent.innerHTML = `
            <div class="space-y-4">
                <div class="bg-purple-50 border-l-4 border-purple-500 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-redo text-purple-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-purple-700">
                                Podcast Anda perlu penjadwalan ulang. Silakan hubungi verifikator untuk informasi lebih lanjut.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <div class="px-3 py-2 bg-gray-50 rounded">${new Date(dateString).toLocaleDateString('id-ID', {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        })}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                        <div class="px-3 py-2 bg-gray-50 rounded">${bookingData.keterangan || '-'}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Narasumber</label>
                        <div class="px-3 py-2 bg-gray-50 rounded">${bookingData.narasumber || '-'}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Verifikator</label>
                        <div class="px-3 py-2 bg-purple-50 text-purple-800 rounded">${bookingData.catatan || 'Tidak ada catatan'}</div>
                    </div>
                </div>
            </div>
        `;
    }
    
    document.getElementById('detailModal').classList.remove('hidden');
    document.getElementById('detailModal').classList.add('flex');
}

function openBookingModalForSameDate(dateString, displayDate) {
    closeDetailModal();
    openBookingModal(dateString, displayDate);
}

function closeDetailModal() {
    document.getElementById('detailModal').classList.remove('flex');
    document.getElementById('detailModal').classList.add('hidden');
    currentBookingData = null;
}

// Show success message if exists
@if(session('success'))
    Swal.fire({
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        icon: 'success',
        confirmButtonText: 'OK'
    });
@endif

@if(session('error'))
    Swal.fire({
        title: 'Error!',
        text: '{{ session('error') }}',
        icon: 'error',
        confirmButtonText: 'OK'
    });
@endif
</script>
@endpush
@endsection