<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Logbook Kegiatan Magang</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between mb-6 print:hidden">
                    <a href="{{ route('peserta.dashboard') }}" class="text-gray-600 hover:text-gray-900">&larr; Kembali</a>
                </div>
            <!-- Pesan Error/Sukses (Misal: Jarak terlalu jauh) -->
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <strong class="font-bold">Ups!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                </div>
            @endif

            <!-- Form Input Logbook -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6 border-t-4 border-teal-600">
                <h3 class="text-lg font-bold mb-4 text-gray-800">Isi Jurnal Hari Ini</h3>
                
                <form action="{{ route('peserta.logbook.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Input Hidden untuk Koordinat GPS -->
                    <input type="hidden" name="latitude" id="lat">
                    <input type="hidden" name="longitude" id="lng">

                    <!-- Kegiatan -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Kegiatan</label>
                        <textarea name="kegiatan" class="w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200" rows="3" required placeholder="Deskripsikan pekerjaan anda hari ini..."></textarea>
                    </div>

                    <!-- Bukti Foto -->
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Bukti Foto</label>
                        <input type="file" name="foto" class="w-full text-sm text-gray-500 border border-gray-300 rounded p-2 bg-gray-50">
                    </div>

                    <!-- Status GPS & Tombol -->
                    <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg">
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase mb-1">Status GPS:</p>
                            <div id="loc-status" class="text-sm font-medium text-orange-500 flex items-center">
                                <i class="fas fa-spinner fa-spin mr-2"></i> Sedang mencari lokasi...
                            </div>
                            <div id="coords-display" class="text-xs text-gray-400 mt-1 hidden">
                                Lat: <span id="show-lat">-</span>, Lng: <span id="show-lng">-</span>
                            </div>
                        </div>

                        <button type="submit" id="btn-submit" class="bg-teal-600 text-white px-6 py-2 rounded hover:bg-teal-700 disabled:opacity-50 disabled:cursor-not-allowed transition shadow-md font-bold" disabled>
                            <i class="fas fa-save mr-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabel Riwayat -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4 text-gray-800">Riwayat Logbook</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kegiatan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($logs as $log)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($log->tanggal)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ Str::limit($log->kegiatan, 60) }}
                                    @if($log->komentar_mentor)
                                        <div class="text-xs text-red-500 mt-1">Catatan: {{ $log->komentar_mentor }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $log->status_validasi == 'disetujui' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $log->status_validasi == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $log->status_validasi == 'revisi' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst($log->status_validasi) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                            @if($logs->isEmpty())
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500 text-sm">Belum ada logbook.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT GEOTAGGING -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            getLocation();
        });

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, showError, { enableHighAccuracy: true });
            } else {
                document.getElementById("loc-status").innerHTML = "<span class='text-red-500'>Browser tidak mendukung GPS.</span>";
            }
        }

        function showPosition(position) {
            // Isi input hidden
            document.getElementById("lat").value = position.coords.latitude;
            document.getElementById("lng").value = position.coords.longitude;
            
            // Tampilkan koordinat ke user (biar yakin)
            document.getElementById("show-lat").innerText = position.coords.latitude.toFixed(6);
            document.getElementById("show-lng").innerText = position.coords.longitude.toFixed(6);
            document.getElementById("coords-display").classList.remove("hidden");

            // Aktifkan tombol simpan
            let btn = document.getElementById("btn-submit");
            btn.disabled = false;
            
            // Ubah status text
            document.getElementById("loc-status").innerHTML = "<span class='text-green-600 font-bold'><i class='fas fa-map-marker-alt mr-1'></i> Lokasi Ditemukan (Akurat)</span>";
        }

        function showError(error) {
            let msg = "";
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    msg = "Izin lokasi ditolak. Harap aktifkan GPS browser.";
                    break;
                case error.POSITION_UNAVAILABLE:
                    msg = "Informasi lokasi tidak tersedia.";
                    break;
                case error.TIMEOUT:
                    msg = "Waktu permintaan lokasi habis (Timeout).";
                    break;
                case error.UNKNOWN_ERROR:
                    msg = "Terjadi kesalahan tidak diketahui.";
                    break;
            }
            document.getElementById("loc-status").innerHTML = "<span class='text-red-500 font-bold'><i class='fas fa-exclamation-triangle mr-1'></i> " + msg + "</span>";
        }
    </script>
</x-app-layout>