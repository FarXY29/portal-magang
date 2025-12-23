<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Validasi Logbook: {{ $app->user->name }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Navigasi & Alert -->
            <div class="flex justify-between mb-6 print:hidden">
                <a href="{{ route('mentor.dashboard') }}" class="text-gray-600 hover:text-gray-900">&larr; Kembali</a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6 shadow-sm text-sm">
                    <i class="fas fa-check mr-1"></i> {{ session('success') }}
                </div>
            @endif

            @if($logs->isEmpty())
                <div class="text-center py-12 bg-white rounded-lg border border-gray-200 shadow-sm">
                    <i class="fas fa-book text-gray-300 text-4xl mb-3"></i>
                    <p class="text-gray-500 text-sm">Mahasiswa ini belum mengisi logbook kegiatan.</p>
                </div>
            @else
                <!-- Layout Grid: Pasti terbagi kolom di layar sedang/besar -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start" x-data="{ activeTab: {{ $logs->first()->id }} }">
                    
                    <!-- KOLOM KIRI: MENU LIST (Lebar 4 dari 12) -->
                    <div class="md:col-span-4 col-span-1">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden sticky top-6">
                            <div class="p-4 bg-gray-50 border-b border-gray-200">
                                <h3 class="font-bold text-gray-700 text-sm">Riwayat Logbook</h3>
                            </div>
                            <div class="max-h-[70vh] overflow-y-auto">
                                <ul class="divide-y divide-gray-100">
                                    @foreach($logs as $log)
                                    <li>
                                        <button 
                                            @click="activeTab = {{ $log->id }}"
                                            :class="{ 'bg-indigo-50 border-l-4 border-indigo-500': activeTab === {{ $log->id }}, 'hover:bg-gray-50 border-l-4 border-transparent': activeTab !== {{ $log->id }} }"
                                            class="w-full text-left px-4 py-3 transition duration-150 ease-in-out focus:outline-none">
                                            <div class="flex justify-between items-center mb-1">
                                                <span class="text-sm font-bold text-gray-700">
                                                    {{ \Carbon\Carbon::parse($log->tanggal)->format('d M Y') }}
                                                </span>
                                                <!-- Status Badge -->
                                                @if($log->status_validasi == 'disetujui')
                                                    <span class="text-[10px] bg-green-100 text-green-800 px-2 py-0.5 rounded-full font-bold">OK</span>
                                                @elseif($log->status_validasi == 'revisi')
                                                    <span class="text-[10px] bg-red-100 text-red-800 px-2 py-0.5 rounded-full font-bold">REV</span>
                                                @else
                                                    <span class="text-[10px] bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded-full font-bold">PENDING</span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-500 truncate">
                                                {{ Str::limit($log->kegiatan, 40) }}
                                            </p>
                                        </button>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- KOLOM KANAN: HALAMAN DETAIL (Lebar 8 dari 12) -->
                    <div class="md:col-span-8 col-span-1">
                        @foreach($logs as $log)
                        <div x-show="activeTab === {{ $log->id }}" 
                             x-cloak
                             class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 min-h-[300px]">
                            
                            <!-- Header Detail -->
                            <div class="flex justify-between items-start border-b border-gray-100 pb-4 mb-4">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800">Detail Kegiatan</h3>
                                    <p class="text-sm text-gray-500 mt-1">
                                        <i class="far fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($log->tanggal)->format('l, d F Y') }}
                                    </p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                                    {{ $log->status_validasi == 'disetujui' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $log->status_validasi == 'revisi' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $log->status_validasi == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                    {{ ucfirst($log->status_validasi) }}
                                </span>
                            </div>

                            <div class="flex flex-col gap-6">
                                <!-- Bukti Foto (Kiri) & Deskripsi (Kanan) -->
                                <div class="flex flex-col md:flex-row gap-6">
                                    <!-- Foto -->
                                    <div class="w-full md:w-1/3 flex-shrink-0">
                                        <h4 class="text-xs font-bold text-gray-400 uppercase mb-2">Dokumentasi</h4>
                                        @if($log->bukti_foto_path)
                                            <div class="relative group">
                                                <img src="{{ Storage::url($log->bukti_foto_path) }}" class="max-w-[250px] max-h-[200px] rounded-lg border border-gray-200 shadow-sm hover:opacity-90 cursor-pointer object-cover" onclick="window.open(this.src)" title="Klik untuk memperbesar">
                                            </div>
                                        @else
                                            <div class="w-full h-32 bg-gray-50 rounded-lg flex items-center justify-center text-gray-400 text-xs italic border border-dashed border-gray-300">
                                                Tanpa Foto
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Deskripsi -->
                                    <div class="w-full md:w-2/3">
                                        <h4 class="text-xs font-bold text-gray-400 uppercase mb-2">Deskripsi Aktivitas</h4>
                                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-100 text-sm text-gray-800 leading-relaxed whitespace-pre-line h-full">
                                            {{ $log->kegiatan }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Form Validasi -->
                                <div class="mt-4 pt-4 border-t border-gray-100">
                                    @if($log->komentar_mentor)
                                        <div class="mb-4 p-3 bg-indigo-50 rounded border border-indigo-100 text-sm">
                                            <span class="font-bold text-indigo-700 text-xs uppercase block mb-1">Catatan Anda:</span>
                                            <p class="text-gray-800">{{ $log->komentar_mentor }}</p>
                                        </div>
                                    @endif

                                    @if($log->status_validasi == 'pending' || $log->status_validasi == 'revisi')
                                        <form action="{{ route('mentor.logbook.validasi', $log->id) }}" method="POST" class="bg-gray-50 p-4 rounded-lg">
                                            @csrf
                                            <label class="text-sm font-bold text-gray-700 mb-2 block">Validasi & Beri Nilai:</label>
                                            <div class="flex gap-2 items-start">
                                                <input type="text" name="komentar" placeholder="Tulis catatan atau revisi..." class="flex-grow text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 h-10">
                                                
                                                <button type="submit" name="status" value="disetujui" class="bg-green-600 text-white px-4 py-2 rounded-md text-sm font-bold hover:bg-green-700 transition shadow-sm h-10 flex items-center gap-1">
                                                    <i class="fas fa-check"></i> Terima
                                                </button>
                                                <button type="submit" name="status" value="revisi" class="bg-red-600 text-white px-4 py-2 rounded-md text-sm font-bold hover:bg-red-700 transition shadow-sm h-10 flex items-center gap-1">
                                                    <i class="fas fa-undo"></i> Revisi
                                                </button>
                                            </div>
                                        </form>
                                    @else
                                        <div class="text-center text-sm text-gray-400 py-2">
                                            <i class="fas fa-lock mr-1"></i> Logbook ini sudah divalidasi.
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                        @endforeach
                    </div>

                </div>
            @endif

        </div>
    </div>
</x-app-layout>