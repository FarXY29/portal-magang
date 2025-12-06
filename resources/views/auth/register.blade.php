<x-guest-layout>
    <!-- Tombol Kembali -->
    <div class="mb-8">
        <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-800 transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Beranda
        </a>
    </div>

    <!-- Header -->
    <div class="mb-8 text-left">
        <h2 class="text-3xl font-extrabold text-gray-900 leading-tight">
            Daftar Akun Baru
        </h2>
        <p class="mt-2 text-sm text-gray-600">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-bold text-teal-600 hover:text-teal-700 hover:underline transition">
                Masuk di sini
            </a>
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Nama Lengkap -->
        <div>
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
            <input id="name" name="name" type="text" required autofocus autocomplete="name"
                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 sm:text-sm"
                   placeholder="Sesuai KTP" :value="old('name')">
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- Username -->
        <div>
            <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
            <input id="username" name="username" type="text" required
                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 sm:text-sm"
                   placeholder="username_unik" :value="old('username')">
            <x-input-error :messages="$errors->get('username')" class="mt-1" />
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
            <input id="email" name="email" type="email" required autocomplete="username"
                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 sm:text-sm"
                   placeholder="nama@email.com" :value="old('email')">
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
            <input id="password" name="password" type="password" required autocomplete="new-password"
                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 sm:text-sm"
                   placeholder="Min 8 karakter">
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required
                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 sm:text-sm"
                   placeholder="Ulangi password">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <!-- Tombol Daftar (Tebal) -->
        <div class="pt-4">
            <button type="submit" class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-extrabold text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition transform hover:-translate-y-0.5 tracking-wide">
                DAFTAR SEKARANG <i class="fas fa-arrow-right ml-2"></i>
            </button>
            <p class="text-xs text-gray-500 text-center mt-4">
                Dengan mendaftar, Anda menyetujui Syarat & Ketentuan.
            </p>
        </div>

        <!-- Footer Register -->
    <div class="mt-6 text-center border-t border-gray-100 pt-4">
        <p class="text-sm text-gray-600">Anda Dosen / Guru Pembimbing?</p>
        <div class="flex justify-center gap-4 mt-2">
            <a href="{{ route('pembimbing.register') }}" class="text-sm font-bold text-purple-600 hover:underline">
                Daftar Dosen / Guru
            </a>
        </div>
    </div>

    </form>
</x-guest-layout>