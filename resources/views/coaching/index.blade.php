@extends('layouts.app')

@section('title', 'Coaching Clinic')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Coaching Clinic</h1>
        <p class="text-gray-600">Jadwal coaching clinic hanya tersedia setiap hari Rabu dan Jumat</p>
    </div>

    <!-- Calendar Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
        <div class="p-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800">January 2026</h2>
        </div>
        
        <!-- Days Header -->
        <div class="p-4 border-b border-gray-200 grid grid-cols-7 gap-1">
            @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $day)
            <div class="text-center font-medium text-gray-700 py-2">
                {{ $day }}
            </div>
            @endforeach
        </div>
        
        <!-- Calendar Grid -->
        <div class="p-4">
            <div class="grid grid-cols-7 gap-1">
                @php
                    $days = [
                        [29, 30, 1, 2, 3, 4, null],
                        [5, 6, 7, 8, 9, 10, 11],
                        [12, 13, 14, 15, 16, 17, 18],
                        [19, 20, 21, 22, 23, 24, 25],
                        [26, 27, 28, 29, 30, 31, 1]
                    ];
                    
                    $availableWednesdays = [7, 14, 21, 28]; // Rabu
                    $availableFridays = [9, 16, 23, 30]; // Jumat
                    $bookedDays = [7, 23]; // Contoh hari yang sudah dibooking
                @endphp
                
                @foreach($days as $week)
                    @foreach($week as $day)
                        @if($day === null)
                            <div class="p-3 text-center border rounded-lg bg-gray-50"></div>
                        @else
                            @php
                                $isWednesday = in_array($day, [7, 14, 21, 28]);
                                $isFriday = in_array($day, [2, 9, 16, 23, 30]);
                                $isAvailable = $isWednesday || $isFriday;
                                $isBooked = in_array($day, $bookedDays);
                                $isPast = $day < 15; // Contoh: hari ini 15 Januari
                            @endphp
                            
                            <div class="calendar-day p-3 text-center border rounded-lg 
                                {{ !$isAvailable ? 'disabled bg-gray-50 text-gray-400' : '' }}
                                {{ $isAvailable && !$isBooked && !$isPast ? 'coaching-day cursor-pointer hover:bg-orange-50' : '' }}
                                {{ $isBooked ? 'booked bg-green-50 border-green-300 cursor-pointer' : '' }}
                                {{ $isPast && $isAvailable ? 'bg-gray-100 text-gray-400' : '' }}"
                                @if($isAvailable && !$isPast)
                                    onclick="{{ $isBooked ? 'showCoachingDetail('.$day.')' : 'openCoachingBooking('.$day.')' }}"
                                @endif>
                                {{ $day }}
                                @if($isBooked)
                                    <div class="text-xs mt-1 text-green-600 font-medium">
                                        Coaching<br>{{ Auth::user()->nama_opd }}
                                    </div>
                                @elseif($isAvailable && !$isPast)
                                    <div class="text-xs mt-1 {{ $isWednesday ? 'text-purple-600' : 'text-orange-600' }}">
                                        {{ $isWednesday ? 'Rabu' : 'Jumat' }}<br>Available
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endforeach
                @endforeach
            </div>
            
            <!-- Legend -->
            <div class="mt-4 flex flex-wrap gap-4 text-sm">
                <div class="flex items-center">
                    <div class="w-4 h-4 border-2 border-purple-500 mr-2"></div>
                    <span>Rabu (Tersedia)</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 border-2 border-orange-500 mr-2"></div>
                    <span>Jumat (Tersedia)</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-green-100 border border-green-300 mr-2"></div>
                    <span>Sudah Dibooking</span>
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
                            <form action="{{ route('coaching.destroy', $booking->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-800"
                                        onclick="return confirm('Hapus pengajuan ini?')">
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
                            <button class="text-gray-600 hover:text-gray-800">
                                ...
                            </button>
                        </td>
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

<!-- Coaching Booking Modal -->
<div id="coachingModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg w-full max-w-md">
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
                <!-- Table-like layout like in screenshot -->
                <div class="grid grid-cols-3 gap-2 items-center">
                    <label class="text-sm font-medium text-gray-700">Tanggal*</label>
                    <div class="col-span-2">
                        <input type="text" id="coachingDisplayDate" 
                               class="w-full px-3 py-2 border border-gray-300 rounded" readonly>
                    </div>
                </div>
                
                <div class="grid grid-cols-3 gap-2 items-center">
                    <label class="text-sm font-medium text-gray-700">Layanan*</label>
                    <div class="col-span-2">
                        <select name="layanan" 
                                class="w-full px-3 py-2 border border-gray-300 rounded focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                                required>
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
                        <input type="text" name="keterangan" 
                               class="w-full px-3 py-2 border border-gray-300 rounded focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                               placeholder="Masukkan Agenda"
                               required>
                    </div>
                </div>
                
                <div class="grid grid-cols-3 gap-2 items-center">
                    <label class="text-sm font-medium text-gray-700">PIC*</label>
                    <div class="col-span-2">
                        <input type="text" name="pic" 
                               class="w-full px-3 py-2 border border-gray-300 rounded focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                               placeholder="Masukkan PIC"
                               required>
                    </div>
                </div>
                
                <div class="grid grid-cols-3 gap-2 items-center">
                    <label class="text-sm font-medium text-gray-700">No. Telp*</label>
                    <div class="col-span-2">
                        <input type="tel" name="no_telp" 
                               class="w-full px-3 py-2 border border-gray-300 rounded focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                               placeholder="Masukkan No. Telp"
                               required>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <input type="checkbox" id="coachingPersetujuan" name="persetujuan" 
                           class="mt-1 mr-2" required>
                    <label for="coachingPersetujuan" class="text-sm text-gray-700">
                        Mempunyai dan menyetujui apabila ada perubahan jadwal coaching clinic oleh pengelola layanan
                    </label>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeCoachingModal()" 
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Ajukan
                </button>
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