<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <i class="fas fa-book-open text-teal-600"></i>
                {{ __('Logbook Harian') }}
            </h2>
            <div class="text-sm text-gray-500 font-medium bg-white px-4 py-1.5 rounded-full shadow-sm border border-gray-100">
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50 min-h-screen font-sans">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <div class="mb-6 print:hidden">
                <a href="{{ route('peserta.dashboard') }}" class="group inline-flex items-center text-sm font-bold text-gray-500 hover:text-teal-600 transition">
                    <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                        <i class="fas fa-arrow-left text-xs"></i>
                    </div>
                    Kembali ke Dashboard
                </a>
            </div>

            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" class="flex items-center p-4 mb-4 text-green-800 rounded-xl bg-green-50 border border-green-100 shadow-sm relative">
                    <i class="fas fa-check-circle flex-shrink-0 w-5 h-5 mr-3 text-green-600"></i>
                    <div class="text-sm font-bold">{{ session('success') }}</div>
                    <button @click="show = false" class="ml-auto text-green-500 hover:text-green-700"><i class="fas fa-times"></i></button>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" class="flex items-center p-4 mb-4 text-red-800 rounded-xl bg-red-50 border border-red-100 shadow-sm relative">
                    <i class="fas fa-map-marker-slash flex-shrink-0 w-5 h-5 mr-3 text-red-600"></i>
                    <div class="text-sm font-bold">{{ session('error') }}</div>
                    <button @click="show = false" class="ml-auto text-red-500 hover:text-red-700"><i class="fas fa-times"></i></button>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-8">
                        <div class="p-5 border-b border-gray-50 bg-teal-50/50">
                            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                                <i class="fas fa-pen-nib text-teal-600"></i> Tulis Jurnal
                            </h3>
                        </div>
                        
                        <div class="p-6">
                            <form action="{{ route('peserta.logbook.store') }}" method="POST" enctype="multipart/form-data" id="logbookForm">
                                @csrf
                                <input type="hidden" name="latitude" id="lat">
                                <input type="hidden" name="longitude" id="lng">

                                <div class="mb-5">
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Deskripsi Kegiatan</label>
                                    <textarea name="kegiatan" rows="5" class="w-full rounded-xl border-gray-200 focus:border-teal-500 focus:ring-teal-500 text-sm shadow-sm" placeholder="Apa yang Anda kerjakan hari ini?" required></textarea>
                                </div>

                                <div class="mb-5">
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Dokumentasi (Foto)</label>
                                    <div class="flex items-center justify-center w-full">
                                        <label for="foto" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition group">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <i class="fas fa-camera text-2xl text-gray-400 group-hover:text-teal-500 mb-2 transition"></i>
                                                <p class="text-xs text-gray-500"><span class="font-bold">Klik upload</span> foto kegiatan</p>
                                            </div>
                                            <input id="foto" name="foto" type="file" class="hidden" accept="image/*" />
                                        </label>
                                    </div>
                                    <p id="file-name" class="text-xs text-teal-600 mt-2 font-bold hidden text-center"></p>
                                </div>

                                <div class="mb-6 bg-gray-50 p-3 rounded-xl border border-gray-100">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Verifikasi Lokasi</p>
                                    <div id="loc-status" class="flex items-center gap-2 text-xs font-bold text-orange-500">
                                        <i class="fas fa-spinner fa-spin"></i> Mendapatkan lokasi...
                                    </div>
                                    <div id="coords-display" class="text-[10px] text-gray-400 mt-1 hidden font-mono">
                                        Lat: <span id="show-lat"></span>, Lng: <span id="show-lng"></span>
                                    </div>
                                </div>

                                <button type="submit" id="btn-submit" disabled 
                                    class="w-full bg-teal-600 text-white py-3 rounded-xl font-bold shadow-lg hover:bg-teal-700 transition transform active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                                    <i class="fas fa-save"></i> Simpan Laporan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                            <h3 class="font-bold text-gray-800">Riwayat Aktivitas</h3>
                            <span class="text-xs font-bold bg-gray-100 text-gray-600 px-3 py-1 rounded-full">Total: {{ $logs->count() }}</span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-100">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal & Foto</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Detail Kegiatan</th>
                                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Status Validasi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-50">
                                    @forelse($logs as $log)
                                    <tr class="hover:bg-gray-50 transition group">
                                        <td class="px-6 py-4 align-top w-48">
                                            <div class="flex flex-col gap-2">
                                                <span class="text-sm font-bold text-gray-800">
                                                    {{ \Carbon\Carbon::parse($log->tanggal)->translatedFormat('d M Y') }}
                                                </span>
                                                @if($log->bukti_foto_path)
                                                    <div class="w-full h-24 rounded-lg overflow-hidden border border-gray-200 cursor-pointer shadow-sm relative group-hover:shadow-md transition" onclick="window.open('{{ Storage::url($log->bukti_foto_path) }}')">
                                                        <img src="{{ Storage::url($log->bukti_foto_path) }}" class="w-full h-full object-cover">
                                                        <div class="absolute inset-0 bg-black/20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                                            <i class="fas fa-search text-white"></i>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="w-full h-24 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 text-xs border border-dashed border-gray-300">
                                                        No Image
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 align-top">
                                            <p class="text-sm text-gray-700 whitespace-pre-line leading-relaxed">{{ $log->kegiatan }}</p>
                                            
                                            @if($log->komentar_mentor)
                                                <div class="mt-3 bg-red-50 border border-red-100 p-3 rounded-lg flex gap-2 items-start">
                                                    <i class="fas fa-comment-alt text-red-400 mt-1 text-xs"></i>
                                                    <div>
                                                        <span class="block text-xs font-bold text-red-700 uppercase">Catatan Mentor</span>
                                                        <p class="text-xs text-red-600">{{ $log->komentar_mentor }}</p>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 align-top text-right">
                                            @php
                                                $badges = [
                                                    'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => 'fa-clock'],
                                                    'disetujui' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'fa-check-circle'],
                                                    'revisi' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => 'fa-exclamation-circle'],
                                                ];
                                                $status = $badges[$log->status_validasi] ?? $badges['pending'];
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $status['bg'] }} {{ $status['text'] }}">
                                                <i class="fas {{ $status['icon'] }} mr-1.5"></i> {{ ucfirst($log->status_validasi) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-12 text-center text-gray-500">
                                            <div class="flex flex-col items-center">
                                                <i class="fas fa-clipboard-list text-4xl text-gray-300 mb-3"></i>
                                                <p class="font-medium">Belum ada catatan aktivitas.</p>
                                                <p class="text-xs mt-1">Mulai isi jurnal kegiatan harian Anda.</p>
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

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const statusDiv = document.getElementById("loc-status");
        const btnSubmit = document.getElementById("btn-submit");
        const coordsDisplay = document.getElementById("coords-display");
        const showLat = document.getElementById("show-lat");
        const showLng = document.getElementById("show-lng");
        const fileInput = document.getElementById('foto');
        const fileNameDisplay = document.getElementById('file-name');

        // 1. File Upload Preview Name
        if(fileInput) {
            fileInput.addEventListener('change', function(){
                if(this.files && this.files.length > 0){
                    fileNameDisplay.textContent = 'File: ' + this.files[0].name;
                    fileNameDisplay.classList.remove('hidden');
                }
            });
        }

        // 2. Fungsi Utama Request Lokasi
        function requestLocation() {
            if (navigator.geolocation) {
                // Opsi Geolocation yang lebih "ringan" agar tidak timeout
                const options = {
                    enableHighAccuracy: false, // Ubah ke false agar bisa pakai Wi-Fi/Seluler (lebih cepat)
                    timeout: 30000,            // Naikkan ke 30 detik
                    maximumAge: 300000         // Boleh pakai cache lokasi 5 menit terakhir
                };
                
                navigator.geolocation.getCurrentPosition(successLocation, errorLocation, options);
            } else {
                showErrorMsg("Browser tidak mendukung Geolocation.");
            }
        }

        // 3. Callback Sukses
        function successLocation(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            // Set value hidden input
            document.getElementById("lat").value = lat;
            document.getElementById("lng").value = lng;

            // Update UI
            statusDiv.innerHTML = '<span class="text-green-600 flex items-center font-bold"><i class="fas fa-check-circle mr-1.5"></i> Lokasi Terkunci</span>';
            showLat.innerText = lat.toFixed(6);
            showLng.innerText = lng.toFixed(6);
            coordsDisplay.classList.remove("hidden");
            
            // Enable Button
            btnSubmit.disabled = false;
            btnSubmit.classList.remove("opacity-50", "cursor-not-allowed");
        }

        // 4. Callback Error
        function errorLocation(error) {
            let msg = "Gagal mengambil lokasi.";
            let retryBtn = '';

            switch(error.code) {
                case error.PERMISSION_DENIED:
                    msg = "Izin lokasi ditolak. Harap izinkan akses lokasi di browser."; 
                    break;
                case error.POSITION_UNAVAILABLE:
                    msg = "Sinyal lokasi tidak ditemukan. Pastikan GPS aktif."; 
                    break;
                case error.TIMEOUT:
                    msg = "Waktu habis. Sinyal lemah.";
                    // Tambahkan tombol coba lagi khusus timeout
                    retryBtn = `<button type="button" onclick="window.location.reload()" class="ml-2 underline text-blue-600 hover:text-blue-800">Refresh</button>`;
                    break;
            }
            showErrorMsg(msg, retryBtn);
        }

        function showErrorMsg(msg, extraHtml = '') {
            statusDiv.innerHTML = `<div class="flex flex-col"><span class="text-red-500 flex items-center font-bold text-xs"><i class="fas fa-exclamation-triangle mr-1.5"></i> ${msg}</span>${extraHtml}</div>`;
            btnSubmit.disabled = true;
        }

        // Jalankan saat load
        requestLocation();
    });
</script>
</x-app-layout>