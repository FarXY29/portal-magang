<x-guest-layout>
    <!-- Tombol Kembali ke Beranda -->
    <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-teal-600 transition mb-6">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Beranda
    </a>

    <div class="mb-4 text-center">
        <h2 class="text-xl font-bold text-gray-800">Daftar sebagai Pembimbing</h2>
        <p class="text-sm text-gray-600">Khusus Dosen atau Guru Pembimbing</p>
    </div>

    <form method="POST" action="{{ route('pembimbing.register') }}">
        @csrf

        <!-- Nama Lengkap -->
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap (dengan Gelar)')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Contoh: Dr. Budi Santoso, M.Kom" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email Kampus/Sekolah')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Asal Instansi (PENTING) -->
        <div class="mt-4">
            <x-input-label for="asal_instansi" :value="__('Asal Sekolah / Universitas')" />
            <x-text-input id="asal_instansi" class="block mt-1 w-full" type="text" name="asal_instansi" :value="old('asal_instansi')" required placeholder="Contoh: Universitas Lambung Mangkurat" />
            <p class="text-xs text-gray-500 mt-1">Pastikan penulisan nama instansi SAMA PERSIS dengan yang diisi oleh mahasiswa Anda.</p>
            <x-input-error :messages="$errors->get('asal_instansi')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Sudah terdaftar?') }}
            </a>

            <x-primary-button class="ml-4 bg-purple-600 hover:bg-purple-700">
                {{ __('Daftar Pembimbing') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>