<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Lengkapi Berkas Lamaran</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="mb-6 border-b pb-4">
                    <h3 class="text-lg font-bold text-gray-900">Posisi: {{ $position->judul_posisi }}</h3>
                    <p class="text-gray-600">{{ $position->skpd->nama_dinas }}</p>
                </div>

                <!-- Form Upload -->
                <form action="{{ route('peserta.daftar', $position->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Upload CV (DIHAPUS) -->
                    <!-- Input CV dihapus sesuai permintaan -->

                    <!-- Upload Surat Pengantar -->
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Surat Pengantar (PDF)</label>
                        <input type="file" name="surat" class="w-full border border-gray-300 rounded p-2 text-sm" accept=".pdf" required>
                        <p class="text-xs text-gray-500 mt-1">*Surat Permohonan Magang dari Kampus/Sekolah</p>
                        @error('surat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end space-x-2">
                        <a href="{{ route('home') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded hover:bg-teal-700 font-bold shadow">
                            <i class="fas fa-paper-plane mr-1"></i> Kirim Lamaran
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>