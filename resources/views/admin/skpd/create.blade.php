<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Instansi & Akun Admin</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('admin.skpd.store') }}" method="POST">
                    @csrf
                    
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">1. Informasi Instansi</h3>

                    <!-- Nama Instansi -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Instansi</label>
                        <input type="text" name="nama_dinas" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Contoh: Dinas Kesehatan" required>
                    </div>
`
                    <!-- Kode Unit & Alamat -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Kode Unit Kerja</label>
                            <input type="text" name="kode_unit_kerja" class="w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Alamat Kantor</label>
                            <input type="text" name="alamat" class="w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                    </div>

                    <!-- Koordinat (Untuk Absensi Geotagging) -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Latitude</label>
                            <input type="text" name="latitude" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Contoh: -3.3194" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Longitude</label>
                            <input type="text" name="longitude" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Contoh: 114.5908" required>
                        </div>
                        <p class="col-span-2 text-xs text-gray-500">*Koordinat diperlukan untuk validasi lokasi absensi peserta magang.</p>
                    </div>

                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2 mt-8">2. Buat Akun Admin Instansi</h3>

                    <!-- Email & Password -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Email Login Admin</label>
                        <input type="email" name="email_admin" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="admin.dinkes@banjarmasin.go.id" required>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Password Login</label>
                        <input type="password" name="password_admin" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Minimal 8 karakter" required>
                    </div>

                    <div class="flex justify-end space-x-2">
                        <a href="{{ route('admin.skpd.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-blue-800 text-white rounded hover:bg-blue-900 font-bold shadow">
                            <i class="fas fa-save mr-1"></i> Simpan Data
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>