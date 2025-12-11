<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan Rekapitulasi Peserta Magang</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between mb-6 print:hidden">
                    <a href="{{ route('dinas.dashboard') }}" class="text-gray-600 hover:text-gray-900">&larr; Kembali</a>
                </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 flex justify-between items-center print:border-none">
                    <div>
                        <h3 class="text-lg font-bold text-gray-700">Data Peserta Magang</h3>
                        <p class="text-xs text-gray-500">{{ Auth::user()->skpd->nama_dinas }}</p>
                    </div>
                    <button onclick="window.print()" class="bg-gray-800 text-white px-4 py-2 rounded text-sm hover:bg-gray-700 print:hidden transition shadow-sm">
                        <i class="fas fa-print mr-2"></i> Cetak Laporan
                    </button>
                </div>
                
                <table class="min-w-full divide-y divide-gray-200 border border-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nama Peserta</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Posisi</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Mentor</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Periode</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($rekap as $data)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $data->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $data->user->asal_instansi }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $data->position->judul_posisi }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $data->mentor->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-xs text-gray-600">
                                {{ \Carbon\Carbon::parse($data->tanggal_mulai)->format('d M Y') }} 
                                <br> s.d. <br>
                                {{ $data->tanggal_selesai ? \Carbon\Carbon::parse($data->tanggal_selesai)->format('d M Y') : 'Sekarang' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($data->status == 'selesai')
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-bold rounded">Lulus</span>
                                @else
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-bold rounded">Aktif</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-8 text-gray-500">Belum ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Footer Tanda Tangan (Muncul saat Print) -->
                <div class="hidden print:block mt-16 px-10 pb-10">
                    <div class="flex justify-end">
                        <div class="text-center w-1/3">
                            <p class="mb-20">Banjarmasin, {{ date('d F Y') }} <br> Mengetahui,</p>
                            <p class="font-bold underline text-lg">Kepala Dinas</p>
                            <p>NIP. .........................</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>