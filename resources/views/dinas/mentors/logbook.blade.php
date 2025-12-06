<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Validasi Logbook: {{ $app->user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Navigasi -->
            <a href="{{ route('mentor.dashboard') }}" class="mb-4 inline-flex items-center text-gray-600 hover:text-gray-900 font-medium">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Dashboard
            </a>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    <i class="fas fa-check mr-1"></i> {{ session('success') }}
                </div>
            @endif

            <div class="space-y-6">
                @forelse($logs as $log)
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 {{ $log->status_validasi == 'disetujui' ? 'border-green-500' : ($log->status_validasi == 'revisi' ? 'border-red-500' : 'border-yellow-400') }}">
                    <div class="flex flex-col md:flex-row gap-6">
                        
                        <!-- Bukti Foto & Tanggal -->
                        <div class="w-full md:w-1/4">
                            <div class="text-sm text-gray-500 font-bold mb-2">
                                {{ \Carbon\Carbon::parse($log->tanggal)->format('l, d F Y') }}
                            </div>
                            
                            @if($log->bukti_foto_path)
                                <img src="{{ Storage::url($log->bukti_foto_path) }}" class="w-full h-32 object-cover rounded-md border border-gray-200 cursor-zoom-in hover:opacity-90" onclick="window.open(this.src)" title="Klik untuk perbesar">
                            @else
                                <div class="w-full h-32 bg-gray-100 rounded-md flex items-center justify-center text-gray-400 text-xs italic border border-gray-200">
                                    Tanpa Foto
                                </div>
                            @endif
                        </div>

                        <!-- Deskripsi Kegiatan -->
                        <div class="w-full md:w-2/4">
                            <h4 class="font-bold text-gray-800 mb-1">Aktivitas:</h4>
                            <p class="text-gray-700 text-sm leading-relaxed whitespace-pre-line">{{ $log->kegiatan }}</p>
                            
                            <!-- Tampilkan Komentar Jika Ada -->
                            @if($log->komentar_mentor)
                                <div class="mt-3 p-3 bg-gray-50 rounded border border-gray-200 text-sm">
                                    <span class="font-bold text-gray-600 text-xs uppercase">Catatan Anda:</span>
                                    <p class="text-gray-800">{{ $log->komentar_mentor }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Form Validasi -->
                        <div class="w-full md:w-1/4 flex flex-col justify-center border-l pl-0 md:pl-6 border-gray-100">
                            <div class="mb-3 text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                                    {{ $log->status_validasi == 'disetujui' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $log->status_validasi == 'revisi' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $log->status_validasi == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                    {{ $log->status_validasi }}
                                </span>
                            </div>

                            @if($log->status_validasi == 'pending' || $log->status_validasi == 'revisi')
                                <form action="{{ route('mentor.logbook.validasi', $log->id) }}" method="POST" class="space-y-2">
                                    @csrf
                                    <input type="text" name="komentar" placeholder="Komentar / Catatan..." class="w-full text-xs border-gray-300 rounded shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                                    
                                    <div class="flex gap-2">
                                        <button type="submit" name="status" value="disetujui" class="flex-1 bg-green-600 text-white py-1.5 rounded text-xs font-bold hover:bg-green-700 transition">
                                            <i class="fas fa-check"></i> OK
                                        </button>
                                        <button type="submit" name="status" value="revisi" class="flex-1 bg-red-600 text-white py-1.5 rounded text-xs font-bold hover:bg-red-700 transition">
                                            <i class="fas fa-undo"></i> Revisi
                                        </button>
                                    </div>
                                </form>
                            @else
                                <p class="text-center text-xs text-gray-400 mt-2 italic">Logbook telah divalidasi.</p>
                            @endif
                        </div>

                    </div>
                </div>
                @empty
                <div class="text-center py-12 bg-white rounded-lg border border-gray-200">
                    <i class="fas fa-book text-gray-300 text-4xl mb-3"></i>
                    <p class="text-gray-500">Mahasiswa ini belum mengisi logbook kegiatan.</p>
                </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>