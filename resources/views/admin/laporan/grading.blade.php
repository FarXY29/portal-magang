<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Analisis Kompetensi Peserta</h2>
    </x-slot>

    <div class="py-8 space-y-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border-t-4 border-blue-500">
                <p class="text-xs font-bold text-gray-400 uppercase">Rata-rata Teknis</p>
                <h3 class="text-3xl font-black text-gray-800">{{ $statsGlobal['avg_teknis'] }}</h3>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border-t-4 border-purple-500">
                <p class="text-xs font-bold text-gray-400 uppercase">Rata-rata Disiplin</p>
                <h3 class="text-3xl font-black text-gray-800">{{ $statsGlobal['avg_disiplin'] }}</h3>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border-t-4 border-teal-500">
                <p class="text-xs font-bold text-gray-400 uppercase">Rata-rata Perilaku</p>
                <h3 class="text-3xl font-black text-gray-800">{{ $statsGlobal['avg_perilaku'] }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-800 mb-6">Distribusi Kualitas SDM</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($distribusi as $label => $jumlah)
                <div class="text-center p-4 bg-gray-50 rounded-xl">
                    <p class="text-2xl font-black text-teal-600">{{ $jumlah }}</p>
                    <p class="text-[10px] font-bold text-gray-400 uppercase">{{ $label }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                <h3 class="font-bold text-gray-800">Peringkat 10 Besar Peserta Terbaik</h3>
                <span class="text-xs font-bold text-teal-600 bg-teal-50 px-3 py-1 rounded-full">Seluruh SKPD</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-bold text-gray-400 uppercase tracking-widest bg-gray-50">
                            <th class="px-6 py-4">Rank</th>
                            <th class="px-6 py-4">Nama Peserta</th>
                            <th class="px-6 py-4">Instansi Magang</th>
                            <th class="px-6 py-4 text-center">Skor Rata-rata</th>
                            <th class="px-6 py-4">Predikat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($ranking as $index => $res)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <span class="w-6 h-6 flex items-center justify-center rounded-full {{ $index < 3 ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-500' }} text-xs font-bold">
                                    {{ $index + 1 }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-800 text-sm">{{ $res['nama'] }}</td>
                            <td class="px-6 py-4 text-xs text-gray-500">{{ $res['instansi'] }}</td>
                            <td class="px-6 py-4 text-center font-black text-teal-600">{{ $res['rata_rata'] }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-[10px] font-bold rounded-lg 
                                    {{ $res['predikat'] == 'Sangat Baik' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ $res['predikat'] }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>