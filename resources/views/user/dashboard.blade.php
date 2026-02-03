@extends('layouts.app')

@section('title', 'User Dashboard')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard User</h1>
    </div>
        <!-- CARD WRAPPER -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            <!-- PODCAST CARD -->
            <a href="{{ route('podcast.index') }}"
                class="block bg-white rounded-2xl shadow p-6 cursor-pointer
                    transition-all duration-300 ease-in-out
                    hover:-translate-y-1 hover:shadow-xl hover:scale-[1.0]">
                <div class="flex justify-center mb-4">
                    <span class="font-semibold text-black">
                        Podcast
                    </span>
                </div>
                <img src="{{ asset('images/podcast3.jpg') }}" alt="Podcast"
                    class="rounded-xl w-full h-49 object-cover mb-4">
                <p class="text-sm text-gray-600 text-justify">
                    <strong>Podcast GrowTalks</strong> merupakan podcast yang menghadirkan
                    obrolan interaktif bersama berbagai narasumber untuk membahas isu sosial,
                    pengalaman hidup, dan pengembangan diri. Setiap episode dirancang untuk
                    memberikan insight, inspirasi, serta perspektif baru yang relevan dengan
                    kehidupan sehari-hari.
                </p>
            </a>

            <!-- COACHING CARD -->
            <a href="{{ route('coaching.index') }}"
                class="block bg-white rounded-2xl shadow p-6 cursor-pointer
                    transition-all duration-300 ease-in-out
                    hover:-translate-y-1 hover:shadow-xl hover:scale-[1.0]">
                <div class="flex justify-center mb-4">
                    <span class="font-semibold text-black">
                        Coaching Clinic
                    </span>
                </div>
                <img src="{{ asset('images/coaching.jpg') }}" alt="Coaching"
                    class="rounded-xl w-full h-49 object-cover mb-4">
                <p class="text-sm text-gray-600 text-justify">
                    <strong>Coaching Clinic</strong> merupakan layanan pendampingan
                    yang dirancang untuk membantu peserta mengembangkan potensi diri
                    melalui sesi konsultasi dan diskusi terarah bersama mentor.
                    Program ini berfokus pada pengembangan keterampilan, pemecahan 
                    masalah, serta pencapaian tujuan personal maupun profesional.
                </p>
            </a>
        </div>
    
    
    <!-- Upcoming Agenda -->
    <div class="flex flex-col gap-1 pb-3 mt-7">
        <h2 class="text-lg font-bold tracking-wide text-black-700">
            Upcoming Agenda
        </h2>
        <p class="text-sm text-slate-500 italic">
            Riwayat agenda podcast dan coaching clinic yang telah diajukan
        </p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 mt-4 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full border border-gray-300 border-collapse">
                <thead>
                    <tr class="bg-blue-900 text-white">
                        <th class="px-6 py-3 text-left font-semibold">Tanggal</th>
                        <th class="px-6 py-3 text-left font-semibold">Layanan</th>
                        <th class="px-6 py-3 text-left font-semibold">Agenda</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">

                    @php
                    $agenda = collect();

                    foreach ($recentPodcasts as $p) {
                        $agenda->push([
                            'tanggal' => $p->tanggal,
                            'waktu' => $p->waktu ?? '-',
                            'host' => $p->nama_pic ?? '-',
                            'layanan' => 'Podcast',
                            'judul' => $p->judul ?? $p->keterangan,
                            'narasumber' => $p->narasumber ?? '-',
                        ]);
                    }

                    foreach ($recentCoachings as $c) {
                        $agenda->push([
                            'tanggal' => $c->tanggal,
                            'waktu' => $c->waktu ?? '-',
                            'host' => $c->mentor ?? '-',
                            'layanan' => 'Coaching Clinic',
                            'judul' => $c->topik ?? 'Coaching',
                            'narasumber' => '',
                        ]);
                    }

                    $agenda = $agenda->sortByDesc('tanggal');
                    @endphp


                    @forelse($agenda as $row)
                    <tr class="hover:bg-slate-50 transition">

                    {{-- TANGGAL + WAKTU + HOST --}}
                    <td class="px-6 py-4 text-sm">
                        <div class="font-medium">
                            {{ \Carbon\Carbon::parse($row['tanggal'])->locale('id')->isoFormat('D MMMM YYYY') }}
                        </div>

                        <!-- <div class="text-xs text-gray-500 mt-1">
                            Waktu: {{ $row['waktu'] }}
                        </div>

                        <div class="text-xs text-gray-500">
                            Host: {{ $row['host'] }}
                        </div> -->
                    </td>


                    {{-- LAYANAN --}}
                    <td class="px-6 py-4 text-sm font-medium">
                        @if($row['layanan'] == 'Podcast')
                            <span class="text-green-600">Podcast</span>
                        @else
                            <span class="text-blue-600">Coaching Clinic</span>
                        @endif
                    </td>


                    {{-- AGENDA --}}
                    <td class="px-6 py-4 text-sm break-words">
                        {{ $row['judul'] }}
                    </td>

                    </tr>

                    @empty
                    <tr>
                    <td colspan="3" class="text-center py-6 text-gray-500">
                    Belum ada agenda diajukan
                    </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
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
