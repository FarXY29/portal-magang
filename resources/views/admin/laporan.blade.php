<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan Tahunan Magang</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between mb-6 print:hidden">
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-900">&larr; Kembali</a>
                </div>
            <!-- Tombol Aksi -->
            <div class="flex justify-end mb-6 gap-3 print:hidden">
                <!-- Tombol Excel Baru -->
                <a href="{{ route('admin.laporan.excel') }}" class="bg-green-600 text-white px-4 py-2 rounded shadow hover:bg-green-700 flex items-center">
                    <i class="fas fa-file-excel mr-2"></i> Export Excel
                </a>

                <button onclick="window.print()" class="bg-gray-800 text-white px-4 py-2 rounded shadow hover:bg-gray-700 flex items-center">
                    <i class="fas fa-print mr-2"></i> Cetak / PDF
                </button>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 print:shadow-none">
                
                <!-- Kop Laporan (Hanya muncul saat Print) -->
                <div class="hidden print:block text-center mb-8 border-b-2 border-gray-800 pb-4">
                    <h1 class="text-2xl font-bold uppercase">Pemerintah Kota Banjarmasin</h1>
                    <h2 class="text-xl">Laporan Rekapitulasi Peserta Magang</h2>
                    <p class="text-sm text-gray-600">Dicetak pada: {{ date('d F Y') }}</p>
                </div>

                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nama Instansi</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Total Pelamar</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Tingkat Seleksi (Proses)</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Rasio Peminat (Proses)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 text-sm">
                        @foreach($laporan as $data)
                        <tr>
                            <td class="px-6 py-4 font-medium">{{ $data['nama_dinas'] }}</td>
                            <td class="px-6 py-4 text-center">{{ $data['total_pelamar'] }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 rounded-full bg-blue-50 text-blue-700 font-bold">
                                    {{ $data['seleksi_rate'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-gray-500">{{ $data['avg_peminat'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Tanda Tangan (Hanya muncul saat Print) -->
                <div class="hidden print:flex justify-end mt-16">
                    <div class="text-center">
                        <p class="mb-20">Banjarmasin, {{ date('d F Y') }} <br> Mengetahui,</p>
                        <p class="font-bold underline">Kepala Bakesbangpol</p>
                        <p>NIP. 19700101 200001 1 001</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>