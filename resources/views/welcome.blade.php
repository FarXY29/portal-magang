<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiMagang - Pemkot Banjarmasin</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Pattern Sasirangan Modern */
        .bg-sasirangan-modern {
            background-color: #0F766E;
            background-image: 
                linear-gradient(to bottom right, rgba(15, 118, 110, 0.9), rgba(17, 94, 89, 0.95)),
                url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ccfbf1' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            background-size: cover, auto;
        }

        /* Styling Konten CKEditor */
        .ck-content ul { list-style-type: disc; padding-left: 20px; margin-bottom: 0.5rem; }
        .ck-content ol { list-style-type: decimal; padding-left: 20px; margin-bottom: 0.5rem; }
        .ck-content h2 { font-size: 1.25em; font-weight: 700; margin-top: 1rem; color: #111827; }
        .ck-content h3 { font-size: 1.1em; font-weight: 600; margin-top: 0.75rem; color: #374151; }
        .ck-content p { margin-bottom: 0.5em; line-height: 1.6; }
    </style>
</head>
<body class="bg-slate-50 font-sans text-slate-600 flex flex-col min-h-screen">

    <nav x-data="{ mobileMenuOpen: false, scrolled: false }" 
         @scroll.window="scrolled = (window.pageYOffset > 20)"
         :class="scrolled ? 'bg-white/90 backdrop-blur-md shadow-sm' : 'bg-transparent'"
         class="fixed w-full top-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-3">
                    <div class="bg-white rounded-full p-1 shadow-sm">
                        <x-application-logo class="w-10 h-10 fill-current text-teal-600" />
                    </div>
                    <div class="flex flex-col">
                        <span class="text-lg font-bold text-slate-800 leading-none tracking-tight uppercase" :class="scrolled ? 'text-slate-800' : 'text-white'">Portal Magang</span>
                        <span class="text-[10px] font-medium tracking-widest uppercase" :class="scrolled ? 'text-teal-600' : 'text-teal-100'">Pemkot Banjarmasin</span>
                    </div>
                </div>

                <div class="hidden md:flex items-center gap-6">
                    <a href="#lowongan" class="text-sm font-semibold transition hover:text-teal-400" :class="scrolled ? 'text-slate-600' : 'text-white/90'">Cari Lowongan</a>
                    
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="bg-teal-500 hover:bg-teal-600 text-white px-5 py-2.5 rounded-full font-bold shadow-lg shadow-teal-500/30 transition-all text-sm transform hover:-translate-y-0.5">
                                <i class="fas fa-columns mr-2"></i>Dashboard
                            </a>
                        @else
                            <div class="flex items-center gap-3">
                                <a href="{{ route('login') }}" class="px-5 py-2.5 text-sm font-bold transition rounded-full" :class="scrolled ? 'text-slate-600 hover:bg-slate-100' : 'text-white hover:bg-white/10'">
                                    Masuk
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="bg-white text-teal-700 hover:bg-teal-50 px-5 py-2.5 rounded-full font-bold shadow-lg transition-all text-sm transform hover:-translate-y-0.5">
                                        Daftar Sekarang
                                    </a>
                                @endif
                            </div>
                        @endauth
                    @endif
                </div>

                <div class="md:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-lg transition focus:outline-none" :class="scrolled ? 'text-slate-600' : 'text-white'">
                        <i class="fas" :class="mobileMenuOpen ? 'fa-times text-2xl' : 'fa-bars text-2xl'"></i>
                    </button>
                </div>
            </div>
        </div>

        <div x-show="mobileMenuOpen" 
             x-cloak
             @click.away="mobileMenuOpen = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="md:hidden bg-white border-t border-slate-100 shadow-xl absolute w-full left-0">
            <div class="px-4 py-6 space-y-4">
                <a href="#lowongan" @click="mobileMenuOpen = false" class="flex items-center px-4 py-3 text-base font-semibold text-slate-700 bg-slate-50 rounded-xl hover:bg-teal-50 hover:text-teal-600 transition">
                    <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center shadow-sm text-teal-500 mr-3">
                        <i class="fas fa-search text-sm"></i>
                    </div>
                    Cari Lowongan
                </a>

                <div class="border-t border-slate-100 pt-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="flex items-center justify-center w-full px-4 py-3 bg-teal-600 text-white rounded-xl font-bold shadow-lg shadow-teal-200 transition">
                                <i class="fas fa-th-large mr-2"></i> Ke Dashboard
                            </a>
                        @else
                            <div class="grid grid-cols-2 gap-3">
                                <a href="{{ route('login') }}" class="flex items-center justify-center px-4 py-3 text-sm font-bold text-slate-700 border border-slate-200 rounded-xl hover:bg-slate-50 transition">
                                    Masuk
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="flex items-center justify-center px-4 py-3 text-sm font-bold text-white bg-teal-600 rounded-xl hover:bg-teal-700 shadow-md transition">
                                        Daftar
                                    </a>
                                @endif
                            </div>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <div class="bg-sasirangan-modern text-white pt-40 pb-32 md:pb-40 relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-96 h-96 bg-white opacity-5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-72 h-72 bg-teal-400 opacity-10 rounded-full blur-2xl"></div>

        <div class="max-w-7xl mx-auto px-4 text-center relative z-10">
            <span class="inline-block py-1 px-3 rounded-full bg-teal-800/50 border border-teal-400/30 backdrop-blur-sm text-teal-100 text-xs font-bold tracking-wider uppercase mb-6 animate-pulse">
                Official Magang Portal
            </span>
            <h1 class="text-4xl md:text-6xl font-extrabold mb-6 leading-tight drop-shadow-sm">
                Bangun Karir Impian <br/> Bersama <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-200 to-white">Kota Banjarmasin</span>
            </h1>
            <p class="text-lg md:text-xl text-teal-100 mb-10 max-w-2xl mx-auto font-light leading-relaxed">
                Platform resmi pencarian tempat magang di berbagai SKPD dan Instansi Pemerintah Kota Banjarmasin.
            </p>
            
            <div class="max-w-2xl mx-auto">
                <form action="{{ route('home') }}#lowongan" method="GET" class="relative group">
                    <div class="flex items-center p-2 bg-white rounded-full shadow-2xl shadow-teal-900/20 transform transition duration-300 focus-within:scale-[1.02]">
                        <div class="pl-6 text-slate-400">
                            <i class="fas fa-search text-lg"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            class="w-full py-3.5 px-4 text-slate-700 bg-transparent text-base font-medium placeholder-slate-400 focus:outline-none border-none ring-0" 
                            placeholder="Cari posisi atau instansi...">
                        <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-8 rounded-full transition duration-300 shadow-md">
                            Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none">
            <svg class="relative block w-full h-[80px] md:h-[120px]" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z" class="fill-slate-50"></path>
            </svg>
        </div>
    </div>

    <div class="relative z-20 -mt-24 px-4">
        <div class="max-w-5xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-8 rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 flex flex-col items-center text-center transform hover:-translate-y-1 transition duration-300">
                    <div class="w-14 h-14 bg-teal-50 rounded-2xl flex items-center justify-center text-teal-600 mb-4">
                        <i class="fas fa-building text-2xl"></i>
                    </div>
                    <div class="text-4xl font-extrabold text-slate-800">{{ $totalSkpd }}</div>
                    <div class="text-slate-500 text-sm font-semibold uppercase tracking-wide mt-1">Instansi Terdaftar</div>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 flex flex-col items-center text-center transform hover:-translate-y-1 transition duration-300">
                    <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 mb-4">
                        <i class="fas fa-briefcase text-2xl"></i>
                    </div>
                    <div class="text-4xl font-extrabold text-slate-800">{{ $totalLowongan }}</div>
                    <div class="text-slate-500 text-sm font-semibold uppercase tracking-wide mt-1">Posisi Tersedia</div>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 flex flex-col items-center text-center transform hover:-translate-y-1 transition duration-300">
                    <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-500 mb-4">
                        <i class="fas fa-user-graduate text-2xl"></i>
                    </div>
                    <div class="text-4xl font-extrabold text-slate-800">{{ $totalAlumni }}</div>
                    <div class="text-slate-500 text-sm font-semibold uppercase tracking-wide mt-1">Alumni Magang</div>
                </div>
            </div>
        </div>
    </div>

    <div id="lowongan" class="max-w-7xl mx-auto px-4 py-20 flex-grow scroll-mt-24">
    
        <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Lowongan Terbaru</h2>
                <p class="text-slate-500 mt-2 text-lg">Temukan kesempatan mengembangkan diri di instansi pemerintah.</p>
            </div>
            
            @if(request()->anyFilled(['posisi', 'skpd_id', 'jurusan', 'search']))
                <a href="/" class="group flex items-center bg-red-50 text-red-600 px-5 py-2.5 rounded-full text-sm font-bold hover:bg-red-100 hover:text-red-700 transition">
                    <i class="fas fa-undo-alt mr-2 group-hover:-rotate-180 transition duration-500"></i> Reset Filter
                </a>
            @endif
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 mb-10">
            <form action="{{ route('home') }}" method="GET">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif

                <div class="grid grid-cols-1 md:grid-cols-12 gap-5 items-end">
                    <div class="md:col-span-5">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Instansi</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-teal-500 transition">
                                <i class="fas fa-building"></i>
                            </span>
                            <select name="skpd_id" class="w-full pl-11 pr-10 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm font-medium bg-slate-50 focus:bg-white transition appearance-none cursor-pointer">
                                <option value="">Semua Instansi</option>
                                @foreach($skpds as $skpd)
                                    <option value="{{ $skpd->id }}" {{ request('skpd_id') == $skpd->id ? 'selected' : '' }}>
                                        {{ Str::limit($skpd->nama_dinas, 40) }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </span>
                        </div>
                    </div>

                    <div class="md:col-span-5">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Jurusan / Kualifikasi</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-teal-500 transition">
                                <i class="fas fa-graduation-cap"></i>
                            </span>
                            <input type="text" name="jurusan" value="{{ request('jurusan') }}" placeholder="Contoh: Teknik Informatika..." 
                                class="w-full pl-11 pr-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm font-medium bg-slate-50 focus:bg-white transition">
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-3 px-4 rounded-xl shadow-lg shadow-slate-200 transition transform active:scale-95 flex items-center justify-center">
                            <i class="fas fa-filter mr-2"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($lowongans as $loker)
                @if($loker->kuota > 0)
                    <div class="group bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full relative">
                        <div class="p-6 pb-4 border-b border-slate-50 bg-gradient-to-br from-white to-slate-50">
                            <div class="flex justify-between items-start mb-3">
                                <div class="w-12 h-12 rounded-xl bg-teal-50 flex items-center justify-center text-teal-600 shadow-sm">
                                    <i class="fas fa-laptop-code text-xl"></i>
                                </div>
                                <span class="bg-emerald-100 text-emerald-700 text-[10px] px-2.5 py-1 rounded-full font-extrabold uppercase tracking-wider">
                                    {{ $loker->status }}
                                </span>
                            </div>
                            <h3 class="text-xl font-bold text-slate-800 mb-1 group-hover:text-teal-600 transition line-clamp-1" title="{{ $loker->judul ?? 'Posisi Magang' }}">{{ $loker->skpd->nama_dinas }}
                            </h3>
                        </div>

                        <div class="p-6 pt-4 flex-grow flex flex-col">
                            <div class="mb-4">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wide">Kualifikasi:</span>
                                <div class="mt-1.5 flex flex-wrap gap-2">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                        <i class="fas fa-graduation-cap mr-1.5 text-slate-400"></i>
                                        {{ Str::limit($loker->required_major, 30) }}
                                    </span>
                                </div>
                            </div>

                            <div class="prose prose-sm prose-slate max-w-none mb-6 line-clamp-3 text-slate-500 text-sm">
                                {!! Str::limit(strip_tags($loker->deskripsi), 120) !!}
                            </div>

                            <div class="mt-auto pt-4 border-t border-slate-50 flex items-center justify-between text-sm">
                                <div class="flex items-center text-slate-500">
                                    <div class="relative mr-2">
                                        <i class="fas fa-chart-pie text-teal-500 text-lg"></i>
                                        <span class="absolute -top-1 -right-1 flex h-2.5 w-2.5">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                                        </span>
                                    </div>
                                    
                                    <span class="text-xs font-medium">
                                        Kuota {{ \Carbon\Carbon::now()->translatedFormat('F') }}: 
                                        <b class="text-slate-800 text-sm ml-1">{{ $loker->kuota }}</b>
                                    </span>
                                </div>

                                @if($loker->kuota < 5)
                                    <span class="text-[10px] text-red-600 bg-red-50 px-2 py-0.5 rounded-full font-bold border border-red-100">
                                        Segera Habis!
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="p-4 bg-slate-50">
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
                                        <a href="{{ route('peserta.daftar.form', $loker->id) }}" class="flex items-center justify-center w-full bg-teal-600 hover:bg-teal-700 text-white py-3 rounded-xl font-bold shadow-md hover:shadow-lg transition-all transform active:scale-95">
                                            Ajukan Lamaran <i class="fas fa-arrow-right ml-2 text-sm"></i>
                                        </a>
                                    @else
                                        <button disabled class="flex items-center justify-center w-full bg-slate-200 text-slate-400 py-3 rounded-xl font-bold cursor-not-allowed">
                                            <i class="fas fa-lock mr-2"></i> Jurusan Tidak Sesuai
                                        </button>
                                    @endif
                                @elseif(auth()->user()->role == 'admin_kota' || auth()->user()->role == 'admin_skpd')
                                    <button disabled class="w-full text-center text-xs font-bold text-slate-400 uppercase tracking-widest py-2">Preview Mode</button>
                                @endif
                            @else
                                <a href="{{ route('peserta.daftar.form', $loker->id) }}" class="flex items-center justify-center w-full bg-slate-900 hover:bg-slate-800 text-white py-3 rounded-xl font-bold shadow-md hover:shadow-lg transition-all transform active:scale-95 group-hover:bg-teal-600 group-hover:text-white">
                                    Ajukan Lamaran
                                </a>
                            @endauth
                        </div>
                    </div>
                @endif
            @empty
                <div class="col-span-full py-16 text-center">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                        <i class="fas fa-folder-open text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800">Belum ada lowongan yang sesuai</h3>
                    <p class="text-slate-500 mt-2 max-w-md mx-auto">Coba ubah kata kunci pencarian atau filter jurusan Anda untuk menemukan hasil yang lebih banyak.</p>
                    <a href="/" class="inline-block mt-6 text-teal-600 font-bold hover:text-teal-700 hover:underline">
                        Hapus Semua Filter
                    </a>
                </div>
            @endforelse
        </div>
        

        <div class="mt-12">
            {{ $lowongans->links() }}
        </div>
    </div>

    <footer class="bg-slate-900 text-white pt-16 pb-8 mt-auto border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center mb-8">
                <div class="flex items-center gap-3 mb-4 md:mb-0">
                    <x-application-logo class="w-10 h-10 fill-current text-teal-500" />
                    <div>
                        <h4 class="font-bold text-lg leading-none">Portal Magang</h4>
                        <span class="text-xs text-slate-400 uppercase tracking-widest">Pemerintah Kota Banjarmasin</span>
                    </div>
                </div>
                <div class="flex gap-6 text-slate-400">
                    <a href="#" class="hover:text-teal-400 transition"><i class="fab fa-instagram text-xl"></i></a>
                    <a href="#" class="hover:text-teal-400 transition"><i class="fab fa-facebook text-xl"></i></a>
                    <a href="#" class="hover:text-teal-400 transition"><i class="fas fa-globe text-xl"></i></a>
                </div>
            </div>
            <div class="border-t border-slate-800 pt-8 text-center md:text-left flex flex-col md:flex-row justify-between text-sm text-slate-500">
                <p>&copy; {{ date('Y') }} Diskominfotik Banjarmasin. All rights reserved.</p>
                <div class="mt-2 md:mt-0 space-x-4">
                    <a href="#" class="hover:text-white transition">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>