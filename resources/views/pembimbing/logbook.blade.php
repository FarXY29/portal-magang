<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <i class="fas fa-search-plus text-purple-600"></i>
                {{ __('Monitoring Logbook') }}
            </h2>
            <div class="text-sm text-gray-500 font-medium bg-white px-4 py-1.5 rounded-full shadow-sm border border-gray-100">
                Mahasiswa: <span class="font-bold text-purple-700">{{ $app->user->name }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex justify-between items-center mb-6 print:hidden">
                <a href="{{ route('pembimbing.dashboard') }}" class="group flex items-center text-sm font-bold text-gray-500 hover:text-purple-600 transition">
                    <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center mr-2 group-hover:border-purple-500 shadow-sm">
                        <i class="fas fa-arrow-left text-xs"></i>
                    </div>
                    Kembali ke Dashboard
                </a>
            </div>

            @if($logs->isEmpty())
                <div class="flex flex-col items-center justify-center py-16 bg-white rounded-2xl shadow-sm border border-gray-100 text-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4 text-gray-300">
                        <i class="fas fa-book-open text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-700">Belum Ada Data</h3>
                    <p class="text-sm text-gray-500 mt-1">Mahasiswa ini belum mengisi logbook kegiatan magang.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start" 
                     x-data="{ activeTab: {{ $logs->first()->id }} }">
                    
                    <div class="md:col-span-4 col-span-1">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-8">
                            <div class="p-4 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                                <h3 class="font-bold text-gray-700 text-sm">Riwayat Aktivitas</h3>
                                <span class="text-[10px] font-bold bg-purple-100 text-purple-700 px-2 py-0.5 rounded-full">{{ $logs->count() }} Data</span>
                            </div>
                            
                            <div class="max-h-[75vh] overflow-y-auto custom-scrollbar">
                                <ul class="divide-y divide-gray-50">
                                    @foreach($logs as $log)
                                    <li>
                                        <button 
                                            @click="activeTab = {{ $log->id }}"
                                            :class="{ 'bg-purple-50 border-l-4 border-purple-500': activeTab === {{ $log->id }}, 'hover:bg-gray-50 border-l-4 border-transparent': activeTab !== {{ $log->id }} }"
                                            class="w-full text-left px-4 py-3 transition duration-150 ease-in-out focus:outline-none group">
                                            
                                            <div class="flex justify-between items-center mb-1">
                                                <span class="text-sm font-bold text-gray-800" 
                                                      :class="{ 'text-purple-700': activeTab === {{ $log->id }} }">
                                                    {{ \Carbon\Carbon::parse($log->tanggal)->format('d M Y') }}
                                                </span>
                                                
                                                @if($log->status_validasi == 'disetujui')
                                                    <i class="fas fa-check-circle text-green-500 text-xs" title="Disetujui"></i>
                                                @elseif($log->status_validasi == 'revisi')
                                                    <i class="fas fa-exclamation-circle text-red-500 text-xs" title="Revisi"></i>
                                                @else
                                                    <i class="fas fa-clock text-yellow-500 text-xs" title="Pending"></i>
                                                @endif
                                            </div>
                                            
                                            <p class="text-xs text-gray-500 truncate group-hover:text-gray-700">
                                                {{ Str::limit($log->kegiatan, 45) }}
                                            </p>
                                        </button>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-8 col-span-1">
                        @foreach($logs as $log)
                        <div x-show="activeTab === {{ $log->id }}" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-x-2"
                             x-transition:enter-end="opacity-100 translate-x-0"
                             style="display: none;">
                            
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                                
                                <div class="p-6 border-b border-gray-50 flex justify-between items-start bg-white">
                                    <div>
                                        <h3 class="text-xl font-extrabold text-gray-800">Detail Kegiatan</h3>
                                        <p class="text-sm text-gray-500 mt-1 flex items-center">
                                            <i class="far fa-calendar-alt mr-2 text-purple-500"></i> 
                                            {{ \Carbon\Carbon::parse($log->tanggal)->translatedFormat('l, d F Y') }}
                                        </p>
                                    </div>
                                    
                                    @php
                                        $badges = [
                                            'disetujui' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'icon' => 'fa-check-circle', 'label' => 'Valid'],
                                            'revisi'    => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'icon' => 'fa-undo', 'label' => 'Revisi'],
                                            'pending'   => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => 'fa-clock', 'label' => 'Pending']
                                        ];
                                        $s = $badges[$log->status_validasi] ?? $badges['pending'];
                                    @endphp
                                    <span class="px-4 py-1.5 rounded-full text-xs font-bold uppercase border border-transparent {{ $s['bg'] }} {{ $s['text'] }} flex items-center gap-2">
                                        <i class="fas {{ $s['icon'] }}"></i> {{ $s['label'] }}
                                    </span>
                                </div>

                                <div class="p-6">
                                    <div class="flex flex-col lg:flex-row gap-8">
                                        
                                        <div class="w-full lg:w-1/3 flex-shrink-0">
                                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Dokumentasi</h4>
                                            @if($log->bukti_foto_path)
                                                <div class="relative group rounded-xl overflow-hidden shadow-sm border border-gray-200 cursor-zoom-in" onclick="window.open('{{ Storage::url($log->bukti_foto_path) }}')">
                                                    <img src="{{ Storage::url($log->bukti_foto_path) }}" class="w-full h-48 object-cover transition transform group-hover:scale-105 duration-500">
                                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition flex items-center justify-center">
                                                        <i class="fas fa-search-plus text-white opacity-0 group-hover:opacity-100 transition"></i>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="w-full h-40 bg-gray-50 rounded-xl flex flex-col items-center justify-center text-gray-400 text-xs border-2 border-dashed border-gray-200">
                                                    <i class="far fa-image text-2xl mb-1"></i>
                                                    <span>Tidak ada foto</span>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="w-full lg:w-2/3">
                                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Deskripsi Pekerjaan</h4>
                                            <div class="p-5 bg-gray-50 rounded-xl border border-gray-100 text-gray-700 text-sm leading-relaxed whitespace-pre-line min-h-[10rem]">
                                                {{ $log->kegiatan }}
                                            </div>
                                        </div>
                                    </div>

                                    @if($log->komentar_mentor)
                                        <div class="mt-8 pt-6 border-t border-gray-100">
                                            <div class="p-4 bg-indigo-50 rounded-xl border border-indigo-100 flex gap-3 items-start">
                                                <div class="p-2 bg-white rounded-full shadow-sm text-indigo-500">
                                                    <i class="fas fa-comment-dots"></i>
                                                </div>
                                                <div>
                                                    <span class="block text-xs font-bold text-indigo-700 uppercase mb-1">Catatan Mentor Lapangan:</span>
                                                    <p class="text-sm text-indigo-900 italic">"{{ $log->komentar_mentor }}"</p>
                                                </div>
                                            </div>
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

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
    </style>
</x-app-layout>