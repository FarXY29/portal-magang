<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Eksekutif (Kepala Dinas)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Statistik Utama -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Card Lowongan -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500 flex justify-between items-center">
                    <div>
                        <p class="text-sm font-bold text-gray-500 uppercase">Posisi Magang</p>
                        <h3 class="text-3xl font-extrabold text-gray-800">{{ $totalLowongan }}</h3>
                    </div>
                    <i class="fas fa-briefcase text-3xl text-blue-200"></i>
                </div>

                <!-- Card Peserta Aktif -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500 flex justify-between items-center">
                    <div>
                        <p class="text-sm font-bold text-gray-500 uppercase">Peserta Aktif</p>
                        <h3 class="text-3xl font-extrabold text-gray-800">{{ $totalPesertaAktif }}</h3>
                    </div>
                    <i class="fas fa-users text-3xl text-green-200"></i>
                </div>

                <!-- Card Alumni -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500 flex justify-between items-center">
                    <div>
                        <p class="text-sm font-bold text-gray-500 uppercase">Total Alumni</p>
                        <h3 class="text-3xl font-extrabold text-gray-800">{{ $totalAlumni }}</h3>
                    </div>
                    <i class="fas fa-graduation-cap text-3xl text-purple-200"></i>
                </div>
            </div>

            <!-- Grafik / Tabel Ringkas -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Tabel Lowongan Populer -->
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Posisi Paling Diminati</h3>
                    <ul class="divide-y divide-gray-100">
                        @foreach($popularPositions as $pos)
                        <li class="py-3 flex justify-between items-center">
                            <div>
                                <span class="text-sm font-semibold text-gray-700">{{ $pos->judul_posisi }}</span>
                                <p class="text-xs text-gray-500">Kuota: {{ $pos->kuota }}</p>
                            </div>
                            <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2 py-1 rounded-full">
                                {{ $pos->applications_count }} Pelamar
                            </span>
                        </li>
                        @endforeach
                    </ul>
                    <div class="mt-4 text-right">
                        <a href="{{ route('kepala_dinas.laporan.lowongan') }}" class="text-sm text-blue-600 hover:underline">Lihat Detail Lowongan &rarr;</a>
                    </div>
                </div>

                <!-- Menu Laporan -->
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Akses Laporan Cepat</h3>
                    <div class="space-y-3">
                        <a href="{{ route('kepala_dinas.laporan.peserta') }}" class="block p-4 border rounded-lg hover:bg-gray-50 transition border-gray-200 group">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="p-2 bg-green-100 rounded-lg text-green-600 mr-3">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-800 group-hover:text-green-700">Laporan Data Peserta</h4>
                                        <p class="text-xs text-gray-500">Daftar mahasiswa magang & alumni.</p>
                                    </div>
                                </div>
                                <i class="fas fa-chevron-right text-gray-300"></i>
                            </div>
                        </a>

                        <a href="{{ route('kepala_dinas.laporan.lowongan') }}" class="block p-4 border rounded-lg hover:bg-gray-50 transition border-gray-200 group">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="p-2 bg-blue-100 rounded-lg text-blue-600 mr-3">
                                        <i class="fas fa-briefcase"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-800 group-hover:text-blue-700">Laporan Lowongan</h4>
                                        <p class="text-xs text-gray-500">Status kuota & jumlah pelamar.</p>
                                    </div>
                                </div>
                                <i class="fas fa-chevron-right text-gray-300"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>