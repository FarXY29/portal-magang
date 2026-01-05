<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Portal Magang') }}</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 h-full overflow-hidden" x-data="{ sidebarOpen: false }">
    
    <div class="flex h-full overflow-hidden">
        
        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" 
             class="fixed inset-0 z-40 bg-black/50 lg:hidden"></div>

        <aside x-cloak 
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed lg:static inset-y-0 left-0 z-50 w-64 h-full transition duration-300 transform bg-white lg:translate-x-0 lg:flex-shrink-0 border-r border-gray-200">
            @include('layouts.navigation')
        </aside>

        <div class="flex-1 flex flex-col h-full min-w-0 overflow-hidden">
            <header class="bg-white border-b border-gray-200 flex-shrink-0 z-30 shadow-sm">
                <div class="max-w-full mx-auto py-4 px-4 sm:px-6 flex items-center">
                    <button @click="sidebarOpen = true" class="p-2 -ml-2 mr-3 text-gray-500 hover:text-teal-600 lg:hidden focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 class="font-extrabold text-lg sm:text-xl text-gray-800 leading-tight truncate">
                        {{ $header ?? config('app.name', 'Portal Magang') }}
                    </h2>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-gray-50">
                <div class="max-w-7xl mx-auto">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
</body>
</html>