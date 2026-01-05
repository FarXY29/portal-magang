<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                {{ __('Analisis Nilai & Kompetensi') }}
            </h2>
            <div class="flex items-center gap-2 text-xs font-medium text-gray-500 bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100">
                <i class="fas fa-building text-teal-600"></i>
                <span>{{ Auth::user()->skpd->nama_dinas }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-6 space-y-8">
        
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 overflow-hidden relative">
            <div class="relative z-10">
                <h3 class="text-lg font-bold text-gray-800 mb-2">Evaluasi Kualitas SDM Magang</h3>
                <p class="text-sm text-gray-500 max-w-2xl">
                    Laporan ini memproses nilai rata-rata dari seluruh peserta magang di instansi Anda untuk mengukur efektivitas bimbingan dan kualitas kompetensi yang dihasilkan.
                </p>
            </div>
            <i class="fas fa-chart-line absolute -right-4 -bottom-4 text-9xl text-gray-50 opacity-50"></i>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-blue-500 hover:shadow-md transition">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Rata-rata Teknis</p>
                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 text-xs">
                        <i class="fas fa-tools"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-black text-gray-800">{{ $statsGlobal['avg_teknis'] ?? 0 }}</h3>
                <p class="text-[10px] text-gray-400 mt-2">Kesiapan kerja & keahlian bidang</p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-purple-500 hover:shadow-md transition">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Rata-rata Disiplin</p>
                    <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center text-purple-600 text-xs">
                        <i class="fas fa-user-clock"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-black text-gray-800">{{ $statsGlobal['avg_disiplin'] ?? 0 }}</h3>
                <p class="text-[10px] text-gray-400 mt-2">Ketepatan waktu & aturan kantor</p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-teal-500 hover:shadow-md transition">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Rata-rata Perilaku</p>
                    <div class="w-8 h-8 rounded-lg bg-teal-50 flex items-center justify-center text-teal-600 text-xs">
                        <i class="fas fa-heart"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-black text-gray-800">{{ $statsGlobal['avg_perilaku'] ?? 0 }}</h3>
                <p class="text-[10px] text-gray-400 mt-2">Etika, kerjasama & komunikasi</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-5 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                        <h3 class="font-bold text-gray-800 text-sm">Peringkat Peserta Terbaik</h3>
                        <span class="text-[10px] font-bold text-gray-400 uppercase">Berdasarkan Skor Akhir</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-[10px] font-bold text-gray-400 uppercase tracking-widest bg-white border-b border-gray-50">
                                    <th class="px-6 py-4">Rank</th>
                                    <th class="px-6 py-4">Nama </th>
                                    <th class="px-6 py-4 text-center">Rata-rata</th>
                                    <th class="px-6 py-4">Predikat</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($ranking as $index => $res)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="w-7 h-7 flex items-center justify-center rounded-lg {{ $index == 0 ? 'bg-yellow-400 text-white' : 'bg-gray-100 text-gray-500' }} text-xs font-black shadow-sm">
                                            {{ $index + 1 }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-bold text-gray-800 leading-tight">{{ $res['nama'] }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="text-sm font-black text-teal-600">{{ $res['rata_rata'] }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex px-2 py-1 text-[9px] font-bold rounded-md 
                                            {{ $res['predikat'] == 'Sangat Baik' ? 'bg-green-100 text-green-700' : 
                                               ($res['predikat'] == 'Baik' ? 'bg-blue-100 text-blue-700' : 'bg-orange-100 text-orange-700') }}">
                                            {{ $res['predikat'] }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-gray-400 text-sm">
                                        Belum ada data penilaian yang masuk.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                    <h3 class="font-bold text-gray-800 text-sm mb-6">Distribusi Predikat</h3>
                    <div class="space-y-4">
                        @foreach($distribusi as $label => $jumlah)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 rounded-full {{ $label == 'Sangat Baik' ? 'bg-green-500' : ($label == 'Baik' ? 'bg-blue-500' : 'bg-orange-500') }}"></div>
                                <span class="text-xs font-bold text-gray-600">{{ $label }}</span>
                            </div>
                            <span class="text-sm font-black text-gray-800">{{ $jumlah }} <small class="text-gray-400 font-normal">Orang</small></span>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-8 pt-6 border-t border-gray-50">
                        <p class="text-[10px] text-gray-400 leading-relaxed italic">
                            * Data ini diproses secara otomatis berdasarkan input nilai harian dan evaluasi akhir oleh Mentor.
                        </p>
                    </div>
                </div>

                <button onclick="window.print()" class="w-full flex items-center justify-center gap-2 bg-gray-900 text-white py-4 rounded-2xl font-bold text-sm hover:bg-gray-800 transition shadow-lg shadow-gray-200">
                    <i class="fas fa-print"></i>
                    Cetak Analisis Nilai
                </button>
            </div>
        </div>

    </div>
</x-app-layout>