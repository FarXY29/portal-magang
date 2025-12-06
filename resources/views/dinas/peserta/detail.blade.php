<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Logbook: {{ $app->user->name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Info Peserta -->
            <div class="bg-white p-6 rounded-lg shadow-sm mb-6 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold">{{ $app->user->name }}</h3>
                    <p class="text-gray-600">{{ $app->position->judul_posisi }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Total Logbook</p>
                    <p class="text-2xl font-bold text-teal-600">{{ $logs->count() }}</p>
                </div>
            </div>

            <!-- List Logbook -->
            <div class="space-y-4">
                @forelse($logs as $log)
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                    <div class="flex flex-col md:flex-row gap-6">
                        
                        <!-- Kolom Kiri: Info & Bukti -->
                        <div class="w-full md:w-1/4">
                            <div class="text-sm font-bold text-gray-500 mb-1">Tanggal</div>
                            <div class="text-lg font-bold text-gray-800 mb-4">{{ \Carbon\Carbon::parse($log->tanggal)->format('d M Y') }}</div>
                            
                            @if($log->bukti_foto_path)
                                <img src="{{ Storage::url($log->bukti_foto_path) }}" class="rounded-lg shadow-sm w-full h-32 object-cover cursor-pointer hover:opacity-75 transition" onclick="window.open(this.src)">
                                <p class="text-xs text-gray-400 mt-1 text-center">Klik untuk memperbesar</p>
                            @else
                                <div class="w-full h-32 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 text-xs">
                                    Tidak ada foto
                                </div>
                            @endif
                        </div>

                        <!-- Kolom Tengah: Kegiatan -->
                        <div class="w-full md:w-2/4">
                            <div class="text-sm font-bold text-gray-500 mb-1">Deskripsi Kegiatan</div>
                            <p class="text-gray-700 whitespace-pre-line">{{ $log->kegiatan }}</p>
                            
                            @if($log->komentar_mentor)
                                <div class="mt-4 p-3 bg-yellow-50 rounded border border-yellow-100">
                                    <p class="text-xs font-bold text-yellow-700">Catatan Anda:</p>
                                    <p class="text-sm text-yellow-800">{{ $log->komentar_mentor }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Kolom Kanan: Aksi Validasi -->
                        <div class="w-full md:w-1/4 flex flex-col justify-center border-l pl-0 md:pl-6 border-gray-100">
                            <div class="mb-2 text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase
                                    {{ $log->status_validasi == 'disetujui' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $log->status_validasi == 'revisi' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $log->status_validasi == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                    {{ $log->status_validasi }}
                                </span>
                            </div>

                            @if($log->status_validasi == 'pending')
                                <form action="{{ route('dinas.logbook.validasi', $log->id) }}" method="POST" class="space-y-2">
                                    @csrf
                                    <input type="text" name="komentar" placeholder="Komentar (Opsional)" class="w-full text-xs border-gray-300 rounded">
                                    
                                    <button type="submit" name="status" value="disetujui" class="w-full bg-green-600 text-white py-2 rounded text-sm hover:bg-green-700">
                                        <i class="fas fa-check"></i> Setujui
                                    </button>
                                    <button type="submit" name="status" value="revisi" class="w-full bg-red-600 text-white py-2 rounded text-sm hover:bg-red-700">
                                        <i class="fas fa-times"></i> Revisi
                                    </button>
                                </form>
                            @else
                                <p class="text-center text-xs text-gray-400 mt-2">Sudah divalidasi</p>
                            @endif
                        </div>

                    </div>
                </div>
                @empty
                <div class="text-center py-10 text-gray-500 bg-white rounded-lg">
                    Peserta ini belum mengisi logbook sama sekali.
                </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>