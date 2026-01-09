<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Rekap Peserta Magang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
                <form action="{{ route('dinas.laporan.rekap') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Aktif (Diterima)</option>
                                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Asal Sekolah/Kampus</label>
                            <input type="text" name="asal_instansi" value="{{ request('asal_instansi') }}" 
                                placeholder="Cari nama kampus..."
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Urutkan Nama</label>
                            <select name="sort" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">Terbaru (Default)</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama (A - Z)</option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama (Z - A)</option>
                            </select>
                        </div>
                        
                        <div class="flex gap-2">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow text-sm w-full">
                                <i class="fas fa-filter mr-1"></i> Terapkan
                            </button>
                            <a href="{{ route('dinas.laporan.rekap') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded shadow text-sm" title="Reset Filter">
                                <i class="fas fa-undo"></i>
                            </a>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <div class="flex flex-col md:flex-row md:items-end gap-4">
                            
                            <div class="flex items-center gap-2">
                                <div class="w-full">
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Dari Tanggal</label>
                                    <input type="date" name="start_date" value="{{ request('start_date') }}" 
                                        class="w-full md:w-36 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                </div>
                                
                                <span class="text-gray-400 mt-6">-</span>
                                
                                <div class="w-full">
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Sampai Tanggal</label>
                                    <input type="date" name="end_date" value="{{ request('end_date') }}" 
                                        class="w-full md:w-36 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                </div>
                            </div>

                            <div class="flex flex-1 items-center gap-3 md:mb-0.5">
                                <p class="text-xs text-gray-500 italic bg-gray-50 px-3 py-2 rounded border border-gray-200 flex-grow md:flex-grow-0">
                                    <i class="fas fa-info-circle mr-1 text-blue-400"></i>
                                    Menampilkan peserta yang periode magangnya beririsan.
                                </p>

                                <a href="{{ route('dinas.laporan.rekap.print', request()->query()) }}" target="_blank" class="whitespace-nowrap bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow text-sm ml-auto md:ml-0">
                                    <i class="fas fa-file-pdf mr-1"></i> Cetak PDF
                                </a>
                            </div>

                        </div>
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold">Data Peserta</h3>
                        <span class="text-sm bg-gray-100 px-3 py-1 rounded-full text-gray-600">Total: {{ $applications->count() }} Data</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Peserta</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asal Instansi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($applications as $index => $app)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-900">{{ $app->user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $app->user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ $app->user->asal_instansi ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ \Carbon\Carbon::parse($app->tanggal_mulai)->format('d M Y') }} - 
                                            {{ \Carbon\Carbon::parse($app->tanggal_selesai)->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($app->status == 'diterima')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                            @elseif($app->status == 'pending')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                            @elseif($app->status == 'ditolak')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>
                                            @elseif($app->status == 'selesai')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Selesai</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 italic">
                                            Tidak ada data peserta ditemukan sesuai filter.
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
</x-app-layout>