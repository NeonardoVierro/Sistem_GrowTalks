<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Podcast & Coaching Clinic - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .input-field {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            transition: all 0.3s;
        }
        .input-field:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="login-container w-full max-w-md p-8">
        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Podcast & Coaching Clinic</h1>
            <h2 class="text-xl text-gray-600">Login</h2>
        </div>
        
        <!-- Form -->
        <form method="POST" action="{{ route('login.store') }}" class="space-y-6">
            @csrf
            
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
                    @foreach($errors->all() as $error)
                        <p class="text-red-700">{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            
            <!-- Username Field -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Username*
                </label>
                <input 
                    type="text" 
                    name="email"
                    value="{{ old('email') }}"
                    class="input-field w-full px-4 py-3"
                    placeholder="Masukkan Username"
                    required
                    autofocus
                >
            </div>
            
            <!-- Password Field -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Password*
                </label>
                <input 
                    type="password" 
                    name="password"
                    class="input-field w-full px-4 py-3"
                    placeholder="Masukkan Password"
                    required
                >
            </div>
            
            <!-- Remember Me -->
            <div class="flex items-center">
                <input 
                    type="checkbox" 
                    id="remember" 
                    name="remember"
                    class="h-4 w-4 text-blue-600 rounded"
                >
                <label for="remember" class="ml-2 text-sm text-gray-600">
                    Remember Me
                </label>
            </div>
            
            <!-- Login Button -->
            <button type="submit" class="btn-login w-full text-white font-semibold py-3 px-4 rounded-lg">
                Login
            </button>
        </form>
        
        <!-- Copyright -->
        <div class="mt-8 pt-6 border-t border-gray-200 text-center text-sm text-gray-500">
            © {{ date('Y') }} Podcast & Coaching Clinic
        </div>
    </div>
</body>
</html>