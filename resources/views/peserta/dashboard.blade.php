<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <i class="fas fa-columns text-teal-600"></i>
                {{ __('Dashboard Peserta') }}
            </h2>
            <div class="text-sm text-gray-500 font-medium bg-white px-4 py-1.5 rounded-full shadow-sm border border-gray-100">
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" class="flex items-center p-4 mb-4 text-green-800 rounded-xl bg-green-50 border border-green-100 shadow-sm relative">
                    <i class="fas fa-check-circle flex-shrink-0 w-5 h-5 mr-3 text-green-600"></i>
                    <div class="text-sm font-bold">{{ session('success') }}</div>
                    <button @click="show = false" class="ml-auto text-green-500 hover:text-green-700"><i class="fas fa-times"></i></button>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" class="flex items-center p-4 mb-4 text-red-800 rounded-xl bg-red-50 border border-red-100 shadow-sm relative">
                    <i class="fas fa-exclamation-triangle flex-shrink-0 w-5 h-5 mr-3 text-red-600"></i>
                    <div class="text-sm font-bold">{{ session('error') }}</div>
                    <button @click="show = false" class="ml-auto text-red-500 hover:text-red-700"><i class="fas fa-times"></i></button>
                </div>
            @endif

            @if(empty(Auth::user()->nik) || empty(Auth::user()->asal_instansi))
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-xl shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-yellow-500 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-bold text-yellow-800">Profil Belum Lengkap</h3>
                            <div class="mt-1 text-sm text-yellow-700">
                                Silakan lengkapi NIK dan Asal Instansi agar sertifikat dapat dicetak. 
                                <a href="{{ route('profile.edit') }}" class="font-bold underline hover:text-yellow-900">Lengkapi Sekarang &rarr;</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($activeApp && $activeApp->status == 'diterima')
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 md:p-8 flex flex-col md:flex-row justify-between items-center gap-6 bg-gradient-to-br from-white to-gray-50">
                        
                        <div class="w-full md:w-auto text-center md:text-left">
                            <h3 class="text-xl font-extrabold text-gray-800 mb-1">Halo, {{ Auth::user()->name }}!</h3>
                            <p class="text-sm text-gray-500 mb-4">Jangan lupa absen hari ini ya.</p>
                            
                            <div class="inline-flex flex-col sm:flex-row gap-3 text-xs font-bold text-gray-600 bg-white p-3 rounded-xl shadow-sm border border-gray-100">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                    Masuk: {{ \Carbon\Carbon::parse($jamKerja->jam_mulai_masuk)->format('H:i') }}
                                </div>
                                <div class="hidden sm:block border-l border-gray-300 h-4 self-center"></div>
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                    Pulang: {{ \Carbon\Carbon::parse($jamKerja->jam_mulai_pulang)->format('H:i') }}
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap justify-center gap-3 w-full md:w-auto">
                            @if(!$attendanceToday)
                                <form action="{{ route('peserta.absen.masuk') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white rounded-xl font-bold shadow-lg shadow-teal-200 transition transform hover:-translate-y-1 flex items-center gap-2">
                                        <i class="fas fa-fingerprint"></i> Absen Datang
                                    </button>
                                </form>
                                
                                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'modal-izin')" class="px-6 py-3 bg-white border-2 border-yellow-400 text-yellow-600 hover:bg-yellow-50 rounded-xl font-bold transition flex items-center gap-2">
                                    <i class="fas fa-file-medical"></i> Izin / Sakit
                                </button>

                            @elseif($attendanceToday->status == 'hadir' && empty($attendanceToday->clock_out))
                                <div class="flex flex-col items-center gap-2">
                                    <div class="text-sm font-bold text-teal-700 bg-teal-50 px-4 py-2 rounded-lg border border-teal-100">
                                        <i class="fas fa-check-circle mr-1"></i> Masuk: {{ \Carbon\Carbon::parse($attendanceToday->clock_in)->format('H:i') }}
                                    </div>
                                    <form action="{{ route('peserta.absen.pulang') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-xl font-bold shadow-lg shadow-red-200 transition transform hover:-translate-y-1 flex items-center gap-2">
                                            <i class="fas fa-sign-out-alt"></i> Absen Pulang
                                        </button>
                                    </form>
                                </div>

                            @else
                                <div class="text-center">
                                    @if($attendanceToday->status == 'hadir')
                                        <div class="px-6 py-3 bg-green-50 text-green-700 rounded-xl border border-green-200 font-bold flex flex-col items-center">
                                            <span><i class="fas fa-check-double mr-1"></i> Kehadiran Terekam</span>
                                            <span class="text-xs font-normal mt-1 text-green-600">
                                                {{ \Carbon\Carbon::parse($attendanceToday->clock_in)->format('H:i') }} - 
                                                {{ $attendanceToday->clock_out ? \Carbon\Carbon::parse($attendanceToday->clock_out)->format('H:i') : '?' }}
                                            </span>
                                        </div>
                                    @else
                                        <div class="px-6 py-3 bg-yellow-50 text-yellow-700 rounded-xl border border-yellow-200 font-bold flex items-center gap-2">
                                            <i class="fas fa-info-circle"></i> Status: {{ ucfirst($attendanceToday->status) }}
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                    </div>
                </div>

                <x-modal name="modal-izin" focusable>
                    <div class="p-6">
                        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-file-alt text-yellow-500"></i> Form Izin / Sakit
                        </h2>
                        <form action="{{ route('peserta.absen.izin') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Jenis Keterangan</label>
                                    <select name="status" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-teal-500 focus:ring-teal-500">
                                        <option value="sakit">Sakit (Upload Surat Dokter)</option>
                                        <option value="izin">Izin (Keperluan Mendesak)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Alasan Detail</label>
                                    <textarea name="description" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-teal-500 focus:ring-teal-500" required placeholder="Jelaskan alasan Anda..."></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Bukti Foto / Surat</label>
                                    <input type="file" name="proof_file" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 border border-gray-300 rounded-lg" required>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end gap-3">
                                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 font-bold hover:bg-gray-50 transition">Batal</button>
                                <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-lg font-bold hover:bg-teal-700 transition shadow-md">Kirim Pengajuan</button>
                            </div>
                        </form>
                    </div>
                </x-modal>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-briefcase text-teal-500"></i> Riwayat Lamaran
                    </h3>
                </div>
                
                <div class="p-6 space-y-4">
                    @forelse($myApplications as $app)
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center p-5 rounded-xl border transition hover:shadow-md 
                            {{ $app->status == 'diterima' ? 'bg-teal-50/50 border-teal-100' : ($app->status == 'selesai' ? 'bg-blue-50/50 border-blue-100' : 'bg-white border-gray-100') }}">
                            
                            <div class="mb-4 md:mb-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <h4 class="font-bold text-gray-900 text-lg">{{ $app->position->skpd->nama_dinas }}</h4>
                                    @php
                                        $badges = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'diterima' => 'bg-green-100 text-green-800',
                                            'selesai' => 'bg-blue-100 text-blue-800',
                                            'ditolak' => 'bg-red-100 text-red-800'
                                        ];
                                    @endphp
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase {{ $badges[$app->status] ?? 'bg-gray-100' }}">
                                        {{ $app->status }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600">{{ $app->position->judul_posisi }}</p>
                                
                                @if($app->nilai_angka)
                                    <div class="mt-2 inline-flex items-center px-3 py-1 bg-white rounded border border-blue-200 text-xs font-bold text-blue-700 shadow-sm">
                                        <i class="fas fa-star mr-1 text-yellow-400"></i> Nilai Akhir: {{ $app->nilai_angka }} ({{ $app->predikat }})
                                    </div>
                                @endif
                            </div>

                            <div class="flex flex-wrap gap-2 justify-end w-full md:w-auto">
                                @if($app->status == 'diterima')
                                    <a href="{{ route('peserta.loa.download', $app->id) }}" target="_blank" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-bold hover:bg-indigo-700 transition shadow-sm flex items-center gap-2">
                                        <i class="fas fa-file-contract"></i> Surat Balasan
                                    </a>

                                    <a href="{{ route('peserta.logbook.index') }}" class="px-4 py-2 bg-teal-600 text-white rounded-lg text-sm font-bold hover:bg-teal-700 transition shadow-sm flex items-center gap-2">
                                        <i class="fas fa-book-open"></i> Logbook
                                    </a>
                                    <a href="{{ route('peserta.logbook.print') }}" target="_blank" class="px-4 py-2 bg-gray-800 text-white rounded-lg text-sm font-bold hover:bg-gray-900 transition shadow-sm flex items-center gap-2">
                                        <i class="fas fa-print"></i> Rekap
                                    </a>
                                @elseif($app->status == 'selesai')
                                    <a href="{{ route('peserta.loa.download', $app->id) }}" target="_blank" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-bold hover:bg-indigo-700 transition shadow-sm flex items-center gap-2">
                                        <i class="fas fa-file-contract"></i> Surat Balasan
                                    </a>
                                    <a href="{{ route('peserta.sertifikat') }}" target="_blank" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold hover:bg-blue-700 transition shadow-sm flex items-center gap-2">
                                        <i class="fas fa-certificate"></i> Sertifikat
                                    </a>
                                    <a href="{{ route('peserta.download.nilai', $app->id) }}" target="_blank" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-bold hover:bg-green-700 transition shadow-sm flex items-center gap-2">
                                        <i class="fas fa-file-alt"></i> Transkrip
                                    </a>
                                    
                                    <a href="{{ route('peserta.logbook.print') }}" target="_blank" class="px-4 py-2 bg-gray-800 text-white rounded-lg text-sm font-bold hover:bg-gray-900 transition shadow-sm flex items-center gap-2">
                                        <i class="fas fa-print"></i> Rekap
                                    </a>
                                @endif
                            </div>

                        </div>
                    @empty
                        <div class="text-center py-10">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-300">
                                <i class="fas fa-inbox text-3xl"></i>
                            </div>
                            <p class="text-gray-500 font-medium">Belum ada riwayat lamaran.</p>
                            <a href="{{ route('home') }}" class="mt-2 inline-block text-teal-600 font-bold hover:underline">Cari Lowongan Magang &rarr;</a>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>