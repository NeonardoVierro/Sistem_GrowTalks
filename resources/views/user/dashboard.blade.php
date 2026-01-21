@extends('layouts.app')

@section('title', 'User Dashboard')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">User Dashboard</h1>
        <div class="flex space-x-4 mt-2">
            <a href="{{ route('podcast.index') }}" class="text-blue-600 hover:text-blue-800">Podcast</a>
            <a href="{{ route('coaching.index') }}" class="text-blue-600 hover:text-blue-800">Coaching</a>
        </div>
    </div>

    <!-- Calendar Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
        <!-- Calendar Header -->
        <div class="p-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800">January 2026</h2>
        </div>

        <!-- Calendar -->
        <div class="p-4">
            <!-- Days Header -->
            <div class="grid grid-cols-7 gap-1 mb-2">
                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $day)
                <div class="text-center font-medium text-gray-700 py-2">
                    {{ $day }}
                </div>
                @endforeach
            </div>

            <!-- Calendar Grid -->
            <div class="grid grid-cols-7 gap-1">
                <!-- Week 1 -->
                <div class="calendar-day disabled p-3 text-center border rounded-lg">29</div>
                <div class="calendar-day disabled p-3 text-center border rounded-lg">30</div>
                <div class="calendar-day disabled p-3 text-center border rounded-lg">1</div>
                <div class="calendar-day disabled p-3 text-center border rounded-lg">2</div>
                <div class="calendar-day disabled p-3 text-center border rounded-lg">3</div>
                <div class="calendar-day disabled p-3 text-center border rounded-lg">4</div>
                <div class="calendar-day disabled p-3 text-center border rounded-lg"></div>

                <!-- Week 2 -->
                <div class="calendar-day disabled p-3 text-center border rounded-lg">5</div>
                <div class="calendar-day disabled p-3 text-center border rounded-lg">6</div>
                <div class="calendar-day coaching-day p-3 text-center border rounded-lg cursor-pointer hover:bg-blue-50"
                    onclick="showCoachingDetail('9 Januari 2026')">7</div>
                <div class="calendar-day disabled p-3 text-center border rounded-lg">8</div>
                <div class="calendar-day podcast-day booked p-3 text-center border rounded-lg bg-green-50 cursor-pointer"
                    onclick="showPodcastDetail('9 Januari 2026')">
                    9<br>
                    <span class="text-xs text-green-600">Podcast: Diskusi 1</span>
                </div>
                <div class="calendar-day disabled p-3 text-center border rounded-lg">10</div>
                <div class="calendar-day disabled p-3 text-center border rounded-lg">11</div>

                <!-- Week 3 -->
                <div class="calendar-day disabled p-3 text-center border rounded-lg">12</div>
                <div class="calendar-day disabled p-3 text-center border rounded-lg">13</div>
                <div class="calendar-day coaching-day p-3 text-center border rounded-lg cursor-pointer hover:bg-blue-50"
                    onclick="showCoachingDetail('14 Januari 2026')">14</div>
                <div class="calendar-day disabled p-3 text-center border rounded-lg">15</div>
                <div class="calendar-day podcast-day p-3 text-center border rounded-lg cursor-pointer hover:bg-blue-50"
                    onclick="openPodcastBooking()">16</div>
                <div class="calendar-day disabled p-3 text-center border rounded-lg">17</div>
                <div class="calendar-day disabled p-3 text-center border rounded-lg">18</div>

                <!-- Week 4 -->
                <div class="calendar-day disabled p-3 text-center border rounded-lg">19</div>
                <div class="calendar-day disabled p-3 text-center border rounded-lg">20</div>
                <div class="calendar-day coaching-day p-3 text-center border rounded-lg cursor-pointer hover:bg-blue-50"
                    onclick="openCoachingBooking('21 Januari 2026')">21</div>
                <div class="calendar-day disabled p-3 text-center border rounded-lg">22</div>
                <div class="calendar-day podcast-day p-3 text-center border rounded-lg cursor-pointer hover:bg-blue-50"
                    onclick="openPodcastBooking('23 Januari 2026')">23</div>
                <div class="calendar-day disabled p-3 text-center border rounded-lg">24</div>
                <div class="calendar-day disabled p-3 text-center border rounded-lg">25</div>

                <!-- Week 5 -->
                <div class="calendar-day disabled p-3 text-center border rounded-lg">26</div>
                <div class="calendar-day disabled p-3 text-center border rounded-lg">27</div>
                <div class="calendar-day coaching-day p-3 text-center border rounded-lg cursor-pointer hover:bg-blue-50"
                    onclick="openCoachingBooking('28 Januari 2026')">28</div>
                <div class="calendar-day disabled p-3 text-center border rounded-lg">29</div>
                <div class="calendar-day podcast-day p-3 text-center border rounded-lg cursor-pointer hover:bg-blue-50"
                    onclick="openPodcastBooking('30 Januari 2026')">30</div>
                <div class="calendar-day disabled p-3 text-center border rounded-lg">31</div>
                <div class="calendar-day disabled p-3 text-center border rounded-lg">1</div>
            </div>
        </div>

        <!-- Podcast Diaries Section -->
        <div class="p-4 border-t border-gray-200">
            <h3 class="font-bold text-gray-800 mb-2">Podcast Diaries</h3>
            <p class="text-gray-600">Catatan dan agenda podcast Anda</p>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Recent Podcasts -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-4 border-b border-gray-200">
                <h3 class="font-bold text-gray-800">Podcast Terbaru</h3>
            </div>
            <div class="p-4">
                @if($recentPodcasts->count() > 0)
                @foreach($recentPodcasts as $podcast)
                <div class="mb-3 pb-3 border-b border-gray-100 last:border-0">
                    <div class="flex justify-between">
                        <span class="font-medium">{{ $podcast->keterangan }}</span>
                        <span
                            class="text-sm {{ $podcast->status_verifikasi == 'disetujui' ? 'text-green-600' : ($podcast->status_verifikasi == 'ditolak' ? 'text-red-600' : 'text-yellow-600') }}">
                            {{ ucfirst($podcast->status_verifikasi) }}
                        </span>
                    </div>
                    <div class="text-sm text-gray-600 mt-1">
                        {{ $podcast->tanggal->format('d M Y') }} • {{ $podcast->narasumber }}
                    </div>
                </div>
                @endforeach
                @else
                <p class="text-gray-500 text-center py-4">Belum ada podcast</p>
                @endif
            </div>
        </div>

        <!-- Recent Coachings -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-4 border-b border-gray-200">
                <h3 class="font-bold text-gray-800">Coaching Terbaru</h3>
            </div>
            <div class="p-4">
                @if($recentCoachings->count() > 0)
                @foreach($recentCoachings as $coaching)
                <div class="mb-3 pb-3 border-b border-gray-100 last:border-0">
                    <div class="flex justify-between">
                        <span class="font-medium">{{ $coaching->layanan }}</span>
                        <span
                            class="text-sm {{ $coaching->status_verifikasi == 'disetujui' ? 'text-green-600' : ($coaching->status_verifikasi == 'ditolak' ? 'text-red-600' : 'text-yellow-600') }}">
                            {{ ucfirst($coaching->status_verifikasi) }}
                        </span>
                    </div>
                    <div class="text-sm text-gray-600 mt-1">
                        {{ $coaching->tanggal->format('d M Y') }} • {{ $coaching->pic }}
                    </div>
                </div>
                @endforeach
                @else
                <p class="text-gray-500 text-center py-4">Belum ada coaching</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Podcast Detail Modal -->
<div id="podcastDetailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg w-full max-w-md">
        <div class="p-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">Detail Podcast</h3>
            <button onclick="closePodcastDetail()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-4">
            <div class="space-y-3">
                <div>
                    <label class="text-sm text-gray-600">Tanggal:</label>
                    <p class="font-medium" id="detailPodcastTanggal">9 Januari 2026</p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Waktu:</label>
                    <p class="font-medium" id="detailPodcastWaktu">13.00-16.00</p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Host:</label>
                    <p class="font-medium" id="detailPodcastHost">Widyoko</p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Judul:</label>
                    <p class="font-medium" id="detailPodcastJudul">Selamatkan Karir dan Keluarga Dengan Kenali, Cegah,
                        dan Lawan Stroke</p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Narasumber:</label>
                    <p class="font-medium" id="detailPodcastNarasumber">Ahmad Basuki</p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Instansi:</label>
                    <p class="font-medium" id="detailPodcastInstansi">{{ Auth::user()->nama_opd }}</p>
                </div>
            </div>
        </div>
        <div class="p-4 border-t border-gray-200 flex justify-end">
            <button onclick="closePodcastDetail()"
                class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">
                Tutup
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function showPodcastDetail(tanggal) {
        // Set data from database or static for demo
        document.getElementById('detailPodcastTanggal').textContent = tanggal;
        document.getElementById('detailPodcastWaktu').textContent = '13.00-16.00';
        document.getElementById('detailPodcastHost').textContent = 'Widyoko';
        document.getElementById('detailPodcastJudul').textContent =
            'Selamatkan Karir dan Keluarga Dengan Kenali, Cegah, dan Lawan Stroke';
        document.getElementById('detailPodcastNarasumber').textContent = 'Ahmad Basuki';
        document.getElementById('detailPodcastInstansi').textContent = '{{ Auth::user()->nama_opd }}';

        document.getElementById('podcastDetailModal').classList.remove('hidden');
        document.getElementById('podcastDetailModal').classList.add('flex');
    }

    function closePodcastDetail() {
        document.getElementById('podcastDetailModal').classList.remove('flex');
        document.getElementById('podcastDetailModal').classList.add('hidden');
    }

    function openPodcastBooking() {
        window.location.href = "{{ route('podcast.index') }}";
    }

    function openCoachingBooking() {
        window.location.href = "{{ route('coaching.index') }}";
    }

</script>
@endpush
@endsection
