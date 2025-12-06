<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Mentor Lapangan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500">
                <h3 class="text-lg font-bold mb-1">Daftar Mahasiswa Bimbingan</h3>
                <p class="text-sm text-gray-500 mb-4">Berikut adalah peserta magang yang ditugaskan kepada Anda.</p>
                
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Peserta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Posisi Magang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($interns as $mhs)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="font-bold">{{ $mhs->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $mhs->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $mhs->position->judul_posisi }}</td>
                            <td class="px-6 py-4">
                                @if($mhs->status == 'diterima')
                                    <span class="px-2 py-1 text-xs font-bold bg-green-100 text-green-800 rounded-full">Sedang Magang</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-bold bg-blue-100 text-blue-800 rounded-full">Selesai</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('mentor.logbook', $mhs->id) }}" class="inline-flex items-center bg-indigo-600 text-white px-3 py-1.5 rounded text-xs font-bold hover:bg-indigo-700 shadow-sm transition">
                                    <i class="fas fa-check-double mr-1"></i> Validasi Logbook
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-8 text-gray-500">
                                <i class="far fa-folder-open text-2xl mb-2 block"></i>
                                Belum ada mahasiswa yang ditugaskan ke Anda.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>