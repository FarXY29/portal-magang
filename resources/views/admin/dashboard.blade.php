<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Overview') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- 1. WELCOME BANNER (PERBAIKAN: Teks Gelap & Background Putih) -->
            <div class="relative bg-white rounded-2xl p-8 shadow-sm border-l-8 border-teal-600 mb-10 overflow-hidden">
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold mb-2 text-gray-800">Selamat Datang, Super Admin!</h1>
                        <p class="text-gray-600 text-sm md:text-base max-w-xl">
                            Ini adalah pusat kontrol Portal Magang Pemerintah Kota Banjarmasin. Pantau statistik, kelola data SKPD, dan cetak laporan dari sini.
                        </p>
                    </div>
                    <div class="mt-6 md:mt-0 bg-teal-50 p-4 rounded-lg border border-teal-100 text-center min-w-[150px]">
                        <p class="text-xs font-bold uppercase tracking-wider text-teal-600 mb-1">Tanggal Hari Ini</p>
                        <p class="text-xl font-bold text-gray-800">{{ date('d F Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- 2. STATISTIC CARDS -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Card 1: Total SKPD -->
                <div class="bg-white rounded-xl shadow-sm p-6 border-b-4 border-blue-500 flex items-center justify-between hover:-translate-y-1 transition duration-300">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Dinas (SKPD)</p>
                        <h3 class="text-3xl font-extrabold text-gray-800">{{ $totalSkpd ?? 0 }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 text-xl shadow-inner">
                        <i class="fas fa-building"></i>
                    </div>
                </div>

                <!-- Card 2: Total User -->
                <div class="bg-white rounded-xl shadow-sm p-6 border-b-4 border-green-500 flex items-center justify-between hover:-translate-y-1 transition duration-300">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Pengguna Terdaftar</p>
                        <h3 class="text-3xl font-extrabold text-gray-800">{{ $totalUser ?? 0 }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-600 text-xl shadow-inner">
                        <i class="fas fa-users"></i>
                    </div>
                </div>

                <!-- Placeholder Card (Periode) -->
                <div class="bg-white rounded-xl shadow-sm p-6 border-b-4 border-orange-400 flex items-center justify-between opacity-90">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Periode Aktif</p>
                        <h3 class="text-xl font-bold text-gray-700">{{ date('Y') }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-orange-50 flex items-center justify-center text-orange-500 text-xl shadow-inner">
                        <i class="fas fa-calendar-alt"></i>
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
                                <h3 class="text-lg font-bold text-gray-800">Tren Peminat</h3>
                                <p class="text-sm text-gray-500">5 Dinas dengan pelamar terbanyak</p>
                            </div>
                            <button class="text-gray-400 hover:text-gray-600"><i class="fas fa-ellipsis-h"></i></button>
                        </div>
                        
                        <div class="relative h-80 w-full">
                            <!-- Grafik menggunakan data dari atribut HTML -->
                            <canvas id="skpdChart"
                                data-labels="{{ json_encode($chartLabels) }}"
                                data-values="{{ json_encode($chartData) }}">
                            </canvas>
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN (Sempit): MENU & DAFTAR TERBARU -->
                <div class="space-y-8">
                    
                    <!-- Quick Actions Menu -->
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Aksi Cepat</h3>
                        <div class="space-y-3">
                            <a href="{{ route('admin.skpd.index') }}" class="flex items-center p-3 rounded-xl bg-gray-50 hover:bg-teal-50 border border-transparent hover:border-teal-200 transition group">
                                <div class="w-10 h-10 rounded-lg bg-white shadow-sm flex items-center justify-center text-teal-600 group-hover:bg-teal-600 group-hover:text-white transition">
                                    <i class="fas fa-database"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-bold text-gray-800 group-hover:text-teal-700">Master Data SKPD</p>
                                    <p class="text-xs text-gray-500">Tambah/Edit Dinas</p>
                                </div>
                                <i class="fas fa-chevron-right ml-auto text-gray-300 group-hover:text-teal-400 text-xs"></i>
                            </a>

                            <!-- MENU BARU: MANAJEMEN USER -->
                        <a href="{{ route('admin.users.index') }}" class="flex items-center p-3 rounded-xl bg-gray-50 hover:bg-purple-50 border border-transparent hover:border-purple-200 transition group">
                            <div class="w-10 h-10 rounded-lg bg-white shadow-sm flex items-center justify-center text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition">
                                <i class="fas fa-users-cog"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-bold text-gray-800 group-hover:text-purple-700">Manajemen Pengguna</p>
                                <p class="text-xs text-gray-500">Kelola Semua Akun</p>
                            </div>
                            <i class="fas fa-chevron-right ml-auto text-gray-300 group-hover:text-purple-400 text-xs"></i>
                        </a>

                            <a href="{{ route('admin.laporan') }}" class="flex items-center p-3 rounded-xl bg-gray-50 hover:bg-blue-50 border border-transparent hover:border-blue-200 transition group">
                                <div class="w-10 h-10 rounded-lg bg-white shadow-sm flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-bold text-gray-800 group-hover:text-blue-700">Laporan & Rekap</p>
                                    <p class="text-xs text-gray-500">Cetak PDF / Excel</p>
                                </div>
                                <i class="fas fa-chevron-right ml-auto text-gray-300 group-hover:text-blue-400 text-xs"></i>
                            </a>

                            <!-- MENU BARU: MONITORING LOGBOOK -->
                        <a href="{{ route('admin.users.logbooks') }}" class="flex items-center p-3 rounded-xl bg-gray-50 hover:bg-orange-50 border border-transparent hover:border-orange-200 transition group">
                            <div class="w-10 h-10 rounded-lg bg-white shadow-sm flex items-center justify-center text-orange-600 group-hover:bg-orange-600 group-hover:text-white transition">
                                <i class="fas fa-eye"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-bold text-gray-800 group-hover:text-orange-700">Monitoring Logbook</p>
                                <p class="text-xs text-gray-500">Pantau Aktivitas Peserta</p>
                            </div>
                            <i class="fas fa-chevron-right ml-auto text-gray-300 group-hover:text-orange-400 text-xs"></i>
                        </a>

                        <!-- MENU BARU: PENGATURAN -->
                        <a href="{{ route('admin.settings.index') }}" class="flex items-center p-3 rounded-xl bg-gray-50 hover:bg-gray-100 border border-transparent hover:border-gray-300 transition group">
                            <div class="w-10 h-10 rounded-lg bg-white shadow-sm flex items-center justify-center text-gray-600 group-hover:bg-gray-800 group-hover:text-white transition">
                                <i class="fas fa-cogs"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-bold text-gray-800 group-hover:text-gray-900">Pengaturan Sistem</p>
                                <p class="text-xs text-gray-500">Kontrol Global Aplikasi</p>
                            </div>
                            <i class="fas fa-chevron-right ml-auto text-gray-300 group-hover:text-gray-400 text-xs"></i>
                        </a>
                        </div>
                    </div>

                    <!-- Recent SKPD List -->
                    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
                        <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                            <h3 class="font-bold text-sm text-gray-700">SKPD Terbaru</h3>
                            <span class="text-xs bg-white border border-gray-200 px-2 py-1 rounded text-gray-500">Last 5</span>
                        </div>
                        <ul class="divide-y divide-gray-50">
                            @foreach($recentSkpds as $dinas)
                            <li class="p-4 hover:bg-gray-50 transition">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 rounded bg-indigo-100 flex items-center justify-center text-indigo-600 text-xs font-bold">
                                            {{ substr($dinas->nama_dinas, 0, 1) }}
                                        </div>
                                    </div>
                                    <div class="ml-3 overflow-hidden">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $dinas->nama_dinas }}</p>
                                        <p class="text-xs text-gray-400 flex items-center mt-0.5">
                                            <i class="far fa-clock mr-1"></i> {{ $dinas->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- SCRIPT CHART.JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const canvas = document.getElementById('skpdChart');
        const ctx = canvas.getContext('2d');
        
        // Ambil data dari atribut HTML
        const labels = JSON.parse(canvas.dataset.labels);
        const dataValues = JSON.parse(canvas.dataset.values);

        // Membuat Gradient untuk grafik
        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(13, 148, 136, 0.8)'); // Teal Pekat
        gradient.addColorStop(1, 'rgba(13, 148, 136, 0.1)'); // Teal Pudar

        const skpdChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Pelamar',
                    data: dataValues,
                    backgroundColor: gradient,
                    borderColor: '#0F766E',
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
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#111827',
                        titleFont: { size: 13 },
                        bodyFont: { size: 13 },
                        padding: 10,
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f3f4f6',
                            borderDash: [5, 5]
                        },
                        ticks: {
                            stepSize: 1,
                            font: { size: 11 }
                        },
                        border: { display: false }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { size: 11 },
                            autoSkip: false,
                            maxRotation: 45,
                            minRotation: 0
                        },
                        border: { display: false }
                    }
                }
            }
        });
    </script>
</x-app-layout>