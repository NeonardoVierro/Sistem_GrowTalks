<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Verifikator Coaching</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            background: linear-gradient(180deg, #059669 0%, #047857 100%);
            color: white;
        }
        .sidebar a:hover {
            background: rgba(255,255,255,0.1);
        }
        .sidebar a.active {
            background: rgba(255,255,255,0.15);
            border-left: 4px solid #34d399;
        }
        .table-row:hover {
            background-color: #f8fafc;
        }
        .status-badge {
            padding: 2px 10px;
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
        .card-stat {
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Sidebar -->
    <div class="sidebar fixed left-0 top-0 h-screen w-64 shadow-lg">
        <!-- Logo -->
        <div class="p-6 border-b border-green-700">
            <h1 class="text-xl font-bold">Podcast & Coaching Clinic</h1>
            <p class="text-sm text-green-200 mt-1">Verifikator Coaching</p>
        </div>
        
        <!-- Navigation -->
        <nav class="p-4 space-y-2">
            <a href="{{ route('verifikator-coaching.dashboard') }}" 
               class="block py-3 px-4 rounded-lg {{ request()->is('verifikator-coaching/dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-bar mr-3"></i>
                Dashboard
            </a>
            <a href="{{ route('verifikator-coaching.approval') }}" 
               class="block py-3 px-4 rounded-lg {{ request()->is('verifikator-coaching/approval*') ? 'active' : '' }}">
                <i class="fas fa-check-circle mr-3"></i>
                Approval Coaching
            </a>
            <a href="{{ route('verifikator-coaching.report') }}" 
               class="block py-3 px-4 rounded-lg {{ request()->is('verifikator-coaching/report') ? 'active' : '' }}">
                <i class="fas fa-file-alt mr-3"></i>
                Laporan Coaching
            </a>
        </nav>
        
        <!-- User Info & Logout -->
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-green-700">
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                    <span class="font-bold">{{ strtoupper(substr(auth()->guard('internal')->user()->nama_user, 0, 1)) }}</span>
                </div>
                <div class="ml-3">
                    <p class="font-medium">{{ auth()->guard('internal')->user()->nama_user }}</p>
                    <p class="text-xs text-green-300">{{ auth()->guard('internal')->user()->jabatan }}</p>
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
    <main class="ml-64 min-h-screen">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>