<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800">Formulir Penilaian PKL</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between mb-6 print:hidden">
                    <a href="{{ route('mentor.dashboard') }}" class="text-gray-600 hover:text-gray-900">&larr; Kembali</a>
                </div>
                <h3 class="text-lg font-bold mb-4">Penilaian Peserta: {{ $application->user->name }}</h3>

                <form action="{{ route('mentor.simpan_nilai', $application->id) }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        @php
                            $kriteria = [
                                'nilai_sikap' => '1. Sikap / Sopan Santun',
                                'nilai_disiplin' => '2. Kedisiplinan',
                                'nilai_kesungguhan' => '3. Kesungguhan',
                                'nilai_mandiri' => '4. Kemampuan Bekerja Mandiri',
                                'nilai_kerjasama' => '5. Kemampuan Bekerja Sama',
                                'nilai_ketelitian' => '6. Ketelitian',
                                'nilai_pendapat' => '7. Kemampuan Mengemukakan Pendapat',
                                'nilai_serap_hal_baru' => '8. Kemampuan Menyerap Hal Baru',
                                'nilai_inisiatif' => '9. Inisiatif dan Kreatifitas',
                                'nilai_kepuasan' => '10. Kepuasan Pemberi Kerja Praktek',
                            ];
                        @endphp

                        @foreach($kriteria as $field => $label)
                        <div>
                            <label class="block font-medium text-sm text-gray-700 mb-1">{{ $label }}</label>
                            <input type="number" name="{{ $field }}" min="0" max="100" required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                                   placeholder="0-100">
                        </div>
                        @endforeach
                    </div>

                    <div class="mb-6">
                        <label class="block font-medium text-sm text-gray-700 mb-1">Catatan Tambahan (Opsional)</label>
                        <textarea name="catatan_mentor" rows="3" class="w-full rounded-md border-gray-300 shadow-sm"></textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-2 px-6 rounded-lg transition">
                            Simpan & Selesaikan Magang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>