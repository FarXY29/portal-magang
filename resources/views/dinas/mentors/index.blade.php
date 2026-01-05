<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola Pembimbing Lapangan</h2>
    </x-slot>

    <div class="py-12">
        <div class="flex justify-between mb-6 print:hidden">
                    <a href="{{ route('dinas.dashboard') }}" class="text-gray-600 hover:text-gray-900">&larr; Kembali</a>
                </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col md:flex-row gap-6">
            
            <!-- Form Tambah Mentor -->
            <div class="w-full md:w-1/3">
                <div class="bg-white p-6 shadow-sm rounded-lg border border-gray-200">
                    <h3 class="font-bold mb-4 text-gray-800 border-b pb-2">Tambah Pegawai (Mentor)</h3>
                    
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4 text-sm">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('dinas.mentors.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="text-xs font-bold text-gray-600 uppercase">Nama Pegawai</label>
                            <input type="text" name="name" class="w-full border-gray-300 rounded text-sm mt-1 focus:ring-teal-500 focus:border-teal-500" required>
                        </div>
                        <div class="mb-3">
                            <label class="text-xs font-bold text-gray-600 uppercase">NIP</label>
                            <input type="text" name="nip" class="w-full border-gray-300 rounded text-sm mt-1 focus:ring-teal-500 focus:border-teal-500">
                        </div>
                        <div class="mb-3">
                            <label class="text-xs font-bold text-gray-600 uppercase">Email Login</label>
                            <input type="email" name="email" class="w-full border-gray-300 rounded text-sm mt-1 focus:ring-teal-500 focus:border-teal-500" required>
                        </div>
                        <div class="mb-4">
                            <label class="text-xs font-bold text-gray-600 uppercase">Password</label>
                            <input type="password" name="password" class="w-full border-gray-300 rounded text-sm mt-1 focus:ring-teal-500 focus:border-teal-500" required>
                        </div>
                        <button class="w-full bg-teal-600 text-white py-2 rounded font-bold hover:bg-teal-700 shadow transition transform hover:-translate-y-0.5">Simpan Akun</button>
                    </form>
                </div>
            </div>

            <!-- List Mentor -->
            <div class="w-full md:w-2/3 bg-white p-6 shadow-sm rounded-lg border border-gray-200">
                <h3 class="font-bold mb-4 text-gray-800 border-b pb-2">Daftar Pembimbing Lapangan</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left border-b bg-gray-50">
                                <th class="pb-2 pl-2 py-2 text-xs font-bold text-gray-500 uppercase">Nama / NIP</th>
                                <th class="pb-2 py-2 text-xs font-bold text-gray-500 uppercase">Email</th>
                                <th class="pb-2 py-2 text-xs font-bold text-gray-500 uppercase text-right pr-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mentors as $mentor)
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="py-3 pl-2">
                                    <div class="font-bold text-gray-900">{{ $mentor->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $mentor->nik ?? '-' }}</div>
                                </td>
                                <td class="text-sm text-gray-600">{{ $mentor->email }}</td>
                                <td class="text-right pr-2">
                                    <div class="flex justify-end gap-2">
                                        <!-- TOMBOL EDIT -->
                                        <a href="{{ route('dinas.mentors.edit', $mentor->id) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1 rounded text-xs font-bold transition">
                                            Edit
                                        </a>

                                        <!-- TOMBOL HAPUS -->
                                        <form action="{{ route('dinas.mentors.destroy', $mentor->id) }}" method="POST" onsubmit="return confirm('Hapus akun ini? Pembimbing ini akan hilang dari data peserta yang dibimbingnya.')">
                                            @csrf @method('DELETE')
                                            <button class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1 rounded text-xs font-bold transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @if($mentors->isEmpty())
                            <tr>
                                <td colspan="3" class="text-center py-8 text-gray-400 text-sm">Belum ada mentor yang ditambahkan.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>