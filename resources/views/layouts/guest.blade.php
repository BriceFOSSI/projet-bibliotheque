<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap CSS (en ligne) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tailwind CSS (en ligne, optionnel) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js pour interactions (dropdown, menu hamburger) -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans text-gray-900 antialiased bg-gray-100 dark:bg-gray-900">

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div>
            <a href="{{ url('/') }}">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    </div>

    <!-- Bootstrap JS (en ligne) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
