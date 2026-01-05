<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Mentor Lapangan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 shadow-sm">
                    <i class="fas fa-check mr-1"></i> {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500">
                <h3 class="text-lg font-bold mb-1">Daftar Mahasiswa Bimbingan</h3>
                <p class="text-sm text-gray-500 mb-4">Kelola logbook harian dan berikan penilaian akhir.</p>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Peserta</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jadwal Magang</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Kehadiran (Valid)</th> <!-- Kolom Baru -->
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nilai Akhir</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($interns as $mhs)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">{{ $mhs->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $mhs->user->email }}</div>
                                    <div class="mt-1">
                                        @if($mhs->status == 'diterima')
                                            <span class="px-2 py-0.5 text-[10px] bg-green-100 text-green-800 rounded-full font-bold">AKTIF</span>
                                        @else
                                            <span class="px-2 py-0.5 text-[10px] bg-blue-100 text-blue-800 rounded-full font-bold">SELESAI</span>
                                        @endif
                                    </div>
                                </td>

                                
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($mhs->tanggal_mulai)
                                        <div class="text-sm text-gray-900 font-medium">
                                            <i class="far fa-calendar-alt text-indigo-400 mr-1"></i>
                                            {{ \Carbon\Carbon::parse($mhs->tanggal_mulai)->format('d M') }} - {{ \Carbon\Carbon::parse($mhs->tanggal_selesai)->format('d M Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1 pl-5">
                                            Status: 
                                            @if($mhs->status == 'diterima') 
                                                <span class="text-green-600 font-bold">Aktif</span>
                                            @else 
                                                <span class="text-blue-600 font-bold">Lulus</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400">Belum diset</span>
                                    @endif
                                </td>
                                
                                <!-- KOLOM KEHADIRAN (BARU) -->
                                <td class="px-6 py-4 text-center">
                                    @php
                                        // Menghitung logbook yang sudah divalidasi (disetujui)
                                        $validLogs = $mhs->logs->where('status_validasi', 'disetujui')->count();
                                    @endphp
                                    <div class="text-lg font-bold text-green-600">{{ $validLogs }} Hari</div>
                                    <div class="text-[10px] text-gray-400">Total Validasi</div>
                                </td>

                                <td class="px-6 py-4">
                                    @if($mhs->nilai_angka)
                                        <div class="text-lg font-bold text-indigo-700">{{ $mhs->nilai_angka }}</div>
                                        <div class="text-xs text-gray-500">Predikat: {{ $mhs->predikat }}</div>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Belum dinilai</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 flex flex-col gap-2">
                                    <!-- Validasi Logbook -->
                                    <a href="{{ route('mentor.logbook', $mhs->id) }}" class="inline-flex items-center justify-center bg-white border border-gray-300 text-gray-700 px-3 py-1.5 rounded text-xs font-bold hover:bg-gray-50 shadow-sm transition">
                                        <i class="fas fa-book mr-1"></i> Logbook
                                    </a>
                                    <!-- Validasi Kehadiran -->
                                    <a href="{{ route('mentor.attendance.index', $mhs->id) }}" class="inline-flex items-center justify-center bg-white border border-gray-300 text-gray-700 px-3 py-1.5 rounded text-xs font-bold hover:bg-gray-50 shadow-sm transition">
                                        <i class="fas fa-book mr-1"></i> Kehadiran
                                    </a>

                                    <!-- Input Nilai (Hanya jika belum selesai/lulus) -->
                                    <div class="flex items-center gap-3">

                                        @if($mhs->status == 'diterima' || $mhs->status == 'selesai')
                                            <a href="{{ route('mentor.penilaian', $mhs->id) }}" 
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest transition ease-in-out duration-150 {{ $mhs->nilai_rata_rata ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-teal-600 hover:bg-teal-700' }}">
                                                <i class="fas fa-edit mr-1.5"></i>
                                                {{ $mhs->nilai_rata_rata ? 'Edit Nilai' : 'Input Nilai' }}
                                            </a>
                                        @elseif($mhs->status == 'pending')
                                            <span class="text-gray-400 text-xs italic">Menunggu Persetujuan</span>
                                        @else
                                            <span class="text-red-400 text-xs italic">Ditolak</span>
                                        @endif
                                        
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-8 text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="far fa-folder-open text-2xl mb-2 text-gray-400"></i>
                                        <span>Belum ada mahasiswa yang ditugaskan ke Anda.</span>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>