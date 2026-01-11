<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <i class="fas fa-plus-circle text-teal-600"></i>
                {{ __('Buat Lowongan Baru') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50 min-h-screen font-sans">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('dinas.lowongan.index') }}" class="inline-flex items-center text-sm font-bold text-gray-500 hover:text-teal-600 transition group">
                    <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center mr-2 group-hover:border-teal-500 shadow-sm">
                        <i class="fas fa-arrow-left text-xs"></i>
                    </div>
                    Kembali ke Daftar Lowongan
                </a>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                
                <div class="p-6 border-b border-gray-50 bg-gray-50/50 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-teal-50 flex items-center justify-center text-teal-600 text-xl border border-teal-100">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Detail Lowongan</h3>
                        <p class="text-xs text-gray-500">Lengkapi informasi posisi magang yang dibutuhkan.</p>
                    </div>
                </div>

                <div class="p-8">
                    <form action="{{ route('dinas.lowongan.store') }}" method="POST">
                        @csrf
                        
                        <div class="space-y-6">


                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Syarat Jurusan</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                            <i class="fas fa-graduation-cap"></i>
                                        </span>
                                        <input type="text" name="required_major" value="{{ old('required_major') }}" 
                                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border-gray-300 focus:border-teal-500 focus:ring focus:ring-teal-200 transition shadow-sm"
                                            placeholder="Contoh: Teknik Informatika, Akuntansi" required>
                                    </div>
                                    <p class="text-[10px] text-gray-400 mt-1 ml-1">*Pisahkan dengan koma jika lebih dari satu.</p>
                                    @error('required_major') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Kuota Penerimaan</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                            <i class="fas fa-users"></i>
                                        </span>
                                        <input type="number" name="kuota" value="{{ old('kuota', 1) }}" min="1" 
                                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border-gray-300 focus:border-teal-500 focus:ring focus:ring-teal-200 transition shadow-sm" required>
                                    </div>
                                    @error('kuota') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Batas Akhir Pendaftaran</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                        <i class="far fa-calendar-alt"></i>
                                    </span>
                                    <input type="date" name="batas_daftar" value="{{ old('batas_daftar') }}" 
                                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border-gray-300 focus:border-teal-500 focus:ring focus:ring-teal-200 transition shadow-sm md:w-1/2" required>
                                </div>
                                @error('batas_daftar') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Pekerjaan & Persyaratan Detail</label>
                                <div class="rounded-xl overflow-hidden border border-gray-300 shadow-sm focus-within:ring-2 focus-within:ring-teal-200 focus-within:border-teal-500 transition">
                                    <textarea id="editor" name="deskripsi" class="w-full border-0 focus:ring-0">{{ old('deskripsi') }}</textarea>
                                </div>
                                @error('deskripsi') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                            <a href="{{ route('dinas.lowongan.index') }}" class="px-6 py-2.5 rounded-xl border border-gray-300 text-gray-600 font-bold hover:bg-gray-50 transition text-sm">
                                Batal
                            </a>
                            <button type="submit" class="px-6 py-2.5 bg-teal-600 text-white rounded-xl font-bold hover:bg-teal-700 shadow-lg shadow-teal-200 transition transform active:scale-95 text-sm flex items-center gap-2">
                                <i class="fas fa-paper-plane"></i> Terbitkan Lowongan
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/super-build/ckeditor.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const editorElement = document.querySelector('#editor');
            if (editorElement) {
                CKEDITOR.ClassicEditor.create(editorElement, {
                    toolbar: {
                        items: [
                            'heading', '|',
                            'bold', 'italic', 'underline', 'bulletedList', 'numberedList', '|',
                            'outdent', 'indent', '|', 'undo', 'redo'
                        ],
                        shouldNotGroupWhenFull: true
                    },
                    placeholder: 'Jelaskan tanggung jawab, jobdesk, dan kualifikasi khusus di sini...',
                    removePlugins: [
                        'CKBox', 'CKFinder', 'EasyImage', 'RealTimeCollaborativeComments', 
                        'RealTimeCollaborativeTrackChanges', 'RealTimeCollaborativeRevisionHistory', 
                        'PresenceList', 'Comments', 'TrackChanges', 'TrackChangesData', 
                        'RevisionHistory', 'Pagination', 'WProofreader', 'MathType', 
                        'Image', 'ImageCaption', 'ImageStyle', 'ImageToolbar', 'ImageUpload', 
                        'Table', 'TableToolbar', 'MediaEmbed'
                    ]
                }).then(editor => {
                    editor.editing.view.change(writer => {
                        writer.setStyle('min-height', '200px', editor.editing.view.document.getRoot());
                    });
                }).catch(error => console.error(error));
            }
        });
    </script>
</x-app-layout>