<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GrowTalks - Dashboard Publik</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@700&family=Poppins:wght@400;600&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
            overflow-x: hidden;
        }

        .font-oswald { font-family: 'Oswald', sans-serif; }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Snap behavior stability */
        .snap-container {
            height: 100vh;
            scroll-snap-type: y mandatory;
            overflow-y: scroll;
            scroll-behavior: smooth;
        }
        .snap-section {
            scroll-snap-align: start;
            min-height: 100vh;
            position: relative;
        }
    </style>
</head>

<body class="bg-gray-100 snap-container">

<nav class="w-full bg-white/80 backdrop-blur-md shadow fixed top-0 left-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo_diskominfo.png') }}" class="h-10" alt="logo diskominfo">
            <div class="flex flex-col leading-tight border-l pl-3 border-gray-300 hidden sm:flex">
                <span class="font-oswald text-xl tracking-tighter text-blue-800">GROWTALKS</span>
                <span class="text-[10px] uppercase tracking-widest text-gray-500">Diskominfo Surakarta</span>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <a href="{{ route('login') }}"
               class="px-6 py-2 border-2 border-blue-600 text-blue-600 rounded-full text-sm font-semibold hover:bg-blue-600 hover:text-white transition-all duration-300 shadow-sm">
                Login Dashboard
            </a>
        </div>
    </div>
</nav>

<section id="hero" class="snap-section flex items-center bg-white overflow-hidden">
    <div class="absolute inset-0 opacity-40 pointer-events-none z-0"
        style="
            background-image:
                url('{{ asset('images/batik.png') }}'),
                url('{{ asset('images/batik.png') }}'),
                url('{{ asset('images/batik.png') }}'),
                url('{{ asset('images/batik.png') }}'),
                url('{{ asset('images/batik.png') }}'),
                url('{{ asset('images/batik.png') }}'),
                url('{{ asset('images/batik.png') }}'),
                url('{{ asset('images/batik.png') }}');
            background-repeat: no-repeat;
            background-size: 420px, 420px, 420px, 420px, 420px, 420px, 420px, 420px;
            background-position:
                left 250px top 10px,
                right -20px top 10px,
                left -200px top 150px,
                right 380px top 150px,
                right -260px top 300px,
                right 500px top 450px,
                left 50px top 580px,
                right -20px top 630px;
        ">
    </div>

    <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-10 items-center w-full z-10">
        <div class="space-y-6">
            <div class="inline-block px-4 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold tracking-widest uppercase">
                Digital Transformation
            </div>
            <h1 class="text-6xl md:text-8xl font-oswald uppercase tracking-tighter leading-none text-gray-900">
                GROW<span class="text-blue-600">TALKS</span>
            </h1>
            <p class="text-gray-600 text-lg md:text-xl leading-relaxed max-w-lg">
                Ajukan agenda dan ide untuk podcast dan coaching clinic kalian dengan mudah dan efektif dalam satu genggaman platform.
            </p>

            <div class="flex flex-wrap gap-4 pt-4">
                <button onclick="slideTo('about')"
                   class="inline-block bg-blue-600 text-white px-8 py-4 rounded-full shadow-xl hover:bg-blue-700 transform hover:-translate-y-1 transition-all font-bold">
                   Jelajahi GrowTalks
                </button>
                <button onclick="slideTo('podcast')"
                   class="inline-block bg-gray-100 text-gray-700 px-8 py-4 rounded-full shadow hover:bg-gray-200 transition-all font-bold">
                   Lihat Gallery
                </button>
            </div>
        </div>

        <div class="relative h-[650px] hidden md:block">
            <img src="{{ asset('images/podcast_pub.png') }}" 
                 class="absolute w-[450px] top-[-20px] right-[-20px] z-20 drop-shadow-2xl animate-pulse" 
                 style="animation-duration: 4s;">
            <img src="{{ asset('images/coaching_pub.png') }}" 
                 class="absolute w-[450px] top-56 right-[120px] z-10 drop-shadow-2xl opacity-90">
            
            <div class="absolute top-40 right-40 w-64 h-64 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
            <div class="absolute top-20 right-10 w-64 h-64 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
        </div>
    </div>
</section>

<section id="about" class="snap-section flex items-center bg-gray-50 overflow-hidden">
    <div class="absolute inset-0 opacity-40 pointer-events-none z-0"
        style="
            background-image: url('{{ asset('images/batik.png') }}'), url('{{ asset('images/batik.png') }}');
            background-repeat: no-repeat;
            background-size: 420px, 420px;
            background-position: left -120px top 100px, right -120px bottom 120px;
        ">
    </div>

    <div class="max-w-6xl mx-auto px-6 z-10 py-12">
        <div class="text-center mb-12">
            <h2 class="text-5xl md:text-6xl font-oswald uppercase mb-4 text-gray-800">
                About GrowTalks
            </h2>
            <div class="h-1.5 w-32 bg-blue-600 mx-auto rounded-full"></div>
        </div>

        <div class="bg-white/60 backdrop-blur-sm p-8 md:p-12 rounded-[3rem] shadow-2xl border border-white/50 mb-16">
            <p class="text-center text-gray-700 text-lg md:text-xl leading-relaxed">
                <span class="text-blue-600 font-bold italic">GrowTalks</span> merupakan sebuah platform digital yang dikembangkan oleh
                <strong>Dinas Komunikasi, Informatika, Statistik, dan Persandian (Diskominfo)
                Kota Surakarta</strong> sebagai sarana pendukung pelayanan publik berbasis teknologi
                informasi. 
                <br><br>
                Website ini dirancang untuk memfasilitasi proses pengajuan, pengelolaan, dan
                pemantauan kegiatan secara terstruktur, transparan, dan efisien, khususnya
                dalam pelaksanaan kegiatan komunikasi dan pengembangan kapasitas sumber daya manusia.
            </p>
        </div>

        <h3 class="text-3xl font-oswald text-center mb-12 tracking-wide text-gray-600 uppercase italic">
            Layanan Kegiatan Utama
        </h3>

        <div class="grid md:grid-cols-2 gap-10">
            <div class="group bg-white rounded-[2.5rem] shadow-lg p-10 hover:shadow-2xl transition-all duration-500 border-b-8 border-blue-500 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <img src="{{ asset('images/batik.png') }}" class="w-32 rotate-12">
                </div>
                <div class="bg-blue-50 w-20 h-20 rounded-2xl flex items-center justify-center text-4xl mb-6 shadow-inner">🎙️</div>
                <h4 class="font-bold text-3xl mb-4 text-gray-800 italic">Podcast</h4>
                <div class="text-gray-600 leading-relaxed space-y-4">
                    <p><strong>KOMINPOD (Kominfo Podcast)</strong> dikelola oleh Diskominfo SP Kota Surakarta sebagai media edukasi kebijakan publik.</p>
                    <p class="text-sm bg-blue-50 p-3 rounded-xl border-l-4 border-blue-400">
                        📍 Disiarkan setiap <strong>Jumat</strong> melalui YouTube dan Radio Konata.
                    </p>
                </div>
            </div>

            <div class="group bg-white rounded-[2.5rem] shadow-lg p-10 hover:shadow-2xl transition-all duration-500 border-b-8 border-green-500 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <img src="{{ asset('images/batik.png') }}" class="w-32 -rotate-12">
                </div>
                <div class="bg-green-50 w-20 h-20 rounded-2xl flex items-center justify-center text-4xl mb-6 shadow-inner">🧠</div>
                <h4 class="font-bold text-3xl mb-4 text-gray-800 italic">Coaching Clinic</h4>
                <div class="text-gray-600 leading-relaxed space-y-4">
                    <p>Layanan pendampingan teknis mingguan untuk implementasi E-Sign, web, dan desain grafis perangkat daerah.</p>
                    <p class="text-sm bg-green-50 p-3 rounded-xl border-l-4 border-green-400">
                        📍 <strong>Rabu & Jumat</strong> bertempat di Ruang Upakari 3.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>


<section id="podcast" class="snap-section flex items-center bg-white overflow-hidden">
    <div class="absolute inset-0 opacity-10 pointer-events-none">
        <div class="grid grid-cols-4 gap-4 transform -rotate-12 scale-150">
            @for($i=0; $i<16; $i++)
                <img src="{{ asset('images/batik.png') }}" class="w-full">
            @endfor
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 w-full z-10 py-12">
        <h2 class="text-6xl font-oswald uppercase text-center mb-4 text-gray-900 tracking-tighter">
            PODCAST <span class="text-blue-600 italic">GALLERY</span>
        </h2>
        <p class="text-center text-gray-500 mb-16 font-medium tracking-widest uppercase">Merekam Jejak Literasi Digital</p>

        <div class="relative w-full h-[450px] flex items-center justify-center">
            @if($podcasts->count() > 0)
            <div class="relative w-full h-[450px] flex items-center justify-center">

                @foreach($podcasts as $podcast)
                    <div class="podcast-slide absolute w-[300px] md:w-[500px] transition-all duration-700">
                        <div class="bg-white rounded-[2rem] shadow-2xl overflow-hidden">

                            <img src="{{ asset('storage/'.$podcast->cover_path) }}"
                                onclick="openImage(this.src)"
                                class="w-full h-[300px] object-cover">

                            <div class="p-5 border-t">
                                <h3 class="font-semibold text-lg text-gray-800">
                                    {{ $podcast->nama_opd }}
                                </h3>

                                <p class="text-sm text-gray-500 mt-1">
                                    {{ \Carbon\Carbon::parse($podcast->tanggal)->format('d F Y') }}
                                </p>

                                <p class="text-sm text-gray-600 mt-2">
                                    {{ $podcast->keterangan }}
                                </p>
                            </div>

                        </div>
                    </div>
                @endforeach

            </div>

            @else
                <p class="text-center text-gray-500">
                    Belum ada podcast yang dipublikasikan.
                </p>
            @endif
        </div>
    </div>
</section>


<section id="coaching" class="snap-section flex items-center bg-blue-950 overflow-hidden">
    <div class="absolute inset-0 opacity-20 pointer-events-none" 
         style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;">
    </div>

    <div class="max-w-7xl mx-auto px-6 w-full z-10 py-12 text-white">
        <h2 class="text-6xl font-oswald uppercase text-center mb-4 tracking-tighter">
            COACHING <span class="text-blue-400">CLINIC</span>
        </h2>
        <div class="h-1 w-20 bg-blue-400 mx-auto mb-16"></div>

        <div class="relative w-full h-[450px] flex items-center justify-center">
            @if($coachings->count() > 0)
            <div class="relative w-full h-[450px] flex items-center justify-center">
                @foreach($coachings as $coaching)
                    <div class="podcast-slide absolute w-[300px] md:w-[500px] transition-all duration-700">
                        <div class="bg-white rounded-[2rem] shadow-2xl overflow-hidden">
                            <img src="{{ asset('storage/'.$coaching->dokumentasi_path) }}"
                                onclick="openImage(this.src)"
                                class="w-full h-[300px] object-cover">
                            <div class="p-5 border-t">
                                <h3 class="font-semibold text-lg text-gray-800">
                                    {{ $coaching->nama_opd }}
                                </h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ \Carbon\Carbon::parse($coaching->tanggal)->format('d F Y') }}
                                </p>
                                <p class="text-sm text-gray-600 mt-2">
                                    {{ $coaching->keterangan }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @else
                <p class="text-center text-gray-500">
                    Belum ada coaching yang dipublikasikan.
                </p>
            @endif
        </div>
    </div>
</section>

<footer class="bg-gray-100 py-8 text-center">

    <!-- Logo -->
    <img src="{{ asset('images/logo_diskominfo.png') }}" 
         class="h-12 mx-auto mb-3" 
         alt="Diskominfo Surakarta">

    <!-- Title -->
    <p class="text-gray-700 font-semibold text-base">
        GrowTalks — Digital Service Hub
    </p>

    <p class="text-gray-500 text-sm mb-4">
        Diskominfo SP Kota Surakarta
    </p>

    <!-- Social Media -->
    <div class="flex justify-center gap-6 mb-4">

        <!-- Website -->
        <a href="https://diskominfosp.surakarta.go.id/" 
           target="_blank"
           class="text-gray-600 hover:text-blue-600 transition transform hover:scale-110">

            <svg xmlns="http://www.w3.org/2000/svg" 
                 class="w-6 h-6" 
                 fill="none" 
                 viewBox="0 0 24 24" 
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" 
                      d="M12 2a10 10 0 100 20 10 10 0 000-20zm0 0c2.5 2.7 4 6 4 10s-1.5 7.3-4 10m0-20C9.5 4.7 8 8 8 12s1.5 7.3 4 10" />
            </svg>

        </a>

        <!-- Instagram -->
        <a href="https://www.instagram.com/diskominfosp_surakarta" 
           target="_blank"
           class="text-gray-600 hover:text-pink-600 transition transform hover:scale-110">

            <svg xmlns="http://www.w3.org/2000/svg" 
                 class="w-6 h-6" 
                 fill="none" 
                 viewBox="0 0 24 24" 
                 stroke="currentColor">
                <rect x="3" y="3" width="18" height="18" rx="5" stroke-width="1.8"/>
                <circle cx="12" cy="12" r="4" stroke-width="1.8"/>
                <circle cx="17.5" cy="6.5" r="1" fill="currentColor"/>
            </svg>

        </a>

        <!-- YouTube -->
        <a href="https://www.youtube.com/@diskominfospsurakarta8388" 
           target="_blank"
           class="text-gray-600 hover:text-red-600 transition transform hover:scale-110">

            <svg xmlns="http://www.w3.org/2000/svg" 
                 class="w-6 h-6" 
                 viewBox="0 0 24 24" 
                 fill="currentColor">
                <path d="M21.8 8s-.2-1.4-.8-2c-.8-.8-1.7-.8-2.1-.9C15.9 5 12 5 12 5h-.1s-3.9 0-6.9.1c-.4 0-1.3.1-2.1.9C2.3 6.6 2 8 2 8S2 9.6 2 11.2v1.6C2 14.4 2 16 2 16s.2 1.4.8 2c.8.8 1.9.8 2.4.9 1.7.1 6.8.1 6.8.1s3.9 0 6.9-.1c.4 0 1.3-.1 2.1-.9.6-.6.8-2 .8-2s.2-1.6.2-3.2v-1.6C22 9.6 21.8 8 21.8 8zM10 14.5v-5l5 2.5-5 2.5z"/>
            </svg>

        </a>

    </div>

    <p class="text-gray-400 text-xs tracking-widest uppercase">
        © {{ date('Y') }} GrowTalks. All Rights Reserved
    </p>

</footer>

<script>
/* Smooth Scroll Trigger */
function slideTo(id) {
    document.getElementById(id).scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    });
}

/* Slider Engine */
function initSlider(selector, interval) {
    const slides = document.querySelectorAll(selector);
    let current = 0;

    function update() {
        slides.forEach((slide, index) => {
            // Clean active classes
            slide.classList.remove('z-30', 'scale-100', 'translate-x-0', 'opacity-100', 'z-20', 'scale-90', 'translate-x-48', 'opacity-60', 'blur-sm', 'z-10', '-translate-x-48');
            
            if (index === current) {
                slide.classList.add('z-30', 'scale-100', 'translate-x-0', 'opacity-100');
                slide.style.filter = "none";
            } else if (index === (current + 1) % slides.length) {
                slide.classList.add('z-20', 'scale-90', 'translate-x-48', 'opacity-60', 'blur-sm');
            } else {
                slide.classList.add('z-10', 'scale-90', '-translate-x-48', 'opacity-60', 'blur-sm');
            }
        });
    }

    if (slides.length > 0) {
        setInterval(() => {
            current = (current + 1) % slides.length;
            update();
        }, interval);
        update();
    }
}

// Start Sliders on Load
document.addEventListener('DOMContentLoaded', () => {
    initSlider('.podcast-slide', 3000);
    initSlider('.cc-slide', 4000);
});
</script>

<script src="https://unpkg.com/heroicons@2.0.18/dist/heroicons.js"></script>

<script>
function openImage(src) {
    const lightbox = document.getElementById('lightbox');
    const img = document.getElementById('lightbox-img');

    img.src = src;
    lightbox.classList.remove('hidden');
    lightbox.classList.add('flex');
}

function closeImage() {
    const lightbox = document.getElementById('lightbox');
    lightbox.classList.add('hidden');
    lightbox.classList.remove('flex');
}
</script>

</body>
<!-- LIGHTBOX -->
<div id="lightbox"
     class="fixed inset-0 bg-black/80 hidden items-center justify-center z-[9999]"
     onclick="closeImage()">

    <img id="lightbox-img"
         class="max-w-[90%] max-h-[90%] rounded-2xl shadow-2xl">

    <!-- Close Button -->
    <button onclick="closeImage()"
            class="absolute top-6 right-6 text-white text-4xl font-bold">
        &times;
    </button>
</div>
</html>