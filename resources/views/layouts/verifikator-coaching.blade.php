<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Podcast & Coaching Clinic</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { 
            font-family: 'Poppins', sans-serif;
        }

        .sidebar {
            background-image:
                linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)),
                url("{{ asset('images/background.png') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: white;
            transition: all 0.3s ease;
        }

        .sidebar a {
            background: rgba(0,0,0,0.25);
        }

        .sidebar a:hover {
            background: rgba(255,255,255,0.15);
        }

        .sidebar a.active {
            background: #4a5568;
            border-left: 4px solid #E8CA00;
            color: #E8CA00;
            font-weight: 600;
        }

        /* LOGIKA RESPONSIVE BARU */
        @media (max-width: 768px) {
            .sidebar {
                left: -100% !important;
                z-index: 100;
            }
            .sidebar.active-mobile {
                left: 0 !important;
            }
            .main-content-area {
                margin-left: 0 !important;
            }
        }

        /* tabel responsif: cukup scroll horizontal */
        .responsive-table {
            width: 100%;
            border-collapse: collapse;
        }

        .responsive-wrapper {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-50 min-h-screen">

@auth('internal')
<!-- TOP NAVBAR -->
<header
    class="fixed top-0 left-0 right-0 h-16
    bg-gradient-to-r from-gray-400 via-gray-100 to-white
    shadow border-b-4 border-gray-600 z-50
    flex items-center justify-between px-6 py-4">

    <div class="flex items-center gap-3">
        <button onclick="toggleSidebar()" class="md:hidden text-gray-800 p-2">
            <i class="fas fa-bars text-xl"></i>
        </button>
        <span class="font-bold text-base md:text-lg text-gray-800 truncate">
            Podcast & Coaching Clinic
        </span>
    </div>

    <!-- USER MENU -->
    <div class="relative">
        <button onclick="toggleUserMenu()"
            class="flex items-center gap-2 text-gray-700 hover:text-gray-900 font-medium">
            <span class="hidden sm:inline">{{ auth()->guard('internal')->user()->email }}</span>
            <i class="fas fa-user-circle md:hidden text-xl"></i>
            <i class="fas fa-chevron-down text-xs"></i>
        </button>

        <div id="userDropdown"
            class="hidden absolute right-0 mt-3 w-48 bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full px-4 py-3 text-left text-sm text-red-600 hover:bg-red-50">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </button>
            </form>
        </div>
    </div>
</header>

<!-- overlay ketika sidebar terbuka di mobile -->
<div id="sidebarOverlay" class="hidden fixed inset-0 bg-black bg-opacity-25 z-40"></div>

<!-- ============ SIDEBAR ============ -->
<div id="sidebarMenu" class="sidebar fixed left-0 top-16 bottom-0 w-64 shadow-lg z-40">

    <!-- LOGO -->
    <div class="p-6 border-b border-gray-700 text-center">
        <img src="{{ asset('images/logos.png') }}" class="mx-auto h-12">
        <h2 class="mt-3 text-lg font-bold">GrowTalks</h2>
        <p class="text-sm text-gray-200">Verifikator Coaching</p>
    </div>

    <!-- MENU -->
    <nav class="p-6 pt-10 space-y-2">
        <a href="{{ route('verifikator-coaching.dashboard') }}"
           class="block py-3 px-4 rounded-lg {{ request()->is('verifikator-coaching/dashboard') ? 'active' : '' }}">
            <i class="fas fa-home mr-3"></i> Dashboard
        </a>

        <a href="{{ route('verifikator-coaching.approval') }}"
           class="block py-3 px-4 rounded-lg {{ request()->is('verifikator-coaching/approval*') ? 'active' : '' }}">
            <i class="fas fa-check-circle mr-3"></i> Approval Coaching
        </a>

        <a href="{{ route('verifikator-coaching.report') }}"
           class="block py-3 px-4 rounded-lg {{ request()->is('verifikator-coaching/report*') ? 'active' : '' }}">
            <i class="fas fa-file-alt mr-3"></i> Laporan Coaching
        </a>
    </nav>
</div>
@endauth

<!-- ============ TOPBAR ============ -->
@auth('internal')
@endauth

<!-- ============ CONTENT ============ -->
<main class="main-content-area @auth('internal') md:ml-64 @endauth pt-20 min-h-screen px-4 md:px-6">
    @yield('content')
</main>

<script>
function toggleUserMenu() {
    document.getElementById('userDropdown').classList.toggle('hidden');
}

function toggleSidebar() {
    const sb = document.getElementById('sidebarMenu');
    const overlay = document.getElementById('sidebarOverlay');
    const isOpen = sb.classList.toggle('active-mobile');
    if (isOpen) {
        overlay.classList.remove('hidden');
    } else {
        overlay.classList.add('hidden');
    }
}

document.addEventListener('click', function(e) {
    const menu = document.getElementById('userDropdown');
    const sidebar = document.getElementById('sidebarMenu');
    
    if (!e.target.closest('.relative')) {
        menu?.classList.add('hidden');
    }

    if (window.innerWidth < 768 && !sidebar.contains(e.target) && !e.target.closest('button')) {
        sidebar.classList.remove('active-mobile');
        document.getElementById('sidebarOverlay').classList.add('hidden');
    }

    if (e.target.id === 'sidebarOverlay') {
        sidebar.classList.remove('active-mobile');
        e.target.classList.add('hidden');
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@stack('scripts')
</body>
</html>
