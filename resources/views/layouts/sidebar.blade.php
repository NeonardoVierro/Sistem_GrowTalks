<aside class="fixed left-0 top-0 h-screen w-64 bg-blue-700 shadow-lg z-50 text-white">

    <!-- HEADER SIDEBAR -->
    <div class="p-6 border-b border-blue-600 flex flex-col items-center text-center">

        <!-- JUDUL UTAMA -->
        <h1 class="text-lg font-bold mb-2">
            GrowTalks
        </h1>

        <!-- LOGO PEMERINTAH DI ATAS TULISAN PODCAST -->
        <img 
            src="{{ asset('images/logos.png') }}" 
            alt="Logo Pemerintah"
            class="h-14 w-auto mb-2"
        >

        <!-- TULISAN DI BAWAH LOGO -->
        <p class="text-sm text-blue-200">
            Podcast & Coaching Clinic
        </p>

    </div>

    <!-- MENU NAVIGASI -->
    <nav class="p-4">
        <ul class="space-y-2">

            <li>
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center p-3 rounded-lg hover:bg-blue-600 
                   {{ request()->is('dashboard') ? 'bg-blue-600' : '' }}">
                    <i class="fas fa-home mr-3"></i>
                    Dashboard
                </a>
            </li>

            <li>
                <a href="{{ route('podcast.index') }}" 
                   class="flex items-center p-3 rounded-lg hover:bg-blue-600 
                   {{ request()->is('podcast*') ? 'bg-blue-600' : '' }}">
                    <i class="fas fa-podcast mr-3"></i>
                    Podcast
                </a>
            </li>

            <li>
                <a href="{{ route('coaching.index') }}" 
                   class="flex items-center p-3 rounded-lg hover:bg-blue-600 
                   {{ request()->is('coaching*') ? 'bg-blue-600' : '' }}">
                    <i class="fas fa-chalkboard-teacher mr-3"></i>
                    Coaching Clinic
                </a>
            </li>

        </ul>

        <!-- LOGOUT -->
        <div class="absolute bottom-4 left-4 right-4">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" 
                        class="flex items-center w-full p-3 rounded-lg hover:bg-red-600 bg-red-500 text-white">
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    Logout
                </button>
            </form>
        </div>

    </nav>

</aside>
