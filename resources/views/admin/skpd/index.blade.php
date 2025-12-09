<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Data Master SKPD</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between mb-6 print:hidden">
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-900">&larr; Kembali</a>
                </div>
            <div class="flex justify-between items-center mb-6">
                <div>
                    @if(session('success'))
                        <div class="text-green-600 font-bold bg-green-100 px-4 py-2 rounded">
                            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                        </div>
                    @endif
                </div>
                <a href="{{ route('admin.skpd.create') }}" class="bg-blue-800 text-white px-4 py-2 rounded shadow hover:bg-blue-900 transition">
                    <i class="fas fa-plus mr-1"></i> Tambah Dinas Baru
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Dinas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Unit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Koordinat (Lat, Lng)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($skpds as $dinas)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $dinas->nama_dinas }}</div>
                                <div class="text-xs text-gray-500">{{ Str::limit($dinas->alamat, 40) }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $dinas->kode_unit_kerja }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $dinas->latitude }}, {{ $dinas->longitude }}
                            </td>
                            <td class="px-6 py-4">
                                <form action="{{ route('admin.skpd.destroy', $dinas->id) }}" method="POST" onsubmit="return confirm('Hapus Dinas ini? Semua data user dan lowongan terkait akan ikut terhapus!')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-600 hover:text-red-900 font-bold text-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>