<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Lowongan Baru</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between mb-6 print:hidden">
                    <a href="{{ route('dinas.lowongan.index') }}" class="text-gray-600 hover:text-gray-900">&larr; Kembali</a>
                </div>
                <form action="{{ route('dinas.lowongan.store') }}" method="POST">
                    @csrf
                    

                    <!-- Syarat Jurusan -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Syarat Jurusan</label>
                        <input type="text" name="required_major" class="w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200" placeholder="Contoh: Teknik Informatika, Sistem Informasi, TKJ" required>
                        <p class="text-xs text-gray-500 mt-1">Pisahkan dengan koma jika lebih dari satu jurusan. Tulis "Semua Jurusan" jika bebas.</p>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-4">
                        <label class="block font-bold text-gray-700 mb-2">Deskripsi Pekerjaan & Syarat</label>
                        
                        <textarea name="deskripsi" class="wysiwyg-editor w-full border-gray-300 rounded-md shadow-sm">{{ old('deskripsi') }}</textarea>
                        
                        @error('deskripsi')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <!-- Kuota -->
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Kuota Penerimaan</label>
                            <input type="number" name="kuota" class="w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200" min="1" required>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-2 mt-6">
                        <a href="{{ route('dinas.lowongan.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded hover:bg-teal-700 font-bold shadow">
                            <i class="fas fa-save mr-1"></i> Terbitkan Lowongan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>