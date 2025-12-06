<x-guest-layout>
    <!-- Tombol Kembali ke Beranda -->
    <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-teal-600 transition mb-6">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Beranda
    </a>

    <div>
        <h2 class="text-3xl font-extrabold text-gray-900">
            Daftar Akun Baru
        </h2>
        <p class="mt-2 text-sm text-gray-600">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-medium text-teal-600 hover:text-teal-500">
                Login di sini
            </a>
        </p>
    </div>

    <div class="mt-8">
        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <!-- Nama Lengkap -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="far fa-user text-gray-400"></i>
                    </div>
                    <input id="name" name="name" type="text" required autofocus autocomplete="name"
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm"
                           placeholder="Nama Lengkap Anda" :value="old('name')">
                </div>
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Username -->
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="far fa-id-badge text-gray-400"></i>
                    </div>
                    <input id="username" name="username" type="text" required
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm"
                           placeholder="Username Anda" :value="old('username')">
                </div>
                <x-input-error :messages="$errors->get('username')" class="mt-2" />
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="far fa-envelope text-gray-400"></i>
                    </div>
                    <input id="email" name="email" type="email" required autocomplete="username"
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm"
                           placeholder="nama@email.com" :value="old('email')">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input id="password" name="password" type="password" required autocomplete="new-password"
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm"
                           placeholder="Minimal 8 karakter">
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm"
                           placeholder="Ketik ulang password">
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div>
                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-bold text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition">
                    DAFTAR SEKARANG <i class="fas fa-user-plus ml-2 mt-1"></i>
                </button>
                <p class="text-xs text-gray-500 text-center mt-4">
                    Dengan mendaftar, Anda menyetujui Syarat & Ketentuan yang berlaku pada Portal Magang Pemerintah Kota Banjarmasin.
                </p>
            </div>
        </form>
    </div>
</x-guest-layout>