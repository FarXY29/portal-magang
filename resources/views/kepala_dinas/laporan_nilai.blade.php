<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan Penilaian Akhir</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between mb-6 print:hidden">
                    <a href="{{ route('kepala_dinas.dashboard') }}" class="text-gray-600 hover:text-gray-900">&larr; Kembali</a>
                    <button onclick="window.print()" class="bg-gray-800 text-white px-4 py-2 rounded text-sm">Cetak</button>
                </div>

                <table class="min-w-full border text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-4 py-2 text-left">Nama Peserta</th>
                            <th class="border px-4 py-2 text-left">Posisi</th>
                            <th class="border px-4 py-2 text-left">Mentor Penilai</th>
                            <th class="border px-4 py-2 text-center">Nilai Angka</th>
                            <th class="border px-4 py-2 text-center">Predikat</th>
                            <th class="border px-4 py-2 text-left">Catatan Mentor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($scores as $s)
                        <tr>
                            <td class="border px-4 py-2 font-bold">{{ $s->user->name }}</td>
                            <td class="border px-4 py-2">{{ $s->position->judul_posisi }}</td>
                            <td class="border px-4 py-2">{{ $s->mentor->name ?? '-' }}</td>
                            <td class="border px-4 py-2 text-center font-bold text-blue-600">{{ $s->nilai_angka }}</td>
                            <td class="border px-4 py-2 text-center">{{ $s->predikat }}</td>
                            <td class="border px-4 py-2 italic text-gray-600">{{ Str::limit($s->catatan_mentor, 50) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>