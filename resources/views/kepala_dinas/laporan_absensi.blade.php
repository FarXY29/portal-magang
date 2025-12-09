<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan Keaktifan Peserta (Logbook)</h2>
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
                            <th class="border px-4 py-2 text-left">Nama Peserta</th>
                            <th class="border px-4 py-2 text-left">Posisi</th>
                            <th class="border px-4 py-2 text-center">Total Logbook</th>
                            <th class="border px-4 py-2 text-center">Logbook Valid (Disetujui)</th>
                            <th class="border px-4 py-2 text-center">Keaktifan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activity as $act)
                        <tr>
                            <td class="border px-4 py-2 font-bold">{{ $act->user->name }}</td>
                            <td class="border px-4 py-2">{{ $act->position->judul_posisi }}</td>
                            <td class="border px-4 py-2 text-center">{{ $act->total_log }} Hari</td>
                            <td class="border px-4 py-2 text-center text-green-600 font-bold">{{ $act->valid_log }} Hari</td>
                            <td class="border px-4 py-2 text-center">
                                @if($act->total_log > 0)
                                    {{ round(($act->valid_log / $act->total_log) * 100) }}% Valid
                                @else
                                    0%
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>