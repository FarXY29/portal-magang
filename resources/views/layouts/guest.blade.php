<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Portal Magang') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-100">
        <div class="min-h-screen flex flex-col sm:justify-cente items-center pt-6 sm:pt-0">
            
            <!-- Logo di Atas -->
            <div class="mb-6 text-center">
                <a href="/" class="flex flex-col items-center group">
                    <svg class="h-16 w-16 text-teal-600 group-hover:text-teal-700 transition" fill="currentColor" viewBox="0 0 24 24">
                       <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                    </svg>
                    <span class="mt-2 text-3xl font-bold text-gray-800 tracking-wider">SiMagang</span>
                </a>
            </div>

            <!-- Card Form yang Lebih Kecil & Tengah -->
            <div class="w-full sm:max-w-md mt-2 px-8 py-8 bg-white shadow-xl overflow-hidden sm:rounded-2xl border border-gray-100">
                {{ $slot }}
            </div>

            <!-- Footer Kecil -->
            <div class="mt-8 text-center text-xs text-gray-400">
                &copy; {{ date('Y') }} Pemerintah Kota Banjarmasin
            </div>
        </div>
    </body>
</html>