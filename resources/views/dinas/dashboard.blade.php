<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Statistik Instansi
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="relative bg-white rounded-2xl p-8 shadow-sm border-l-8 border-teal-600 mb-10 overflow-hidden">
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold mb-2 text-gray-800">Trend Peminat Magang</h1>
                        <p class="text-gray-600 text-sm md:text-base max-w-xl">
                            Pantau statistik jumlah pelamar yang masuk ke {{ Auth::user()->skpd->nama_dinas ?? 'Dinas' }} berdasarkan periode waktu.
                        </p>
                    </div>
                </div>
            </div>

            @php
                $skpdId = Auth::user()->skpd_id;
                
                // Menyiapkan data 12 bulan terakhir (Januari - Desember atau mundur dari bulan sekarang)
                $chartLabels = [];
                $chartData = [];
                $growthData = [];
                
                for ($i = 11; $i >= 0; $i--) {
                    $date = now()->subMonths($i);
                    $chartLabels[] = $date->format('M Y');
                    
                    // Hitung jumlah aplikasi berdasarkan bulan dan SKPD
                    $count = \App\Models\Application::whereHas('position', function($q) use ($skpdId) {
                        $q->where('skpd_id', $skpdId);
                    })
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
                    
                    $chartData[] = $count;

                    // --- PROSES PERBANDINGAN (GROWTH ANALYSIS) ---
                    $currentMonth = end($chartData);
                    $lastMonth = prev($chartData);
                    $selisih = $currentMonth - $lastMonth;
                    
                    if ($lastMonth > 0) {
                        $persentaseGrowth = round(($selisih / $lastMonth) * 100, 1);
                        $statusGrowth = $persentaseGrowth >= 0 ? 'Meningkat' : 'Menurun';
                    } else {
                        $persentaseGrowth = $currentMonth > 0 ? 100 : 0;
                        $statusGrowth = 'Baru';
                    }
                }

                // Data ringkasan (Cards)
                $pelamarMasuk = \App\Models\Application::whereHas('position', fn($q) => $q->where('skpd_id', $skpdId))
                    ->where('status', 'pending')->count();
                $pesertaAktif = \App\Models\Application::whereHas('position', fn($q) => $q->where('skpd_id', $skpdId))
                    ->where('status', 'diterima')->count();
                $lowonganBuka = \App\Models\InternshipPosition::where('skpd_id', $skpdId)->where('status', 'buka')->count();
            @endphp


            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm p-6 border-b-4 border-yellow-500 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Pelamar Pending</p>
                        <h3 class="text-3xl font-extrabold text-gray-800">{{ $pelamarMasuk }}</h3>
                    </div>
                    <div class="text-yellow-500 text-2xl"><i class="fas fa-clock"></i></div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border-b-4 border-green-500 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Peserta Aktif</p>
                        <h3 class="text-3xl font-extrabold text-gray-800">{{ $pesertaAktif }}</h3>
                    </div>
                    <div class="text-green-500 text-2xl"><i class="fas fa-user-check"></i></div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border-b-4 border-blue-500 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Lowongan Buka</p>
                        <h3 class="text-3xl font-extrabold text-gray-800">{{ $lowonganBuka }}</h3>
                    </div>
                    <div class="text-blue-500 text-2xl"><i class="fas fa-briefcase"></i></div>
                </div>
            </div>

            
            <div class="bg-teal-50 border border-teal-100 p-4 rounded-xl mb-6 flex items-center justify-between">
                <div>
                    <p class="text-teal-800 font-bold text-sm">Analisis Trend Bulan Ini:</p>
                    <p class="text-teal-600 text-xs">
                        Peminat {{ $statusGrowth }} sebesar <span class="font-bold">{{ abs($persentaseGrowth) }}%</span> dibandingkan bulan lalu.
                    </p>
                </div>
                <div class="text-2xl {{ $persentaseGrowth >= 0 ? 'text-green-500' : 'text-red-500' }}">
                    <i class="fas {{ $persentaseGrowth >= 0 ? 'fa-chart-line' : 'fa-chart-line-down' }}"></i>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Grafik Trend Peminat</h3>
                        <p class="text-sm text-gray-500">Jumlah pelamar per bulan</p>
                    </div>
                    
                    <div class="flex bg-gray-100 p-1 rounded-lg">
                        <button onclick="updateChartRange(1)" class="range-btn px-3 py-1.5 text-xs font-bold rounded-md transition" data-range="1">1 Bln</button>
                        <button onclick="updateChartRange(3)" class="range-btn px-3 py-1.5 text-xs font-bold rounded-md transition" data-range="3">3 Bln</button>
                        <button onclick="updateChartRange(6)" class="range-btn px-3 py-1.5 text-xs font-bold rounded-md transition" data-range="6">6 Bln</button>
                        <button onclick="updateChartRange(12)" class="range-btn px-3 py-1.5 text-xs font-bold rounded-md bg-white shadow-sm text-teal-600" data-range="12">1 Tahun</button>
                    </div>
                </div>
                
                <div class="relative h-96 w-full">
                    <canvas id="peminatChart"
                        data-labels="{{ json_encode($chartLabels) }}"
                        data-values="{{ json_encode($chartData) }}">
                    </canvas>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const canvas = document.getElementById('peminatChart');
        const ctx = canvas.getContext('2d');
        
        // Data asli dari PHP (12 bulan)
        const allLabels = JSON.parse(canvas.dataset.labels);
        const allValues = JSON.parse(canvas.dataset.values);

        // Inisialisasi Chart (Default 12 Bulan)
        const peminatChart = new Chart(ctx, {
            type: 'line', // Menggunakan Line Chart agar trend lebih terlihat
            data: {
                labels: allLabels,
                datasets: [{
                    label: 'Jumlah Pelamar',
                    data: allValues,
                    borderColor: '#0D9488',
                    backgroundColor: 'rgba(13, 148, 136, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointBackgroundColor: '#0D9488',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } },
                    x: { grid: { display: false } }
                }
            }
        });

        // Fungsi Filter Waktu
        function updateChartRange(range) {
            // Potong array data sesuai range dari belakang
            const newLabels = allLabels.slice(-range);
            const newValues = allValues.slice(-range);

            peminatChart.data.labels = newLabels;
            peminatChart.data.datasets[0].data = newValues;
            peminatChart.update();

            // Update UI Button Active
            document.querySelectorAll('.range-btn').forEach(btn => {
                btn.classList.remove('bg-white', 'shadow-sm', 'text-teal-600');
                if(btn.getAttribute('data-range') == range) {
                    btn.classList.add('bg-white', 'shadow-sm', 'text-teal-600');
                }
            });
        }
    </script>
</x-app-layout>