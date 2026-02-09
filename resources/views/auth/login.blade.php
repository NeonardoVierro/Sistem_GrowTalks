<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GrowTalks - Login</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@700&family=Poppins:wght@400;600&display=swap');
        
        body {
            background-image:
                url("{{ asset('images/batik.png') }}"),
                url("{{ asset('images/batik.png') }}"),
                url("{{ asset('images/batik.png') }}"),
                url("{{ asset('images/batik.png') }}"),
                url("{{ asset('images/batik.png') }}"),
                url("{{ asset('images/batik.png') }}");

            background-size: 420px, 420px, 420px, 420px, 420px, 420px;
            background-repeat: no-repeat, no-repeat, no-repeat, no-repeat, no-repeat, no-repeat;

            background-position:
                left 500px top 60px,
                left -90px top 150px,
                right 60px top 150px,
                right 10px top 500px,
                right 500px top 450px,
                left 50px top 550px;

            min-height: 100vh;
            font-family: 'Inter', system-ui, sans-serif;
        }

        .font-oswald { font-family: 'Oswald', sans-serif; }

        .card {
            background: rgba(255, 255, 255, 0.97);
            border-radius: 14px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .input {
            border: 2px solid #cbd5e1;
            border-radius: 8px;
        }

        .input:focus {
            border-color: #003366;
            box-shadow: 0 0 0 3px rgba(0,51,102,.2);
            outline: none;
        }

        .btn {
            background: #004F8D;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,51,102,.3);
        }
    </style>
</head>

<body class="h-screen overflow-hidden flex items-center justify-center px-6">

    <!-- CARD UTAMA -->
    <div class="card w-full max-w-5xl overflow-hidden">

        <div class="grid grid-cols-1 md:grid-cols-2">

            <div class="hidden md:flex items-center justify-center bg-gray-50 p-10">
                <img 
                    src="{{ asset('images/podcast.png') }}"
                    alt="Login Illustration"
                    class="max-w-sm w-full"
                >
            </div>

            <div class="p-10 md:p-14">

                <!-- Judul -->
                <h1 class="font-oswald uppercase text-3xl font-bold text-gray-800 mb-2">
                    LOGIN GROWTALKS
                </h1>

                <!-- FORM -->
                <form method="POST" action="{{ route('login.store') }}" class="space-y-6">
                    @csrf

                    @if($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-500 p-3 text-sm text-red-700">
                            @foreach($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <!-- EMAIL -->
                    <div>
                        <label class="text-sm font-semibold text-gray-700">
                            Email
                        </label>

                        <div class="relative mt-1">
                            <!-- ICON EMAIL -->
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <!-- Mail Icon -->
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8
                                        M5 19h14a2 2 0 002-2V7
                                        a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </span>

                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="input w-full px-4 py-3 pl-12"
                                placeholder="contoh: user@gmail.com"
                                required
                            >
                        </div>
                    </div>

                    <!-- PASSWORD -->
                    <div>
                        <label class="text-sm font-semibold text-gray-700">
                            Password
                        </label>

                        <div class="relative mt-1">
                            <!-- Icon Gembok -->
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <!-- Lock Icon -->
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 11c1.657 0 3 1.343 3 3
                                        0 1.657-1.343 3-3 3
                                        s-3-1.343-3-3c0-1.657 1.343-3 3-3z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 11V7a5 5 0 0110 0v4
                                        M5 11h14v9a2 2 0 01-2 2H7
                                        a2 2 0 01-2-2v-9z" />
                                </svg>
                            </span>

                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="input w-full px-4 py-3 pl-12 pr-12"
                                placeholder="Masukkan password"
                                required
                            >

                            <!-- Icon Mata -->
                            <button 
                                type="button"
                                onclick="togglePassword()"
                                class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-gray-700"
                            >
                                <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" 
                                    class="h-5 w-5" fill="none" viewBox="0 0 24 24" 
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5
                                        c4.478 0 8.268 2.943 9.542 7
                                        -1.274 4.057-5.064 7-9.542 7
                                        -4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                        </div>
                    </div>

                    <!-- REMEMBER -->
                    <div class="flex items-center">
                        <input type="checkbox" name="remember" class="mr-2">
                        <span class="text-sm text-gray-600">Remember Me</span>
                    </div>

                    <!-- BUTTON -->
                    <button
                        type="submit"
                        class="btn w-full text-white font-semibold py-3 rounded-lg"
                    >
                        Login
                    </button>

                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13.875 18.825A10.05 10.05 0 0112 19
                        c-4.478 0-8.268-2.943-9.542-7
                        a9.956 9.956 0 012.497-4.419M6.223 6.223
                        A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7
                        a9.978 9.978 0 01-4.293 5.184M15 12
                        a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3l18 18" />
                `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5
                        c4.478 0 8.268 2.943 9.542 7
                        -1.274 4.057-5.064 7-9.542 7
                        -4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        }
    </script>

</body>
</html>
