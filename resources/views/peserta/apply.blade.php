<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Lengkapi Berkas Lamaran</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="mb-6 border-b pb-4">
                    <h3 class="text-2xl font-bold text-gray-900">{{ $position->skpd->nama_dinas }}</h3>
                    <p class="text-gray-500 text-sm mt-1">Silakan lengkapi form di bawah ini untuk melamar magang.</p>
                </div>

                {{-- === TAMBAHAN: INFORMASI LOWONGAN === --}}
                <div class="bg-teal-50 border border-teal-100 rounded-lg p-5 mb-8 shadow-sm">
                    <div class="mb-4">
                        <h4 class="text-lg font-bold text-teal-800 mb-1">
                            {{ $position->judul_posisi ?? 'Posisi Magang' }}
                        </h4>
                        <div class="flex flex-wrap gap-4 text-sm mt-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-white border border-teal-200 text-teal-700 font-medium">
                                <i class="fas fa-graduation-cap mr-2"></i> {{ $position->required_major }}
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-white border border-teal-200 text-teal-700 font-medium">
                                <i class="fas fa-users mr-2"></i> Kuota: {{ $position->kuota }} Orang
                            </span>
                        </div>
                    </div>

                    <div class="text-sm text-gray-600 bg-white p-4 rounded border border-teal-100">
                        <span class="font-bold text-gray-700 block mb-1">Deskripsi Pekerjaan:</span>
                        <div class="prose prose-sm max-w-none">
                            {!! $position->deskripsi !!}
                        </div>
                    </div>
                </div>
                {{-- === AKHIR TAMBAHAN === --}}

                @if(session('error'))
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700 font-bold">Gagal Melamar</p>
                                <p class="text-sm text-red-600 mt-1">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('peserta.daftar', $position->id) }}" method="POST" enctype="multipart/form-data" id="applyForm">
                    @csrf
                    
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Surat Pengantar (PDF)</label>
                        <input type="file" name="surat" class="w-full border border-gray-300 rounded p-2 text-sm focus:ring-teal-500 focus:border-teal-500" accept=".pdf" required>
                        <p class="text-xs text-gray-500 mt-1">*Surat Permohonan Magang dari Kampus/Sekolah</p>
                        @error('surat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-2">
                        <div>
                            <x-input-label for="tanggal_mulai" :value="__('Rencana Tanggal Mulai')" />
                            <input type="date" id="tanggal_mulai" name="tanggal_mulai" 
                                value="{{ old('tanggal_mulai') }}" 
                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500" 
                                min="{{ date('Y-m-d') }}" required>
                        </div>

                        <div>
                            <x-input-label for="tanggal_selesai" :value="__('Rencana Tanggal Selesai')" />
                            <input type="date" id="tanggal_selesai" name="tanggal_selesai" 
                                value="{{ old('tanggal_selesai') }}"
                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500" 
                                min="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div id="availability-result" class="mb-6 hidden rounded-md p-4 border text-sm font-bold flex items-center gap-2">
                    </div>

                    <div class="flex justify-end space-x-2 pt-4 border-t">
                        <a href="{{ route('home') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 transition">Batal</a>
                        <button type="submit" id="submitBtn" class="px-4 py-2 bg-teal-600 text-white rounded hover:bg-teal-700 font-bold shadow disabled:opacity-50 disabled:cursor-not-allowed transition">
                            <i class="fas fa-paper-plane mr-1"></i> Kirim Lamaran
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startInput = document.getElementById('tanggal_mulai');
            const endInput = document.getElementById('tanggal_selesai');
            const resultDiv = document.getElementById('availability-result');
            const submitBtn = document.getElementById('submitBtn');
            const form = document.getElementById('applyForm');
            const positionId = "{{ $position->id }}"; // ID Posisi dari Controller

            // Fungsi Validasi Tanggal
            function validateDates() {
                const startDate = startInput.value;
                const endDate = endInput.value;

                // Pastikan Tanggal Selesai minimal sama dengan Tanggal Mulai
                if(startDate) {
                    endInput.min = startDate;
                }

                // Hanya cek jika kedua tanggal sudah diisi
                if (startDate && endDate) {
                    if (new Date(endDate) < new Date(startDate)) {
                        showResult('error', 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.', 'text-red-600 bg-red-50 border-red-200');
                        submitBtn.disabled = true;
                        return;
                    }
                    
                    // Panggil Fungsi Cek ke Server
                    checkAvailability(startDate, endDate);
                } else {
                    hideResult();
                }
            }

            // Fungsi AJAX Fetch ke Server
            function checkAvailability(start, end) {
                // Tampilkan status loading
                showResult('loading', 'Mengecek ketersediaan kuota...', 'text-gray-600 bg-gray-50 border-gray-200');
                submitBtn.disabled = true;

                fetch(`/magang/check-availability/${positionId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ start: start, end: end })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'available') {
                        showResult('success', data.message, data.class);
                        submitBtn.disabled = false; // Aktifkan tombol kirim
                    } else {
                        showResult('error', data.message, data.class);
                        submitBtn.disabled = true; // Matikan tombol kirim
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showResult('error', 'Terjadi kesalahan saat mengecek kuota.', 'text-red-600 bg-red-50 border-red-200');
                });
            }

            // Helper untuk update tampilan alert
            function showResult(type, message, cssClass) {
                resultDiv.className = `mb-6 rounded-md p-4 border text-sm font-bold flex items-center gap-2 ${cssClass}`;
                
                let icon = '';
                if(type === 'loading') icon = '<i class="fas fa-spinner fa-spin"></i>';
                else if(type === 'success') icon = '<i class="fas fa-check-circle"></i>';
                else icon = '<i class="fas fa-times-circle"></i>';

                resultDiv.innerHTML = `${icon} <span>${message}</span>`;
                resultDiv.classList.remove('hidden');
            }

            function hideResult() {
                resultDiv.classList.add('hidden');
            }

            // Pasang Event Listener
            startInput.addEventListener('change', validateDates);
            endInput.addEventListener('change', validateDates);
        });
    </script>
</x-app-layout>