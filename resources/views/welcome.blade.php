<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PC Ticketing System</title>
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
                <p class="text-2xl font-semibold opacity-90">Login Your Account</p>
            </div>
        </div>

        <!-- Right Side - Auth Content -->
        <div class="w-full lg:w-1/2 flex flex-col items-center justify-center p-6 sm:p-12 relative">
            <!-- Mobile Logo -->
            <div class="absolute top-6 left-6 lg:hidden">
                <a href="/" class="bg-red-500 hover:bg-red-600 inline-flex w-10 h-10 rounded items-center justify-center transition">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                    </svg>
                </a>
            </div>

            <div class="w-full max-w-md">
                @guest
                    <!-- Welcome Section -->
                    <div class="text-center">
                        <h1 class="text-4xl font-bold mb-2 text-gray-900">Login To Your Account</h1>
                        <hr class="border-t-4 border-cyan-500 mb-8">
                        
                        <div class="space-y-4">
                            <a href="{{ route('login') }}" class="block w-full py-3 px-4 bg-gray-400 hover:bg-gray-500 text-gray-900 font-bold rounded-lg transition duration-200">
                                Login
                            </a>
                           
                        </div>
                    </div>
                @else
                    <!-- Logged In Dashboard -->
                    <div class="text-center">
                        <h2 class="text-3xl font-bold mb-6 text-gray-900">Welcome, {{ auth()->user()->displayName() }}!</h2>
                        <p class="text-gray-600 mb-8">Logged in as <strong class="text-cyan-600">{{ auth()->user()->role }}</strong></p>
                        
                        <a href="{{ route('dashboard') }}" class="inline-block w-full py-3 px-4 bg-cyan-500 hover:bg-cyan-600 text-white font-bold rounded-lg transition">
                            Go to Dashboard
                        </a>
                        
                        <form method="POST" action="{{ route('logout') }}" class="mt-4">
                            @csrf
                            <button type="submit" class="w-full py-3 px-4 bg-gray-300 hover:bg-gray-400 text-gray-900 font-bold rounded-lg transition">
                                Logout
                            </button>
                        </form>
                    </div>
                @endguest
            </div>

            <!-- Navigation arrows (mobile only) -->
            <div class="flex gap-3 mt-12 lg:hidden absolute bottom-6">
                <button class="bg-gray-700 hover:bg-gray-800 text-white p-2.5 rounded-full transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <button class="bg-gray-700 hover:bg-gray-800 text-white p-2.5 rounded-full transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</body>
</html>
