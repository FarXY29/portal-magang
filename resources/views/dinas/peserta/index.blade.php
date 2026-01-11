<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <i class="fas fa-users-cog text-teal-600"></i>
                {{ __('Kelola Peserta & Mentor') }}
            </h2>
            <div class="text-sm text-gray-500 font-medium bg-white px-4 py-1.5 rounded-full shadow-sm border border-gray-100">
                Peserta Aktif: <span class="font-bold text-teal-600">{{ $interns->where('status', 'diterima')->count() }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex justify-between items-center mb-6 print:hidden">
                <a href="{{ route('dinas.dashboard') }}" class="group flex items-center text-sm font-bold text-gray-500 hover:text-teal-600 transition">
                    <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                        <i class="fas fa-arrow-left text-xs"></i>
                    </div>
                    Kembali ke Dashboard
                </a>
            </div>

            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" class="flex items-center p-4 mb-4 text-green-800 rounded-xl bg-green-50 border border-green-100 shadow-sm relative">
                    <i class="fas fa-check-circle flex-shrink-0 w-5 h-5 mr-3 text-green-600"></i>
                    <div class="text-sm font-bold">{{ session('success') }}</div>
                    <button @click="show = false" class="ml-auto text-green-500 hover:text-green-700"><i class="fas fa-times"></i></button>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Profil Peserta</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-1/3">Assign Mentor</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Periode Magang</th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50">
                            @forelse($interns as $intern)
                            <tr class="hover:bg-gray-50 transition duration-150 {{ $intern->status == 'selesai' ? 'bg-gray-50/50' : '' }}">
                                
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if($intern->status == 'selesai')
                                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold border border-blue-200">
                                                    <i class="fas fa-graduation-cap"></i>
                                                </div>
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-teal-50 flex items-center justify-center text-teal-600 font-bold border border-teal-100">
                                                    {{ strtoupper(substr($intern->user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-sm font-bold text-gray-900 truncate {{ $intern->status == 'selesai' ? 'text-gray-500 line-through' : '' }}">
                                                {{ $intern->user->name }}
                                            </div>
                                            <div class="text-xs text-gray-500">{{ $intern->user->email }}</div>
                                            
                                            @if($intern->status == 'selesai')
                                                <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                                    LULUS / ALUMNI
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    @if($intern->status == 'diterima')
                                        <form action="{{ route('dinas.peserta.assign', $intern->id) }}" method="POST" class="flex flex-col gap-2">
                                            @csrf
                                            <div class="flex items-center gap-2">
                                                <select name="mentor_id" class="w-full text-xs rounded-lg border-gray-300 focus:border-teal-500 focus:ring-teal-500 cursor-pointer py-1.5 pl-2 pr-8 shadow-sm">
                                                    <option value="">-- Pilih Mentor --</option>
                                                    @foreach($mentors as $mentor)
                                                        <option value="{{ $mentor->id }}" {{ $intern->mentor_id == $mentor->id ? 'selected' : '' }}>
                                                            {{ $mentor->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="p-1.5 bg-teal-600 text-white rounded-md hover:bg-teal-700 transition shadow-sm" title="Simpan Mentor">
                                                    <i class="fas fa-save text-xs"></i>
                                                </button>
                                            </div>
                                            @if($intern->mentor_id)
                                                <div class="text-[10px] text-green-600 font-medium flex items-center">
                                                    <i class="fas fa-check-circle mr-1"></i> Terhubung: {{ $intern->mentor->name }}
                                                </div>
                                            @else
                                                <div class="text-[10px] text-red-500 font-medium flex items-center animate-pulse">
                                                    <i class="fas fa-exclamation-circle mr-1"></i> Wajib pilih mentor!
                                                </div>
                                            @endif
                                        </form>
                                    @else
                                        <div class="text-xs text-gray-500 italic flex items-center">
                                            <i class="fas fa-user-tie mr-1.5 text-gray-400"></i>
                                            {{ $intern->mentor->name ?? 'Tidak ada mentor' }}
                                        </div>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($intern->tanggal_mulai)
                                        <div class="flex flex-col">
                                            <span class="text-xs font-bold text-gray-700 flex items-center gap-1.5">
                                                <i class="far fa-calendar-alt text-gray-400"></i>
                                                {{ \Carbon\Carbon::parse($intern->tanggal_mulai)->format('d M y') }} 
                                                <i class="fas fa-arrow-right text-gray-300 text-[10px]"></i> 
                                                {{ \Carbon\Carbon::parse($intern->tanggal_selesai)->format('d M y') }}
                                            </span>
                                            
                                            @php
                                                $selesai = \Carbon\Carbon::parse($intern->tanggal_selesai);
                                                $sisa = now()->diffInDays($selesai, false);
                                            @endphp

                                            @if($intern->status != 'selesai')
                                                @if($sisa > 0)
                                                    <span class="text-[10px] text-teal-600 font-bold mt-1 bg-teal-50 w-fit px-1.5 py-0.5 rounded">
                                                        <i class="fas fa-hourglass-half mr-1"></i> Sisa {{ ceil($sisa) }} hari
                                                    </span>
                                                @else
                                                    <span class="text-[10px] text-red-500 font-bold mt-1 bg-red-50 w-fit px-1.5 py-0.5 rounded">
                                                        <i class="fas fa-flag-checkered mr-1"></i> Waktu Habis
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Tanggal belum diatur</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end items-center gap-2">
                                        <a href="{{ route('dinas.peserta.logbook', $intern->id) }}" class="p-2 bg-white border border-gray-200 rounded-lg text-gray-600 hover:text-teal-600 hover:border-teal-300 transition shadow-sm" title="Pantau Logbook">
                                            <i class="fas fa-book-open"></i>
                                        </a>

                                        @if($intern->status == 'diterima')
                                            <form action="{{ route('dinas.peserta.selesai', $intern->id) }}" method="POST" onsubmit="return confirm('PERINGATAN!\n\nMeluluskan peserta ini akan:\n1. Menerbitkan Sertifikat Otomatis\n2. Menutup akses edit logbook peserta\n\nLanjutkan?')">
                                                @csrf
                                                <button type="submit" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition shadow-sm font-bold border border-blue-100" title="Luluskan Peserta">
                                                    <i class="fas fa-graduation-cap"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                            <i class="fas fa-users-slash text-3xl text-gray-300"></i>
                                        </div>
                                        <p class="font-bold text-gray-600">Belum ada peserta aktif</p>
                                        <p class="text-xs mt-1">Terima pelamar di menu "Pelamar Masuk" terlebih dahulu.</p>
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