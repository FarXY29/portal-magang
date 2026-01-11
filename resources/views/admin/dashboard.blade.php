<x-app-layout>
    {{-- Inject FontAwesome & Google Fonts jika belum ada di Layout Utama --}}
    @push('styles')
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @endpush

    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <i class="fas fa-tachometer-alt text-teal-600"></i>
                {{ __('Super Admin Overview') }}
            </h2>
            <div class="text-sm text-gray-500 font-medium bg-white px-4 py-1.5 rounded-full shadow-sm border border-gray-100">
                <i class="far fa-calendar-alt mr-2"></i> {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <div class="relative overflow-hidden rounded-3xl bg-teal-600 text-white shadow-xl shadow-teal-200">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-48 h-48 bg-teal-300 opacity-20 rounded-full blur-2xl"></div>
                
                <div class="relative z-10 p-8 md:p-10 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-extrabold mb-2">Selamat Datang, Super Admin!</h1>
                        <p class="text-teal-100 text-lg font-light max-w-2xl">
                            Pantau performa dan statistik dari <span class="font-bold text-white bg-white/20 px-2 py-0.5 rounded">{{ $totalSkpd }}</span> instansi di lingkungan Pemerintah Kota Banjarmasin.
                        </p>
                    </div>
                    <div class="hidden md:block">
                         <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center text-3xl">
                            👋
                         </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-lg transition duration-300">
                    <div class="flex justify-between items-start relative z-10">
                        <div>
                            <p class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-1">Total Instansi</p>
                            <h3 class="text-4xl font-black text-gray-800 group-hover:text-teal-600 transition">{{ $totalSkpd }}</h3>
                        </div>
                        <div class="w-12 h-12 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center text-xl shadow-sm">
                            <i class="fas fa-building"></i>
                        </div>
                    </div>
                    <div class="absolute -bottom-4 -right-4 text-9xl text-gray-50 opacity-50 group-hover:scale-110 transition duration-500 pointer-events-none">
                        <i class="fas fa-building"></i>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-lg transition duration-300">
                    <div class="flex justify-between items-start relative z-10">
                        <div>
                            <p class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-1">Total Pengguna</p>
                            <h3 class="text-4xl font-black text-gray-800 group-hover:text-blue-600 transition">{{ $totalUser }}</h3>
                        </div>
                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl shadow-sm">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="absolute -bottom-4 -right-4 text-9xl text-gray-50 opacity-50 group-hover:scale-110 transition duration-500 pointer-events-none">
                        <i class="fas fa-users"></i>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-lg transition duration-300">
                    <div class="flex justify-between items-start relative z-10">
                        <div>
                            <p class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-1">Periode Aktif</p>
                            <h3 class="text-4xl font-black text-gray-800 group-hover:text-orange-500 transition">{{ date('Y') }}</h3>
                        </div>
                        <div class="w-12 h-12 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center text-xl shadow-sm">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                    </div>
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-orange-50 rounded-full opacity-50"></div>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                
                <div class="xl:col-span-2 space-y-8">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                        <div class="flex justify-between items-end mb-6">
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">Statistik Peminat</h3>
                                <p class="text-sm text-gray-500 mt-1">Distribusi pelamar magang per instansi.</p>
                            </div>
                            <span class="text-xs font-semibold bg-gray-100 text-gray-500 px-2 py-1 rounded">Live Data</span>
                        </div>
                        
                        <div class="relative w-full overflow-x-auto pb-2 custom-scrollbar">
                            <div style="min-width: 800px; height: 400px;">
                                <canvas id="skpdChart"
                                    data-labels="{{ json_encode($chartLabels) }}"
                                    data-values="{{ json_encode($chartData) }}">
                                </canvas>
                            </div>
                        </div>
                        <p class="text-center text-xs text-gray-400 mt-4 italic"><i class="fas fa-arrows-alt-h mr-1"></i> Geser horizontal untuk melihat data instansi lainnya</p>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                            <h3 class="font-bold text-gray-800">Instansi Terbaru Bergabung</h3>
                            <a href="{{ route('admin.skpd.index') }}" class="text-sm text-teal-600 hover:text-teal-700 font-semibold hover:underline">Lihat Semua</a>
                        </div>
                        <div class="divide-y divide-gray-50">
                            @foreach($recentSkpds as $dinas)
                            <div class="p-4 flex items-center gap-4 hover:bg-gray-50 transition">
                                <div class="w-10 h-10 rounded-full bg-teal-100 text-teal-600 flex items-center justify-center font-bold text-sm shrink-0">
                                    {{ substr($dinas->nama_dinas, 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-gray-800 truncate">{{ $dinas->nama_dinas }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ $dinas->alamat ?? 'Alamat belum diisi' }}</p>
                                </div>
                                <span class="text-xs font-medium text-gray-400 bg-gray-100 px-2 py-1 rounded-full whitespace-nowrap">
                                    {{ $dinas->created_at->diffForHumans() }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="space-y-8">
                    
                    <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-2xl shadow-lg p-6 text-white relative overflow-hidden">
                        <div class="relative z-10">
                            <h3 class="text-lg font-bold mb-2 flex items-center gap-2">
                                <i class="fas fa-qrcode"></i> Cek Validitas Sertifikat
                            </h3>
                            <p class="text-blue-100 text-xs mb-4">Verifikasi keaslian sertifikat magang secara instan.</p>
                            
                            <form action="{{ route('certificate.search') }}" method="POST" class="space-y-3">
                                @csrf
                                <div class="relative text-gray-800">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                        <i class="fas fa-search text-xs"></i>
                                    </span>
                                    <input type="text" name="nomor_sertifikat" 
                                        placeholder="No. Seri (ex: MG-202X-...)" 
                                        class="w-full pl-9 py-2.5 rounded-lg border-0 focus:ring-2 focus:ring-blue-300 text-sm font-semibold shadow-inner placeholder-gray-400"
                                        required>
                                </div>
                                <button type="submit" class="w-full py-2.5 bg-blue-500 hover:bg-blue-400 text-white font-bold rounded-lg transition shadow-md text-sm flex items-center justify-center gap-2">
                                    Periksa Sekarang
                                </button>
                            </form>
                            
                            @if(session('error'))
                                <div class="mt-3 bg-red-500/20 border border-red-500/50 rounded p-2 text-xs font-medium text-red-100 flex items-center gap-2">
                                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                                </div>
                            @endif
                        </div>
                        <i class="fas fa-award absolute -bottom-6 -right-6 text-9xl text-white opacity-10"></i>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Aksi Cepat</h3>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <a href="{{ route('admin.skpd.index') }}" class="group flex flex-col items-center justify-center p-4 rounded-xl bg-teal-50 hover:bg-teal-600 transition duration-300 border border-teal-100/50">
                                <i class="fas fa-building text-2xl text-teal-600 group-hover:text-white mb-2 transition"></i>
                                <span class="text-xs font-bold text-teal-800 group-hover:text-white text-center">Data SKPD</span>
                            </a>
                            
                            <a href="{{ route('admin.users.index') }}" class="group flex flex-col items-center justify-center p-4 rounded-xl bg-indigo-50 hover:bg-indigo-600 transition duration-300 border border-indigo-100/50">
                                <i class="fas fa-users-cog text-2xl text-indigo-600 group-hover:text-white mb-2 transition"></i>
                                <span class="text-xs font-bold text-indigo-800 group-hover:text-white text-center">Pengguna</span>
                            </a>

                            <a href="{{ route('admin.laporan') }}" class="group flex flex-col items-center justify-center p-4 rounded-xl bg-orange-50 hover:bg-orange-500 transition duration-300 border border-orange-100/50">
                                <i class="fas fa-chart-pie text-2xl text-orange-500 group-hover:text-white mb-2 transition"></i>
                                <span class="text-xs font-bold text-orange-800 group-hover:text-white text-center">Laporan</span>
                            </a>

                            <a href="{{ route('admin.laporan.peserta_global') }}" class="group flex flex-col items-center justify-center p-4 rounded-xl bg-pink-50 hover:bg-pink-500 transition duration-300 border border-pink-100/50">
                                <i class="fas fa-globe-asia text-2xl text-pink-500 group-hover:text-white mb-2 transition"></i>
                                <span class="text-xs font-bold text-pink-800 group-hover:text-white text-center">Data Global</span>
                            </a>

                            <a href="{{ route('admin.users.logbooks') }}" class="group flex flex-col items-center justify-center p-4 rounded-xl bg-purple-50 hover:bg-purple-600 transition duration-300 border border-purple-100/50">
                                <i class="fas fa-book-open text-2xl text-purple-600 group-hover:text-white mb-2 transition"></i>
                                <span class="text-xs font-bold text-purple-800 group-hover:text-white text-center">Logbook</span>
                            </a>

                            <a href="{{ route('admin.settings.index') }}" class="group flex flex-col items-center justify-center p-4 rounded-xl bg-gray-50 hover:bg-gray-800 transition duration-300 border border-gray-200">
                                <i class="fas fa-cogs text-2xl text-gray-600 group-hover:text-white mb-2 transition"></i>
                                <span class="text-xs font-bold text-gray-700 group-hover:text-white text-center">Sistem</span>
                            </a>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('skpdChart');
            
            // Safety check
            if(!canvas) return;

            const labels = JSON.parse(canvas.dataset.labels);
            const dataValues = JSON.parse(canvas.dataset.values);

            // Setup Chart.js
            new Chart(canvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Pelamar',
                        data: dataValues,
                        backgroundColor: (context) => {
                            // Gradient Effect on Bars
                            const ctx = context.chart.ctx;
                            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                            gradient.addColorStop(0, '#0D9488'); // Teal 600
                            gradient.addColorStop(1, '#CCFBF1'); // Teal 100
                            return gradient;
                        },
                        borderColor: '#0F766E',
                        borderWidth: 0,
                        borderRadius: 6,
                        barPercentage: 0.6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#111827',
                            titleFont: { family: 'Inter', size: 13 },
                            bodyFont: { family: 'Inter', size: 12 },
                            padding: 10,
                            cornerRadius: 8,
                            callbacks: {
                                label: (context) => ` ${context.parsed.y} Orang`
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: { family: 'Inter', size: 11 },
                                autoSkip: false,
                                maxRotation: 45,
                                minRotation: 45
                            }
                        },
                        y: {
                            border: { dash: [4, 4] },
                            grid: { color: '#f3f4f6' },
                            beginAtZero: true,
                            ticks: { stepSize: 1, font: { family: 'Inter' } }
                        }
                    }
                }
            });
        });
    </script>
    
    <style>
        .custom-scrollbar::-webkit-scrollbar { height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</x-app-layout>