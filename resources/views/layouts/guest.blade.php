<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
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
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col justify-center items-center px-4 py-8">
            <div class="mb-2">
                <a href="/">
                    <x-application-logo class="w-24 h-24 text-indigo-700" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-5 px-5 py-6 sm:px-7 sm:py-7 atelier-surface overflow-hidden rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
