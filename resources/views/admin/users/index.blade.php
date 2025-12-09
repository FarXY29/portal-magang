<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manajemen Pengguna (Super Admin)</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between mb-6 print:hidden">
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-900">&larr; Kembali</a>
                </div>
            <!-- Notifikasi -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Toolbar (Search & Add) -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                
                <!-- FORM PENCARIAN OTOMATIS -->
                <form method="GET" action="{{ route('admin.users.index') }}" id="searchForm" class="flex w-full md:w-auto">
                    
                    <!-- Filter Role (Auto Submit saat ganti) -->
                    <select name="role" 
                            class="border-gray-300 rounded-l-md text-sm focus:ring-teal-500 focus:border-teal-500 cursor-pointer"
                            onchange="document.getElementById('searchForm').submit()">
                        <option value="">Semua Role</option>
                        <option value="admin_kota" {{ request('role') == 'admin_kota' ? 'selected' : '' }}>Super Admin</option>
                        <option value="admin_skpd" {{ request('role') == 'admin_skpd' ? 'selected' : '' }}>Admin Dinas</option>
                        <option value="mentor" {{ request('role') == 'mentor' ? 'selected' : '' }}>Mentor</option>
                        <option value="pembimbing" {{ request('role') == 'pembimbing' ? 'selected' : '' }}>Dosen/Guru</option>
                        <option value="peserta" {{ request('role') == 'peserta' ? 'selected' : '' }}>Peserta</option>
                    </select>

                    <!-- Input Search (Auto Submit dengan delay/debounce) -->
                    <div class="relative w-full md:w-64">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Cari nama/email..." 
                               class="border-gray-300 border-l-0 text-sm focus:ring-teal-500 focus:border-teal-500 w-full rounded-r-md"
                               oninput="autoSubmitSearch()">
                        
                        <!-- Icon Loading (Opsional, muncul saat mengetik) -->
                        <div id="loadingIcon" class="absolute right-3 top-2.5 hidden">
                            <i class="fas fa-spinner fa-spin text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Tombol Cari disembunyikan karena sudah otomatis, tapi bisa dimunculkan jika perlu -->
                    <noscript>
                        <button class="bg-teal-600 text-white px-4 rounded-r-md hover:bg-teal-700 text-sm ml-2">Cari</button>
                    </noscript>
                </form>

                <a href="{{ route('admin.users.create') }}" class="bg-blue-800 text-white px-4 py-2 rounded-md shadow hover:bg-blue-900 transition text-sm font-bold w-full md:w-auto text-center">
                    <i class="fas fa-user-plus mr-2"></i> Tambah User Baru
                </a>
            </div>

            <!-- Tabel User -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama / Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role (Peran)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Detail Info</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900">{{ $user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $user->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $user->role == 'admin_kota' ? 'bg-red-100 text-red-800' : 
                                      ($user->role == 'admin_skpd' ? 'bg-blue-100 text-blue-800' : 
                                      ($user->role == 'peserta' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ strtoupper(str_replace('_', ' ', $user->role)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-600">
                                @if($user->skpd)
                                    <div class="flex items-center"><i class="fas fa-building mr-1 w-4"></i> {{ $user->skpd->nama_dinas }}</div>
                                @endif
                                @if($user->asal_instansi)
                                    <div class="flex items-center"><i class="fas fa-university mr-1 w-4"></i> {{ $user->asal_instansi }}</div>
                                @endif
                                @if($user->nik || $user->phone)
                                    <div class="flex items-center mt-1 text-gray-400">
                                        {{ $user->phone }} {{ $user->nik ? ' | NIK: '.$user->nik : '' }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm font-medium flex gap-2">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1 rounded">Edit</a>
                                
                                @if(auth()->id() != $user->id)
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus user ini secara permanen?')">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600 hover:text-red-900 bg-red-50 px-3 py-1 rounded">Hapus</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">Tidak ada data pengguna.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-4 border-t border-gray-200">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT AUTO SEARCH -->
    <script>
        let timeout = null;

        function autoSubmitSearch() {
            // Tampilkan icon loading jika ada
            const loading = document.getElementById('loadingIcon');
            if(loading) loading.classList.remove('hidden');

            // Hapus timeout sebelumnya (reset timer jika user masih mengetik)
            clearTimeout(timeout);

            // Set timer baru: Form dikirim 1 detik setelah berhenti mengetik
            timeout = setTimeout(function () {
                document.getElementById('searchForm').submit();
            }, 1000);
        }
    </script>
</x-app-layout>