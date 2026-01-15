<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Portal Magang') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        /* Sembunyikan scrollbar default tapi tetap bisa scroll */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background-color: #94a3b8; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50" x-data="{ sidebarOpen: false }">
    
    <div class="flex h-screen overflow-hidden">

        <div x-show="sidebarOpen" x-cloak 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false" 
             class="fixed inset-0 z-40 bg-gray-900/50 lg:hidden backdrop-blur-sm">
        </div>

        <aside x-cloak 
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 shadow-xl lg:shadow-none lg:static lg:inset-auto lg:translate-x-0 transition-transform duration-300 transform h-full flex flex-col">
            
            <div class="flex items-center justify-center h-16 bg-gradient-to-r from-teal-600 to-teal-700">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <x-application-logo class="block h-8 w-auto fill-current text-teal-600" />
                    <span class="text-lg font-bold text-gray-800 tracking-tight">Portal<span class="text-white">Magang</span></span>
                </a>
            </div>

            <div class="flex-1 overflow-y-auto custom-scrollbar py-4 px-3 space-y-1">
                @include('layouts.navigation')
            </div>

            <div class="p-4 border-t border-gray-100 text-center">
                <p class="text-[10px] text-gray-400">&copy; {{ date('Y') }} Pemkot Banjarmasin</p>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 z-30">
                
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="text-gray-500 hover:text-gray-700 focus:outline-none lg:hidden">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 class="font-bold text-lg text-gray-800 leading-tight truncate">
                        {{ $header ?? 'Dashboard' }}
                    </h2>
                </div>

                <div class="flex items-center gap-4">
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition focus:outline-none">
                            <div class="text-right hidden sm:block">
                                <div class="text-sm font-bold text-gray-800">{{ Auth::user()->name }}</div>
                                <div class="text-[10px] text-gray-500 uppercase">{{ Auth::user()->role ?? 'User' }}</div>
                            </div>
                            <div class="h-9 w-9 rounded-full bg-gray-100 flex items-center justify-center border border-gray-200 text-gray-500">
                                <i class="fas fa-user"></i>
                            </div>
                            <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                        </button>

                        <div x-show="open" @click.away="open = false" x-cloak
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-50 origin-top-right transition-all">
                            
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <i class="fas fa-user-circle mr-2 text-gray-400"></i> Profil Saya
                            </a>
                            
                            <div class="border-t border-gray-100 my-1"></div>
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 custom-scrollbar p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>

        </div>
    </div>
</body>
</html>