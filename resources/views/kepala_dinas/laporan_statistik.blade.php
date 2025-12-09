<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan Statistik Peminat Magang</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between mb-6 print:hidden">
                    <a href="{{ route('kepala_dinas.dashboard') }}" class="text-gray-600 hover:text-gray-900">&larr; Kembali</a>
                    <button onclick="window.print()" class="bg-gray-800 text-white px-4 py-2 rounded text-sm">Cetak</button>
                </div>

                <table class="min-w-full border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-4 py-2 text-left">Posisi Magang</th>
                            <th class="border px-4 py-2 text-center">Kuota Disediakan</th>
                            <th class="border px-4 py-2 text-center">Jumlah Pelamar</th>
                            <th class="border px-4 py-2 text-center">Diterima / Lulus</th>
                            <th class="border px-4 py-2 text-center">Rasio Keketatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stats as $st)
                        <tr>
                            <td class="border px-4 py-2 font-bold">{{ $st->judul_posisi }}</td>
                            <td class="border px-4 py-2 text-center">{{ $st->kuota + $st->diterima }} (Awal)</td>
                            <td class="border px-4 py-2 text-center font-bold text-blue-600">{{ $st->total_pelamar }} Orang</td>
                            <td class="border px-4 py-2 text-center text-green-600">{{ $st->diterima }} Orang</td>
                            <td class="border px-4 py-2 text-center">
                                1 : {{ $st->total_pelamar > 0 ? round($st->total_pelamar / max(($st->kuota + $st->diterima), 1), 1) : 0 }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>