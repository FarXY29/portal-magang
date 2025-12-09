<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard Kepala Dinas</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Statistik Ringkas (Sama seperti sebelumnya) -->
            <!-- ... (Kode Card Statistik) ... -->

            <!-- MENU 6 LAPORAN LENGKAP -->
            <h3 class="text-lg font-bold text-gray-800 mb-4 px-1">Pusat Laporan & Data</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <!-- 1. Laporan Peserta -->
                <a href="{{ route('kepala_dinas.laporan.peserta') }}" class="block p-6 bg-white border border-gray-200 rounded-lg hover:shadow-md transition hover:border-green-400 group">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 text-green-600 rounded-full mr-4"><i class="fas fa-users"></i></div>
                        <div>
                            <h4 class="font-bold text-gray-800 group-hover:text-green-700">1. Data Peserta</h4>
                            <p class="text-xs text-gray-500">Daftar lengkap peserta & status.</p>
                        </div>
                    </div>
                </a>

                <!-- 2. Laporan Lowongan -->
                <a href="{{ route('kepala_dinas.laporan.lowongan') }}" class="block p-6 bg-white border border-gray-200 rounded-lg hover:shadow-md transition hover:border-blue-400 group">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 text-blue-600 rounded-full mr-4"><i class="fas fa-briefcase"></i></div>
                        <div>
                            <h4 class="font-bold text-gray-800 group-hover:text-blue-700">2. Ketersediaan Lowongan</h4>
                            <p class="text-xs text-gray-500">Kuota & status pendaftaran.</p>
                        </div>
                    </div>
                </a>

                <!-- 3. Laporan Demografi -->
                <a href="{{ route('kepala_dinas.laporan.demografi') }}" class="block p-6 bg-white border border-gray-200 rounded-lg hover:shadow-md transition hover:border-purple-400 group">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 text-purple-600 rounded-full mr-4"><i class="fas fa-university"></i></div>
                        <div>
                            <h4 class="font-bold text-gray-800 group-hover:text-purple-700">3. Demografi Instansi</h4>
                            <p class="text-xs text-gray-500">Persebaran asal kampus/sekolah.</p>
                        </div>
                    </div>
                </a>

                <!-- 4. Laporan Nilai -->
                <a href="{{ route('kepala_dinas.laporan.nilai') }}" class="block p-6 bg-white border border-gray-200 rounded-lg hover:shadow-md transition hover:border-yellow-400 group">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 text-yellow-600 rounded-full mr-4"><i class="fas fa-star"></i></div>
                        <div>
                            <h4 class="font-bold text-gray-800 group-hover:text-yellow-700">4. Penilaian Akhir</h4>
                            <p class="text-xs text-gray-500">Transkrip nilai & predikat.</p>
                        </div>
                    </div>
                </a>

                <!-- 5. Laporan Keaktifan -->
                <a href="{{ route('kepala_dinas.laporan.absensi') }}" class="block p-6 bg-white border border-gray-200 rounded-lg hover:shadow-md transition hover:border-teal-400 group">
                    <div class="flex items-center">
                        <div class="p-3 bg-teal-100 text-teal-600 rounded-full mr-4"><i class="fas fa-calendar-check"></i></div>
                        <div>
                            <h4 class="font-bold text-gray-800 group-hover:text-teal-700">5. Keaktifan Logbook</h4>
                            <p class="text-xs text-gray-500">Rekap absensi & kegiatan.</p>
                        </div>
                    </div>
                </a>

                <!-- 6. Laporan Statistik -->
                <a href="{{ route('kepala_dinas.laporan.statistik') }}" class="block p-6 bg-white border border-gray-200 rounded-lg hover:shadow-md transition hover:border-red-400 group">
                    <div class="flex items-center">
                        <div class="p-3 bg-red-100 text-red-600 rounded-full mr-4"><i class="fas fa-chart-bar"></i></div>
                        <div>
                            <h4 class="font-bold text-gray-800 group-hover:text-red-700">6. Statistik Peminat</h4>
                            <p class="text-xs text-gray-500">Analisis rasio pelamar.</p>
                        </div>
                    </div>
                </a>

            </div>
        </div>
    </div>
</x-app-layout>