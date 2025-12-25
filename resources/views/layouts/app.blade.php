<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Portal Magang') }}</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .ck-content ul { list-style-type: disc; padding-left: 20px; }
        .ck-content ol { list-style-type: decimal; padding-left: 20px; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex">
        <aside class="w-64 flex-shrink-0">
            @include('layouts.navigation')
        </aside>

        <div class="flex-1 flex flex-col min-w-0">
            @if (isset($header))
                <header class="bg-white border-b border-gray-200 sticky top-0 z-10 shadow-sm">
                    <div class="max-w-full mx-auto py-4 px-6 sm:px-8">
                        <h2 class="font-extrabold text-xl text-gray-800 leading-tight">
                            {{ $header }}
                        </h2>
                    </div>
                </header>
            @endif

            <main class="p-6 sm:p-8">
                {{ $slot }}
            </main>
        </div>
    </div>

    <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
    <script>
        document.querySelectorAll('.wysiwyg-editor').forEach(editor => {
            ClassicEditor.create(editor).catch(err => console.error(err));
        });
    </script>
</body>
</html>