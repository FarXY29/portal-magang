<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Pembimbing Akademik
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Info Instansi -->
            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-purple-500 mb-6">
                <h3 class="text-lg font-bold text-gray-800">
                    @if($instansi)
                        <i class="fas fa-university mr-2 text-purple-600"></i> {{ $instansi }}
                    @else
                        <span class="text-red-500">Instansi Belum Diset</span>
                    @endif
                </h3>
                <p class="text-sm text-gray-500 mt-1">
                    Memantau mahasiswa magang yang berasal dari sekolah/kampus yang sama.
                </p>
                
                @if(isset($warning))
                    <div class="mt-4 bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded text-sm">
                        <i class="fas fa-exclamation-triangle mr-1"></i> {{ $warning }} 
                        <a href="{{ route('profile.edit') }}" class="underline font-bold ml-1">Edit Profil</a>
                    </div>
                @endif
            </div>

            <!-- Tabel Mahasiswa -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h4 class="font-bold text-gray-700">Daftar Mahasiswa Bimbingan</h4>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-white">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi Magang</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monitoring</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($students as $mhs)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">{{ $mhs->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $mhs->user->email }}</div>
                                    <div class="text-xs text-gray-400">NIK: {{ $mhs->user->nik ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 font-medium">{{ $mhs->position->skpd->nama_dinas }}</div>
                                    <div class="text-xs text-gray-500">{{ $mhs->position->judul_posisi }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($mhs->status == 'diterima')
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Sedang Magang
                                        </span>
                                    @elseif($mhs->status == 'selesai')
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Selesai / Lulus
                                        </span>
                                    @endif
                                    
                                    @if($mhs->nilai_angka)
                                        <div class="mt-1 text-xs font-bold text-purple-600">
                                            Nilai: {{ $mhs->nilai_angka }} ({{ $mhs->predikat }})
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('pembimbing.logbook', $mhs->id) }}" class="text-purple-600 hover:text-purple-900 bg-purple-50 px-3 py-1.5 rounded-md transition hover:bg-purple-100">
                                        <i class="fas fa-eye mr-1"></i> Lihat Logbook
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                    <i class="fas fa-users-slash text-3xl mb-2 text-gray-300"></i>
                                    <p>Tidak ditemukan mahasiswa aktif dari <strong>{{ $instansi }}</strong>.</p>
                                    <p class="text-xs mt-1">Pastikan mahasiswa Anda sudah menuliskan nama instansi yang sama persis di profil mereka.</p>
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