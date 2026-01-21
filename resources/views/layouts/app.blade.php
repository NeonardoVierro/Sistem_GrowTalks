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


        .calendar-day {
            transition: all 0.2s;
        }
        .calendar-day:hover:not(.disabled):not(.booked) {
            background: #ebf8ff;
            cursor: pointer;
        }
        .calendar-day.booked {
            background: #c6f6d5;
            border-color: #38a169;
        }
        .calendar-day.disabled {
            background: #f7fafc;
            color: #cbd5e0;
            cursor: not-allowed;
        }
        .calendar-day.podcast-day:not(.booked):not(.disabled) {
            border: 2px solid #4299e1;
        }
        .calendar-day.coaching-day:not(.booked):not(.disabled) {
            border: 2px solid #ed8936;
        }
        .table-row:hover {
            background: #f7fafc;
        }
        .status-badge {
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
        .status-disetujui {
            background: #d1fae5;
            color: #065f46;
        }
        .status-ditolak {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Striping kolom ganjil & genap */
        .table-striped-cols td:nth-child(odd) {
            background-color: #f9fafb; /* gray-50 */
        }

        .table-striped-cols td:nth-child(even) {
            background-color: #ffffff;
        }

        /* Supaya hover baris tetap kelihatan */
        .table-striped-cols tbody tr:hover td {
            background-color: #f3f4f6 !important; /* gray-100 */
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen font-['poppins']">
    @auth
        <!-- Sidebar -->
        <div class="sidebar fixed left-0 top-16 bottom-0 w-64 shadow-lg">
            
            <!-- Logo -->
            <div class="p-6 border-b border-gray-700 text-center">
                <img src="{{ asset('images/logos.png') }}" alt="Logo" class="mx-auto h-12">

                <h2 class="mt-3 inline-block px-4 py-1 text-lg font-bold text-white">
                    GrowTalks
                </h2>

            </div>

            <!-- Navigation -->
            <nav class="p-6 pt-10 space-y-2">
                <a href="{{ route('dashboard') }}" 
                   class="block py-3 px-4 rounded-lg {{ request()->is('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home mr-3"></i>
                    Dashboard
                </a>
                <a href="{{ route('podcast.index') }}" 
                   class="block py-3 px-4 rounded-lg {{ request()->is('podcast*') ? 'active' : '' }}">
                    <i class="fas fa-podcast mr-3"></i>
                    Podcast
                </a>
                <a href="{{ route('coaching.index') }}" 
                   class="block py-3 px-4 rounded-lg {{ request()->is('coaching*') ? 'active' : '' }}">
                    <i class="fas fa-chalkboard-teacher mr-3"></i>
                    Coaching Clinic
                </a>
            </nav>
        </div>
    @endauth

    @auth
    <!-- TOP NAVBAR -->
    <header
        class="fixed top-0 left-0 right-0 h-16
            bg-gradient-to-r from-gray-400 via-gray-100 to-white
            shadow border-b-4 border-gray-600 z-50
            flex items-center justify-between px-6 py-4">
 
        <!-- Left Brand -->
        <div class="flex items-center gap-3">
            <span class="font-bold text-lg text-gray-800">
                Podcast & Coaching Clinic
            </span>
        </div>

        <!-- Right User Menu -->
        <div class="relative">
            <button onclick="toggleUserMenu()"
                class="flex items-center gap-2 text-gray-700 hover:text-gray-900 font-medium">

                {{ Auth::user()->email }}

                <i class="fas fa-chevron-down text-xs"></i>
            </button>

            <!-- Dropdown -->
            <div id="userDropdown"
                class="hidden absolute right-0 mt-3 w-48 bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
                
                <a href="{{ route('user.profile') }}"
                    class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-user mr-2"></i> Profile
                </a>

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
    @endauth

    
    <!-- Main Content -->
    <main class="@auth ml-64 pt-20 @endauth min-h-screen">
        @yield('content')
    </main>

    <script>
        function toggleUserMenu() {
            document.getElementById('userDropdown').classList.toggle('hidden');
        }

        document.addEventListener('click', function(e) {
            const menu = document.getElementById('userDropdown');
            if (!e.target.closest('.relative')) {
                menu?.classList.add('hidden');
            }
        });
    </script>


    @stack('scripts')
</body>
</html>