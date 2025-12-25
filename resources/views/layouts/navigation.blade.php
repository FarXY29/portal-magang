<nav class="flex flex-col h-screen sticky top-0 bg-white border-r border-gray-200 text-gray-600 shadow-sm">
    <div class="h-16 flex items-center px-6 bg-teal-600 border-b border-teal-700 flex-shrink-0">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <div class="bg-white p-1 rounded-lg shadow-sm">
                <x-application-logo class="w-7 h-7 fill-current text-teal-600" />
            </div>
            <span class="text-white font-black text-sm tracking-tighter uppercase">Portal Magang</span>
        </a>
    </div>

    <div class="flex-1 overflow-y-auto scrollbar-hide flex flex-col">
        
        <div class="px-4 py-8 space-y-4">
            

            @if(Auth::user()->role == 'admin_kota')
            <div x-data="{ open: {{ request()->routeIs('admin.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="flex items-center justify-between w-full px-3 py-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest hover:text-teal-600 transition-colors focus:outline-none">
                    <span>Super Admin</span>
                    <i class="fas text-[8px]" :class="open ? 'fa-chevron-down' : 'fa-chevron-right'"></i>
                </button>
                <div x-show="open" x-transition.origin.top class="mt-2 flex flex-col space-y-1 border-l-2 border-gray-100 ml-3">
                    <a href="{{ route('admin.skpd.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-r-xl hover:bg-gray-50 hover:text-teal-600 {{ request()->routeIs('admin.skpd.*') ? 'text-teal-700 font-bold' : '' }}">
                        Data Master SKPD
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-r-xl hover:bg-gray-50 hover:text-teal-600 {{ request()->routeIs('admin.users.index') ? 'text-teal-700 font-bold' : '' }}">
                        Manajemen Pengguna
                    </a>
                    <a href="{{ route('admin.laporan') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-r-xl hover:bg-gray-50 hover:text-teal-600 {{ request()->routeIs('admin.laporan') ? 'text-teal-700 font-bold' : '' }}">
                        Laporan SKPD
                    </a>
                    <a href="{{ route('admin.users.logbooks') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-r-xl hover:bg-gray-50 hover:text-teal-600 {{ request()->routeIs('admin.users.logbooks') ? 'text-teal-700 font-bold' : '' }}">
                        Monitoring Logbook
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-r-xl hover:bg-gray-50 hover:text-teal-600 {{ request()->routeIs('admin.settings.*') ? 'text-teal-700 font-bold' : '' }}">
                        Pengaturan Sistem
                    </a>
                </div>
            </div>
            @endif

            @if(Auth::user()->role == 'admin_skpd')
            <div x-data="{ open: {{ request()->routeIs('dinas.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="flex items-center justify-between w-full px-3 py-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest hover:text-teal-600 transition-colors focus:outline-none">
                    <span>Manajemen Dinas</span>
                    <i class="fas text-[8px]" :class="open ? 'fa-chevron-down' : 'fa-chevron-right'"></i>
                </button>
                <div x-show="open" x-transition.origin.top class="mt-2 flex flex-col space-y-1 border-l-2 border-gray-100 ml-3">
                    <a href="{{ route('dinas.lowongan.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-r-xl hover:bg-gray-50 hover:text-teal-600 {{ request()->routeIs('dinas.lowongan.*') ? 'text-teal-700 font-bold' : '' }}">
                        Kelola Lowongan
                    </a>
                    <a href="{{ route('dinas.pelamar') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-r-xl hover:bg-gray-50 hover:text-teal-600 {{ request()->routeIs('dinas.pelamar') ? 'text-teal-700 font-bold' : '' }}">
                        Pelamar Masuk
                    </a>
                    <a href="{{ route('dinas.peserta.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-r-xl hover:bg-gray-50 hover:text-teal-600 {{ request()->routeIs('dinas.peserta.index') ? 'text-teal-700 font-bold' : '' }}">
                        Monitoring Peserta
                    </a>
                    <a href="{{ route('dinas.mentors.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-r-xl hover:bg-gray-50 hover:text-teal-600 {{ request()->routeIs('dinas.mentors.*') ? 'text-teal-700 font-bold' : '' }}">
                        Data Mentor
                    </a>
                    <a href="{{ route('dinas.laporan.rekap') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-r-xl hover:bg-gray-50 hover:text-teal-600 {{ request()->routeIs('dinas.laporan.rekap') ? 'text-teal-700 font-bold' : '' }}">
                        Laporan Rekap
                    </a>
                    <a href="{{ route('dinas.settings') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-r-xl hover:bg-gray-50 hover:text-teal-600 {{ request()->routeIs('dinas.laporan.rekap') ? 'text-teal-700 font-bold' : '' }}">
                        Pengaturan
                    </a>
                </div>
            </div>
            @endif

            @if(Auth::user()->role == 'mentor')
            <div x-data="{ open: {{ request()->routeIs('mentor.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="flex items-center justify-between w-full px-3 py-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest hover:text-teal-600 transition-colors focus:outline-none">
                    <span>Monitoring</span>
                    <i class="fas text-[8px]" :class="open ? 'fa-chevron-down' : 'fa-chevron-right'"></i>
                </button>
                <div x-show="open" x-transition.origin.top class="mt-2 flex flex-col space-y-1 border-l-2 border-gray-100 ml-3">
                    <a href="{{ route('mentor.attendance.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-r-xl hover:bg-gray-50 hover:text-teal-600 {{ request()->routeIs('mentor.attendance.*') ? 'text-teal-700 font-bold' : '' }}">
                        Absensi Peserta
                    </a>
                </div>
            </div>
            @endif

            @if(Auth::user()->role == 'peserta')
            <div x-data="{ open: {{ request()->routeIs('peserta.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="flex items-center justify-between w-full px-3 py-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest hover:text-teal-600 transition-colors focus:outline-none">
                    <span>Kegiatan Saya</span>
                    <i class="fas text-[8px]" :class="open ? 'fa-chevron-down' : 'fa-chevron-right'"></i>
                </button>
                <div x-show="open" x-transition.origin.top class="mt-2 flex flex-col space-y-1 border-l-2 border-gray-100 ml-3">
                    <a href="{{ route('peserta.logbook.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-r-xl hover:bg-gray-50 hover:text-teal-600 {{ request()->routeIs('peserta.logbook.index') ? 'text-teal-700 font-bold' : '' }}">
                        Logbook Harian
                    </a>
                    <a href="{{ route('peserta.sertifikat') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-r-xl hover:bg-gray-50 hover:text-teal-600 {{ request()->routeIs('peserta.sertifikat') ? 'text-teal-700 font-bold' : '' }}">
                        Sertifikat
                    </a>
                </div>
            </div>
            @endif
        </div>

        <div class="px-4 pb-8 mt-4">
            <div class="p-4 border-t border-gray-100 bg-gray-50 rounded-2xl">
                <div class="flex items-center mb-4 px-1">
                    <div class="w-10 h-10 rounded-full bg-teal-600 flex items-center justify-center text-white font-black text-sm shadow-sm flex-shrink-0 ring-2 ring-white">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="ml-3 min-w-0">
                        <p class="text-xs font-bold text-gray-800 truncate leading-tight">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] font-medium text-teal-600 uppercase tracking-tighter">{{ Auth::user()->role }}</p>
                    </div>
                </div>
                
                <div class="flex flex-col space-y-1">
                    <a href="{{ route('profile.edit') }}" class="flex items-center px-3 py-2 text-[11px] font-bold text-gray-600 hover:text-teal-600 rounded-lg hover:bg-white transition duration-200">
                        <i class="fas fa-user-circle w-5 mr-3 text-center text-gray-400"></i> Profil Saya
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-3 py-2 text-[11px] font-bold text-red-500 hover:text-red-700 rounded-lg hover:bg-red-50 transition duration-200">
                            <i class="fas fa-sign-out-alt w-5 mr-3 text-center"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</nav>