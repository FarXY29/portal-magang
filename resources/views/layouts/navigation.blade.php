<nav x-data="{ open: false, visible: true, lastScroll: 0 }"
     @scroll.window="
        visible = (window.pageYOffset < lastScroll || window.pageYOffset <= 0 || open);
        lastScroll = window.pageYOffset;
     "
     :class="{ '-translate-y-full': !visible, 'translate-y-0': visible }"
     class="bg-white border-b border-gray-100 fixed w-full z-50 transition-transform duration-300 top-0">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-24 sm:flex">
                    <!-- 1. Dashboard (Semua User) -->
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <!-- 2. DROPDOWN ADMIN DINAS (SKPD) -->
                    @if(Auth::user()->role == 'admin_skpd')
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <div>Menu Admin Dinas</div>
                                        <div class="ml-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('dinas.mentors.index')">{{ __('Data Mentor') }}</x-dropdown-link>
                                    <x-dropdown-link :href="route('dinas.pelamar')">{{ __('Pelamar Masuk') }}</x-dropdown-link>
                                    <x-dropdown-link :href="route('dinas.lowongan.index')">{{ __('Kelola Lowongan') }}</x-dropdown-link>
                                    <x-dropdown-link :href="route('dinas.peserta.index')">{{ __('Monitoring Peserta') }}</x-dropdown-link>
                                    <x-dropdown-link :href="route('dinas.laporan.rekap')">{{ __('Laporan Rekap') }}</x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endif

                    <!-- 3. DROPDOWN SUPER ADMIN (ADMIN KOTA) - BARU -->
                    @if(Auth::user()->role == 'admin_kota')
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <div>Menu Super Admin</div>
                                        <div class="ml-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('admin.skpd.index')">{{ __('Data Master SKPD') }}</x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.users.index')">{{ __('Manajemen User') }}</x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.laporan')">{{ __('laporan SKPD') }}</x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.laporan.peserta_global')">{{ __('Data Peserta Global') }}</x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.users.logbooks')">{{ __('Monitoring Logbook') }}</x-dropdown-link>
                                    <div class="border-t border-gray-100"></div>
                                    <x-dropdown-link :href="route('admin.settings.index')">{{ __('Pengaturan Sistem') }}</x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger (Menu Mobile) -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Tampilan HP) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <!-- Menu Mobile Khusus Admin Dinas -->
            @if(Auth::user()->role == 'admin_skpd')
                <div class="border-t border-gray-200 mt-2 pt-2">
                    <div class="px-4 text-xs font-bold text-gray-500 uppercase">Menu Admin Dinas</div>
                    <x-responsive-nav-link :href="route('dinas.mentors.index')">{{ __('Data Mentor') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('dinas.pelamar')">{{ __('Pelamar Masuk') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('dinas.lowongan.index')">{{ __('Kelola Lowongan') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('dinas.peserta.index')">{{ __('Monitoring Peserta') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('dinas.laporan.rekap')">{{ __('Laporan Rekap') }}</x-responsive-nav-link>
                </div>
            @endif

            <!-- Menu Mobile Khusus Super Admin -->
            @if(Auth::user()->role == 'admin_kota')
                <div class="border-t border-gray-200 mt-2 pt-2">
                    <div class="px-4 text-xs font-bold text-gray-500 uppercase">Menu Super Admin</div>
                    <x-responsive-nav-link :href="route('admin.skpd.index')">{{ __('Data SKPD') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.users.index')">{{ __('Manajemen User') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.users.logbooks')">{{ __('Monitoring Logbook') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.settings.index')">{{ __('Pengaturan') }}</x-responsive-nav-link>
                </div>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">{{ __('Profile') }}</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>