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
                        <h1 class="text-3xl font-bold mb-2 text-gray-800">Selamat Datang, <strong>Admin {{ $skpd->nama_dinas }}</h1>
                        <p class="text-gray-600 text-sm md:text-base max-w-xl">
                            Pantau aktivitas peserta magang Anda di sini.
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


            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <a href="{{ route('dinas.pelamar') }}" class="group">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-yellow-400 p-6 group-hover:bg-yellow-50 transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Pelamar Masuk</p>
                                <p class="text-3xl font-bold text-gray-800">{{ $widget['pending'] }}</p>
                            </div>
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                                <i class="fas fa-inbox text-2xl"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mt-2">Menunggu konfirmasi Anda</p>
                    </div>
                </a>

                <a href="{{ route('dinas.peserta.index') }}" class="group">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-teal-500 p-6 group-hover:bg-teal-50 transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Sedang Magang</p>
                                <p class="text-3xl font-bold text-gray-800">{{ $widget['active'] }}</p>
                            </div>
                            <div class="p-3 rounded-full bg-teal-100 text-teal-600">
                                <i class="fas fa-users text-2xl"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mt-2">Peserta aktif saat ini</p>
                    </div>
                </a>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-blue-500 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Lulus / Selesai</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $widget['completed'] }}</p>
                        </div>
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                            <i class="fas fa-check-circle text-2xl"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">Total alumni magang</p>
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

            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 h-full">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-university mr-2 text-indigo-500"></i>
                        Asal Peserta Terbanyak
                    </h3>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-600">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3">#</th>
                                    <th class="px-4 py-3">Instansi Pendidikan</th>
                                    <th class="px-4 py-3 text-right">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topInstansi as $index => $instansi)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3 font-bold">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 font-medium">{{ $instansi->asal_instansi }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <span class="bg-indigo-100 text-indigo-800 text-xs font-bold px-2.5 py-0.5 rounded-full">
                                            {{ $instansi->total_peserta }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-6 text-center text-gray-400 italic">
                                        Belum ada data peserta lulus/aktif.
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