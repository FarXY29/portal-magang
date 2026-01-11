<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <i class="fas fa-chalkboard-teacher text-purple-600"></i>
                {{ __('Dashboard Pembimbing') }}
            </h2>
            <div class="text-sm text-gray-500 font-medium bg-white px-4 py-1.5 rounded-full shadow-sm border border-gray-100">
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <div class="lg:col-span-2 bg-gradient-to-r from-purple-600 to-indigo-700 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white opacity-10 rounded-full blur-2xl"></div>
                    <div class="relative z-10 flex flex-col justify-between h-full">
                        <div>
                            <p class="text-purple-200 text-xs font-bold uppercase tracking-widest mb-1">Instansi Pendidikan</p>
                            <h3 class="text-2xl font-bold">
                                @if(isset($instansi))
                                    {{ $instansi }}
                                @else
                                    <span class="opacity-50 italic">Belum diset</span>
                                @endif
                            </h3>
                        </div>
                        <div class="mt-6 flex items-center gap-2 text-sm text-purple-100">
                            <i class="fas fa-info-circle"></i>
                            <span>Area monitoring aktivitas dan penilaian peserta magang.</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-purple-100 flex flex-col justify-center items-center text-center">
                    <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-full flex items-center justify-center mb-3 text-xl">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h4 class="text-3xl font-black text-gray-800">{{ count($students) }}</h4>
                    <p class="text-xs text-gray-500 font-bold uppercase">Total Peserta Bimbingan</p>
                </div>
            </div>

            @if(isset($warning))
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-xl shadow-sm flex items-start gap-3">
                    <i class="fas fa-exclamation-triangle text-yellow-500 mt-0.5"></i>
                    <div>
                        <h3 class="text-sm font-bold text-yellow-800">Lengkapi Profil Anda</h3>
                        <p class="text-sm text-yellow-700 mt-1">
                            {{ $warning }} 
                            <a href="{{ route('profile.edit') }}" class="underline font-bold hover:text-yellow-900 ml-1">Edit Profil</a>
                        </p>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-list-ul text-purple-500"></i> Daftar Peserta Didik
                        </h3>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Profil Peserta</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Lokasi & Posisi</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Periode Magang</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50">
                            @forelse($students as $mhs)
                            <tr class="hover:bg-purple-50/30 transition group">
                                
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-purple-100 to-indigo-200 flex items-center justify-center text-purple-700 font-bold text-sm border border-purple-200">
                                                {{ strtoupper(substr($mhs->user->name, 0, 1)) }}
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-900">{{ $mhs->user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $mhs->user->email }}</div>
                                            <div class="text-[10px] text-gray-400 mt-0.5">NIK: {{ $mhs->user->nik ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-700 flex items-center gap-1">
                                            <i class="far fa-building text-gray-400"></i> {{ $mhs->position->skpd->nama_dinas }}
                                        </span>
                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded w-fit mt-1 border border-gray-200">
                                            {{ $mhs->position->judul_posisi }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($mhs->tanggal_mulai && $mhs->tanggal_selesai)
                                        <div class="flex flex-col">
                                            <span class="text-xs font-medium text-gray-600 flex items-center gap-1">
                                                <i class="far fa-calendar-alt"></i>
                                                {{ \Carbon\Carbon::parse($mhs->tanggal_mulai)->format('d M') }} - {{ \Carbon\Carbon::parse($mhs->tanggal_selesai)->format('d M Y') }}
                                            </span>
                                            <span class="text-[10px] text-purple-600 font-bold mt-1">
                                                Durasi: {{ \Carbon\Carbon::parse($mhs->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($mhs->tanggal_selesai)) }} Hari
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Jadwal belum diatur</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if($mhs->status == 'diterima')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5 animate-pulse"></span> AKTIF
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-700 border border-blue-200">
                                            <i class="fas fa-check-circle mr-1 text-[10px]"></i> SELESAI
                                        </span>
                                        @if($mhs->nilai_angka)
                                            <div class="mt-1 text-[10px] font-bold text-blue-600">Nilai: {{ $mhs->nilai_angka }}</div>
                                        @endif
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <a href="{{ route('pembimbing.logbook', $mhs->id) }}" class="inline-flex items-center px-3 py-1.5 bg-white border border-purple-200 rounded-lg text-purple-700 text-xs font-bold hover:bg-purple-50 transition shadow-sm">
                                        <i class="fas fa-eye mr-1.5"></i> Monitoring
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                            <i class="fas fa-user-graduate text-3xl text-gray-300"></i>
                                        </div>
                                        <p class="font-medium text-gray-600">Belum ada peserta bimbingan</p>
                                        <p class="text-xs mt-1">Data mahasiswa yang Anda bimbing akan muncul di sini.</p>
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