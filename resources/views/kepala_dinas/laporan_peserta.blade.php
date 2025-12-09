<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan Rekapitulasi Peserta Magang</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <!-- Tombol Kembali -->
                <div class="flex justify-between mb-6 print:hidden">
                    <a href="{{ route('kepala_dinas.dashboard') }}" class="text-gray-600 hover:text-gray-900">&larr; Kembali</a>
                    <button onclick="window.print()" class="bg-gray-800 text-white px-4 py-2 rounded text-sm">Cetak</button>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 flex justify-between items-center print:border-none">
                        <div>
                            <h3 class="text-lg font-bold text-gray-700">Data Peserta & Nilai Akhir</h3>
                            <p class="text-xs text-gray-500 print:block hidden">Dicetak pada: {{ date('d F Y') }}</p>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border border-gray-100">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Asal Instansi</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Posisi & Mentor</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Periode Magang</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Nilai Akhir</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse($interns as $intern)
                                <tr class="hover:bg-gray-50 transition">
                                    <!-- Data Mahasiswa (Tabel Users) -->
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-gray-900">{{ $intern->user->name }}</div>
                                        <div class="text-xs text-gray-500">NIK: {{ $intern->user->nik ?? '-' }}</div>
                                        <div class="text-xs text-gray-400">{{ $intern->user->email }}</div>
                                    </td>

                                    <!-- Data Instansi (Tabel Users) -->
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-700 font-medium">{{ $intern->user->asal_instansi }}</div>
                                    </td>

                                    <!-- Data Posisi (Tabel Positions) & Mentor (Tabel Users) -->
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 font-bold">{{ $intern->position->judul_posisi }}</div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            <i class="fas fa-user-tie mr-1"></i> Pembimbing: 
                                            <span class="text-gray-700">{{ $intern->mentor->name ?? 'Belum ditentukan' }}</span>
                                        </div>
                                    </td>

                                    <!-- Data Aplikasi (Tanggal) -->
                                    <td class="px-6 py-4 text-xs text-gray-600">
                                        <div>Mulai: {{ \Carbon\Carbon::parse($intern->tanggal_mulai)->format('d M Y') }}</div>
                                        @if($intern->tanggal_selesai)
                                            <div>Selesai: {{ \Carbon\Carbon::parse($intern->tanggal_selesai)->format('d M Y') }}</div>
                                        @else
                                            <div class="text-green-600 font-bold">Sedang Berjalan</div>
                                        @endif
                                    </td>

                                    <!-- Data Aplikasi (Nilai) -->
                                    <td class="px-6 py-4 text-center">
                                        @if($intern->nilai_angka)
                                            <div class="text-lg font-bold text-blue-700">{{ $intern->nilai_angka }}</div>
                                            <div class="text-xs font-bold text-gray-500 bg-gray-100 px-2 py-0.5 rounded inline-block mt-1">
                                                Predikat: {{ $intern->predikat }}
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Belum dinilai</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-folder-open text-3xl mb-2 text-gray-300"></i>
                                            <p>Belum ada data peserta magang.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Footer Tanda Tangan (Hanya muncul saat Print) -->
                    <div class="hidden print:block mt-16 px-10 pb-10">
                        <div class="flex justify-end">
                            <div class="text-center w-1/3">
                                <p class="mb-20">Banjarmasin, {{ date('d F Y') }} <br> Kepala Dinas,</p>
                                <p class="font-bold underline text-lg">{{ Auth::user()->name }}</p>
                                <p>NIP. {{ Auth::user()->nik ?? '.........................' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>