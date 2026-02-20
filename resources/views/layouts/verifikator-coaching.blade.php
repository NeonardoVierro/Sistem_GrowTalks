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
        body { font-family: 'Poppins', sans-serif; }

        .sidebar {
            background-image:
                linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)),
                url("{{ asset('images/background.png') }}");
            background-size: cover;
            background-position: center;
            color: white;
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
    </style>

    @stack('styles')
</head>

<body class="bg-gray-50 min-h-screen">

@auth('internal')
<!-- ============ SIDEBAR ============ -->
<div class="sidebar fixed left-0 top-16 bottom-0 w-64 shadow-lg">

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

@auth('internal')
<!-- ============ TOPBAR ============ -->
<header
    class="fixed top-0 left-0 right-0 h-16
    bg-gradient-to-r from-gray-400 via-gray-100 to-white
    shadow border-b-4 border-gray-600 z-50
    flex items-center justify-between px-6">

    <span class="font-bold text-lg text-gray-800">
        Podcast & Coaching Clinic
    </span>

    <!-- USER MENU -->
    <div class="relative">
        <button onclick="toggleUserMenu()"
            class="flex items-center gap-2 text-gray-700 font-medium">
            {{ auth()->guard('internal')->user()->email }}
            <i class="fas fa-chevron-down text-xs"></i>
        </button>

        <div id="userDropdown"
            class="hidden absolute right-0 mt-3 w-48 bg-white rounded-lg shadow-lg border overflow-hidden">

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="w-full px-4 py-3 text-left text-red-600 hover:bg-red-50">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </button>
            </form>
        </div>
    </div>
</header>
@endauth

<!-- ============ CONTENT ============ -->
<main class="@auth('internal') ml-64 pt-20 @endauth min-h-screen">
    @yield('content')
</main>

<script>
function toggleUserMenu() {
    document.getElementById('userDropdown').classList.toggle('hidden');
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('.relative')) {
        document.getElementById('userDropdown')?.classList.add('hidden');
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@stack('scripts')
</body>
</html>
