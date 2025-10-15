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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased" style="background-color: black;">
        <div class="fixed inset-0 z-0 pointer-events-none"
             style="background-image:url('{{ asset('images/bg-3840x2400.jpg') }}');
                    background-size:cover;
                    background-position:center;
                    background-attachment:fixed;
                    opacity: .2">
            <div class="w-full h-full bg-black/50"></div>
        </div>

        <div class="relative z-10 min-h-screen">
            @include('layouts.navigation')

            @isset($header)
                <header class="bg-white/80 dark:bg-black/80 backdrop-blur shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
