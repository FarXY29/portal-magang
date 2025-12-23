<x-guest-layout>
    
    <!-- Tombol Kembali (Di Paling Atas) -->
    <div class="mb-8">
        <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-800 transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Beranda
        </a>
    </div>

    <!-- Header (Teks Besar & Rata Kiri) -->
    <div class="mb-8 text-left">
        <h2 class="text-3xl font-extrabold text-gray-900 leading-tight">
            Masuk ke Akun Anda
        </h2>
        <p class="mt-2 text-sm text-gray-600">
            Atau 
            <a href="{{ route('register') }}" class="font-bold text-teal-600 hover:text-teal-700 hover:underline transition">
                daftar sebagai peserta magang baru
            </a>
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email / Username -->
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                Email / Username
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="far fa-envelope text-gray-400 text-lg"></i>
                </div>
                <input id="email" name="email" type="text" required autofocus
                       class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 sm:text-sm transition placeholder-gray-400"
                       placeholder="email@contoh.com atau username" :value="old('email')">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-gray-400 text-lg"></i>
                </div>
                <input id="password" name="password" type="password" required autocomplete="current-password"
                       class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 sm:text-sm transition placeholder-gray-400"
                       placeholder="••••••••">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-teal-600 shadow-sm focus:ring-teal-500 w-4 h-4" name="remember">
                <span class="ml-2 text-sm text-gray-600">Ingat Saya</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm font-bold text-teal-600 hover:text-teal-800">
                    Lupa password?
                </a>
            @endif
        </div>

        <!-- Tombol Login (Tebal & Lebar) -->
        <div>
            <button type="submit" class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-extrabold text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition transform hover:-translate-y-0.5 tracking-wide">
                MASUK SEKARANG <i class="fas fa-arrow-right ml-2"></i>
            </button>
        </div>
        <div class="mt-4">
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">Atau masuk dengan</span>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('google.login') }}" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="h-5 w-5 mr-2" alt="Google">
                    Lanjutkan dengan Google
                </a>
            </div>
        </div>
    </form>

    <!-- Footer Login -->
    <div class="mt-6 text-center border-t border-gray-100 pt-4">
        <p class="text-sm text-gray-600">Belum punya akun?</p>
        <div class="flex justify-center gap-4 mt-2">
            <a href="{{ route('register') }}" class="text-sm font-bold text-teal-600 hover:underline">
                Daftar Mahasiswa
            </a>
            <span class="text-gray-300">|</span>
            <a href="{{ route('pembimbing.register') }}" class="text-sm font-bold text-purple-600 hover:underline">
                Daftar Dosen / Guru
            </a>
        </div>
    </div>
</x-guest-layout>