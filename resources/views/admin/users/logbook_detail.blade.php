<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Logbook: {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-center mb-6">
                <a href="{{ route('admin.users.logbooks') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 font-medium transition text-sm">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
                </a>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-bold uppercase">
                    {{ $app->position->skpd->nama_dinas }}
                </span>
            </div>

            <div class="space-y-4">
                @forelse($logs as $log)
                <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="flex flex-col sm:flex-row gap-4">
                        
                        <!-- Tanggal & Foto -->
                        <div class="sm:w-32 flex-shrink-0">
                            <div class="text-xs font-bold text-gray-500 mb-2">
                                {{ \Carbon\Carbon::parse($log->tanggal)->format('d M Y') }}
                            </div>
                            @if($log->bukti_foto_path)
                                <img src="{{ Storage::url($log->bukti_foto_path) }}" class="w-32 h-24 object-cover rounded border border-gray-200 cursor-pointer hover:opacity-80" onclick="window.open(this.src)">
                            @else
                                <div class="w-32 h-24 bg-gray-50 rounded flex items-center justify-center text-gray-400 text-xs italic border border-gray-200">
                                    No Foto
                                </div>
                            @endif
                        </div>

                        <!-- Kegiatan -->
                        <div class="flex-grow">
                            <div class="flex justify-between items-start">
                                <h4 class="text-sm font-bold text-gray-800 mb-1">Aktivitas</h4>
                                <!-- Status Badge -->
                                <span class="text-[10px] px-2 py-0.5 rounded-full font-bold uppercase
                                    {{ $log->status_validasi == 'disetujui' ? 'bg-green-100 text-green-800' : 
                                      ($log->status_validasi == 'revisi' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ $log->status_validasi }}
                                </span>
                            </div>
                            
                            <p class="text-sm text-gray-700 leading-relaxed mb-2">{{ $log->kegiatan }}</p>
                            
                            <!-- Catatan Mentor -->
                            @if($log->komentar_mentor)
                                <div class="bg-gray-50 p-2 rounded border-l-2 border-indigo-400 text-xs mt-2">
                                    <span class="font-bold text-indigo-600">Komentar Mentor:</span>
                                    <p class="text-gray-600 mt-0.5">{{ $log->komentar_mentor }}</p>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
                @empty
                <div class="text-center py-12 bg-white rounded-lg border border-gray-200">
                    <i class="fas fa-book-open text-gray-300 text-4xl mb-3"></i>
                    <p class="text-gray-500 text-sm">Peserta ini belum mengisi logbook.</p>
                </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>