<!-- resources/views/auth/login.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Pemerintahan - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: #f0f4f8;
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        .login-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-top: 5px solid #003366; /* strip biru pemerintah */
        }
        .input-field {
            border: 2px solid #cbd5e1;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .input-field:focus {
            border-color: #003366;
            box-shadow: 0 0 0 3px rgba(0,51,102,0.2);
            outline: none;
        }
        .btn-login {
            background-color: #003366;
            transition: all 0.3s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(0,51,102,0.3);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center p-4 space-y-6">

    <!-- Judul -->
    <h1 class="text-2xl font-bold text-gray-800 text-center">PODCAST & COACHING CLINIC</h1>
   

    <!-- Kotak login -->
    <div class="login-card w-full max-w-md p-6">
        <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
            @csrf

            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-3 rounded text-sm text-red-700">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Username -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Username</label>
                <input 
                    type="text" 
                    name="email"
                    value="{{ old('email') }}"
                    class="input-field w-full px-3 py-2"
                    placeholder="Masukkan Username / NIP"
                    required
                    autofocus
                >
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                <input 
                    type="password" 
                    name="password"
                    class="input-field w-full px-3 py-2"
                    placeholder="Masukkan Password"
                    required
                >
            </div>

            <!-- Remember Me -->
            <div class="flex items-center text-sm">
                <input 
                    type="checkbox" 
                    id="remember" 
                    name="remember"
                    class="h-4 w-4 text-blue-600 rounded"
                >
                <label for="remember" class="ml-2 text-gray-600">Remember Me</label>
            </div>

            <!-- Button Login -->
            <button type="submit" class="btn-login w-full text-white font-semibold py-2 rounded-lg">
                Login
            </button>
        </form>

        <!-- Link Bawah -->
        <p class="text-xs text-gray-500 text-center mt-4">
            &copy; {{ date('Y') }} Podcast & Coaching Clinic
        </p>
    </div>

</body>
</html>
