<x-guest-layout>
    <div class="flex flex-col md:flex-row gap-6 max-w-6xl mx-auto my-8 px-4 sm:px-6">
        
        <div class="w-full md:w-5/12 bg-purple-700 rounded-3xl shadow-xl overflow-hidden relative flex flex-col justify-between p-8 md:p-12 min-h-[450px]">
            
            <div class="absolute top-0 right-0 -mt-12 -mr-12 w-48 h-48 bg-white opacity-10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -mb-12 -ml-12 w-64 h-64 bg-purple-900 opacity-30 rounded-full blur-3xl"></div>

            <div class="relative z-10">
                <a href="{{ route('home') }}" class="group inline-flex items-center text-sm font-bold text-purple-100 hover:text-white transition">
                    <div class="w-10 h-10 rounded-full bg-purple-800/50 flex items-center justify-center mr-3 group-hover:bg-purple-600 transition shadow-sm border border-purple-500/30">
                        <i class="fas fa-arrow-left text-sm"></i>
                    </div>
                    Kembali ke Beranda
                </a>
            </div>

            <div class="relative z-10 mt-10 md:mt-0">
                <div class="w-20 h-20 bg-white/10 rounded-2xl flex items-center justify-center mb-6 backdrop-blur-md border border-white/20 shadow-inner">
                    <i class="fas fa-chalkboard-teacher text-4xl text-white"></i>
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-white mb-4 drop-shadow-md">
                    Portal Pembimbing
                </h1>
                <p class="text-purple-100 text-lg font-medium leading-relaxed opacity-90">
                    Pantau perkembangan, logbook, dan nilai mahasiswa/siswa bimbingan Anda secara real-time.
                </p>
            </div>

            <div class="relative z-10 mt-12">
                <p class="text-xs text-purple-200/60 font-medium">
                    SiMagang &copy; {{ date('Y') }}
                </p>
            </div>
        </div>

        <div class="w-full md:w-7/12 bg-white rounded-3xl shadow-xl overflow-hidden p-8 md:p-12 border border-gray-100">
            
            <div class="mb-8">
                <h2 class="text-3xl font-extrabold text-gray-900">Daftar Akun Baru</h2>
                <p class="mt-2 text-sm text-gray-500">
                    Khusus untuk Dosen atau Guru Pembimbing Sekolah.
                </p>
            </div>

            <form method="POST" action="{{ route('pembimbing.register') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5 ml-1">Nama Lengkap (Dengan Gelar)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-user-tie text-gray-400"></i>
                        </div>
                        <input id="name" name="name" type="text" required autofocus
                            class="block w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm bg-gray-50 focus:bg-white transition"
                            placeholder="Contoh: Dr. Budi Santoso, M.Kom" :value="old('name')">
                    </div>
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5 ml-1">Email Kampus/Sekolah</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input id="email" name="email" type="email" required
                            class="block w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm bg-gray-50 focus:bg-white transition"
                            placeholder="nama@univ.ac.id" :value="old('email')">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5 ml-1">Asal Universitas / Sekolah</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-university text-gray-400"></i>
                        </div>
                        <input id="asal_instansi" name="asal_instansi" type="text" required
                            class="block w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm bg-gray-50 focus:bg-white transition"
                            placeholder="Contoh: Universitas Lambung Mangkurat" :value="old('asal_instansi')">
                    </div>
                    
                    <div class="mt-2 flex gap-2 bg-yellow-50 p-2.5 rounded-lg border border-yellow-100 items-start">
                        <i class="fas fa-info-circle text-yellow-600 text-xs mt-0.5"></i>
                        <p class="text-xs text-yellow-700 leading-tight">
                            <strong>Penting:</strong> Pastikan penulisan nama instansi SAMA PERSIS dengan yang diisi oleh mahasiswa bimbingan Anda agar data terhubung.
                        </p>
                    </div>
                    <x-input-error :messages="$errors->get('asal_instansi')" class="mt-1" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5 ml-1">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input id="password" name="password" type="password" required autocomplete="new-password"
                                class="block w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm bg-gray-50 focus:bg-white transition"
                                placeholder="Min. 8 karakter">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5 ml-1">Konfirmasi Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-check-double text-gray-400"></i>
                            </div>
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                class="block w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm bg-gray-50 focus:bg-white transition"
                                placeholder="Ulangi password">
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full flex justify-center items-center py-4 px-6 border border-transparent rounded-xl shadow-lg shadow-purple-200 text-sm font-extrabold text-white bg-purple-700 hover:bg-purple-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition transform hover:-translate-y-0.5">
                        DAFTAR PEMBIMBING <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>

                <div class="text-center pt-2">
                    <p class="text-sm text-gray-500">
                        Sudah punya akun? 
                        <a href="{{ route('login') }}" class="font-bold text-purple-700 hover:text-purple-900 hover:underline transition">
                            Masuk di sini
                        </a>
                    </p>
                </div>
            </form>

        </div>
    </div>
</x-guest-layout>