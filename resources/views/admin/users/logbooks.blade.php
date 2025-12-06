<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Monitoring Logbook Peserta</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Pencarian -->
            <div class="flex justify-between items-center mb-6">
                <form method="GET" action="{{ route('admin.users.logbooks') }}" class="flex w-full md:w-auto">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama peserta..." class="border-gray-300 rounded-l-md text-sm focus:ring-teal-500 focus:border-teal-500 w-full md:w-64">
                    <button class="bg-teal-600 text-white px-4 rounded-r-md hover:bg-teal-700 text-sm">Cari</button>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Peserta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Instansi Asal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lokasi Magang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($participants as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900">{{ $user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $user->email }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $user->asal_instansi ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                @if($user->applications->isNotEmpty())
                                    {{ $user->applications->last()->position->skpd->nama_dinas }}
                                    <div class="text-xs text-gray-400">{{ $user->applications->last()->position->judul_posisi }}</div>
                                @else
                                    <span class="text-xs text-red-400 italic">Belum ada data magang</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                @if($user->applications->isNotEmpty())
                                    <a href="{{ route('admin.users.logbooks.show', $user->id) }}" class="text-teal-600 hover:text-teal-900 bg-teal-50 px-3 py-1 rounded border border-teal-200">
                                        <i class="fas fa-book-open mr-1"></i> Lihat Logbook
                                    </a>
                                @else
                                    <span class="text-gray-400 cursor-not-allowed text-xs">No Data</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">Tidak ada peserta ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-4 border-t border-gray-200">
                    {{ $participants->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>