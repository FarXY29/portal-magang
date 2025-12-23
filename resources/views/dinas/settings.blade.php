<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pengaturan Jam Kerja SKPD
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <form action="{{ route('dinas.settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="flex justify-between mb-6 print:hidden">
                        <a href="{{ route('dinas.dashboard') }}" class="text-gray-600 hover:text-gray-900">&larr; Kembali</a>
                    </div>
                    <div class="mb-4">
                        <label class="block font-bold text-gray-700 mb-2">Jam Buka Absen Datang</label>
                        <p class="text-xs text-gray-500 mb-2">Peserta hanya bisa klik tombol "Absen Datang" SETELAH jam ini.</p>
                        <input type="time" name="jam_mulai_masuk" value="{{ $skpd->jam_mulai_masuk }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div class="mb-6">
                        <label class="block font-bold text-gray-700 mb-2">Jam Buka Absen Pulang</label>
                        <p class="text-xs text-gray-500 mb-2">Peserta hanya bisa klik tombol "Absen Pulang" SETELAH jam ini.</p>
                        <input type="time" name="jam_mulai_pulang" value="{{ $skpd->jam_mulai_pulang }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>