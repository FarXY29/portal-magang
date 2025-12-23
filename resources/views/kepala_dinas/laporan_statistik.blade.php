<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Laporan Statistik & Demografi
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between mb-6 print:hidden">
                <a href="{{ route('kepala_dinas.dashboard') }}" class="text-gray-600 hover:text-gray-900 flex items-center transition">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
                </a>
                <a href="{{ route('kepala_dinas.laporan.statistik.print') }}" target="_blank" class="bg-gray-800 text-white px-4 py-2 rounded text-sm hover:bg-gray-700 transition flex items-center">
                    <i class="fas fa-file-pdf mr-2"></i> Download PDF
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-8 print:shadow-none print:border print:border-gray-300">
                <div class="border-b border-gray-200 pb-4 mb-4">
                    <h3 class="text-lg font-bold text-gray-800 uppercase">A. Statistik Peminat & Penerimaan</h3>
                    <p class="text-sm text-gray-500">Analisis rasio jumlah pelamar vs peserta diterima per posisi.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 border border-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Posisi Magang</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Kuota</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Total Pelamar</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Diterima</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Rasio Persaingan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($stats as $data)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $data->judul_posisi }}</td>
                                <td class="px-6 py-4 text-center text-sm text-gray-600">{{ $data->kuota }}</td>
                                <td class="px-6 py-4 text-center text-sm text-gray-600 font-bold">{{ $data->total_pelamar }}</td>
                                <td class="px-6 py-4 text-center text-sm text-green-600 font-bold">{{ $data->diterima }}</td>
                                <td class="px-6 py-4 text-center text-sm text-gray-600">
                                    @if($data->total_pelamar > 0)
                                        1 : {{ round($data->total_pelamar / ($data->diterima > 0 ? $data->diterima : 1), 1) }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada data lowongan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 print:shadow-none print:border print:border-gray-300 page-break-inside-avoid">
                <div class="border-b border-gray-200 pb-4 mb-4">
                    <h3 class="text-lg font-bold text-gray-800 uppercase">B. Demografi Asal Instansi</h3>
                    <p class="text-sm text-gray-500">Sebaran asal universitas/sekolah peserta magang (Status: Diterima & Selesai).</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border border-gray-100">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Asal Instansi</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Jumlah Peserta</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Persentase</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @php $grandTotal = $demografi->sum('total'); @endphp
                                @forelse($demografi as $demo)
                                <tr>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $demo->asal_instansi }}</td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-900 font-bold">{{ $demo->total }}</td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-600">
                                        {{ $grandTotal > 0 ? round(($demo->total / $grandTotal) * 100, 1) : 0 }}%
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="px-6 py-4 text-center text-gray-500">Belum ada data demografi.</td></tr>
                                @endforelse
                                @if($grandTotal > 0)
                                <tr class="bg-gray-100 font-bold">
                                    <td class="px-6 py-3 text-right text-gray-800">TOTAL</td>
                                    <td class="px-6 py-3 text-center text-gray-800">{{ $grandTotal }}</td>
                                    <td class="px-6 py-3 text-center text-gray-800">100%</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="hidden md:block pl-4 border-l border-gray-100">
                        <h4 class="font-bold text-gray-700 mb-4 text-sm uppercase">Visualisasi Sebaran</h4>
                        <div class="space-y-4">
                            @foreach($demografi->take(5) as $demo)
                                @php $percent = $grandTotal > 0 ? ($demo->total / $grandTotal) * 100 : 0; @endphp
                                <div>
                                    <div class="flex justify-between text-xs mb-1">
                                        <span class="font-semibold text-gray-700">{{ Str::limit($demo->asal_instansi, 25) }}</span>
                                        <span class="text-gray-500">{{ round($percent, 1) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $percent }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <p class="text-xs text-gray-400 mt-4 italic">* Menampilkan 5 instansi terbanyak</p>
                    </div>
                </div>
            </div>
            
            <div class="hidden print:flex justify-end mt-16 page-break-inside-avoid">
                <div class="text-center">
                    <p class="mb-20">Banjarmasin, {{ date('d F Y') }} <br> Mengetahui,</p>
                    <p class="font-bold underline">{{ Auth::user()->name }}</p>
                    <p>Kepala Dinas</p>
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