<!DOCTYPE html>
<html lang="id">
<head>
    <style>
        /* Mengembalikan style list yang di-reset oleh Tailwind */
        .ck-content ul { list-style-type: disc; padding-left: 20px; }
        .ck-content ol { list-style-type: decimal; padding-left: 20px; }
        .ck-content h2 { font-size: 1.5em; font-weight: bold; margin-top: 10px; }
        .ck-content h3 { font-size: 1.25em; font-weight: bold; margin-top: 10px; }
        .ck-content p { margin-bottom: 0.5em; }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiMagang - Pemkot Banjarmasin</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .bg-sasirangan {
            background-color: #0F766E;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23115e59' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans text-gray-800 flex flex-col min-h-screen">

    <!-- Navbar -->
    <nav x-data="{ mobileMenuOpen: false }" class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-3">
                    <div class="">
                        <x-application-logo class="w-14 h-14 fill-current text-white" />
                    </div>
                    <span class="text-xl font-black text-gray-900 tracking-tighter uppercase">Portal Magang</span>
                </div>

                <div class="hidden md:flex items-center gap-8">
                    <a href="#lowongan" class="text-sm font-bold text-gray-600 hover:text-teal-600 transition">Cari Lowongan</a>
                    
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-teal-100 transition-all text-sm">Dashboard</a>
                        @else
                            <div class="flex items-center gap-4">
                                <a href="{{ route('login') }}" class="text-sm font-bold text-gray-600 hover:text-teal-600 transition">Masuk</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="bg-gray-900 hover:bg-gray-800 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-gray-200 transition-all text-sm">Daftar</a>
                                @endif
                            </div>
                        @endauth
                    @endif
                </div>

                <div class="md:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2.5 rounded-xl bg-gray-50 text-gray-600 hover:text-teal-600 transition-all focus:outline-none">
                        <i class="fas" :class="mobileMenuOpen ? 'fa-times text-xl' : 'fa-bars text-xl'"></i>
                    </button>
                </div>
            </div>
        </div>

        <div x-show="mobileMenuOpen" 
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="md:hidden bg-white border-t border-gray-50 shadow-xl overflow-hidden">
            <div class="px-4 pt-4 pb-6 space-y-3">
                <a href="#lowongan" @click="mobileMenuOpen = false" class="block px-4 py-3 text-base font-bold text-gray-700 hover:bg-teal-50 hover:text-teal-600 rounded-xl transition">
                    <i class="fas fa-search mr-3"></i> Cari Lowongan
                </a>

                <hr class="border-gray-50">

                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="flex items-center justify-center w-full px-4 py-4 bg-teal-600 text-white rounded-2xl font-black shadow-lg shadow-teal-100 transition">
                            <i class="fas fa-th-large mr-2"></i> Ke Dashboard
                        </a>
                    @else
                        <div class="grid grid-cols-2 gap-3">
                            <a href="{{ route('login') }}" class="flex items-center justify-center px-4 py-3.5 text-sm font-bold text-gray-700 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                                Masuk
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="flex items-center justify-center px-4 py-3.5 text-sm font-bold text-white bg-gray-900 rounded-xl hover:bg-gray-800 transition">
                                    Daftar
                                </a>
                            @endif
                        </div>
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-sasirangan text-white pt-32 pb-24 relative">
        <div class="max-w-7xl mx-auto px-4 text-center relative z-10">
            <h1 class="text-4xl md:text-5xl font-bold mb-4 drop-shadow-md">Bangun Karir Bersama Kota Banjarmasin</h1>
            <p class="text-lg text-teal-100 mb-8 max-w-2xl mx-auto">
                Temukan tempat magang terbaik di berbagai Instansi Pemerintah Kota Banjarmasin.
            </p>
            
            <div class="max-w-3xl mx-auto">
                <form action="{{ route('home') }}#lowongan"  method="GET" class="relative group">
                    <div class="flex shadow-2xl rounded-full overflow-hidden p-1 bg-white/20 backdrop-blur-sm border border-white/30">
                        <div class="flex-grow relative">
                            <i class="fas fa-search absolute left-4 top-3.5 text-gray-400"></i>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                class="w-full py-3 pl-12 pr-4 text-gray-700 bg-white rounded-l-full focus:outline-none focus:border-transparent border-0" 
                                placeholder="Cari instansi (cth. Kominfo)...">
                        </div>
                        <button type="submit" class="bg-yellow-500 hover:bg-yellow-400 text-teal-900 font-bold py-3 px-8 rounded-full transition duration-300">
                            Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none rotate-180">
            <svg class="relative block w-full h-[50px]" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="fill-gray-50"></path>
            </svg>
        </div>
    </div>

    <!-- STATISTIK SINGKAT (DINAMIS) -->
    <div class="bg-gray-50 pb-10 pt-4">
        <div class="max-w-6xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                <!-- Data SKPD -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <div class="text-3xl font-bold text-teal-600">{{ $totalSkpd }}</div>
                    <div class="text-gray-500 uppercase text-xs font-bold tracking-wider mt-1">Instansi Terdaftar</div>
                </div>
                <!-- Data Lowongan Buka -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <div class="text-3xl font-bold text-teal-600">{{ $totalLowongan }}</div>
                    <div class="text-gray-500 uppercase text-xs font-bold tracking-wider mt-1">Posisi Tersedia</div>
                </div>
                <!-- Data Alumni Magang -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <div class="text-3xl font-bold text-teal-600">{{ $totalAlumni }}</div>
                    <div class="text-gray-500 uppercase text-xs font-bold tracking-wider mt-1">Alumni Magang</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Lowongan -->
    <div id="lowongan" class="max-w-7xl mx-auto px-4 py-10 flex-grow scroll-mt-24">
    
        <div class="flex flex-col md:flex-row justify-between items-end mb-6 border-b border-gray-200 pb-4 gap-4">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Lowongan Terbaru</h2>
                <p class="text-gray-500 mt-1">Temukan posisi yang sesuai dengan kualifikasi Anda.</p>
            </div>
            
            @if(request()->anyFilled(['posisi', 'skpd_id', 'jurusan', 'search']))
                <a href="/" class="text-red-500 hover:text-red-700 text-sm font-semibold flex items-center bg-red-50 px-3 py-1.5 rounded-full transition">
                    <i class="fas fa-times-circle mr-1"></i> Reset Filter
                </a>
            @endif
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 mb-8">
            <form action="{{ route('home') }}" method="GET">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    

                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1 ml-1">Pilih Instansi</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <i class="fas fa-building"></i>
                            </span>
                            <select name="skpd_id" class="w-full pl-10 pr-8 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm appearance-none cursor-pointer bg-white">
                                <option value="">Semua Instansi</option>
                                @foreach($skpds as $skpd)
                                    <option value="{{ $skpd->id }}" {{ request('skpd_id') == $skpd->id ? 'selected' : '' }}>
                                        {{ Str::limit($skpd->nama_dinas, 25) }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1 ml-1">Jurusan Anda</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <i class="fas fa-graduation-cap"></i>
                            </span>
                            <input type="text" name="jurusan" value="{{ request('jurusan') }}" placeholder="Contoh: Informatika..." 
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm transition">
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded-lg shadow transition transform active:scale-95 flex items-center justify-center">
                            <i class="fas fa-filter mr-2"></i> Terapkan
                        </button>
                    </div>

                </div>
            </form>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($lowongans as $loker)
                @if($loker->kuota > 0)
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-xl transition duration-300 border border-gray-100 overflow-hidden flex flex-col group">
                        <div class="bg-teal-50 p-4 border-b border-teal-100 flex justify-between items-start group-hover:bg-teal-100 transition">
                            <div>
                                <p class="text-sm text-teal-700 font-semibold mt-1">
                                    <i class="far fa-building mr-1"></i> {{ $loker->skpd->nama_dinas }}
                                </p>
                            </div>
                            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-bold uppercase tracking-wide">
                                {{ $loker->status }}
                            </span>
                        </div>
                        
                        <div class="mt-3 mb-2 px-5">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wide">Syarat Jurusan:</span>
                            <p class="text-sm text-gray-800 font-medium bg-gray-100 p-2 rounded mt-1 border border-gray-200">
                                <i class="fas fa-graduation-cap text-teal-600 mr-1"></i> {{ $loker->required_major }}
                            </p>
                        </div>

                        <div class="p-5 flex-grow flex flex-col">
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2 flex-grow" title="{{ $loker->deskripsi }}">
                                <div class="prose prose-sm max-w-none text-gray-600 mb-4 line-clamp-3">
                                    {!! Str::limit(strip_tags($loker->deskripsi), 150) !!}
                                </div>
                            </p>
                            <div class="text-sm text-gray-500 space-y-2 mb-6 bg-gray-50 p-3 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <span class="flex items-center"><i class="fas fa-users w-5 text-center text-teal-500 mr-2"></i> Kuota:</span>
                                    <span class="font-bold text-gray-700">{{ $loker->kuota }} Orang</span>
                                </div>
                            </div>
                            
                            @auth
                                @if(auth()->user()->role == 'peserta')
                                    @php
                                        $userMajor = strtolower(trim(auth()->user()->major ?? ''));
                                        $reqMajor  = strtolower(trim($loker->required_major ?? ''));
                                        $isMatch = str_contains($reqMajor, 'semua jurusan') || 
                                                str_contains($reqMajor, $userMajor) ||
                                                $reqMajor == '' || 
                                                $reqMajor == '-';
                                    @endphp

                                    @if($isMatch)
                                        <a href="{{ route('peserta.daftar.form', $loker->id) }}" class="w-full block text-center bg-teal-600 text-white py-2 rounded-lg hover:bg-teal-700 transition font-semibold shadow-md hover:shadow-lg transform active:scale-95">Ajukan Lamaran</a>
                                    @else
                                        <button disabled class="w-full block text-center bg-gray-100 text-gray-400 py-2 rounded-lg cursor-not-allowed font-medium border border-gray-200"><i class="fas fa-ban mr-1"></i> Jurusan Tidak Sesuai</button>
                                    @endif
                                @elseif(auth()->user()->role == 'admin_kota' || auth()->user()->role == 'admin_skpd')
                                    <button disabled class="w-full block text-center bg-gray-200 text-gray-500 py-2 rounded-lg cursor-not-allowed text-sm font-medium">Mode Admin (View Only)</button>
                                @endif
                            @else
                                {{-- Ubah link ke route peserta.daftar.form. Middleware Auth akan menangani redirect dan mengingat url tujuan --}}
                                <a href="{{ route('peserta.daftar.form', $loker->id) }}" class="w-full block text-center bg-gray-800 text-white py-2 rounded-lg hover:bg-gray-700 transition font-semibold shadow-md group-hover:bg-gray-900">Ajukan Lamaran</a>
                            @endauth
                        </div>
                    </div>
                @endif
            @empty
                <div class="col-span-full text-center py-12 bg-white rounded-lg border border-dashed border-gray-300">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4 text-gray-400">
                        <i class="fas fa-search fa-2x"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Tidak ada lowongan ditemukan</h3>
                    <p class="text-gray-500 mt-1">Coba sesuaikan filter pencarian Anda.</p>
                    <a href="/" class="inline-block mt-4 text-teal-600 font-bold hover:underline">Reset Filter</a>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $lowongans->links() }}
        </div>
    </div>

    <footer class="bg-gray-900 text-white py-8 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="font-semibold text-lg mb-2">Pemerintah Kota Banjarmasin</p>
            <p class="text-gray-400 text-sm">&copy; {{ date('Y') }} Dinas Komunikasi, Informatika, dan Statistik. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>