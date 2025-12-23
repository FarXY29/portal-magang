<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Dinas
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- 1. WELCOME BANNER (Spesifik Dinas) -->
            <div class="relative bg-white rounded-2xl p-8 shadow-sm border-l-8 border-teal-600 mb-10 overflow-hidden">
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold mb-2 text-gray-800">Halo, Admin {{ Auth::user()->skpd->nama_dinas ?? 'Dinas' }}!</h1>
                        <p class="text-gray-600 text-sm md:text-base max-w-xl">
                            Kelola lowongan, seleksi pelamar, dan pantau kegiatan peserta magang di unit kerja Anda dari sini.
                        </p>
                    </div>
                    <div class="mt-6 md:mt-0 bg-teal-50 p-4 rounded-lg border border-teal-100 text-center min-w-[150px]">
                        <p class="text-xs font-bold uppercase tracking-wider text-teal-600 mb-1">Status Akun</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                                <circle cx="4" cy="4" r="3" />
                            </svg>
                            Aktif
                        </span>
                    </div>
                </div>
            </div>

            <!-- 2. STATISTIC CARDS -->
            <!-- Kita hitung data real-time menggunakan PHP sederhana di view (idealmya di controller, tapi ini shortcut agar tidak mengubah controller lagi) -->
            @php
                $skpdId = Auth::user()->skpd_id;
                // Hitung Pelamar Pending
                $pelamarMasuk = \App\Models\Application::whereHas('position', function($q) use ($skpdId) {
                    $q->where('skpd_id', $skpdId);
                })->where('status', 'pending')->count();

                // Hitung Peserta Aktif
                $pesertaAktif = \App\Models\Application::whereHas('position', function($q) use ($skpdId) {
                    $q->where('skpd_id', $skpdId);
                })->where('status', 'diterima')->count();

                // Hitung Lowongan Buka
                $lowonganBuka = \App\Models\InternshipPosition::where('skpd_id', $skpdId)->where('status', 'buka')->count();
                
                // Data Grafik (Posisi Terpopuler di Dinas Ini)
                $popularPositions = \App\Models\InternshipPosition::where('skpd_id', $skpdId)
                    ->withCount('applications')
                    ->orderBy('applications_count', 'desc')
                    ->take(5)
                    ->get();
                
                $chartLabels = $popularPositions->pluck('judul_posisi')->toArray();
                $chartData = $popularPositions->pluck('applications_count')->toArray();
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Card 1: Pelamar Masuk -->
                <div class="bg-white rounded-xl shadow-sm p-6 border-b-4 border-yellow-500 flex items-center justify-between hover:-translate-y-1 transition duration-300">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Pelamar Masuk</p>
                        <h3 class="text-3xl font-extrabold text-gray-800">{{ $pelamarMasuk }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-yellow-50 flex items-center justify-center text-yellow-600 text-xl shadow-inner">
                        <i class="fas fa-inbox"></i>
                    </div>
                </div>

                <!-- Card 2: Peserta Aktif -->
                <div class="bg-white rounded-xl shadow-sm p-6 border-b-4 border-green-500 flex items-center justify-between hover:-translate-y-1 transition duration-300">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Peserta Magang Aktif</p>
                        <h3 class="text-3xl font-extrabold text-gray-800">{{ $pesertaAktif }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-600 text-xl shadow-inner">
                        <i class="fas fa-user-check"></i>
                    </div>
                </div>

                <!-- Card 3: Lowongan Dibuka -->
                <div class="bg-white rounded-xl shadow-sm p-6 border-b-4 border-blue-500 flex items-center justify-between hover:-translate-y-1 transition duration-300">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Lowongan Dibuka</p>
                        <h3 class="text-3xl font-extrabold text-gray-800">{{ $lowonganBuka }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 text-xl shadow-inner">
                        <i class="fas fa-briefcase"></i>
                    </div>
                </div>
            </div>

            <!-- 3. MAIN CONTENT GRID -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- KOLOM KIRI (Lebar): GRAFIK -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm p-6 h-full border border-gray-100">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Statistik Peminat</h3>
                                <p class="text-sm text-gray-500">Jumlah pelamar per posisi magang</p>
                            </div>
                        </div>
                        
                        <div class="relative h-80 w-full">
                            <canvas id="dinasChart"
                                data-labels="{{ json_encode($chartLabels) }}"
                                data-values="{{ json_encode($chartData) }}">
                            </canvas>
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN (Sempit): MENU -->
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Menu Kelola</h3>
                    <div class="space-y-3">
                        
                        <!-- Menu 1: Pelamar -->
                        <a href="{{ route('dinas.pelamar') }}" class="flex items-center p-3 rounded-xl bg-gray-50 hover:bg-yellow-50 border border-transparent hover:border-yellow-200 transition group">
                            <div class="w-10 h-10 rounded-lg bg-white shadow-sm flex items-center justify-center text-yellow-600 group-hover:bg-yellow-600 group-hover:text-white transition">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-bold text-gray-800 group-hover:text-yellow-700">Seleksi Pelamar</p>
                                <p class="text-xs text-gray-500">Terima atau Tolak</p>
                            </div>
                            <i class="fas fa-chevron-right ml-auto text-gray-300 group-hover:text-yellow-400 text-xs"></i>
                        </a>

                        <!-- Menu 2: Lowongan -->
                        <a href="{{ route('dinas.lowongan.index') }}" class="flex items-center p-3 rounded-xl bg-gray-50 hover:bg-blue-50 border border-transparent hover:border-blue-200 transition group">
                            <div class="w-10 h-10 rounded-lg bg-white shadow-sm flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition">
                                <i class="fas fa-briefcase"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-bold text-gray-800 group-hover:text-blue-700">Kelola Lowongan</p>
                                <p class="text-xs text-gray-500">Tambah/Edit Posisi</p>
                            </div>
                            <i class="fas fa-chevron-right ml-auto text-gray-300 group-hover:text-blue-400 text-xs"></i>
                        </a>

                        <!-- Menu 3: Monitoring -->
                        <a href="{{ route('dinas.peserta.index') }}" class="flex items-center p-3 rounded-xl bg-gray-50 hover:bg-green-50 border border-transparent hover:border-green-200 transition group">
                            <div class="w-10 h-10 rounded-lg bg-white shadow-sm flex items-center justify-center text-green-600 group-hover:bg-green-600 group-hover:text-white transition">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-bold text-gray-800 group-hover:text-green-700">Monitoring Peserta</p>
                                <p class="text-xs text-gray-500">Logbook & Kelulusan</p>
                            </div>
                            <i class="fas fa-chevron-right ml-auto text-gray-300 group-hover:text-green-400 text-xs"></i>
                        </a>

                        <!-- Menu 4: Mentor -->
                        <a href="{{ route('dinas.mentors.index') }}" class="flex items-center p-3 rounded-xl bg-gray-50 hover:bg-purple-50 border border-transparent hover:border-purple-200 transition group">
                            <div class="w-10 h-10 rounded-lg bg-white shadow-sm flex items-center justify-center text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-bold text-gray-800 group-hover:text-purple-700">Data Mentor</p>
                                <p class="text-xs text-gray-500">Kelola Pembimbing</p>
                            </div>
                            <i class="fas fa-chevron-right ml-auto text-gray-300 group-hover:text-purple-400 text-xs"></i>
                        </a>

                        <!-- Menu 5: Laporan Rekap -->
                        <a href="{{ route('dinas.laporan.rekap') }}" class="flex items-center p-3 rounded-xl bg-gray-50 hover:bg-gray-100 border border-transparent hover:border-gray-300 transition group">
                            <div class="w-10 h-10 rounded-lg bg-white shadow-sm flex items-center justify-center text-gray-600 group-hover:bg-gray-800 group-hover:text-white transition">
                                <i class="fas fa-print"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-bold text-gray-800 group-hover:text-gray-900">Cetak Rekap</p>
                                <p class="text-xs text-gray-500">Laporan Akhir</p>
                            </div>
                            <i class="fas fa-chevron-right ml-auto text-gray-300 group-hover:text-gray-400 text-xs"></i>
                        </a>

                        <!-- Menu 6: Settings -->
                        <a href="{{ route('dinas.settings') }}" class="flex items-center p-3 rounded-xl bg-gray-50 hover:bg-gray-100 border border-transparent hover:border-gray-300 transition group">
                            <div class="w-10 h-10 rounded-lg bg-white shadow-sm flex items-center justify-center text-gray-600 group-hover:bg-gray-800 group-hover:text-white transition">
                                <i class="fas fa-cog"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-bold text-gray-800 group-hover:text-gray-900">Pengaturan</p>
                                <p class="text-xs text-gray-500">Kelola Pengaturan</p>
                            </div>
                            <i class="fas fa-chevron-right ml-auto text-gray-300 group-hover:text-gray-400 text-xs"></i>
                        </a>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- SCRIPT CHART.JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const canvas = document.getElementById('dinasChart');
        const ctx = canvas.getContext('2d');
        
        const labels = JSON.parse(canvas.dataset.labels);
        const dataValues = JSON.parse(canvas.dataset.values);

        // Gradient Warna untuk Grafik
        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.8)'); // Biru
        gradient.addColorStop(1, 'rgba(59, 130, 246, 0.1)'); 

        const dinasChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Pelamar',
                    data: dataValues,
                    backgroundColor: gradient,
                    borderColor: '#2563EB',
                    borderWidth: 1,
                    borderRadius: 5,
                    barThickness: 'flex',
                    maxBarThickness: 50
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [5, 5] },
                        ticks: { stepSize: 1 }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    </script>
</x-app-layout>