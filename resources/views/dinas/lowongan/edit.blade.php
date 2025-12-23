<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Lowongan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <form action="{{ route('dinas.lowongan.update', $loker->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <div>
                                <label class="block font-bold text-gray-700 mb-2">Kualifikasi Jurusan</label>
                                <input type="text" name="required_major" value="{{ old('required_major', $loker->required_major) }}" class="w-full border-gray-300 rounded-md shadow-sm">
                            </div>

                            <div>
                                <label class="block font-bold text-gray-700 mb-2">Kapasitas (Kuota)</label>
                                <input type="number" name="kuota" value="{{ old('kuota', $loker->kuota) }}" class="w-full border-gray-300 rounded-md shadow-sm" min="1">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block font-bold text-gray-700 mb-2">Status Lowongan</label>
                            <select name="status" class="w-full border-gray-300 rounded-md shadow-sm">
                                <option value="buka" {{ $loker->status == 'buka' ? 'selected' : '' }}>Dibuka</option>
                                <option value="tutup" {{ $loker->status == 'tutup' ? 'selected' : '' }}>Ditutup</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block font-bold text-gray-700 mb-2">Deskripsi Pekerjaan & Syarat</label>
                            <textarea id="editor" name="deskripsi" class="w-full border-gray-300 rounded-md shadow-sm">{{ old('deskripsi', $loker->deskripsi) }}</textarea>
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('dinas.lowongan.index') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-300">Batal</a>
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Update Perubahan</button>
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
                        items: ['undo', 'redo', '|', 'fontFamily', 'fontSize', '|', 'bold', 'italic', 'underline', 'fontColor', '|', 'alignment', '|', 'bulletedList', 'numberedList'],
                        shouldNotGroupWhenFull: true
                    },
                    placeholder: 'Tulis deskripsi...',
                    fontSize: { options: [ 10, 12, 14, 'default', 18, 20, 22 ] },
                    removePlugins: ['CKBox', 'CKFinder', 'EasyImage', 'RealTimeCollaborativeComments', 'RealTimeCollaborativeTrackChanges', 'RealTimeCollaborativeRevisionHistory', 'PresenceList', 'Comments', 'TrackChanges', 'TrackChangesData', 'RevisionHistory', 'Pagination', 'WProofreader', 'MathType', 'Image', 'ImageCaption', 'ImageStyle', 'ImageToolbar', 'ImageUpload', 'Table', 'TableToolbar', 'MediaEmbed']
                }).then(editor => {
                    editor.editing.view.change(writer => {
                        writer.setStyle('min-height', '300px', editor.editing.view.document.getRoot());
                    });
                }).catch(error => console.error(error));
            }
        });
    </script>
</x-app-layout>