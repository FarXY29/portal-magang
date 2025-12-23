<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Laporan Master Data SKPD
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between mb-6 print:hidden">
                <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-900">
                    &larr; Kembali ke Dashboard
                </a>
                <button onclick="window.print()" class="bg-gray-800 text-white px-4 py-2 rounded shadow hover:bg-gray-700 flex items-center transition">
                    <i class="fas fa-print mr-2"></i> Cetak Laporan
                </button>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 print:shadow-none print:p-0">
                
                <div class="hidden print:block text-center mb-8 border-b-2 border-gray-800 pb-4">
                    <h1 class="text-2xl font-bold uppercase">Pemerintah Kota Banjarmasin</h1>
                    <h2 class="text-xl">Laporan Data Satuan Kerja Perangkat Daerah (SKPD)</h2>
                    <p class="text-sm text-gray-600">Dicetak pada: {{ date('d F Y') }}</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase border w-10">No</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase border">Nama Dinas / Instansi</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase border">Kode Unit Kerja</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase border">Alamat</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase border">Koordinat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($skpds as $index => $skpd)
                            <tr>
                                <td class="px-4 py-3 text-center text-sm text-gray-900 border">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900 border">{{ $skpd->nama_dinas }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 border">{{ $skpd->kode_unit_kerja }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 border">{{ $skpd->alamat }}</td>
                                <td class="px-4 py-3 text-sm text-gray-500 border font-mono text-xs">
                                    {{ $skpd->latitude }}, <br> {{ $skpd->longitude }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500 border">
                                    Belum ada data SKPD.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="hidden print:flex justify-end mt-16 page-break-inside-avoid">
                    <div class="text-center">
                        <p class="mb-20">Banjarmasin, {{ date('d F Y') }} <br> Mengetahui,</p>
                        <p class="font-bold underline">Kepala Bakesbangpol</p>
                        <p>NIP. 19700101 200001 1 001</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
    
    <style>
        @media print {
            body { -webkit-print-color-adjust: exact; }
            .no-print { display: none; }
        }
    </style>
</x-app-layout>