<!-- resources/views/public/dashboard.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
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
    </style>
</head>

<!-- BODY pakai scroll snap biar rasa slider -->
<body class="bg-gray-100 h-screen overflow-y-scroll scroll-smooth snap-y snap-mandatory">

<!-- NAVBAR -->
<nav class="w-full bg-white shadow fixed top-0 left-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <img src="/images/logo_diskominfo.png" class="h-10" alt="logo">
        </div>

        <!-- LOGIN BUTTON -->
        <a href="{{ route('login') }}"
           class="px-5 py-2 border rounded-full text-sm hover:bg-gray-100 transition">
            Login
        </a>
    </div>
</nav>


<!-- ================= HERO / GROWTALKS (SLIDE 1) ================= -->
<section id="hero" class="min-h-screen snap-start flex items-center bg-white relative overflow-hidden">
    <!-- LAYER BATIK OPACITY 50 -->
    <div class="absolute inset-0 opacity-50 pointer-events-none z-0"
        style="
            background-image:
                url('/images/batik.png'),
                url('/images/batik.png'),
                url('/images/batik.png'),
                url('/images/batik.png'),
                url('/images/batik.png'),
                url('/images/batik.png'),
                url('/images/batik.png'),
                url('/images/batik.png');
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
        <div>
            <h1 class="text-6xl font-oswald uppercase tracking-tighter mb-4">GROWTALKS</h1>
            <p class="text-gray-600 mb-6 text-lg">
                Ajukan agenda dan ide untuk podcast dan coaching clinic kalian dengan mudah dan efektif.
            </p>

            <!-- BUTTON SLIDE KE BAWAH -->
            <button onclick="slideTo('about')"
   class="inline-block bg-blue-600 text-white px-6 py-3 rounded-full shadow hover:bg-blue-700 transition">
   About GrowTalks
</button>
        </div>

        <div class="relative h-[650px]">
            <!-- gambar atas -->
            <img src="/images/podcast_pub.png" class="absolute w-[400px] top-[-60px] right-00 z-20">
            <!-- gambar bawah (geser kiri + turun) -->
            <img src="/images/coaching_pub.png" class="absolute w-[400px] top-40 right-[30px] z-20">
        </div>
    </div>
</section>

<!-- ================= ABOUT GROWTALKS (SLIDE 2) ================= -->
<section id="about" class="min-h-screen snap-start flex items-center bg-gray-50 relative overflow-hidden">
    <!-- BATIK BACKGROUND -->
    <div class="absolute inset-0 opacity-40 pointer-events-none z-0"
        style="
            background-image: url('/images/batik.png'), url('/images/batik.png');
            background-repeat: no-repeat;
            background-size: 420px, 420px;
            background-position: left -120px top 100px, right -120px bottom 120px;
        ">
    </div>

    <div class="max-w-6xl mx-auto px-6 z-10">
        <h2 class="text-6xl font-oswald uppercase text-center mb-10">
            About GrowTalks
        </h2>

        <p class="text-center text-gray-600 max-w-4xl mx-auto mb-16 text-lg leading-relaxed">
            <strong>GrowTalks</strong> merupakan sebuah platform digital yang dikembangkan oleh
            <strong>Dinas Komunikasi, Informatika, Statistik, dan Persandian (Diskominfo)
            Kota Surakarta</strong> sebagai sarana pendukung pelayanan publik berbasis teknologi
            informasi.
            <br><br>
            Website ini dirancang untuk memfasilitasi proses pengajuan, pengelolaan, dan
            pemantauan kegiatan secara terstruktur, transparan, dan efisien, khususnya
            dalam pelaksanaan kegiatan komunikasi dan pengembangan kapasitas sumber daya manusia.
        </p>

        <!-- LAYANAN DISKOMINFO -->
        <h3 class="text-3xl font-semibold text-center mb-10">
            Layanan Kegiatan GrowTalks
        </h3>

        <div class="grid md:grid-cols-2 gap-12">
            <!-- PODCAST -->
            <div class="bg-white rounded-2xl shadow-lg p-8 hover:scale-105 transition">
                <div class="text-blue-600 text-4xl mb-4 text-center">🎙️</div>
                <h4 class="font-semibold text-2xl mb-4 text-center">
                    Podcast
                </h4>
                <p class="text-gray-600 text-sm leading-relaxed text-center">
                    <p class="text-gray-600 text-sm leading-relaxed text-center">
                <strong>KOMINPOD (Kominfo Podcast)</strong> merupakan media komunikasi strategis
                yang dikelola oleh Dinas Komunikasi, Informatika, Statistik, dan Persandian
                Kota Surakarta sebagai sarana edukatif bagi masyarakat.
                <br><br>
                Podcast ini menyajikan informasi seputar kebijakan publik, layanan pemerintah,
                serta isu-isu terkini yang disampaikan langsung dari sumber resmi.
                KOMINPOD disiarkan secara rutin setiap hari Jumat melalui
                <strong>YouTube</strong> dan <strong>Radio Konata</strong>,
                dengan tujuan memperkuat transparansi, meningkatkan literasi informasi,
                serta membangun komunikasi publik yang informatif dan interaktif.
            </p>
            </div>

            <!-- COACHING CLINIC -->
            <div class="bg-white rounded-2xl shadow-lg p-8 hover:scale-105 transition">
                <div class="text-green-600 text-4xl mb-4 text-center">🧠</div>
                <h4 class="font-semibold text-2xl mb-4 text-center">
                    Coaching Clinic
                </h4>
                <p class="text-gray-600 text-sm leading-relaxed text-center">
                     <strong>Coaching Clinic Diskominfo SP Kota Surakarta</strong> merupakan layanan
                pendampingan teknis yang diselenggarakan secara rutin setiap minggu,
                yaitu pada hari <strong>Rabu dan Jumat</strong>, bertempat di
                <strong>Ruang Upakari 3</strong>.
                <br><br>
                Program ini bertujuan untuk memfasilitasi konsultasi dan pendampingan bagi
                perangkat daerah dalam implementasi
                <strong>Tanda Tangan Elektronik (E-Sign)</strong>,
                pengelolaan website dan aplikasi, serta pengembangan desain grafis
                menggunakan platform <strong>Canva</strong>,
                guna mendukung peningkatan kualitas layanan digital pemerintahan.
                </p>
            </div>
        </div>
    </div>
</section>


<!-- ================= PODCAST (SLIDE 2) ================= -->
<section id="podcast" class="min-h-screen snap-start flex items-center bg-white relative overflow-hidden">

    <!-- BATIK BACKGROUND -->
    <div class="absolute inset-0 opacity-50 pointer-events-none z-0"
        style="
            background-image: url('/images/batik.png');
            background-repeat: repeat;
            background-size: 420px;
        ">
    </div>

    <div class="max-w-7xl mx-auto px-6 w-full z-10">
        <h2 class="text-6xl font-extrabold text-center mb-14 font-oswald uppercase">
            PODCAST
        </h2>

        <!-- SLIDER -->
        <div class="relative w-full h-[420px] flex items-center justify-center">

            <!-- IMAGE 1 -->
            <img src="/images/podcast_dummy.jpeg"
                class="podcast-slide absolute w-[420px] rounded-2xl shadow-xl transition-all duration-700 ease-in-out">

            <!-- IMAGE 2 -->
            <img src="/images/p1.jpg"
                class="podcast-slide absolute w-[420px] rounded-2xl shadow-xl transition-all duration-700 ease-in-out">

            <!-- IMAGE 3 -->
            <img src="/images/p2.jpg"
                class="podcast-slide absolute w-[420px] rounded-2xl shadow-xl transition-all duration-700 ease-in-out">
        </div>
    </div>
</section>


<!-- ================= COACHING CLINIC (SLIDE 3) ================= -->
<section id="coaching" class="min-h-screen snap-start flex items-center bg-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-50 pointer-events-none z-0"
        style="
            background-image:
                url('/images/batik.png'),
                url('/images/batik.png'),
                url('/images/batik.png'),
                url('/images/batik.png'),
                url('/images/batik.png'),
                url('/images/batik.png'),
                url('/images/batik.png');
            background-repeat: no-repeat;
            background-size: 420px;
            background-position:
                left 500px top 10px,
                left -90px top 150px,
                right 120px top 150px,
                right 10px top 350px,
                right 500px top 450px,
                left 50px top 580px,
                right -20px top 630px;
        ">
    </div>

    <div class="max-w-7xl mx-auto px-6 w-full z-10">
        <h2 class="text-6xl font-extrabold text-center mb-14 font-oswald uppercase">
            COACHING CLINIC
        </h2>

        <!-- SLIDER -->
        <div class="relative h-[420px] flex items-center justify-center">
            <img src="/images/cc1.jpg"
                 class="cc-slide absolute w-[420px] rounded-2xl shadow-xl transition-all duration-700 ease-in-out">
            <img src="/images/cc2.jpg"
                 class="cc-slide absolute w-[420px] rounded-2xl shadow-xl transition-all duration-700 ease-in-out">
            <img src="/images/cc3.jpg"
                 class="cc-slide absolute w-[420px] rounded-2xl shadow-xl transition-all duration-700 ease-in-out">
        </div>
    </div>
</section>



<!-- FOOTER -->
<footer class="bg-gray-100 py-8 text-center text-gray-500 snap-start">
    © {{ date('Y') }} GrowTalks — Diskominfo Surakarta
</footer>


<!-- SCRIPT -->
<script>
/* ================= SCROLL SLIDE ================= */
function slideTo(id) {
    document.getElementById(id).scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    });
}

/* ================= PODCAST SLIDER ================= */
const podcastSlides = document.querySelectorAll('.podcast-slide');
let podcastCurrent = 0;

function updatePodcastSlides() {
    podcastSlides.forEach((slide, index) => {
        slide.className = slide.className.replace(
            /z-\d+|scale-\d+|translate-x-\S+|opacity-\d+|blur-sm/g, ''
        );

        if (index === podcastCurrent) {
            slide.classList.add('z-30','scale-100','translate-x-0','opacity-100');
        } else if (index === (podcastCurrent + 1) % podcastSlides.length) {
            slide.classList.add('z-20','scale-90','translate-x-40','opacity-70','blur-sm');
        } else {
            slide.classList.add('z-10','scale-90','-translate-x-40','opacity-70','blur-sm');
        }
    });
}

if (podcastSlides.length > 0) {
    setInterval(() => {
        podcastCurrent = (podcastCurrent + 1) % podcastSlides.length;
        updatePodcastSlides();
    }, 3000);

    updatePodcastSlides();
}

/* ================= COACHING CLINIC SLIDER ================= */
const ccSlides = document.querySelectorAll('.cc-slide');
let ccCurrent = 0;

function updateCCSlides() {
    ccSlides.forEach((slide, index) => {
        slide.className = slide.className.replace(
            /z-\d+|scale-\d+|translate-x-\S+|opacity-\d+|blur-sm/g, ''
        );

        if (index === ccCurrent) {
            slide.classList.add('z-30','scale-100','translate-x-0','opacity-100');
        } else if (index === (ccCurrent + 1) % ccSlides.length) {
            slide.classList.add('z-20','scale-90','translate-x-40','opacity-70','blur-sm');
        } else {
            slide.classList.add('z-10','scale-90','-translate-x-40','opacity-70','blur-sm');
        }
    });
}

if (ccSlides.length > 0) {
    setInterval(() => {
        ccCurrent = (ccCurrent + 1) % ccSlides.length;
        updateCCSlides();
    }, 3500);

    updateCCSlides();
}
</script>

</body>
</html>