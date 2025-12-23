<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Eksekutif Kepala Dinas
        </h2>
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-teal-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Peserta (Aktif/Alumni)</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $totalPeserta }}</p>
                        </div>
                        <div class="p-3 bg-teal-50 rounded-full text-teal-600">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Posisi Lowongan Dibuka</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $totalLowongan }}</p>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-full text-blue-600">
                            <i class="fas fa-briefcase text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-yellow-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Rata-rata Nilai Kinerja</p>
                            <p class="text-3xl font-bold text-gray-800">
                                {{ number_format($rataRataNilai, 1) }}<span class="text-sm text-gray-400">/100</span>
                            </p>
                        </div>
                        <div class="p-3 bg-yellow-50 rounded-full text-yellow-600">
                            <i class="fas fa-star text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                
                <div class="bg-white p-6 rounded-lg shadow-sm lg:col-span-2">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Tren Peminat Magang Tahun Ini</h3>
                    <div class="relative h-64">
                        <canvas id="chartTren"></canvas>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Sebaran Asal Instansi</h3>
                    <div class="relative h-64 flex justify-center">
                        <canvas id="chartInstansi"></canvas>
                    </div>
                </div>
            </div>

            <h3 class="text-lg font-bold text-gray-800 mb-4 px-1">Laporan Lengkap</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <a href="{{ route('kepala_dinas.laporan.peserta') }}" class="flex items-center p-6 bg-white border border-gray-200 rounded-lg hover:shadow-md transition hover:border-teal-500 group">
                    <div class="p-4 bg-teal-100 text-teal-600 rounded-full mr-5 group-hover:bg-teal-600 group-hover:text-white transition">
                        <i class="fas fa-file-alt text-2xl"></i>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-gray-800 group-hover:text-teal-700">Laporan Eksekutif Peserta</h4>
                        <p class="text-gray-500 text-sm mt-1">Data lengkap peserta, status penerimaan, dan transkrip nilai akhir.</p>
                    </div>
                    <i class="fas fa-arrow-right ml-auto text-gray-300 group-hover:text-teal-500"></i>
                </a>

                <a href="{{ route('kepala_dinas.laporan.statistik') }}" class="flex items-center p-6 bg-white border border-gray-200 rounded-lg hover:shadow-md transition hover:border-blue-500 group">
                    <div class="p-4 bg-blue-100 text-blue-600 rounded-full mr-5 group-hover:bg-blue-600 group-hover:text-white transition">
                        <i class="fas fa-chart-pie text-2xl"></i>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-gray-800 group-hover:text-blue-700">Laporan Statistik & Demografi</h4>
                        <p class="text-gray-500 text-sm mt-1">Analisis tren peminat, ketersediaan kuota, dan sebaran asal instansi.</p>
                    </div>
                    <i class="fas fa-arrow-right ml-auto text-gray-300 group-hover:text-blue-500"></i>
                </a>

            </div>
        </div>
    </div>

    <script>
        // 1. Konfigurasi Chart Tren (Bar Chart)
        const ctxTren = document.getElementById('chartTren').getContext('2d');
        new Chart(ctxTren, {
            type: 'bar',
            data: {
                labels: @json($months),
                datasets: [
                    {
                        label: 'Total Pelamar',
                        data: @json($dataPelamar),
                        backgroundColor: 'rgba(209, 213, 219, 0.5)', // Gray
                        borderColor: 'rgba(156, 163, 175, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Diterima Magang',
                        data: @json($dataDiterima),
                        backgroundColor: 'rgba(20, 184, 166, 0.8)', // Teal
                        borderColor: 'rgba(13, 148, 136, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // 2. Konfigurasi Chart Instansi (Doughnut Chart)
        const ctxInstansi = document.getElementById('chartInstansi').getContext('2d');
        new Chart(ctxInstansi, {
            type: 'doughnut',
            data: {
                labels: @json($chartInstansiLabels),
                datasets: [{
                    data: @json($chartInstansiData),
                    backgroundColor: [
                        '#0d9488', // Teal 600  
                        '#f59e0b', // Amber 500
                        '#3b82f6', // Blue 500
                        '#8b5cf6', // Violet 500
                        '#ec4899', // Pink 500
                        '#9ca3af'  // Gray 400 (Lainnya)
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12, font: { size: 10 } }
                    }
                }
            }
        });
    </script>
</x-app-layout>