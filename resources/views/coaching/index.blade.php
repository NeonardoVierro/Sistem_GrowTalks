@extends('layouts.app')

@section('title', 'Coaching Clinic')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Coaching Clinic</h1>
        <p class="text-gray-600">Jadwal coaching clinic hanya tersedia setiap hari Rabu dan Jumat</p>
    </div>

    @php
    use Carbon\Carbon;

    $currentMonth = 1;
    $currentYear = 2026;
    $today = Carbon::now();

    // Dummy booking data
    $bookings = [
        (object)[
            'id' => 1,
            'tanggal' => '2026-01-07',
            'layanan' => 'Oranya Cawana',
            'keterangan' => 'Konsultasi Branding',
            'pic' => 'Andi',
            'no_telp' => '081234567890',
            'status_verifikasi' => 'Disetujui',
            'id_coaching' => 1,
        ],
        (object)[
            'id' => 2,
            'tanggal' => '2026-01-23',
            'layanan' => 'Website & Aplikasi',
            'keterangan' => 'Konsultasi Website',
            'pic' => 'Budi',
            'no_telp' => '081987654321',
            'status_verifikasi' => 'Disetujui',
            'id_coaching' => 2,
        ],
    ];

    // Ambil hari dari tanggal booking
    $bookedDays = collect($bookings)->map(fn($b) => (int) Carbon::parse($b->tanggal)->day)->toArray();

    // Kalender sederhana
    $calendar = [
        [29,30,31,1,2,3,4],
        [5,6,7,8,9,10,11],
        [12,13,14,15,16,17,18],
        [19,20,21,22,23,24,25],
        [26,27,28,29,30,31,1],
    ];
    @endphp

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
                @foreach($calendar as $weekIndex => $week)
                    @foreach($week as $day)
                        @php
                            $isPrevMonth = ($day > 20 && $weekIndex == 0);
                            $isNextMonth = ($day < 10 && $weekIndex == count($calendar)-1);
                            $isCurrentMonth = !$isPrevMonth && !$isNextMonth;

                            $date = $isCurrentMonth ? Carbon::create($currentYear, $currentMonth, $day) : null;
                            $isWednesday = $date?->isWednesday() ?? false;
                            $isFriday = $date?->isFriday() ?? false;
                            $isAvailable = $isWednesday || $isFriday;
                            $isPast = $date?->lt($today) ?? true;
                            $isBooked = $isCurrentMonth && in_array($day, $bookedDays);
                            $canClick = $isCurrentMonth && $isAvailable && !$isPast && !$isBooked;

                            // background class
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
                                onclick="openCoachingBooking({{ $day }})"
                            @elseif($isBooked)
                                onclick="showCoachingDetail({{ $day }})"
                            @endif
                        >
                            <div class="font-semibold">{{ $day }}</div>

                            @if($isBooked)
                                @php
                                    $bookingInfo = collect($bookings)->firstWhere(fn($b) => (int) Carbon::parse($b->tanggal)->day === $day);
                                @endphp
                                <div class="text-xs text-green-700 font-medium mt-1">
                                    {{ $bookingInfo->layanan ?? 'Coaching' }}<br>{{ $bookingInfo->pic ?? '-' }}
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
                    <div class="w-4 h-4 border-2 border-gray-300 mr-2"></div>
                    <span>Tersedia</span>
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

    <!-- Booking Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800">Antrian Pengajuan Coaching Clinic</h2>
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
                        <th class="py-3 px-4 text-left font-medium text-gray-700 border-b">Layanan</th>
                        <th class="py-3 px-4 text-left font-medium text-gray-700 border-b">Agenda</th>
                        <th class="py-3 px-4 text-left font-medium text-gray-700 border-b">PIC</th>
                        <th class="py-3 px-4 text-left font-medium text-gray-700 border-b">No. Telp</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4 border-b">
                            <form action="#" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Hapus pengajuan ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                        <td class="py-3 px-4 border-b">
                            @php
                                $status = $booking->status_verifikasi;
                                switch(strtolower($status)) {
                                    case 'disetujui':
                                        $bg = 'bg-green-100 text-green-800';
                                        break;
                                    case 'pending':
                                        $bg = 'bg-yellow-100 text-yellow-800';
                                        break;
                                    case 'ditolak':
                                        $bg = 'bg-red-100 text-red-800';
                                        break;
                                    default:
                                        $bg = 'bg-gray-100 text-gray-800';
                                }
                            @endphp
                            <span class="px-2 py-1 rounded-full text-sm font-medium {{ $bg }}">
                                {{ $status }}
                            </span>
                        </td>
                        <td class="py-3 px-4 border-b">{{ $booking->keterangan }}</td>
                        <td class="py-3 px-4 border-b font-mono">
                            CCA{{ date('Ymd', strtotime($booking->tanggal)) }}{{ $booking->id_coaching }}
                        </td>
                        <td class="py-3 px-4 border-b">
                            {{ \Carbon\Carbon::parse($booking->tanggal)->locale('id')->isoFormat('D MMMM YYYY') }}
                        </td>
                        <td class="py-3 px-4 border-b">{{ $booking->layanan }}</td>
                        <td class="py-3 px-4 border-b">{{ $booking->keterangan }}</td>
                        <td class="py-3 px-4 border-b">{{ $booking->pic }}</td>
                        <td class="py-3 px-4 border-b">{{ $booking->no_telp }}</td>
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
        <form id="coachingForm" action="#" method="POST" class="p-4">
            @csrf
            <input type="hidden" id="coachingSelectedDate" name="tanggal">

            <div class="space-y-4">
                <div class="grid grid-cols-3 gap-2 items-center">
                    <label class="text-sm font-medium text-gray-700">Tanggal*</label>
                    <div class="col-span-2">
                        <input type="text" id="coachingDisplayDate" class="w-full px-3 py-2 border border-gray-300 rounded" readonly>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-2 items-center">
                    <label class="text-sm font-medium text-gray-700">Layanan*</label>
                    <div class="col-span-2">
                        <select name="layanan" class="w-full px-3 py-2 border border-gray-300 rounded" required>
                            <option value="">Pilih Layanan</option>
                            <option value="Oranya Cawana">Oranya Cawana</option>
                            <option value="TTL Design">TTL Design</option>
                            <option value="Website & Aplikasi">Website & Aplikasi</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-2 items-center">
                    <label class="text-sm font-medium text-gray-700">Agenda*</label>
                    <div class="col-span-2">
                        <input type="text" name="keterangan" class="w-full px-3 py-2 border border-gray-300 rounded" required>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-2 items-center">
                    <label class="text-sm font-medium text-gray-700">PIC*</label>
                    <div class="col-span-2">
                        <input type="text" name="pic" class="w-full px-3 py-2 border border-gray-300 rounded" required>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-2 items-center">
                    <label class="text-sm font-medium text-gray-700">No. Telp*</label>
                    <div class="col-span-2">
                        <input type="tel" name="no_telp" class="w-full px-3 py-2 border border-gray-300 rounded" required>
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

@push('scripts')
<script>
    function openCoachingBooking(day) {
        const dateStr = `${day} Januari 2026`;
        document.getElementById('coachingDisplayDate').value = dateStr;
        document.getElementById('coachingSelectedDate').value = `2026-01-${day.toString().padStart(2, '0')}`;
        
        document.getElementById('coachingModal').classList.remove('hidden');
        document.getElementById('coachingModal').classList.add('flex');
    }
    
    function closeCoachingModal() {
        document.getElementById('coachingModal').classList.remove('flex');
        document.getElementById('coachingModal').classList.add('hidden');
    }
    
    function showCoachingDetail(day) {
        const dateStr = `${day} Januari 2026`;
        const isWednesday = [7, 14, 21, 28].includes(day);
        const layanan = isWednesday ? 'Oranya Cawana' : 'Website & Aplikasi';
        
        alert(`Detail Coaching Clinic untuk ${dateStr}\n\n` +
              `Tanggal: ${dateStr}\n` +
              `Layanan: ${layanan}\n` +
              `Agenda: Konsultasi Digital Marketing\n` +
              `PIC: {{ Auth::user()->nama_pic }}\n` +
              `No. Telp: {{ Auth::user()->kontak_pic }}\n` +
              `Instansi: {{ Auth::user()->nama_opd }}`);
    }
</script>
@endpush
@endsection
