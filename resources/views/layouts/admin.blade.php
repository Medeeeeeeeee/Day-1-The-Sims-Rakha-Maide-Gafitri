@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-100">
    <!-- sidebar -->
    <aside class="relative w-64 bg-white shadow p-4 h-screen sticky top-0 flex flex-col justify-between">
        <div>
            <div class="mb-6 flex flex-col items-center text-center">
                <div class="w-16 h-16 bg-gray-300 rounded-full flex items-center justify-center">
                    <span class="text-xl text-white">{{ strtoupper(substr(auth()->user()->displayName(),0,1)) }}</span>
                </div>
                <p class="mt-2 font-semibold text-gray-700">{{ auth()->user()->displayName() }}</p>
                @if(auth()->user()->gender)
                <p class="text-xs text-gray-500">{{ auth()->user()->gender }}</p>
                @endif
                <p class="text-xs text-gray-500 truncate w-40">{{ auth()->user()->email }}</p>
                <p class="text-xs text-gray-500">{{ auth()->user()->faculty ?? 'Fakultas' }}</p>
            </div>
            <nav class="space-y-2">
                <a href="{{ route('profile') }}" class="flex items-center px-3 py-2 rounded hover:bg-gray-200 {{ request()->routeIs('profile') ? 'bg-gray-200' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A15.938 15.938 0 0112 15c2.21 0 4.31.54 6.121 1.504M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span class="ml-2">Profil</span>
                </a>
                <a href="{{ route('admin.dashboard') }}" class="relative flex items-center px-3 py-2 rounded hover:bg-gray-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-200' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6"/></svg>
                    <span class="ml-2">Home</span>
                    @if(isset($notif) && $notif > 0)
                        <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">{{ $notif }}</span>
                    @endif
                </a>
                <!-- admin-only link to lab management -->
                <a href="{{ route('labs.index') }}" class="flex items-center px-3 py-2 rounded hover:bg-gray-200 {{ request()->routeIs('labs.*') ? 'bg-gray-200' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>
                    <span class="ml-2">Kelola Lab</span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left flex items-center px-3 py-2 rounded hover:bg-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7"/></svg>
                        <span class="ml-2">Logout</span>
                    </button>
                </form>
            </nav>
        </div>
        <div class="text-sm text-gray-500 mt-4">Laravel Ticketing</div>
    </aside>

    <main class="flex-1 p-6 overflow-auto">
        <div class="max-w-5xl mx-auto">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @yield('admin-content')
        </div>
    </main>
</div>
@endsection