<!DOCTYPE html>
<html lang="id">
<head>
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
    </style>
</head>
<body class="bg-gray-50 font-sans text-gray-800 flex flex-col min-h-screen">

    <!-- Navbar -->
    <nav class="bg-white shadow-md fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center text-teal-700 font-bold text-xl">
                    <a href="/" class="flex items-center">
                        <i class="fas fa-building-columns mr-2"></i> 
                        SiMagang <span class="text-yellow-500 ml-1">Banjarmasin</span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                     @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="bg-teal-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-teal-700 shadow-lg transition">
                                <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                            </a>
                        @else
                            <div class="hidden md:flex items-center space-x-2">
                                <a href="{{ route('pembimbing.register') }}" class="text-gray-500 hover:text-purple-700 px-3 py-2 text-xs font-bold uppercase tracking-wide transition">
                                    Untuk Dosen/Guru
                                </a>
                                <span class="text-gray-300">|</span>
                            </div>
                            <a href="{{ route('login') }}" class="text-gray-600 hover:text-teal-700 px-3 py-2 rounded-md text-sm font-medium transition">
                                Masuk
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-teal-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-teal-700 shadow-lg transition">
                                    Daftar Mahasiswa
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-sasirangan text-white pt-32 pb-24 relative">
        <div class="max-w-7xl mx-auto px-4 text-center relative z-10">
            <h1 class="text-4xl md:text-5xl font-bold mb-4 drop-shadow-md">Bangun Karir Bersama Kota Banjarmasin</h1>
            <p class="text-lg text-teal-100 mb-8 max-w-2xl mx-auto">
                Temukan tempat magang terbaik di berbagai Dinas Pemerintah Kota Banjarmasin.
            </p>
            
            <div class="max-w-3xl mx-auto">
                <form action="{{ route('home') }}" method="GET" class="relative">
                    <div class="flex shadow-2xl rounded-full overflow-hidden p-1 bg-white/20 backdrop-blur-sm border border-white/30">
                        <div class="flex-grow relative">
                            <i class="fas fa-search absolute left-4 top-3.5 text-gray-400"></i>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                class="w-full py-3 pl-12 pr-4 text-gray-700 bg-white rounded-l-full focus:outline-none focus:border-transparent border-0" 
                                placeholder="Cari posisi (e.g. Programmer) atau dinas (e.g. Kominfo)...">
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
                    <div class="text-gray-500 uppercase text-xs font-bold tracking-wider mt-1">SKPD Terdaftar</div>
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
    <div id="lowongan" class="max-w-7xl mx-auto px-4 py-10 flex-grow">
        <div class="flex justify-between items-center mb-8 border-l-4 border-teal-600 pl-4">
            <h2 class="text-3xl font-bold text-gray-800">
                @if(request('search'))
                    Hasil Pencarian: "{{ request('search') }}"
                @else
                    Lowongan Terbaru
                @endif
            </h2>
            @if(request('search'))
                <a href="/" class="text-red-500 hover:text-red-700 text-sm font-semibold">
                    <i class="fas fa-times mr-1"></i> Reset Pencarian
                </a>
            @endif
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($lowongans as $loker)
            <div class="bg-white rounded-xl shadow-sm hover:shadow-xl transition duration-300 border border-gray-100 overflow-hidden flex flex-col group">
                <div class="bg-teal-50 p-4 border-b border-teal-100 flex justify-between items-start group-hover:bg-teal-100 transition">
                    <div>
                        <h3 class="font-bold text-lg text-gray-900 leading-tight">{{ $loker->judul_posisi }}</h3>
                        <p class="text-sm text-teal-700 font-semibold mt-1">
                            <i class="far fa-building mr-1"></i> {{ $loker->skpd->nama_dinas }}
                        </p>
                    </div>
                    <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-bold uppercase tracking-wide">
                        {{ $loker->status }}
                    </span>
                </div>

                <div class="p-5 flex-grow flex flex-col">
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2 flex-grow" title="{{ $loker->deskripsi }}">
                        {{ $loker->deskripsi }}
                    </p>
                    
                    <div class="text-sm text-gray-500 space-y-2 mb-6 bg-gray-50 p-3 rounded-lg">
                        <div class="flex items-center justify-between">
                            <span class="flex items-center"><i class="far fa-calendar-alt w-5 text-center text-teal-500 mr-2"></i> Batas:</span>
                            <span class="font-bold text-gray-700">{{ \Carbon\Carbon::parse($loker->batas_daftar)->format('d M Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="flex items-center"><i class="fas fa-users w-5 text-center text-teal-500 mr-2"></i> Kuota:</span>
                            <span class="font-bold text-gray-700">{{ $loker->kuota }} Orang</span>
                        </div>
                    </div>

                    @auth
                        @if(auth()->user()->role == 'peserta')
                            <a href="{{ route('peserta.daftar.form', $loker->id) }}" 
                               class="w-full block text-center bg-teal-600 text-white py-2 rounded-lg hover:bg-teal-700 transition font-semibold shadow-md hover:shadow-lg transform active:scale-95">
                                Lamar Sekarang
                            </a>
                        @elseif(auth()->user()->role == 'admin_kota' || auth()->user()->role == 'admin_skpd')
                            <button disabled class="w-full block text-center bg-gray-200 text-gray-500 py-2 rounded-lg cursor-not-allowed text-sm font-medium">
                                Mode Admin (View Only)
                            </button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="w-full block text-center bg-gray-800 text-white py-2 rounded-lg hover:bg-gray-700 transition font-semibold shadow-md group-hover:bg-gray-900">
                            Login untuk Melamar
                        </a>
                    @endauth
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12 bg-white rounded-lg border border-dashed border-gray-300">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4 text-gray-400">
                    <i class="fas fa-search fa-2x"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900">Tidak ada lowongan ditemukan</h3>
                <p class="text-gray-500 mt-1">Coba cari dengan kata kunci lain atau hubungi dinas terkait.</p>
                @if(request('search'))
                    <a href="/" class="inline-block mt-4 text-teal-600 font-bold hover:underline">Lihat Semua Lowongan</a>
                @endif
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