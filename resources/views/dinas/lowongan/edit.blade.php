<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Lowongan Magang</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between mb-6 print:hidden">
                    <a href="{{ route('dinas.lowongan.index') }}" class="text-gray-600 hover:text-gray-900">&larr; Kembali</a>
                </div>
                <form action="{{ route('dinas.lowongan.update', $loker->id) }}" method="POST">
                    @csrf
                    @method('PUT') <!-- Method Spoofing untuk Update -->
                    
                    <!-- Judul Posisi -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Judul Posisi Magang</label>
                        <input type="text" name="judul_posisi" value="{{ old('judul_posisi', $loker->judul_posisi) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200" required>
                    </div>

                    <!-- Syarat Jurusan -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Syarat Jurusan</label>
                        <input type="text" name="required_major" value="{{ old('required_major', $loker->required_major) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200" required>
                        <p class="text-xs text-gray-500 mt-1">Pisahkan dengan koma jika lebih dari satu.</p>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Deskripsi Pekerjaan & Syarat</label>
                        <textarea name="deskripsi" rows="4" class="w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200" required>{{ old('deskripsi', $loker->deskripsi) }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <!-- Kuota -->
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Kuota Penerimaan</label>
                            <input type="number" name="kuota" value="{{ old('kuota', $loker->kuota) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200" min="0" required>
                        </div>
                        
                        <!-- Batas Daftar -->
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Batas Pendaftaran</label>
                            <input type="date" name="batas_daftar" value="{{ old('batas_daftar', $loker->batas_daftar) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200" required>
                        </div>
                    </div>

                    <!-- Status Buka/Tutup -->
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Status Lowongan</label>
                        <select name="status" class="w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200">
                            <option value="buka" {{ $loker->status == 'buka' ? 'selected' : '' }}>BUKA</option>
                            <option value="tutup" {{ $loker->status == 'tutup' ? 'selected' : '' }}>TUTUP</option>
                        </select>
                    </div>

                    <div class="flex justify-end space-x-2 mt-6">
                        <a href="{{ route('dinas.lowongan.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded hover:bg-teal-700 font-bold shadow">
                            <i class="fas fa-save mr-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>