<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - PC Ticketing System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white font-sans antialiased">
    <div class="min-h-screen flex">
        <!-- Left Side - Branding -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-cyan-500 to-teal-500 relative overflow-hidden items-center justify-center p-12">
            <!-- Decorative shapes -->
            <div class="absolute top-0 left-0 w-96 h-96 bg-cyan-300 opacity-30 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 right-0 w-80 h-80 bg-teal-300 opacity-20 rounded-full translate-x-1/3 translate-y-1/3"></div>
            
            <!-- Logo -->
            <div class="absolute top-6 left-6 z-10">
                <a href="/" class="bg-red-500 hover:bg-red-600 inline-flex w-10 h-10 rounded items-center justify-center transition">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                    </svg>
                </a>
            </div>
            
            <!-- Content -->
            <div class="relative z-10 text-white text-center max-w-md">
                <h1 class="text-6xl font-bold mb-4">welcome</h1>
                <p class="text-2xl font-semibold opacity-90">login your account</p>
            </div>
        </div>

        <!-- Right Side - Register Form -->
        <div class="w-full lg:w-1/2 flex flex-col items-center justify-center p-6 sm:p-12 relative overflow-y-auto">
            <!-- Mobile Logo -->
            <div class="absolute top-6 left-6 lg:hidden">
                <a href="/" class="bg-red-500 hover:bg-red-600 inline-flex w-10 h-10 rounded items-center justify-center transition">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                    </svg>
                </a>
            </div>

            <div class="w-full max-w-md">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold mb-2 text-gray-900">Registrasi Akun</h1>
                    <hr class="border-t-4 border-cyan-500">
                </div>

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        @foreach ($errors->all() as $error)
                            <p class="text-sm">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">NIM</label>
                        <input type="text" name="nim" value="{{ old('nim') }}" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-cyan-500" placeholder="NIM.0123456789">
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Gender</label>
                        <select name="gender" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-cyan-500">
                            <option value="">Pilih Gender</option>
                            <option value="Pria" {{ old('gender')=='Pria'?'selected':'' }}>Pria</option>
                            <option value="Wanita" {{ old('gender')=='Wanita'?'selected':'' }}>Wanita</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-cyan-500" placeholder="zakir23@univ.ac.id">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Gender</label>
                        <select name="gender" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-cyan-500">
                            <option value="">Pilih Gender</option>
                            <option value="Pria">Pria</option>
                            <option value="Wanita">Wanita</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Password</label>
                            <input type="password" name="password" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-cyan-500">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-cyan-500">
                        </div>
                    </div>

                    <button type="submit" class="w-full mt-6 py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition duration-200">
                        Daftar
                    </button>
                </form>

                <p class="text-center text-sm text-gray-600 mt-6">
                    Sudah punya akun? <a href="{{ route('login') }}" class="text-cyan-600 font-semibold hover:underline">Login</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
