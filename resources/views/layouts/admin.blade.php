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
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
        }

        /* SIDEBAR */
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

        /* RESPONSIVE MOBILE */
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

        /* TABLE RESPONSIVE */
        .responsive-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 100%;
        }

        .responsive-wrapper {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* SCROLLBAR STYLING */
        .responsive-wrapper::-webkit-scrollbar {
            height: 6px;
        }

        .responsive-wrapper::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .responsive-wrapper::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .responsive-wrapper::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* TABLE STYLES */
        .responsive-table th {
            background-color: #1e293b;
            color: white;
            font-weight: 600;
            padding: 12px;
            text-align: left;
            white-space: nowrap;
        }

        .responsive-table td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
        }

        .responsive-table tbody tr:hover {
            background-color: #f8fafc;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-50 min-h-screen">

    <!-- SIDEBAR -->
    <div id="sidebarMenu" class="sidebar fixed left-0 top-16 bottom-0 w-64 shadow-lg z-50">

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

    <!-- overlay ketika sidebar terbuka di mobile -->
    <div id="sidebarOverlay" class="hidden fixed inset-0 bg-black bg-opacity-25 z-40"></div>

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

        <!-- ADMIN MENU -->
        <div class="relative">
            <button onclick="toggleUserMenu()"
                class="flex items-center gap-2 text-gray-700 hover:text-gray-900 font-medium">
                <span class="hidden sm:inline text-sm">{{ auth()->guard('internal')->user()->email ?? 'Admin' }}</span>
                <i class="fas fa-user-circle md:hidden text-xl"></i>
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
   <main class="main-content-area md:ml-64 pt-20 min-h-screen px-4 md:px-8 py-6">
        @yield('content')
    </main>

    <script>
        function toggleUserMenu() {
            document.getElementById('adminDropdown').classList.toggle('hidden');
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
            const menu = document.getElementById('adminDropdown');
            const sidebar = document.getElementById('sidebarMenu');
            
            // Close user dropdown if click outside
            if (!e.target.closest('.relative')) {
                menu?.classList.add('hidden');
            }

            // Close sidebar on mobile if click outside
            if (window.innerWidth < 768 && !sidebar.contains(e.target) && !e.target.closest('button')) {
                sidebar.classList.remove('active-mobile');
                document.getElementById('sidebarOverlay').classList.add('hidden');
            }

            // Close sidebar if click overlay
            if (e.target.id === 'sidebarOverlay') {
                sidebar.classList.remove('active-mobile');
                e.target.classList.add('hidden');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
