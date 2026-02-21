<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - Admin Podcast & Coaching Clinic</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        /* SIDEBAR SAMA DENGAN USER */
        .sidebar {
            background-image:
                linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)),
                url("{{ asset('images/background.png') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
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

    <!-- SIDEBAR -->
    <div class="sidebar fixed left-0 top-16 bottom-0 w-64 shadow-lg z-40">

        <!-- LOGO -->
        <div class="p-6 border-b border-gray-700 text-center">
            <img src="{{ asset('images/logos.png') }}" class="mx-auto h-12">
            <h2 class="mt-3 inline-block px-4 py-1 text-lg font-bold text-white">
                GrowTalks
            </h2>
        </div>

        <!-- NAVIGATION -->
        <nav class="p-6 pt-10 space-y-2">

            <a href="{{ route('admin.dashboard') }}"
               class="block py-3 px-4 rounded-lg {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <i class="fas fa-home mr-3"></i>
                Dashboard
            </a>

            <a href="{{ route('admin.users') }}"
               class="block py-3 px-4 rounded-lg {{ request()->is('admin/users*') ? 'active' : '' }}">
                <i class="fas fa-users mr-3"></i>
                Manajemen User
            </a>

            <a href="{{ route('admin.staffs.index') }}"
               class="block py-3 px-4 rounded-lg {{ request()->is('admin/staffs*') ? 'active' : '' }}">
                <i class="fas fa-user-cog mr-3"></i>
                Manajemen Staff
            </a>
            
            <a href="{{ route('admin.podcasts') }}"
               class="block py-3 px-4 rounded-lg {{ request()->is('admin/podcast*') ? 'active' : '' }}">
                <i class="fas fa-podcast mr-3"></i>
                Podcast
            </a>

            <a href="{{ route('admin.coachings') }}"
               class="block py-3 px-4 rounded-lg {{ request()->is('admin/coaching*') ? 'active' : '' }}">
                <i class="fas fa-chalkboard-teacher mr-3"></i>
                Coaching Clinic
            </a>

        </nav>
    </div>

    <!-- TOP NAVBAR -->
    <header
        class="fixed top-0 left-0 right-0 h-16
               bg-gradient-to-r from-gray-400 via-gray-100 to-white
               shadow border-b-4 border-gray-600 z-50
               flex items-center justify-between px-6">

        <span class="font-bold text-lg text-gray-800">
            Podcast & Coaching Clinic
        </span>

        <!-- ADMIN MENU -->
        <div class="relative">
            <button onclick="toggleAdminMenu()"
                class="flex items-center gap-2 text-gray-700 font-medium">

                {{ auth()->guard('internal')->user()->email ?? 'Admin' }}
                <i class="fas fa-chevron-down text-xs"></i>
            </button>

            <div id="adminDropdown"
                class="hidden absolute right-0 mt-3 w-48 bg-white rounded-lg shadow-lg border overflow-hidden">

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form>

            </div>
        </div>
    </header>

    <!-- CONTENT -->
   <main class="ml-64 pt-24 min-h-screen px-8">
        @yield('content')
    </main>

    <script>
        function toggleAdminMenu() {
            document.getElementById('adminDropdown').classList.toggle('hidden');
        }

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.relative')) {
                document.getElementById('adminDropdown')?.classList.add('hidden');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
