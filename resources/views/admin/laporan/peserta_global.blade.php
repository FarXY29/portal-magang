<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan Global Peserta Magang</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between mb-6 print:hidden">
                <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-900">&larr; Kembali</a>
            </div>

            <div class="flex flex-col md:flex-row gap-6 items-start">

                <div class="w-full md:w-1/4 bg-white p-5 rounded-lg shadow-sm border border-gray-100 print:hidden sticky top-6">
                    <div class="mb-4 border-b border-gray-100 pb-2">
                        <h3 class="text-gray-800 font-bold text-sm uppercase tracking-wide flex items-center">
                            <i class="fas fa-filter mr-2 text-indigo-500"></i> Filter Data
                        </h3>
                    </div>
                    
                    <form method="GET" action="{{ route('admin.laporan.peserta_global') }}" class="flex flex-col gap-4">
                        
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Lokasi Magang</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                    <i class="fas fa-building text-xs"></i>
                                </span>
                                <select name="skpd_id" class="w-full pl-9 border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm cursor-pointer">
                                    <option value="">Semua Instansi</option>
                                    @foreach($listSkpd as $skpd)
                                        <option value="{{ $skpd->id }}" {{ request('skpd_id') == $skpd->id ? 'selected' : '' }}>
                                            {{ Str::limit($skpd->nama_dinas, 20) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Asal Universitas/Sekolah</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                    <i class="fas fa-university text-xs"></i>
                                </span>
                                <select name="instansi" class="w-full pl-9 border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm cursor-pointer">
                                    <option value="">Semua Universitas/Sekolah</option>
                                    @foreach($listInstansi as $instansi)
                                        <option value="{{ $instansi }}" {{ request('instansi') == $instansi ? 'selected' : '' }}>
                                            {{ Str::limit($instansi, 20) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Cari Posisi</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                    <i class="fas fa-search text-xs"></i>
                                </span>
                                <input type="text" name="posisi" value="{{ request('posisi') }}" placeholder="Contoh: Programmer..." 
                                       class="w-full pl-9 border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                            </div>
                        </div>

                        <div class="pt-2 flex flex-col gap-2">
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md shadow hover:bg-indigo-700 text-sm font-bold w-full transition flex items-center justify-center">
                                Terapkan Filter
                            </button>
                            @if(request()->anyFilled(['skpd_id', 'instansi', 'posisi']))
                                <a href="{{ route('admin.laporan.peserta_global') }}" class="bg-gray-100 text-gray-600 px-4 py-2 rounded-md border border-gray-300 hover:bg-gray-200 text-sm font-bold w-full text-center transition">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>


                <div class="w-full md:w-3/4">
                    
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                        <div class="p-5 bg-white border-b border-gray-200 flex flex-col sm:flex-row justify-between items-center print:border-none gap-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Hasil Data</h3>
                                <p class="text-sm text-gray-500">
                                    Menampilkan <span class="font-bold text-indigo-600">{{ $allInterns->count() }}</span> peserta
                                </p>
                            </div>
                            
                            <a href="{{ route('admin.laporan.peserta_global.print', request()->query()) }}" target="_blank" class="bg-gray-800 text-white px-5 py-2 rounded-md shadow hover:bg-gray-700 transition flex items-center text-sm font-bold whitespace-nowrap">
                                <i class="fas fa-file-pdf mr-2"></i> Download PDF
                            </a>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Peserta / Instansi</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Lokasi</th>
                                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Mulai Magang</th>
                                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Selesai Magang</th>
                                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @forelse($allInterns as $data)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-gray-900 text-sm">{{ $data->user->name }}</div>
                                            <div class="text-xs text-gray-500 flex items-center mt-1">
                                                <i class="fas fa-university mr-1 text-gray-300"></i> {{ Str::limit($data->user->asal_instansi, 25) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-800 font-medium">{{ $data->position->skpd->nama_dinas }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-600">
                                            {{ \Carbon\Carbon::parse($data->tanggal_mulai)->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-600">
                                            {{ \Carbon\Carbon::parse($data->tanggal_selesai)->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if($data->status == 'selesai')
                                                <span class="px-2.5 py-1 bg-blue-100 text-blue-800 text-xs font-bold rounded-full border border-blue-200">Selesai</span>
                                            @else
                                                <span class="px-2.5 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full border border-green-200">Aktif</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <i class="fas fa-search text-gray-300 text-4xl mb-3"></i>
                                                <p>Tidak ada data ditemukan.</p>
                                                <a href="{{ route('admin.laporan.peserta_global') }}" class="text-indigo-600 hover:underline text-sm mt-2">Reset Filter</a>
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
        </div>
    </div>
</x-app-layout>