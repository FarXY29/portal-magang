<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard Dinas</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Selamat Datang, Admin Dinas</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Menu 1: Kelola Pelamar -->
                    <a href="{{ route('dinas.pelamar') }}" class="block p-6 border rounded-lg hover:bg-teal-50 bg-white border-teal-200 shadow-sm hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-bold text-xl text-teal-800">Pelamar Masuk</h4>
                            <i class="fas fa-users text-2xl text-teal-500"></i>
                        </div>
                        <p class="text-gray-600 text-sm">Cek dan seleksi mahasiswa yang mendaftar magang.</p>
                    </a>

                    <!-- Menu 2: Kelola Lowongan (YANG BARU DITAMBAHKAN) -->
                    <a href="{{ route('dinas.lowongan.index') }}" class="block p-6 border rounded-lg hover:bg-yellow-50 bg-white border-yellow-200 shadow-sm hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-bold text-xl text-yellow-700">Kelola Lowongan</h4>
                            <i class="fas fa-briefcase text-2xl text-yellow-500"></i>
                        </div>
                        <p class="text-gray-600 text-sm">Tambah posisi magang baru atau tutup pendaftaran.</p>
                    </a>

                    <!-- Menu 3: Peserta Aktif (SUDAH AKTIF) -->
                    <a href="{{ route('dinas.peserta.index') }}" class="block p-6 border rounded-lg hover:bg-blue-50 bg-white border-blue-200 shadow-sm hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-bold text-xl text-blue-800">Peserta Aktif</h4>
                            <i class="fas fa-id-card text-2xl text-blue-500"></i>
                        </div>
                        <p class="text-gray-600 text-sm">Monitor logbook harian peserta & validasi kegiatan.</p>
                    </a>

                    <!-- Menu 4: Kelola Pembimbing Lapangan -->
                     <a href="{{ route('dinas.mentors.index') }}" class="block p-6 border rounded-lg bg-white hover:bg-purple-50 border-purple-200">
                    <h4 class="font-bold text-xl text-purple-800">Data Mentor</h4>
                    <p class="text-gray-600 text-sm">Kelola akun pegawai pembimbing lapangan.</p>
                    </a>

                    <!-- Menu 5: Laporan & Data -->
                     <a href="{{ route('dinas.laporan.rekap') }}" class="block p-6 border rounded-lg bg-white hover:bg-gray-50 border-gray-200 transition">
                        <h4 class="font-bold text-xl text-gray-800">Laporan Rekap</h4>
                        <p class="text-gray-600 text-sm">Cetak daftar peserta magang di dinas ini.</p>
                    </a>
                </div>
        </div>
    </div>
</x-app-layout>