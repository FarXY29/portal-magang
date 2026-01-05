<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Peserta') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Notifikasi Sukses -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                </div>
            @endif

            <!-- Notifikasi Error -->
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
                </div>
            @endif

            <!-- Warning Profil Belum Lengkap -->
            @if(empty(Auth::user()->nik) || empty(Auth::user()->asal_instansi))
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                <strong>Profil Belum Lengkap!</strong> Silakan lengkapi NIK dan Asal Instansi (Kampus/Sekolah) di menu 
                                <a href="{{ route('profile.edit') }}" class="font-bold underline hover:text-yellow-800">Profil</a> 
                                agar dosen pembimbing bisa memantau dan sertifikat tercetak dengan benar.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            @if($activeApp)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border-l-4 border-indigo-500">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex flex-col md:flex-row justify-between items-center">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Absensi Harian</h3>
                                <p class="text-sm text-gray-500">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                                
                                <div class="mt-2 text-xs text-gray-500 bg-gray-100 p-2 rounded inline-block">
                                    <span>
                                        <i class="fas fa-clock text-green-600"></i> 
                                        Masuk: <strong>{{ \Carbon\Carbon::parse($jamKerja->jam_mulai_masuk)->format('H:i') }}</strong>
                                    </span>
                                    <span>
                                        <i class="fas fa-clock text-red-600"></i> 
                                        Pulang: <strong>{{ \Carbon\Carbon::parse($jamKerja->jam_mulai_pulang)->format('H:i') }}</strong>
                                    </span>
                                </div>
                                <div class="mt-2 text-sm text-gray-600">
                                    <span class="font-bold"><i class="far fa-calendar-alt"></i> Jadwal:</span> 
                                    {{ \Carbon\Carbon::parse($activeApp->tanggal_mulai)->format('d M Y') }} 
                                    s/d 
                                    {{ \Carbon\Carbon::parse($activeApp->tanggal_selesai)->format('d M Y') }}
                                </div>
                            </div>

                            <div class="mt-4 md:mt-0 flex gap-3">
                                @if(!$attendanceToday)
                                    <form action="{{ route('peserta.absen.masuk') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-full shadow-lg transition transform hover:scale-105">
                                            <i class="fas fa-fingerprint mr-2"></i> Absen Datang
                                        </button>
                                    </form>
                                    
                                    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'modal-izin')" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-6 rounded-full shadow-lg transition">
                                        <i class="fas fa-file-medical mr-2"></i> Izin / Sakit
                                    </button>

                                @elseif($attendanceToday->status == 'hadir' && $attendanceToday->clock_out == null)
                                    <div class="flex items-center gap-4">
                                        <span class="text-green-600 font-bold bg-green-100 px-3 py-1 rounded">
                                            <i class="fas fa-check-circle mr-1"></i> Masuk: {{ $attendanceToday->clock_in }}
                                        </span>
                                        <form action="{{ route('peserta.absen.pulang') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-full shadow-lg transition">
                                                <i class="fas fa-sign-out-alt mr-2"></i> Absen Pulang
                                            </button>
                                        </form>
                                    </div>

                                @else
                                    <div class="text-center">
                                        @if($attendanceToday->status == 'hadir')
                                            <div class="text-green-600 font-bold bg-green-50 px-4 py-2 rounded border border-green-200">
                                                <i class="fas fa-check-double mr-1"></i> Hadir Lengkap
                                                <div class="text-xs font-normal text-gray-500 mt-1">
                                                    {{ $attendanceToday->clock_in }} - {{ $attendanceToday->clock_out }}
                                                </div>
                                            </div>
                                        @else
                                            <div class="text-yellow-600 font-bold bg-yellow-50 px-4 py-2 rounded border border-yellow-200 uppercase">
                                                <i class="fas fa-info-circle mr-1"></i> Status: {{ $attendanceToday->status }}
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <x-modal name="modal-izin" focusable>
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Form Pengajuan Izin / Sakit</h2>
                        <form action="{{ route('peserta.absen.izin') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="mb-4">
                                <x-input-label for="status" value="Jenis Izin" />
                                <select name="status" id="status" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 mt-1" required>
                                    <option value="sakit">Sakit (Wajib Surat Dokter)</option>
                                    <option value="izin">Izin (Keperluan Mendesak)</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <x-input-label for="description" value="Alasan / Keterangan" />
                                <textarea name="description" id="description" rows="3" class="w-full border-gray-300 rounded-md shadow-sm mt-1" required placeholder="Jelaskan alasan Anda tidak bisa hadir..."></textarea>
                            </div>

                            <div class="mb-4">
                                <x-input-label for="proof_file" value="Bukti Foto / Surat (JPG/PNG)" />
                                <input type="file" name="proof_file" id="proof_file" class="w-full border border-gray-300 rounded p-2 mt-1" required>
                            </div>

                            <div class="flex justify-end mt-6">
                                <x-secondary-button x-on:click="$dispatch('close')" class="mr-3">
                                    Batal
                                </x-secondary-button>
                                <x-primary-button class="bg-yellow-500 hover:bg-yellow-600">
                                    Kirim Pengajuan
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </x-modal>
                @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
                <h3 class="text-lg font-bold mb-4 text-gray-800 border-b pb-2">Status Lamaran Saya</h3>
                
                @forelse($myApplications as $app)
                    <div class="border rounded-lg p-5 mb-4 flex flex-col md:flex-row justify-between items-start md:items-center transition hover:shadow-md 
                        {{ $app->status == 'diterima' ? 'bg-teal-50 border-teal-200' : ($app->status == 'selesai' ? 'bg-blue-50 border-blue-200' : 'bg-gray-50') }}">
                        
                        <!-- Info Utama -->
                        <div class="mb-4 md:mb-0">
                            <h4 class="font-bold text-lg text-gray-900">{{ $app->position->skpd->nama_dinas }}</h4>
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="far fa-clock mr-1"></i> Status: 
                                <span class="font-bold uppercase text-gray-700">{{ $app->status }}</span>
                            </p>

                            <!-- Tampilkan Nilai Jika Ada -->
                            @if($app->nilai_angka)
                                <div class="mt-3 p-2 bg-white rounded border border-blue-100 inline-block">
                                    <span class="text-xs font-bold text-gray-500 uppercase">Nilai Akhir:</span>
                                    <div class="text-lg font-bold text-blue-600">
                                        {{ $app->nilai_angka }} <span class="text-sm text-gray-400">({{ $app->predikat }})</span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Aksi -->
                        <div class="text-right flex flex-col items-end gap-2">
                            @if($app->status == 'diterima')
                                <div class="flex gap-2">
                                    <!-- Tombol Isi Logbook -->
                                    <a href="{{ route('peserta.logbook.index') }}" class="inline-flex items-center bg-teal-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-teal-700 transition shadow-sm">
                                        <i class="fas fa-book-open mr-2"></i> Isi Logbook
                                    </a>
                                    
                                    <!-- Tombol Cetak Rekap (Baru) -->
                                    <a href="{{ route('peserta.logbook.print') }}" target="_blank" class="inline-flex items-center bg-gray-800 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-900 transition shadow-sm">
                                        <i class="fas fa-print mr-2"></i> Cetak Rekap
                                    </a>
                                </div>

                            @elseif($app->status == 'selesai')
                                <div class="flex gap-2">
                                    <!-- Tombol Download Sertifikat -->
                                    <a href="{{ route('peserta.sertifikat') }}" class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition shadow-sm">
                                        <i class="fas fa-certificate mr-2"></i> Download Sertifikat
                                    </a>
                                    <a href="{{ route('peserta.download.nilai', $app->id) }}" class="flex-shrink-0 inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow-lg shadow-green-200 transition transform hover:-translate-y-1">
                                        <i class="fas fa-file-pdf mr-2"></i> Download Transkrip
                                    </a>
                                    <!-- Tombol Cetak Rekap -->
                                    <a href="{{ route('peserta.logbook.print') }}" target="_blank" class="inline-flex items-center bg-gray-800 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-900 transition shadow-sm">
                                        <i class="fas fa-print mr-2"></i> Rekap
                                    </a>
                                </div>

                            @elseif($app->status == 'pending')
                                <span class="text-sm text-yellow-600 bg-yellow-100 px-3 py-1 rounded-full">Menunggu Seleksi</span>
                            @elseif($app->status == 'ditolak')
                                <span class="text-sm text-red-600 bg-red-100 px-3 py-1 rounded-full">Lamaran Ditolak</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <p class="text-gray-500 italic">Belum ada lamaran aktif.</p>
                        <a href="{{ route('home') }}" class="mt-3 inline-block bg-teal-600 text-white px-6 py-2 rounded-full text-sm font-bold hover:bg-teal-700 transition">
                            Cari Lowongan
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>