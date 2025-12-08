<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan Data Peserta Magang</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Tombol Kembali -->
            <div class="mb-6 print:hidden">
                <a href="{{ route('kepala_dinas.dashboard') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 font-medium transition">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 flex justify-between items-center print:border-none">
                    <h3 class="text-lg font-bold text-gray-700">Daftar Mahasiswa</h3>
                    <button onclick="window.print()" class="bg-gray-800 text-white px-4 py-2 rounded text-sm hover:bg-gray-700 print:hidden transition shadow-sm">
                        <i class="fas fa-print mr-2"></i> Cetak Laporan
                    </button>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Peserta</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Asal Instansi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Posisi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nilai Akhir</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($interns as $intern)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">{{ $intern->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $intern->user->nik ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $intern->user->asal_instansi }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $intern->position->judul_posisi }}</td>
                                <td class="px-6 py-4">
                                    @if($intern->status == 'diterima')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Lulus</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-800">
                                    {{ $intern->nilai_angka ?? '-' }} <span class="text-xs font-normal text-gray-500">{{ $intern->predikat ? '('.$intern->predikat.')' : '' }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-8 text-gray-500">Data tidak ditemukan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>