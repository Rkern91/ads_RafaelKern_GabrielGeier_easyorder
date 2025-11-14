<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'EasyOrder' }}</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <!-- Scripts -->

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body class="font-sans antialiased" style="background-color: black;">

        <div>
            <!-- Conteúdo -->
            <main>
                <div class="max-w-7xl mx-auto px-4 py-6">
                    {{ $slot }}
                </div>
            </main>

            <!-- Rodapé -->
            <footer class="bg-black mt-10 py-4 shadow-inner">
                <div class="max-w-7xl mx-auto px-4 text-center text-sm text-gray-500">
                    © {{ date('Y') }} EasyOrder. Todos os direitos reservados.
                </div>
            </footer>
        </div>
    </body>
</html>
