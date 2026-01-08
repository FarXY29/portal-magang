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
                <div class="flex gap-1"> <a href="{{ route('admin.skpd.print_pdf') }}" target="_blank" class="bg-gray-800 text-white px-4 py-2 rounded shadow hover:bg-gray-700 transition flex items-center">
                        <i class="fas fa-file-pdf mr-2"></i> Download PDF
                    </a>

                    <a href="{{ route('admin.skpd.create') }}" class="bg-blue-800 text-white px-4 py-2 rounded shadow hover:bg-blue-900 transition">
                        <i class="fas fa-plus mr-1"></i> Tambah Instansi Baru
                    </a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Instansi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Unit</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase print:text-black">Peserta</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase print:text-black">Lowongan</th>
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
                            <td class="px-6 py-4 text-sm text-center text-gray-900">
                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                    {{ $dinas->positions->count() }} Posisi
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-center text-gray-900">
                                @php
                                    // Menghitung peserta yang statusnya 'diterima' atau 'selesai'
                                    $totalPeserta = $dinas->positions->flatMap->applications
                                                    ->whereIn('status', ['diterima', 'selesai'])->count();
                                @endphp
                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                    {{ $totalPeserta }} Orang
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $dinas->latitude }}, {{ $dinas->longitude }}
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.skpd.edit', $dinas->id) }}" class="text-green-600 hover:text-white-900 font-bold text-sm">
                                    Edit
                                </a>
                                <form action="{{ route('admin.skpd.destroy', $dinas->id) }}" method="POST" onsubmit="return confirm('Hapus Instansi ini? Semua data user dan lowongan terkait akan ikut terhapus!')">
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