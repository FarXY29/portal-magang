<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Penilaian Akhir Magang
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="mb-6 border-b pb-4">
                    <h3 class="text-lg font-bold text-gray-900">{{ $app->user->name }}</h3>
                    <p class="text-sm text-gray-600">Posisi: {{ $app->position->judul_posisi }}</p>
                </div>

                <form action="{{ route('mentor.grading.store', $app->id) }}" method="POST">
                    @csrf
                    
                    <!-- Input Nilai Angka -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nilai Akhir (0-100)</label>
                        <input type="number" name="nilai_angka" value="{{ old('nilai_angka', $app->nilai_angka) }}" 
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 font-bold text-lg" 
                               min="0" max="100" required placeholder="Contoh: 85">
                        <p class="text-xs text-gray-500 mt-1">Predikat (A/B/C) akan dihitung otomatis oleh sistem.</p>
                    </div>

                    <!-- Input Catatan -->
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Catatan Evaluasi / Kesan Pesan</label>
                        <textarea name="catatan" rows="4" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required placeholder="Tuliskan evaluasi kinerja, kedisiplinan, dan saran untuk mahasiswa...">{{ old('catatan', $app->catatan_mentor) }}</textarea>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('mentor.dashboard') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md font-bold hover:bg-indigo-700 shadow-md transition">
                            <i class="fas fa-save mr-1"></i> Simpan Nilai
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>