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

        <!-- Font Awesome (Untuk Ikon) -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex bg-gray-100">
            
            <!-- BAGIAN KIRI: GAMBAR LATAR -->
            <div class="hidden lg:block relative w-0 flex-1 bg-teal-800">
                <div class="absolute inset-0 h-full w-full bg-gradient-to-br from-teal-900 to-teal-600 opacity-90"></div>
                <!-- Ganti URL gambar ini dengan foto kantor/kota Banjarmasin yang bagus -->
                <img class="absolute inset-0 h-full w-full object-cover mix-blend-overlay" src="https://images.unsplash.com/photo-1497366811353-6870744d04b2?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Kantor Pemerintahan">
                
                <div class="absolute inset-0 flex flex-col justify-center px-12 text-white z-10">
                    <div class="flex items-center mb-6">
                        <!-- Logo Pemkot (Ganti dengan file logo asli jika ada) -->
                        <svg class="h-12 w-12 text-teal-200" fill="currentColor" viewBox="0 0 24 24">
                           <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                        </svg>
                        <h1 class="ml-4 text-3xl font-bold tracking-wider">PORTAL MAGANG</h1>
                    </div>
                    <p class="text-xl font-light text-teal-100">Pemerintah Kota Banjarmasin</p>
                    <p class="mt-4 text-sm text-teal-200 max-w-md">Selamat datang di sistem pelayanan terpadu untuk pendaftaran, pengelolaan, dan monitoring kegiatan magang/PKL di lingkungan Pemerintah Kota Banjarmasin.</p>
                </div>
            </div>

            <!-- BAGIAN KANAN: FORMULIR -->
            <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24 bg-white">
                <div class="mx-auto w-full max-w-sm lg:w-96">
                    
                    <!-- Logo untuk Mobile -->
                    <div class="lg:hidden text-center mb-8">
                         <a href="/" class="inline-flex items-center">
                            <svg class="h-10 w-10 text-teal-600" fill="currentColor" viewBox="0 0 24 24">
                               <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                            </svg>
                            <span class="ml-3 text-2xl font-bold text-gray-800 tracking-wider">SiMagang</span>
                         </a>
                    </div>

                    <!-- Slot Konten Utama (Form Login/Register) -->
                    {{ $slot }}

                </div>
            </div>
            
        </div>
    </body>
</html>