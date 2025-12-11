<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Daftar Pelamar Magang</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between mb-6 print:hidden">
                    <a href="{{ route('dinas.dashboard') }}" class="text-gray-600 hover:text-gray-900">&larr; Kembali</a>
                </div>
            <!-- Pesan Sukses/Error -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 shadow-sm text-sm">
                    <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 shadow-sm text-sm">
                    <i class="fas fa-exclamation-triangle mr-1"></i> {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Peserta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posisi Dilamar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($applicants as $app)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-600 font-bold mr-3 text-sm">
                                        {{ substr($app->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $app->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $app->user->email }}</div>
                                        <div class="text-xs text-gray-500">{{ $app->user->phone ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 font-semibold">{{ $app->position->judul_posisi }}</div>
                                <div class="text-xs text-gray-500 mb-1">Tgl Lamar: {{ $app->created_at->format('d M Y') }}</div>
                                
                                <!-- INFO KUOTA -->
                                @if($app->position->kuota > 0)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-blue-100 text-blue-800">
                                        Sisa Kuota: {{ $app->position->kuota }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-red-100 text-red-800">
                                        Kuota Penuh (0)
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $app->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $app->status == 'diterima' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $app->status == 'ditolak' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $app->status == 'selesai' ? 'bg-blue-100 text-blue-800' : '' }}">
                                    {{ ucfirst($app->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                @if($app->status == 'pending')
                                    <div class="flex space-x-2 mb-3">
                                        <!-- Tombol Terima (Hanya muncul jika kuota > 0) -->
                                        @if($app->position->kuota > 0)
                                            <form action="{{ route('dinas.pelamar.terima', $app->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menerima peserta ini? Kuota akan berkurang 1.')">
                                                @csrf
                                                <button type="submit" class="text-white bg-green-500 hover:bg-green-600 px-3 py-1 rounded shadow-sm text-xs transition font-bold">
                                                    <i class="fas fa-check mr-1"></i> Terima
                                                </button>
                                            </form>
                                        @else
                                            <button disabled class="text-white bg-gray-300 cursor-not-allowed px-3 py-1 rounded shadow-sm text-xs font-bold">
                                                Penuh
                                            </button>
                                        @endif
                                        
                                        <!-- Tombol Tolak -->
                                        <form action="{{ route('dinas.pelamar.tolak', $app->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menolak peserta ini?')">
                                            @csrf
                                            <button type="submit" class="text-white bg-red-500 hover:bg-red-600 px-3 py-1 rounded shadow-sm text-xs transition font-bold">
                                                <i class="fas fa-times mr-1"></i> Tolak
                                            </button>
                                        </form>
                                    </div>
                                    
                                    <!-- Link Download Surat Pengantar -->
                                    <div class="flex flex-col space-y-1">
                                        @if($app->surat_pengantar_path)
                                            <a href="{{ Storage::url($app->surat_pengantar_path) }}" target="_blank" class="inline-flex items-center text-purple-600 hover:text-purple-800 hover:underline text-xs font-semibold transition">
                                                <i class="fas fa-file-alt mr-1.5"></i> Lihat Surat Pengantar
                                            </a>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Tidak ada berkas</span>
                                        @endif
                                    </div>

                                @elseif($app->status == 'diterima')
                                    <span class="text-gray-500 text-xs italic block mb-1">
                                        Status: Sedang Magang
                                    </span>
                                    <span class="text-xs text-gray-400">
                                        (Mentor: {{ $app->mentor->name ?? 'Belum Ada' }})
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                                    <p>Belum ada pelamar yang masuk.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>