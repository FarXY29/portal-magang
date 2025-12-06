<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola Peserta & Mentor</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Notifikasi Sukses -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 shadow-sm">
                    <strong class="font-bold"><i class="fas fa-check mr-1"></i> Berhasil!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Peserta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posisi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pembimbing Lapangan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($interns as $intern)
                        <tr class="hover:bg-gray-50 transition {{ $intern->status == 'selesai' ? 'bg-gray-50 opacity-75' : '' }}">
                            <!-- Kolom Nama -->
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $intern->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $intern->user->email }}</div>
                                @if($intern->status == 'selesai')
                                    <span class="mt-1 px-2 py-0.5 text-[10px] bg-blue-100 text-blue-800 rounded-full font-bold">LULUS</span>
                                @endif
                            </td>

                            <!-- Kolom Posisi -->
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $intern->position->judul_posisi }}</td>
                            
                            <!-- KOLOM ASSIGN MENTOR (YANG KITA PERBAIKI) -->
                            <td class="px-6 py-4">
                                @if($intern->status == 'diterima')
                                    <!-- Form Assign Mentor -->
                                    <form action="{{ route('dinas.peserta.assign', $intern->id) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        
                                        <select name="mentor_id" class="text-xs border-gray-300 rounded shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 w-40 cursor-pointer">
                                            <option value="">-- Pilih Mentor --</option>
                                            @foreach($mentors as $mentor)
                                                <option value="{{ $mentor->id }}" {{ $intern->mentor_id == $mentor->id ? 'selected' : '' }}>
                                                    {{ $mentor->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <!-- Tombol Simpan Teks (Agar Lebih Jelas) -->
                                        <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-3 py-1.5 rounded text-xs font-bold shadow-sm transition">
                                            Simpan
                                        </button>
                                    </form>
                                    
                                    <!-- Info Helper -->
                                    @if($intern->mentor_id)
                                        <div class="text-[10px] text-green-600 mt-1">
                                            <i class="fas fa-check-circle"></i> Mentor saat ini: {{ $intern->mentor->name }}
                                        </div>
                                    @else
                                        <div class="text-[10px] text-red-500 mt-1">
                                            *Belum ada mentor
                                        </div>
                                    @endif

                                @else
                                    <!-- Jika sudah lulus, tampilkan teks saja -->
                                    <span class="text-gray-500 text-sm font-medium">
                                        <i class="fas fa-user-tie mr-1"></i> {{ $intern->mentor->name ?? '-' }}
                                    </span>
                                @endif
                            </td>

                            <!-- Kolom Aksi -->
                            <td class="px-6 py-4 flex gap-2">
                                <!-- Lihat Logbook -->
                                <a href="{{ route('dinas.peserta.logbook', $intern->id) }}" class="bg-gray-100 text-gray-700 px-3 py-1.5 rounded text-xs font-bold hover:bg-gray-200 border border-gray-300 transition" title="Intip Logbook">
                                    <i class="fas fa-eye"></i> Logbook
                                </a>

                                <!-- Tombol Luluskan -->
                                @if($intern->status == 'diterima')
                                    <form action="{{ route('dinas.peserta.selesai', $intern->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin meluluskan peserta ini? Sertifikat akan otomatis diterbitkan.')">
                                        @csrf
                                        <button type="submit" class="bg-blue-600 text-white px-3 py-1.5 rounded text-xs font-bold hover:bg-blue-700 shadow-sm transition">
                                            Luluskan
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-8 text-gray-500 italic">Belum ada peserta aktif di dinas ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>