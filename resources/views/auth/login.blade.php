<x-guest-layout>
    <!-- Tombol Kembali ke Beranda -->
    <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-teal-600 transition mb-6">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Beranda
    </a>

    <div>
        <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
            Masuk ke Akun Anda
        </h2>
        <p class="mt-2 text-sm text-gray-600">
            Atau
            <a href="{{ route('register') }}" class="font-medium text-teal-600 hover:text-teal-500">
                daftar sebagai peserta magang baru
            </a>
        </p>
    </div>

    <div class="mt-8">
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email / Username Address -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">
                    Email / Username
                </label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="far fa-user text-gray-400"></i>
                    </div>
                    <!-- Type diganti 'text' agar browser tidak memaksa format email -->
                    <input id="email" name="email" type="text" required autofocus
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm"
                           placeholder="email@contoh.com atau username" :value="old('email')">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">
                    Password
                </label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm"
                           placeholder="********">
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember_me" name="remember" type="checkbox" 
                           class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-900">
                        Ingat Saya
                    </label>
                </div>

                <div class="text-sm">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="font-medium text-teal-600 hover:text-teal-500">
                            Lupa password?
                        </a>
                    @endif
                </div>
            </div>

            <div>
                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-bold text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition">
                    MASUK SEKARANG <i class="fas fa-arrow-right ml-2 mt-1"></i>
                </button>
            </div>
        </form>

        <!-- Link Khusus Dosen/Guru -->
        <div class="mt-6 text-center border-t pt-4">
            <p class="text-sm text-gray-600 mb-2">Anda Dosen atau Guru Pembimbing?</p>
            <a href="{{ route('pembimbing.register') }}" class="inline-block text-sm font-bold text-purple-600 hover:text-purple-800 bg-purple-50 px-4 py-2 rounded-full transition">
                <i class="fas fa-chalkboard-teacher mr-1"></i> Daftar di sini
            </a>
        </div>
    </div>
</x-guest-layout>