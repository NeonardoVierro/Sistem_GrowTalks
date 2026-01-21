<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin Podcast & Coaching Clinic</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .admin-sidebar {
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            color: white;
        }
        .admin-sidebar a:hover {
            background: rgba(255,255,255,0.1);
        }
        .admin-sidebar a.active {
            background: #334155;
            border-left: 4px solid #3b82f6;
        }
        .stat-card {
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .table-container {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Admin Sidebar -->
    <div class="admin-sidebar fixed left-0 top-0 h-screen w-64 shadow-xl">
        <!-- Logo -->
        <div class="p-6 border-b border-gray-700">
            <h1 class="text-xl font-bold">Podcast & Coaching Clinic</h1>
            <p class="text-sm text-gray-400 mt-1">Admin Dashboard</p>
        </div>
        
        <!-- Navigation -->
        <nav class="p-4 space-y-2">
            <a href="{{ route('admin.dashboard') }}" 
               class="block py-3 px-4 rounded-lg {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-bar mr-3"></i>
                Dashboard
            </a>
            <a href="{{ route('admin.users') }}" 
               class="block py-3 px-4 rounded-lg {{ request()->is('admin/users*') ? 'active' : '' }}">
                <i class="fas fa-users mr-3"></i>
                Manajemen User
            </a>
            <a href="{{ route('admin.reports.podcast') }}" 
               class="block py-3 px-4 rounded-lg {{ request()->is('admin/reports/podcast') ? 'active' : '' }}">
                <i class="fas fa-file-alt mr-3"></i>
                Laporan Podcast
            </a>
            <a href="{{ route('admin.reports.coaching') }}" 
               class="block py-3 px-4 rounded-lg {{ request()->is('admin/reports/coaching') ? 'active' : '' }}">
                <i class="fas fa-file-contract mr-3"></i>
                Laporan Coaching
            </a>
        </nav>
        
        <!-- User Info & Logout -->
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-700">
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                    <span class="font-bold">{{ strtoupper(substr(optional(auth()->guard('admin')->user())->nama_user ?? 'U', 0, 1)) }}</span>
                </div>
                <div class="ml-3">
                    <p class="font-medium">{{ optional(auth()->guard('admin')->user())->nama_user ?? 'Admin' }}</p>
                    <p class="text-xs text-gray-400">{{ optional(auth()->guard('admin')->user())->jabatan ?? '' }}</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full text-left py-3 px-4 rounded-lg hover:bg-red-900 text-red-300">
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    Logout
                </button>
            </form>
        </div>
    </div>
    
    <!-- Main Content -->
    <main class="ml-64 min-h-screen p-6">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>