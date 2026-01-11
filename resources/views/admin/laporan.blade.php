<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-gray-800 leading-tight flex items-center gap-2 print:hidden">
            <i class="fas fa-chart-pie text-teal-600"></i>
            {{ __('Laporan Statistik Magang') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50/50 min-h-screen print:bg-white print:py-0">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 print:max-w-full print:px-0">
            
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4 print:hidden">
                <a href="{{ route('admin.dashboard') }}" class="group flex items-center text-sm font-bold text-gray-500 hover:text-teal-600 transition">
                    <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                        <i class="fas fa-arrow-left text-xs"></i>
                    </div>
                    Kembali ke Dashboard
                </a>

                <div class="flex gap-3">
                    <a href="{{ route('admin.laporan.excel') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 shadow transition font-bold text-sm">
                        <i class="fas fa-file-excel mr-2"></i> Export Excel
                    </a>
                    <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 shadow transition font-bold text-sm">
                        <i class="fas fa-print mr-2"></i> Cetak Laporan
                    </button>
                </div>
            </div>

            <div class="bg-white shadow-xl sm:rounded-2xl overflow-hidden print:shadow-none print:rounded-none">
                <div class="p-8 print:p-0">
                    
                    <div class="hidden print:flex items-center justify-center mb-8 border-b-2 border-gray-800 pb-4 gap-6">
                        <img src="{{ asset('images/Banjarmasin_Logo.svg.png') }}" class="h-24 w-auto" alt="Logo Pemkot">
                        <div class="text-center">
                            <h1 class="text-2xl font-bold uppercase tracking-wide">Pemerintah Kota Banjarmasin</h1>
                            <h2 class="text-xl font-semibold uppercase text-gray-700">Laporan Rekapitulasi Program Magang</h2>
                            <p class="text-sm text-gray-500 mt-1 italic">Periode Tahun {{ date('Y') }}</p>
                        </div>
                    </div>

                    <div class="mb-6 print:hidden border-b border-gray-100 pb-4">
                        <h3 class="text-lg font-bold text-gray-800">Statistik Penerimaan Magang per Instansi</h3>
                        <p class="text-sm text-gray-500">Rekapitulasi total pelamar, tingkat seleksi, dan rasio peminat.</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                            <thead class="bg-gray-50 print:bg-gray-100">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border w-12">No</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border">Instansi (SKPD)</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border">Lowongan Aktif</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border">Total Pelamar</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border">Diterima / Selesai</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border">Tingkat Seleksi</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border">Rasio Peminat</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 text-sm">
                                @forelse($laporan as $index => $data)
                                <tr class="hover:bg-gray-50 transition break-inside-avoid">
                                    <td class="px-6 py-4 text-center text-gray-500 border">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 font-semibold text-gray-900 border">{{ $data['nama_dinas'] }}</td>
                                    <td class="px-6 py-4 text-center border text-gray-600">
                                        {{ $data['lowongan_aktif'] }} Posisi
                                    </td>
                                    <td class="px-6 py-4 text-center border">
                                        <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded font-bold text-xs border border-blue-100">
                                            {{ $data['total_pelamar'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center border">
                                        <span class="px-2 py-1 bg-green-50 text-green-700 rounded font-bold text-xs border border-green-100">
                                            {{ $data['total_magang'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center border font-bold text-gray-700">
                                        {{ $data['seleksi_rate'] }}
                                    </td>
                                    <td class="px-6 py-4 text-center border text-gray-500 italic">
                                        {{ $data['avg_peminat'] }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-gray-500 border italic">
                                        Belum ada data statistik magang yang tersedia.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="hidden print:flex justify-end mt-16 break-inside-avoid">
                        <div class="text-center w-64">
                            <p class="mb-1 text-sm">Banjarmasin, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                            <p class="font-bold text-sm uppercase">Kepala Bakesbangpol</p>
                            <div class="h-24"></div> <p class="font-bold underline uppercase text-sm">H. Lukman Fadlun, SH</p>
                            <p class="text-sm">NIP. 19700101 200001 1 001</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            @page {
                margin: 15mm;
                size: landscape; /* Orientasi Landscape agar tabel muat */
            }
            body {
                background: white;
                -webkit-print-color-adjust: exact;
                font-size: 12px;
            }
            /* Hide Navbar, Sidebar, Toolbar */
            nav, header, .print\:hidden {
                display: none !important;
            }
            /* Styling Table Borders */
            table {
                width: 100%;
                border-collapse: collapse;
            }
            table, th, td {
                border: 1px solid #000 !important;
            }
            th {
                background-color: #f3f4f6 !important; /* bg-gray-100 */
                color: #000 !important;
            }
            /* Avoid breaking rows inside table */
            tr {
                page-break-inside: avoid;
            }
            /* Reset shadow & radius */
            .shadow-xl, .shadow-sm {
                box-shadow: none !important;
            }
            .rounded-2xl, .rounded-lg {
                border-radius: 0 !important;
            }
        }
    </style>
</x-app-layout>