<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>

        <style>
            /* Mengembalikan style list yang di-reset oleh Tailwind */
            .ck-content ul { list-style-type: disc; padding-left: 20px; }
            .ck-content ol { list-style-type: decimal; padding-left: 20px; }
            .ck-content h2 { font-size: 1.5em; font-weight: bold; margin-top: 10px; }
            .ck-content h3 { font-size: 1.25em; font-weight: bold; margin-top: 10px; }
            .ck-content p { margin-bottom: 0.5em; }
        </style>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
        <script>
        // Cari semua elemen dengan class "wysiwyg-editor" dan ubah jadi CKEditor
        const editors = document.querySelectorAll('.wysiwyg-editor');
        editors.forEach(editor => {
            ClassicEditor
                .create(editor, {
                    toolbar: [ 'undo', 'redo', '|',
                            'heading', '|',
                            'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
                            'bold', 'italic', 'underline', 'strikethrough', 'removeFormat', '|',
                            'alignment', '|',
                            'bulletedList', 'numberedList', 'todoList', '|',
                            'outdent', 'indent', '|',
                            'link', 'insertTable', 'blockQuote', 'horizontalLine', '|',
                            'findAndReplace', 'selectAll' ],
                })
                .catch(error => {
                    console.error(error);
                });
        });
    </script>
    </body>
</html>
