<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Portal Magang') }}</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .ck-content ul { list-style-type: disc; padding-left: 20px; }
        .ck-content ol { list-style-type: decimal; padding-left: 20px; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen flex overflow-hidden">
        <div 
            x-show="sidebarOpen" 
            x-cloak
            @click="sidebarOpen = false" 
            class="fixed inset-0 z-40 bg-black/50 lg:hidden"
        ></div>

        <aside 
            x-cloak
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="flex inset-y-0 left-0 z-50 w-64 transition duration-300 transform bg-white lg:translate-x-0 lg:static lg:inset-0 lg:flex-shrink-0"
        >
            @include('layouts.navigation')
        </aside>

        <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
            <header class="bg-white border-b border-gray-200 sticky top-0 z-30 shadow-sm">
                <div class="max-w-full mx-auto py-4 px-4 sm:px-6 flex items-center">
                    <button 
                        @click="sidebarOpen = true" 
                        class="p-2 -ml-2 mr-3 text-gray-500 hover:text-teal-600 lg:hidden focus:outline-none"
                    >
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    
                    <h2 class="font-extrabold text-lg sm:text-xl text-gray-800 leading-tight truncate">
                        {{ $header ?? config('app.name', 'Portal Magang') }}
                    </h2>
                </div>
            </header>

            <main class="p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>