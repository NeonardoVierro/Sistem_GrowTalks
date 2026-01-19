<aside class="fixed left-0 top-0 h-screen w-64 bg-white shadow-lg border-r border-gray-200 z-50">
    <!-- Logo -->
    <div class="p-6 border-b border-gray-200 flex items-center gap-3">
    <img 
        src="{{ asset('images/logo-surakarta.svg.png') }}" 
        alt="Logo Podcast & Coaching Clinic"
        class="h-20 w-auto"
    >

    <h1 class="text-2xl font-bold text-primary">
        User GrowTalks
    </h1>
</div>

    
    <!-- Navigation -->
    <nav class="p-4">
        <ul class="space-y-2">
            <li>
                <a href="{{ route('dashboard') }}" class="flex items-center p-3 rounded-lg hover:bg-blue-50 {{ request()->is('dashboard') ? 'bg-blue-50 text-primary' : 'text-gray-700' }}">
                    <i class="fas fa-home mr-3"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('podcast.index') }}" class="flex items-center p-3 rounded-lg hover:bg-blue-50 {{ request()->is('podcast*') ? 'bg-blue-50 text-primary' : 'text-gray-700' }}">
                    <i class="fas fa-podcast mr-3"></i>
                    Podcast
                </a>
            </li>
            <li>
                <a href="{{ route('coaching.index') }}" class="flex items-center p-3 rounded-lg hover:bg-blue-50 {{ request()->is('coaching*') ? 'bg-blue-50 text-primary' : 'text-gray-700' }}">
                    <i class="fas fa-chalkboard-teacher mr-3"></i>
                    Coaching Clinic
                </a>
            </li>
        </ul>
        
        <!-- Logout -->
        <div class="absolute bottom-4 left-4 right-4">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center w-full p-3 rounded-lg hover:bg-red-50 text-red-600">
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    Logout
                </button>
            </form>
        </div>
    </nav>
</aside>