@extends('layouts.app')

@section('title', 'Coaching Clinic')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Coaching Clinic</h1>
        <p class="text-gray-600">Layanan pendampingan dan konsultasi untuk pengembangan kompetensi dan solusi teknis,
    tersedia setiap hari Rabu dan Jumat.</p>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Month Navigation -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 p-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800">
                    {{ \Carbon\Carbon::create($year, $month, 1)->locale('id')->isoFormat('MMMM YYYY') }}
                </h2>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('coaching.index', ['month' => $month - 1 <= 0 ? 12 : $month - 1, 'year' => $month - 1 <= 0 ? $year - 1 : $year]) }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    <i class="fas fa-chevron-left"></i>
                </a>
                <a href="{{ route('coaching.index', ['month' => date('m'), 'year' => date('Y')]) }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Bulan Ini
                </a>
                <a href="{{ route('coaching.index', ['month' => $month + 1 > 12 ? 1 : $month + 1, 'year' => $month + 1 > 12 ? $year + 1 : $year]) }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Calendar Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
        <div class="p-4 border-b border-gray-200 grid grid-cols-7 gap-1">
            @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $day)
                <div class="text-center font-medium text-gray-700 py-2 {{ $day == 'Jumat' || $day == 'Rabu'? 'text-green-600 font-bold' : '' }}">{{ $day }}</div>
            @endforeach
        </div>

        <!-- Calendar Grid -->
        <div class="p-4">
            <div class="grid grid-cols-7 gap-2">
                @foreach($calendar as $week)
                    @foreach($week as $day)
                        @php
                            $isCurrentMonth = $day['is_current_month'] ?? false;
                            $isWednesday = $day['is_wednesday'] ?? false;
                            $isFriday = $day['is_friday'] ?? false;
                            $isAvailable = $isCurrentMonth && ($isWednesday || $isFriday);
                            $isPast = isset($day['date']) && $day['date']->lt(\Carbon\Carbon::today());
                            $dateString = $day['date_string'] ?? null;
                            
                            $hasBookings = $dateString && isset($approvedBookings[$dateString]);
                            $myBookings = $hasBookings ? $approvedBookings[$dateString]->where('is_mine', true) : collect();
                            $hasMyBooking = $myBookings->isNotEmpty();

                            // Background class (align with podcast styles)
                            // Tanggal lewat dengan booking tetap clickable
                            if ($hasMyBooking) {
                                $bgClass = 'bg-green-100 border-green-300 cursor-pointer hover:bg-green-200';
                            } elseif ($isAvailable && !$isPast) {
                                $bgClass = 'cursor-pointer hover:bg-blue-50 border-blue-200';
                            } elseif ($hasBookings && $isPast) {
                                // Tanggal lewat dengan booking: clickable tapi hanya info
                                $bgClass = 'bg-gray-100 cursor-pointer hover:bg-gray-200 border-gray-300';
                            } else {
                                $bgClass = 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200';
                            }

                            // Highlight today similar to podcast
                            if (isset($day['date']) && $day['date']->isToday()) {
                                $bgClass .= ' border-2 border-gray-600';
                            }
                        @endphp

                        <div
                            class="h-24 p-2 text-center border rounded-lg flex flex-col justify-between {{ $bgClass }} {{ $dayTypeClass ?? '' }}"
                            @if($isAvailable && !$isPast)
                                @if($hasBookings)
                                    onclick="showCoachingDetail('{{ $dateString }}')"
                                @else
                                    onclick="openCoachingBooking('{{ $dateString }}', '{{ \Carbon\Carbon::parse($dateString)->locale('id')->isoFormat('D MMMM YYYY') }}')"
                                @endif
                            @endif
                        >
                            <div class="font-semibold text-sm">{{ $day['day'] }}</div>
                            
                            @if($hasMyBooking)
                                <div class="text-xs text-green-700 font-medium mt-1">
                                    <i class="fas fa-check-circle"></i> Disetujui
                                </div>
                            @elseif($isAvailable && !$isPast)
                                <div class="text-xs text-green-600 mt-1">
                                    <i class="fas fa-calendar-plus"></i> Tersedia
                                </div>
                            @elseif(!$isCurrentMonth)
                                <div class="text-xs text-gray-400 mt-1">&nbsp;</div>
                            @endif
                            @if(($isFriday || $isWednesday) && $isCurrentMonth)
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
                    <span>Tersedia (Rabu/Jumat)</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-green-100 border border-green-300 mr-2"></div>
                    <span>Sudah Dibooking (Anda)</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-yellow-100 border border-yellow-300 mr-2"></div>
                    <span>Menunggu Verifikasi</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-gray-100 border border-gray-300 mr-2"></div>
                    <span>Tidak Tersedia / Lewat</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Table -->
    <div class="flex items-center gap-3 py-5">
        <h2 class="text-lg font-bold tracking-wide text-black-700">
            ANTRIAN PENGAJUAN COACHING CLINIC
        </h2>
        <span class="ml-auto bg-gray-100 text-black-700 px-3 py-1 rounded-full text-sm font-semibold">
                Total: {{ $bookings->total() }} Pengajuan
            </span>
    </div>
    
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-200">
        <div class="overflow-x-auto max-h-96 overflow-y-auto">
            <table class="w-full text-sm table-striped-cols">
                <thead class="bg-blue-900 text-white">
                    <tr class="text-left">
                        <th class="py-3 px-4 text center">Aksi</th>
                        <th class="py-3 px-4 ">Status</th>
                        <th class="py-3 px-4 ">Kode Booking</th>
                        <th class="py-3 px-4 ">Tanggal</th>
                        <th class="py-3 px-4 ">Layanan</th>
                        <th class="py-3 px-4 ">Agenda</th>
                        <th class="py-3 px-4 ">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4 border-b">
                            @if($booking->status_verifikasi == 'pending')
                            <form action="{{ route('coaching.destroy', $booking->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Batalkan pengajuan ini?')"
                                    class="text-red-600 hover:text-red-800 w-9 h-9 flex items-center justify-center" title="Batalkan">
                                    <i class="fas fa-times-circle"></i>
                                </button>
                            </form>
                            @else
                            <span class="text-gray-400 cursor-not-allowed w-9 h-9 flex items-center justify-center" title="Tidak dapat dibatalkan">
                                <i class="fas fa-times-circle"></i>
                            </span>
                            @endif
                        </td>
                        <td class="py-3 px-4 border-b">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    @if($booking->status_verifikasi === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($booking->status_verifikasi === 'disetujui') bg-green-100 text-green-800
                                    @elseif($booking->status_verifikasi === 'penjadwalan ulang') bg-purple-100 text-purple-800
                                    @elseif($booking->status_verifikasi === 'ditolak') bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($booking->status_verifikasi) }}
                                </span>
                            </td>
                        <td class="py-3 px-4 border-b font-mono">
                            CC{{ date('Ymd', strtotime($booking->tanggal)) }}{{ $booking->id }}
                        </td>
                        <td class="py-3 px-4 border-b">
                            {{ $booking->tanggal->locale('id')->isoFormat('D MMMM YYYY') }}
                        </td>
                        <td class="py-3 px-4 border-b">{{ $booking->layanan }}</td>
                        <td class="py-3 px-4 border-b">{{ $booking->keterangan }}</td>
                        <td class="py-3 px-4 border-b text-sm text-gray-600">
                            {{ $booking->catatan ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="py-8 px-4 text-center text-gray-500">
                            Belum ada pengajuan coaching clinic
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            <!-- Pagination -->
            @if($bookings->hasPages())
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $bookings->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Booking Modal -->
<div id="coachingModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg w-full max-w-md relative">
        <div class="p-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800">Form Pengajuan Coaching Clinic</h3>
            <button onclick="closeCoachingModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="coachingForm" action="{{ route('coaching.submit') }}" method="POST" class="p-4">
            @csrf
            <input type="hidden" id="coachingSelectedDate" name="tanggal">

            <div class="space-y-4">
                <div class="grid grid-cols-3 gap-2 items-center">
                    <label class="text-sm font-medium text-gray-700">Tanggal*</label>
                    <div class="col-span-2">
                        <input type="text" id="coachingDisplayDate" class="w-full px-3 py-2 border border-gray-300 rounded bg-gray-50" readonly>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-2 items-center">
                    <label class="text-sm font-medium text-gray-700">Layanan*</label>
                    <div class="col-span-2">
                        <select name="layanan" class="w-full px-3 py-2 border border-gray-300 rounded focus:border-blue-500 focus:ring-1 focus:ring-blue-500" required>
                            <option value="">Pilih Layanan</option>
                            <option value="TTL Design">Design Grafis Canva</option>
                            <option value="Website & Aplikasi">TTE BeSign</option>
                            <option value="Digital Marketing">Website & Aplikasi</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-2 items-center">
                    <label class="text-sm font-medium text-gray-700">Agenda*</label>
                    <div class="col-span-2">
                        <input type="text" name="keterangan" 
                               class="w-full px-3 py-2 border border-gray-300 rounded focus:border-blue-500 focus:ring-1 focus:ring-blue-500" 
                               placeholder="Contoh: Konsultasi Website E-commerce" 
                               required>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-2 items-center">
                    <label class="text-sm font-medium text-gray-700">
                        Instansi
                    </label>

                    <div class="col-span-2">
                        <input
                            type="text"
                            name="instansi"
                            value="{{ Auth::user()->instansi }}"
                            readonly
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg
                                focus:border-blue-500 focus:ring-1 focus:ring-blue-500 cursor-not-allowed"
                            placeholder="Nama Instansi"
                            required
                        >
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-2 items-center">
                    <label class="text-sm font-medium text-gray-700">PIC*</label>
                    <div class="col-span-2">
                        <input type="text" name="pic" 
                               class="w-full px-3 py-2 border border-gray-300 rounded focus:border-blue-500 focus:ring-1 focus:ring-blue-500" 
                               value="{{ Auth::user()->nama_pic }}" 
                               required>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-2 items-center">
                    <label class="text-sm font-medium text-gray-700">No. Telp*</label>
                    <div class="col-span-2">
                        <input type="tel" name="no_telp" 
                               class="w-full px-3 py-2 border border-gray-300 rounded focus:border-blue-500 focus:ring-1 focus:ring-blue-500" 
                               value="{{ Auth::user()->kontak_pic }}" 
                               required>
                    </div>
                </div>
                <div class="flex items-start">
                    <input type="checkbox" id="coachingPersetujuan" name="persetujuan" class="mt-1 mr-2" required>
                    <label for="coachingPersetujuan" class="text-sm text-gray-700">
                        Menyetujui apabila ada perubahan jadwal coaching clinic oleh pengelola layanan
                    </label>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeCoachingModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Ajukan</button>
            </div>
        </form>
    </div>
</div>

<!-- Detail Modal -->
<div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg w-full max-w-3xl relative">
        <div class="p-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800">Detail Coaching Clinic</h3>
            <button onclick="closeDetailModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-4" id="detailContent">
            <!-- Detail akan dimuat via JavaScript -->
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openCoachingBooking(dateString, displayDate) {
        document.getElementById('coachingDisplayDate').value = displayDate;
        document.getElementById('coachingSelectedDate').value = dateString;
        
        document.getElementById('coachingModal').classList.remove('hidden');
        document.getElementById('coachingModal').classList.add('flex');
    }
    
    function closeCoachingModal() {
        document.getElementById('coachingModal').classList.remove('flex');
        document.getElementById('coachingModal').classList.add('hidden');
    }
    
    function showCoachingDetail(dateString) {
        // Mengambil detail booking untuk tanggal tersebut
        fetch(`/coaching/detail/${dateString}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const bookings = data.bookings;
                    let bookingsHtml = '';
                    
                    if (bookings.length === 0) {
                        bookingsHtml = '<div class="text-gray-600 py-4">Belum ada booking disetujui untuk tanggal ini.</div>';
                    } else {
                        bookingsHtml = `
                        <div class="space-y-4">
                        ${bookings.map((b, idx) => {

                            const statusClass =
                                b.status === 'disetujui'
                                    ? 'bg-green-100 text-green-800'
                                    : b.status === 'pending'
                                    ? 'bg-yellow-100 text-yellow-800'
                                    : 'bg-gray-100 text-gray-800';

                            return `
                            <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">

                                <!-- HEADER DROPDOWN -->
                                <button
                                    type="button"
                                    class="w-full flex justify-between items-center px-5 py-4
                                        bg-gradient-to-r from-slate-50 to-gray-100
                                        hover:bg-gray-200 transition"
                                    onclick="this.nextElementSibling.classList.toggle('hidden')"
                                >
                                    <div class="text-left">
                                        <div class="font-semibold text-gray-800 text-base">
                                            ${b.instansi ?? 'Instansi Tidak Diketahui'}
                                        </div>
                                        <div class="text-xs text-gray-600">
                                            Layanan: ${b.layanan}
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-3">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold ${statusClass}">
                                            ${b.status}
                                        </span>

                                        <svg class="w-4 h-4 text-gray-500 transition-transform"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </button>

                                <!-- CONTENT DROPDOWN -->
                                <div class="hidden bg-white px-5 py-4 border-t">
                                    <div class="overflow-x-auto">
                                        <table class="w-full border border-gray-200 rounded-lg text-sm">
                                            <tbody class="divide-y">

                                                <tr>
                                                    <td class="bg-gray-50 px-4 py-2 font-medium text-gray-600 w-1/3">
                                                        Instansi
                                                    </td>
                                                    <td class="px-4 py-2 text-gray-800">
                                                        ${b.instansi ?? '-'}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="bg-gray-50 px-4 py-2 font-medium text-gray-600">
                                                        Layanan
                                                    </td>
                                                    <td class="px-4 py-2">
                                                        ${b.layanan}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="bg-gray-50 px-4 py-2 font-medium text-gray-600">
                                                        Agenda
                                                    </td>
                                                    <td class="px-4 py-2">
                                                        ${b.keterangan}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="bg-gray-50 px-4 py-2 font-medium text-gray-600">
                                                        PIC
                                                    </td>
                                                    <td class="px-4 py-2">
                                                        ${b.pic}<br>
                                                        <span class="text-xs text-gray-500">${b.no_telp}</span>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="bg-gray-50 px-4 py-2 font-medium text-gray-600">
                                                        Waktu Coaching
                                                    </td>
                                                    <td class="px-4 py-2">
                                                        ${b.waktu ?? '-'}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="bg-gray-50 px-4 py-2 font-medium text-gray-600">
                                                        Coach
                                                    </td>
                                                    <td class="px-4 py-2">
                                                        ${b.coach ?? '-'}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="bg-gray-50 px-4 py-2 font-medium text-gray-600">
                                                        Catatan
                                                    </td>
                                                    <td class="px-4 py-2 text-gray-700">
                                                        ${b.catatan ?? 'Tidak ada catatan'}
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                            `;
                        }).join('')}
                        </div>
                                                `;




                    }

                    const actionButton = data.can_book ? `<div class="mt-4 flex justify-end"><button onclick="closeDetailModal(); openCoachingBooking('${data.date}', '${data.display_date}')" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Ajukan Booking</button></div>` : '';

                    const detailContent = `
                        <div class="space-y-4"> 
                            <div class="border-b pb-3">
                                <div class="text-sm text-gray-600 mb-1">Tanggal</div>
                                <div class="font-semibold text-lg text-gray-800">${data.display_date}</div>
                            </div>
                            <div>
                            <div class="text-sm text-gray-600 mb-2 font-medium">
                                Daftar Booking
                            </div>

                            <!-- SCROLL AREA -->
                            <div class="max-h-[400px] overflow-y-auto pr-2 space-y-4">
                                ${bookingsHtml}
                            </div>
                        </div>

                            ${actionButton}
                        </div>
                    `;

                    document.getElementById('detailContent').innerHTML = detailContent;
                    document.getElementById('detailModal').classList.remove('hidden');
                    document.getElementById('detailModal').classList.add('flex');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengambil detail booking.');
            });
    }
    
    function closeDetailModal() {
        document.getElementById('detailModal').classList.remove('flex');
        document.getElementById('detailModal').classList.add('hidden');
    }
    
    // Validasi form sebelum submit
    document.getElementById('coachingForm')?.addEventListener('submit', function(e) {
        const checkboxes = this.querySelectorAll('input[type="checkbox"][required]');
        let allChecked = true;
        
        checkboxes.forEach(checkbox => {
            if (!checkbox.checked) {
                allChecked = false;
            }
        });
        
        if (!allChecked) {
            e.preventDefault();
            alert('Harap setujui persyaratan sebelum mengajukan.');
        }
    });
</script>
@endpush
@endsection