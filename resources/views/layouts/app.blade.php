<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Podcast & Coaching Clinic</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            background: linear-gradient(180deg, #2d3748 0%, #1a202c 100%);
            color: white;
        }
        .sidebar a:hover {
            background: rgba(255,255,255,0.1);
        }
        .sidebar a.active {
            background: #4a5568;
            border-left: 4px solid #4299e1;
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
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen">
    @auth
        <!-- Sidebar -->
        <div class="sidebar fixed left-0 top-0 h-screen w-64 shadow-lg">
            <!-- Logo -->
            <div class="p-6 border-b border-gray-700">
                <h1 class="text-xl font-bold">Podcast & Coaching Clinic</h1>
            </div>
            
            <!-- Navigation -->
            <nav class="p-4 space-y-2">
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
                    Coaching
                </a>
            </nav>
            
            <!-- Logout -->
            <div class="absolute bottom-4 left-4 right-4">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left py-3 px-4 rounded-lg hover:bg-red-900 text-red-300">
                        <i class="fas fa-sign-out-alt mr-3"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    @endauth
    
    <!-- Main Content -->
    <main class="@auth ml-64 @endauth min-h-screen">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>