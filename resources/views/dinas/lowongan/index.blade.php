<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola Lowongan Magang</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between mb-6 print:hidden">
                    <a href="{{ route('dinas.dashboard') }}" class="text-gray-600 hover:text-gray-900">&larr; Kembali</a>
                </div>
            <div class="flex justify-between items-center mb-6">
                <div>
                    @if(session('success'))
                        <div class="text-green-600 font-bold bg-green-100 px-4 py-2 rounded shadow-sm text-sm">
                            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                        </div>
                    @endif
                </div>
                <a href="{{ route('dinas.lowongan.create') }}" class="bg-teal-600 text-white px-4 py-2 rounded shadow hover:bg-teal-700 transition">
                    <i class="fas fa-plus mr-1"></i> Tambah Lowongan
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posisi Magang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kuota / Batas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($lowongans as $loker)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900">{{ $loker->judul_posisi }}</div>
                                <div class="text-xs text-gray-500 truncate w-48" title="{{ $loker->deskripsi }}">{{ Str::limit($loker->deskripsi, 50) }}</div>
                                <div class="text-xs text-teal-600 mt-1 font-medium">Syarat: {{ $loker->required_major }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 font-semibold">{{ $loker->kuota }} Orang</div>
                                <div class="text-xs text-gray-500">Batas: {{ \Carbon\Carbon::parse($loker->batas_daftar)->format('d M Y') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $loker->status == 'buka' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($loker->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    <!-- TOMBOL EDIT -->
                                    <a href="{{ route('dinas.lowongan.edit', $loker->id) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1 rounded hover:bg-indigo-100 transition">
                                        Edit
                                    </a>

                                    <!-- TOMBOL HAPUS -->
                                    <form action="{{ route('dinas.lowongan.destroy', $loker->id) }}" method="POST" onsubmit="return confirm('Hapus lowongan ini?')" class="inline-block">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 px-3 py-1 rounded hover:bg-red-100 transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-folder-open text-4xl mb-3 text-gray-300"></i>
                                    <p>Belum ada lowongan yang dibuat.</p>
                                    <p class="text-xs mt-1">Klik tombol "Tambah Lowongan" di kanan atas.</p>
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