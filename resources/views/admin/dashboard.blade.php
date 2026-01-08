<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Super Admin Overview') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white rounded-2xl p-8 shadow-sm border-l-8 border-teal-600 mb-10">
                <h1 class="text-3xl font-bold text-gray-800">Selamat Datang, Super Admin!</h1>
                <p class="text-gray-600">Pantau statistik dari {{ $totalSkpd }} instansi di lingkungan Kota Banjarmasin.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm p-6 border-b-4 border-blue-500">
                    <p class="text-xs font-bold text-gray-400 uppercase">Total SKPD</p>
                    <h3 class="text-3xl font-extrabold text-gray-800">{{ $totalSkpd }}</h3>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border-b-4 border-green-500">
                    <p class="text-xs font-bold text-gray-400 uppercase">Total Pengguna</p>
                    <h3 class="text-3xl font-extrabold text-gray-800">{{ $totalUser }}</h3>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border-b-4 border-orange-400">
                    <p class="text-xs font-bold text-gray-400 uppercase">Periode</p>
                    <h3 class="text-3xl font-extrabold text-gray-800">{{ date('Y') }}</h3>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6 mb-8 border border-gray-100">
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Statistik Peminat Per Instansi</h3>
                    <p class="text-sm text-gray-500">Menampilkan seluruh instansi (Gunakan scroll horizontal untuk melihat semua data)</p>
                </div>
                
                <div class="relative w-full overflow-x-auto pb-4 custom-scrollbar">
                    <div style="min-width: 100px; height: 450px;">
                        <canvas id="skpdChart"
                            data-labels="{{ json_encode($chartLabels) }}"
                            data-values="{{ json_encode($chartData) }}">
                        </canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-4 bg-gray-50 font-bold text-gray-700 border-b">Instansi Terbaru</div>
                        <ul class="divide-y">
                            @foreach($recentSkpds as $dinas)
                            <li class="p-4 flex justify-between items-center text-sm">
                                <span class="font-medium">{{ $dinas->nama_dinas }}</span>
                                <span class="text-gray-400 text-xs">{{ $dinas->created_at->diffForHumans() }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 h-fit">
                    <h3 class="text-xs font-bold text-gray-400 uppercase mb-4 tracking-widest">Aksi Cepat</h3>
                    <div class="space-y-3">
                        <a href="{{ route('admin.skpd.index') }}" class="block p-3 bg-teal-50 text-teal-700 rounded-lg text-sm font-bold text-center hover:bg-teal-100">Kelola Data SKPD</a>
                        <a href="{{ route('admin.users.index') }}" class="block p-3 bg-blue-50 text-blue-700 rounded-lg text-sm font-bold text-center hover:bg-blue-100">Manajemen Pengguna</a>
                        <a href="{{ route('admin.laporan') }}" class="block p-3 bg-teal-50 text-teal-700 rounded-lg text-sm font-bold text-center hover:bg-teal-100">Laporan SKPD</a>
                        <a href="{{ route('admin.laporan.peserta_global') }}" class="block p-3 bg-blue-50 text-blue-700 rounded-lg text-sm font-bold text-center hover:bg-blue-100">Data Peserta Global</a>
                        <a href="{{ route('admin.users.logbooks') }}" class="block p-3 bg-teal-50 text-teal-700 rounded-lg text-sm font-bold text-center hover:bg-teal-100">Monitoring Logbook</a>
                        <a href="{{ route('admin.settings.index') }}" class="block p-3 bg-blue-50 text-blue-700 rounded-lg text-sm font-bold text-center hover:bg-blue-100">Pengaturan Sistem</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const canvas = document.getElementById('skpdChart');
        const labels = JSON.parse(canvas.dataset.labels);
        const dataValues = JSON.parse(canvas.dataset.values);

        new Chart(canvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Pelamar',
                    data: dataValues,
                    backgroundColor: 'rgba(13, 148, 136, 0.7)',
                    borderColor: '#0F766E',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        ticks: {
                            autoSkip: false, // Menampilkan semua 32 instansi
                            maxRotation: 90, // Rotasi vertikal agar rapi
                            minRotation: 90,
                            font: { size: 10 }
                        },
                        grid: { display: false }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (context) => ` ${context.parsed.y} Pelamar`
                        }
                    }
                }
            }
        });
    </script>
    
    <style>
        /* Desain scrollbar agar lebih modern */
        .custom-scrollbar::-webkit-scrollbar { height: 8px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #0D9488; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #0F766E; }
    </style>
</x-app-layout>