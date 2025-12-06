<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informasi Profil') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __("Perbarui informasi profil dan data akademik Anda.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Nama -->
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Alamat email Anda belum diverifikasi.') }}
                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900">
                            {{ __('Kirim ulang verifikasi.') }}
                        </button>
                    </p>
                </div>
            @endif
        </div>

        <!-- NIK -->
        <div>
            <x-input-label for="nik" :value="__('NIK (Nomor Induk Kependudukan)')" />
            <x-text-input id="nik" name="nik" type="text" class="mt-1 block w-full" :value="old('nik', $user->nik)" placeholder="Diperlukan untuk sertifikat" />
            <x-input-error class="mt-2" :messages="$errors->get('nik')" />
        </div>

        <!-- Asal Instansi (PENTING) -->
        <div>
            <x-input-label for="asal_instansi" :value="__('Asal Sekolah / Universitas')" />
            <x-text-input id="asal_instansi" name="asal_instansi" type="text" class="mt-1 block w-full" :value="old('asal_instansi', $user->asal_instansi)" placeholder="Contoh: Universitas Lambung Mangkurat / SMKN 1 Banjarmasin" required />
            <p class="text-xs text-gray-500 mt-1">Tulis nama lengkap instansi agar Pembimbing/Dosen dapat menemukan data Anda.</p>
            <x-input-error class="mt-2" :messages="$errors->get('asal_instansi')" />
        </div>

        <!-- No HP -->
        <div>
            <x-input-label for="phone" :value="__('Nomor WhatsApp')" />
            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" placeholder="Contoh: 0812xxxx" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Simpan Perubahan') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Tersimpan.') }}</p>
            @endif
        </div>
    </form>
</section>