<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan Global Peserta Magang (Semua Dinas)</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between mb-6 print:hidden">
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-900">&larr; Kembali</a>
                </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 flex justify-between items-center print:border-none">
                    <h3 class="text-lg font-bold text-gray-700">Data Master Peserta</h3>
                    <button onclick="window.print()" class="bg-gray-800 text-white px-4 py-2 rounded text-sm hover:bg-gray-700 print:hidden transition shadow-sm">
                        <i class="fas fa-print mr-2"></i> Cetak Laporan
                    </button>
                </div>
                
                <table class="min-w-full divide-y divide-gray-200 border border-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Peserta / Instansi</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Lokasi Magang (SKPD)</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Posisi</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($allInterns as $data)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $data->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $data->user->asal_instansi }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 font-medium">
                                {{ $data->position->skpd->nama_dinas }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $data->position->judul_posisi }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($data->status == 'selesai')
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-bold rounded">Selesai</span>
                                @else
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-bold rounded">Aktif</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-8 text-gray-500">Belum ada data magang di sistem.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>