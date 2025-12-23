<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Data SKPD
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('admin.skpd.index') }}" class="text-gray-600 hover:text-gray-900">&larr; Kembali</a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <form action="{{ route('admin.skpd.update', $skpd->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <x-input-label for="nama_dinas" :value="__('Nama Dinas / Instansi')" />
                            <x-text-input id="nama_dinas" class="block mt-1 w-full" type="text" name="nama_dinas" :value="old('nama_dinas', $skpd->nama_dinas)" required autofocus />
                            <x-input-error :messages="$errors->get('nama_dinas')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="kode_unit_kerja" :value="__('Kode Unit Kerja')" />
                            <x-text-input id="kode_unit_kerja" class="block mt-1 w-full" type="text" name="kode_unit_kerja" :value="old('kode_unit_kerja', $skpd->kode_unit_kerja)" required />
                            <x-input-error :messages="$errors->get('kode_unit_kerja')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="alamat" :value="__('Alamat Kantor')" />
                            <textarea id="alamat" name="alamat" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" rows="3" required>{{ old('alamat', $skpd->alamat) }}</textarea>
                            <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-input-label for="latitude" :value="__('Latitude')" />
                                <x-text-input id="latitude" class="block mt-1 w-full" type="text" name="latitude" :value="old('latitude', $skpd->latitude)" required />
                                <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="longitude" :value="__('Longitude')" />
                                <x-text-input id="longitude" class="block mt-1 w-full" type="text" name="longitude" :value="old('longitude', $skpd->longitude)" required />
                                <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Simpan Perubahan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>