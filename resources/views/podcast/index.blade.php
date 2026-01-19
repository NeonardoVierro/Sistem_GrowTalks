@extends('layouts.app')

@section('title', 'Podcast')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Podcast</h1>
        <p class="text-gray-600">Jadwal podcast hanya tersedia setiap hari Jumat</p>
    </div>

    <!-- Calendar Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
        <div class="p-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800">January 2026</h2>
        </div>

        <!-- Days Header -->
        <div class="p-4 border-b border-gray-200 grid grid-cols-7 gap-1">
            @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $day)
                <div class="text-center font-medium text-gray-700 py-2">{{ $day }}</div>
            @endforeach
        </div>

        <!-- Calendar Grid -->
        <div class="p-4">
            <div class="grid grid-cols-7 gap-2">
                @php
                    use Carbon\Carbon;

                    $calendar = [
                        [29,30,31,1,2,3,4],
                        [5,6,7,8,9,10,11],
                        [12,13,14,15,16,17,18],
                        [19,20,21,22,23,24,25],
                        [26,27,28,29,30,31,1],
                    ];

                    $currentMonth = 1;
                    $currentYear = 2026;
                    $bookedDays = [9]; // contoh booked
                    $today = Carbon::now(); // tanggal sekarang
                @endphp

                @foreach($calendar as $weekIndex => $week)
                    @foreach($week as $day)
                        @php
                            $isPrevMonth = ($day > 20 && $weekIndex == 0);
                            $isNextMonth = ($day < 10 && $weekIndex == count($calendar)-1);
                            $isCurrentMonth = !$isPrevMonth && !$isNextMonth;

                            $date = $isCurrentMonth ? Carbon::create($currentYear, $currentMonth, $day) : null;
                            $isFriday = $date?->isFriday() ?? false;
                            $isPast = $date?->lt($today) ?? true;
                            $isBooked = $isCurrentMonth && in_array($day, $bookedDays);
                            $canClick = $isCurrentMonth && $isFriday && !$isPast && !$isBooked;

                            if ($isBooked) {
                                $bgClass = 'bg-green-100 border-green-300 cursor-pointer';
                            } elseif ($canClick) {
                                $bgClass = 'cursor-pointer hover:bg-blue-50';
                            } else {
                                $bgClass = 'bg-gray-100 text-gray-400 cursor-not-allowed';
                            }
                        @endphp

                        <div
                            class="h-24 p-2 text-center border rounded-lg flex flex-col justify-between {{ $bgClass }}"
                            @if($canClick)
                                onclick="openBookingModal({{ $day }})"
                            @elseif($isBooked)
                                onclick="showPodcastDetail({{ $day }})"
                            @endif
                        >
                            <div class="font-semibold">{{ $day }}</div>
                            @if($isBooked)
                                <div class="text-xs text-green-700 font-medium mt-1">
                                    Podcast<br>{{ optional(Auth::user())->nama_opd ?? '-' }}
                                </div>
                            @elseif($canClick)
                                <div class="text-xs text-blue-600 mt-1">Available</div>
                            @endif
                        </div>
                    @endforeach
                @endforeach
            </div>

            <!-- Legend -->
            <div class="mt-4 flex flex-wrap gap-4 text-sm">
                <div class="flex items-center">
                    <div class="w-4 h-4 border-2 border-blue-500 mr-2"></div>
                    <span>Jumat (Tersedia)</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-green-100 border border-green-300 mr-2"></div>
                    <span>Sudah Dibooking</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-gray-100 border border-gray-300 mr-2"></div>
                    <span>Tidak Tersedia / Lewat</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Table (tetap ada) -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800">Antrian Pengajuan Podcast</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="py-3 px-4 text-left font-medium text-gray-700 border-b">Aksi</th>
                        <th class="py-3 px-4 text-left font-medium text-gray-700 border-b">Status</th>
                        <th class="py-3 px-4 text-left font-medium text-gray-700 border-b">Keterangan</th>
                        <th class="py-3 px-4 text-left font-medium text-gray-700 border-b">Kode Booking</th>
                        <th class="py-3 px-4 text-left font-medium text-gray-700 border-b">Tanggal</th>
                        <th class="py-3 px-4 text-left font-medium text-gray-700 border-b">Judul</th>
                        <th class="py-3 px-4 text-left font-medium text-gray-700 border-b">Narasumber</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4 border-b">
                                <form action="{{ route('podcast.destroy', $booking->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Hapus pengajuan ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                            <td class="py-3 px-4 border-b">
                                <span class="status-badge status-{{ $booking->status_verifikasi }}">
                                    {{ ucfirst($booking->status_verifikasi) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 border-b">
                                <button class="text-gray-600 hover:text-gray-800">...</button>
                            </td>
                            <td class="py-3 px-4 border-b font-mono">
                                {{ date('Ymd', strtotime($booking->tanggal)) }}{{ $booking->id_podcast }}
                            </td>
                            <td class="py-3 px-4 border-b">
                                {{ \Carbon\Carbon::parse($booking->tanggal)->locale('id')->isoFormat('D MMMM YYYY') }}
                                <br><span class="text-sm text-gray-600">Waktu : {{ $booking->kalender->waktu ?? '-' }}</span>
                                <br><span class="text-sm text-gray-600">Host : {{ $booking->host ?? '-' }}</span>
                            </td>
                            <td class="py-3 px-4 border-b font-medium">{{ $booking->keterangan }}</td>
                            <td class="py-3 px-4 border-b">{{ $booking->narasumber }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 px-4 text-center text-gray-500">
                                Belum ada pengajuan podcast
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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

@push('scripts')
<script>
function openBookingModal(day) {
    const dateStr = `${day} Januari 2026`;
    document.getElementById('displayDate').value = dateStr;
    document.getElementById('selectedDate').value = `2026-01-${day.toString().padStart(2,'0')}`;
    document.getElementById('bookingModal').classList.remove('hidden');
    document.getElementById('bookingModal').classList.add('flex');
}

function closeBookingModal() {
    document.getElementById('bookingModal').classList.remove('flex');
    document.getElementById('bookingModal').classList.add('hidden');
}

function showPodcastDetail(day) {
    const dateStr = `${day} Januari 2026`;
    alert(`Detail Podcast untuk ${dateStr}\n\n` +
          `Tanggal: ${dateStr}\n` +
          `Waktu: 13.00-16.00\n` +
          `Host: Widyoko\n` +
          `Judul: Contoh Podcast\n` +
          `Narasumber: Ahmad Basuki\n` +
          `Instansi: {{ optional(Auth::user())->nama_opd ?? '-' }}`);
}
</script>
@endpush
@endsection
