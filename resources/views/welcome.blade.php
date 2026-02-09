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
            <button onclick="slideTo('podcast')"
               class="inline-block bg-blue-600 text-white px-6 py-3 rounded-full shadow hover:bg-blue-700 transition">
                Eksplor lebih lanjut
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


<!-- ================= PODCAST (SLIDE 2) ================= -->
<section id="podcast" class="min-h-screen snap-start flex items-center bg-white relative overflow-hidden">
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
            background-size: 420px, 420px, 420px, 420px, 420px, 420px, 420px;
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
        <h2 class="text-6xl font-extrabold text-center mb-14 font-oswald uppercase">PODCAST</h2>

        <div class="grid md:grid-cols-3 gap-10">
            @for ($i = 0; $i < 3; $i++)
            <div class="z-20 bg-orange-400 rounded-2xl shadow-lg p-4 hover:scale-105 transition">
                <img src="/images/podcast_dummy.jpeg" class="rounded-xl w-full" alt="podcast">
            </div>
            @endfor
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
            background-size: 420px, 420px, 420px, 420px, 420px, 420px, 420px;
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
    <div class="max-w-7xl mx-auto px-6 w-full">
        <h2 class="text-6xl font-extrabold text-center mb-14 font-oswald uppercase">COACHING CLINIC</h2>

        <div class="grid md:grid-cols-3 gap-10">
            @for ($i = 0; $i < 3; $i++)
            <div class="z-20 bg-orange-400 rounded-2xl shadow-lg p-4 hover:scale-105 transition">
                <img src="/images/podcast_dummy.jpeg" class="rounded-xl w-full" alt="coaching">
            </div>
            @endfor
        </div>
    </div>
</section>


<!-- FOOTER -->
<footer class="bg-gray-100 py-8 text-center text-gray-500 snap-start">
    © {{ date('Y') }} GrowTalks — Diskominfo Surakarta
</footer>


<!-- SCRIPT SLIDER SCROLL -->
<script>
function slideTo(id) {
    document.getElementById(id).scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    });
}
</script>

</body>
</html>