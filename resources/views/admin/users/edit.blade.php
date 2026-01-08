<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Pengguna: {{ $user->name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ $user->name }}" class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Email Login</label>
                        <input type="email" name="email" value="{{ $user->email }}" class="w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Password Baru (Opsional)</label>
                        <input type="password" name="password" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Isi hanya jika ingin mengganti password">
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Role (Peran)</label>
                        <select name="role" id="roleSelect" class="w-full border-gray-300 rounded-md shadow-sm" onchange="toggleFields()">
                            <option value="peserta" {{ $user->role == 'peserta' ? 'selected' : '' }}>Peserta Magang</option>
                            <option value="pembimbing" {{ $user->role == 'pembimbing' ? 'selected' : '' }}>Dosen / Guru Pembimbing</option>
                            <option value="mentor" {{ $user->role == 'mentor' ? 'selected' : '' }}>Pembimbing Lapangan (Pegawai)</option>
                            <option value="admin_skpd" {{ $user->role == 'admin_skpd' ? 'selected' : '' }}>Admin Instansi</option>
                            <option value="admin_kota" {{ $user->role == 'admin_kota' ? 'selected' : '' }}>Super Admin</option>
                        </select>
                    </div>

                    <div id="skpdField" class="mb-4 {{ in_array($user->role, ['admin_skpd', 'mentor']) ? '' : 'hidden' }} p-3 bg-blue-50 rounded border border-blue-100">
                        <label class="block text-blue-800 text-xs font-bold mb-2 uppercase">Asal Instansi</label>
                        <select name="skpd_id" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                            <option value="">-- Pilih Instansi --</option>
                            @foreach($skpds as $skpd)
                                <option value="{{ $skpd->id }}" {{ $user->skpd_id == $skpd->id ? 'selected' : '' }}>{{ $skpd->nama_dinas }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="instansiField" class="mb-4 {{ in_array($user->role, ['peserta', 'pembimbing']) ? '' : 'hidden' }} p-3 bg-green-50 rounded border border-green-100">
                        <label class="block text-green-800 text-xs font-bold mb-2 uppercase">Asal Sekolah/Kampus</label>
                        <input type="text" name="asal_instansi" value="{{ $user->asal_instansi }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                    </div>

                    <div class="flex justify-end space-x-2 mt-6">
                        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-blue-800 text-white rounded hover:bg-blue-900 font-bold shadow">
                            Update User
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        function toggleFields() {
            const role = document.getElementById('roleSelect').value;
            const skpdField = document.getElementById('skpdField');
            const instansiField = document.getElementById('instansiField');

            if (role === 'admin_skpd' || role === 'mentor') {
                skpdField.classList.remove('hidden');
                instansiField.classList.add('hidden');
            } else if (role === 'pembimbing' || role === 'peserta') {
                skpdField.classList.add('hidden');
                instansiField.classList.remove('hidden');
            } else {
                skpdField.classList.add('hidden');
                instansiField.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>