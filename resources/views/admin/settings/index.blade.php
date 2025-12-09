<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pengaturan Sistem</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between mb-6 print:hidden">
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-900">&larr; Kembali</a>
                </div>
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                
                <!-- CARD 1: PENGATURAN UMUM -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-bold text-gray-800 border-b pb-2 mb-4">Umum</h3>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Aplikasi</label>
                        <input type="text" name="app_name" value="{{ $settings['app_name'] ?? 'SiMagang Banjarmasin' }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200">
                        <p class="text-xs text-gray-500 mt-1">Nama yang tampil di halaman login dan footer.</p>
                    </div>
                </div>

                <!-- CARD 2: PENGUMUMAN -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-bold text-gray-800 border-b pb-2 mb-4">Pengumuman Global</h3>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Isi Pengumuman</label>
                        <textarea name="announcement" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200" placeholder="Contoh: Pendaftaran ditutup sementara karena libur lebaran.">{{ $settings['announcement'] ?? '' }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Akan muncul di halaman dashboard peserta.</p>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-md font-bold hover:bg-gray-900 shadow-lg transition transform hover:scale-105">
                        <i class="fas fa-save mr-2"></i> Simpan Pengaturan
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>