<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan Demografi Asal Instansi</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between mb-6 print:hidden">
                    <a href="{{ route('kepala_dinas.dashboard') }}" class="text-gray-600 hover:text-gray-900">&larr; Kembali</a>
                    <button onclick="window.print()" class="bg-gray-800 text-white px-4 py-2 rounded text-sm">Cetak</button>
                </div>

                <h3 class="text-lg font-bold text-center mb-6">Persebaran Asal Instansi Peserta Magang</h3>

                <table class="min-w-full border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-4 py-2 text-left">No</th>
                            <th class="border px-4 py-2 text-left">Nama Sekolah / Universitas</th>
                            <th class="border px-4 py-2 text-center">Jumlah Peserta</th>
                            <th class="border px-4 py-2 text-center">Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $grandTotal = $demografi->sum('total'); @endphp
                        @foreach($demografi as $index => $data)
                        <tr>
                            <td class="border px-4 py-2">{{ $index + 1 }}</td>
                            <td class="border px-4 py-2 font-bold">{{ $data->asal_instansi }}</td>
                            <td class="border px-4 py-2 text-center">{{ $data->total }} Orang</td>
                            <td class="border px-4 py-2 text-center">
                                {{ $grandTotal > 0 ? round(($data->total / $grandTotal) * 100, 1) : 0 }}%
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>