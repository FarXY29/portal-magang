<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Pembimbing Lapangan</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between mb-6 print:hidden">
                    <a href="{{ route('dinas.mentors.index') }}" class="text-gray-600 hover:text-gray-900">&larr; Kembali</a>
                </div>
                <form action="{{ route('dinas.mentors.update', $mentor->id) }}" method="POST">
                    @csrf
                    @method('PUT') <!-- Method Spoofing untuk Update -->
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Pegawai</label>
                        <input type="text" name="name" value="{{ old('name', $mentor->name) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500" required>
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">NIP</label>
                        <input type="text" name="nip" value="{{ old('nip', $mentor->nik) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                        @error('nip') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Email Login</label>
                        <input type="email" name="email" value="{{ old('email', $mentor->email) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500" required>
                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Password Baru (Opsional)</label>
                        <input type="password" name="password" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500" placeholder="Biarkan kosong jika tidak ingin mengganti password">
                        <p class="text-xs text-gray-500 mt-1">*Isi minimal 6 karakter hanya jika ingin mereset password.</p>
                    </div>

                    <div class="flex justify-end space-x-2">
                        <a href="{{ route('dinas.mentors.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded hover:bg-teal-700 font-bold shadow">
                            <i class="fas fa-save mr-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>