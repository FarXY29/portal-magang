<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Monitoring Absensi Mahasiswa
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between mb-6 print:hidden">
                <a href="{{ route('mentor.dashboard') }}" class="text-gray-600 hover:text-gray-900">&larr; Kembali</a>
            </div>
            <div class="flex flex-col lg:flex-row gap-8 items-start">
                
                <div class="w-full lg:w-1/4 flex-shrink-0">
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 sticky top-6">
                        
                        <div class="mb-6 border-b border-gray-100 pb-2">
                            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest">
                                Filter Data
                            </h3>
                        </div>

                        <form action="{{ route('mentor.attendance.index') }}" method="GET">
                            
                            <div class="mb-5">
                                <label class="block text-xs font-bold text-gray-600 uppercase mb-2">
                                    Pilih Tanggal
                                </label>
                                <input type="date" name="date" value="{{ $selectedDate }}" 
                                       class="w-full border-gray-300 rounded-md text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-gray-700">
                            </div>

                            <div class="mb-6">
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">
                                    Pilihan Cepat
                                </label>
                                <div class="grid grid-cols-2 gap-2">
                                    @foreach($dateList->take(4) as $dateItem)
                                        @php
                                            $isActive = $dateItem->format('Y-m-d') == $selectedDate;
                                        @endphp
                                        <a href="{{ route('mentor.attendance.index', ['date' => $dateItem->format('Y-m-d')]) }}" 
                                           class="text-xs text-center py-2 px-1 rounded border transition {{ $isActive ? 'bg-indigo-50 border-indigo-500 text-indigo-700 font-bold' : 'bg-gray-50 border-gray-200 text-gray-600 hover:bg-gray-100' }}">
                                            {{ $dateItem->isToday() ? 'HARI INI' : $dateItem->translatedFormat('D, d M') }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-4 rounded shadow-sm transition transform active:scale-95 text-sm">
                                Terapkan Filter
                            </button>
                            
                            @if(request('date') && request('date') != date('Y-m-d'))
                                <a href="{{ route('mentor.attendance.index') }}" class="block text-center mt-3 text-xs text-gray-500 hover:text-indigo-600 underline">
                                    Reset ke Hari Ini
                                </a>
                            @endif

                        </form>
                    </div>
                </div>

                <div class="w-full lg:w-3/4">
                    <div class="bg-white shadow-sm rounded-lg border border-gray-100 overflow-hidden">
                        
                        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4 bg-white">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Hasil Data Absensi</h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    Menampilkan <span class="font-bold text-indigo-600">{{ $attendances->count() }}</span> data pada tanggal {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d F Y') }}
                                </p>
                            </div>
                            
                            <button onclick="window.print()" class="bg-gray-800 text-white px-4 py-2 rounded text-sm font-bold shadow hover:bg-gray-700 transition flex items-center">
                                <i class="fas fa-print mr-2"></i> Cetak PDF
                            </button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-100">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Peserta / Instansi</th>
                                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Waktu Masuk</th>
                                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Waktu Pulang</th>
                                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @forelse($attendances as $row)
                                    <tr class="hover:bg-gray-50 transition duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-9 w-9 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center font-bold text-sm">
                                                    {{ substr($row->application->user->name, 0, 1) }}
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-bold text-gray-900">{{ $row->application->user->name }}</div>
                                                    <div class="text-xs text-gray-500">{{ Str::limit($row->application->position->judul_posisi, 30) }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-600 font-medium">
                                            @if($row->status == 'hadir')
                                                {{ \Carbon\Carbon::parse($row->clock_in)->format('H:i') }}
                                            @else
                                                <span class="text-gray-300">-</span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-600 font-medium">
                                            @if($row->status == 'hadir')
                                                @if($row->clock_out)
                                                    {{ \Carbon\Carbon::parse($row->clock_out)->format('H:i') }}
                                                @else
                                                    <span class="text-red-400 text-xs italic">Belum</span>
                                                @endif
                                            @else
                                                <span class="text-gray-300">-</span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if($row->status == 'hadir')
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-green-50 text-green-700">
                                                    Hadir
                                                </span>
                                            @elseif($row->status == 'sakit')
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-yellow-50 text-yellow-700">
                                                    Sakit
                                                </span>
                                            @elseif($row->status == 'izin')
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-blue-50 text-blue-700">
                                                    Izin
                                                </span>
                                            @endif

                                            @if($row->status != 'hadir')
                                                @if($row->validation_status == 'pending')
                                                    <span class="ml-1 text-[10px] bg-red-100 text-red-600 px-1.5 py-0.5 rounded font-bold" title="Menunggu Persetujuan">!</span>
                                                @endif
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if($row->status == 'hadir')
                                                <span class="text-gray-300 text-xs font-bold">Auto</span>
                                            @else
                                                @if($row->validation_status == 'pending')
                                                    <div class="flex justify-center space-x-2">
                                                        <button x-data="" x-on:click="$dispatch('open-modal', 'modal-bukti-{{ $row->id }}')" class="text-indigo-600 hover:text-indigo-900 text-xs font-bold underline mr-2">
                                                            Cek
                                                        </button>
                                                        
                                                        <form action="{{ route('mentor.attendance.validate', $row->id) }}" method="POST" class="inline">
                                                            @csrf
                                                            <input type="hidden" name="status_validasi" value="approved">
                                                            <button type="submit" class="text-green-600 hover:text-green-800" title="Setujui">
                                                                <i class="fas fa-check-circle text-lg"></i>
                                                            </button>
                                                        </form>
                                                        
                                                        <button x-data="" x-on:click="$dispatch('open-modal', 'modal-tolak-{{ $row->id }}')" class="text-red-500 hover:text-red-700" title="Tolak">
                                                            <i class="fas fa-times-circle text-lg"></i>
                                                        </button>
                                                    </div>
                                                @else
                                                    <button x-data="" x-on:click="$dispatch('open-modal', 'modal-bukti-{{ $row->id }}')" class="text-gray-500 hover:text-indigo-600 text-xs underline">
                                                        Detail
                                                    </button>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>

                                    @if($row->proof_file)
                                    <x-modal name="modal-bukti-{{ $row->id }}" focusable>
                                        <div class="p-6">
                                            <div class="flex justify-between items-center mb-4 border-b pb-2">
                                                <h2 class="text-lg font-bold text-gray-800">Bukti Izin / Sakit</h2>
                                                <button x-on:click="$dispatch('close')" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
                                            </div>
                                            <div class="flex justify-center bg-gray-100 rounded-lg p-2 mb-4">
                                                <img src="{{ asset('storage/' . $row->proof_file) }}" class="max-h-[60vh] rounded shadow" alt="Bukti">
                                            </div>
                                            <div class="bg-gray-50 p-4 rounded border border-gray-100">
                                                <p class="text-xs text-gray-500 font-bold uppercase mb-1">Keterangan Mahasiswa:</p>
                                                <p class="text-gray-800 text-sm italic">"{{ $row->description }}"</p>
                                            </div>
                                            <div class="mt-4 flex justify-end">
                                                <x-secondary-button x-on:click="$dispatch('close')">Tutup</x-secondary-button>
                                            </div>
                                        </div>
                                    </x-modal>
                                    @endif

                                    <x-modal name="modal-tolak-{{ $row->id }}" focusable>
                                        <div class="p-6">
                                            <h2 class="text-lg font-bold text-red-600 mb-4 border-b pb-2">Konfirmasi Penolakan</h2>
                                            <form action="{{ route('mentor.attendance.validate', $row->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status_validasi" value="rejected">
                                                <div class="mb-4">
                                                    <label class="block text-sm font-bold text-gray-700 mb-1">Alasan Penolakan</label>
                                                    <textarea name="mentor_note" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" required placeholder="Contoh: Bukti tidak valid atau tidak terbaca"></textarea>
                                                </div>
                                                <div class="mt-6 flex justify-end space-x-3">
                                                    <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
                                                    <x-primary-button class="bg-red-600 hover:bg-red-700 border-none">Tolak Pengajuan</x-primary-button>
                                                </div>
                                            </form>
                                        </div>
                                    </x-modal>

                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="h-16 w-16 bg-gray-100 rounded-full flex items-center justify-center mb-3 text-gray-400">
                                                    <i class="far fa-folder-open text-3xl"></i>
                                                </div>
                                                <p class="font-medium text-gray-600">Tidak ada data absensi ditemukan</p>
                                                <p class="text-xs text-gray-400 mt-1">Coba pilih tanggal lain pada filter.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        @if($attendances instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                            {{ $attendances->links() }}
                        </div>
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>