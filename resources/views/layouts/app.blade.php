<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'GOTIM') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cairo-play:400,500,600" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://kit.fontawesome.com/f00d5bcc97.js" crossorigin="anonymous"></script>

    <!-- CDN para jQuery y Select2 -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">

    <div class="min-h-screen">
        <!-- Navegación principal -->
        @include('layouts.navigation')

        <!-- Header opcional -->
        @isset($header)
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                    <!-- Título de la página -->
                    <div>
                        {{ $header }}
                    </div>

                    <!-- Botón de Volver (se oculta en el dashboard) -->
                    @if (!request()->routeIs('dashboard'))
                        <button onclick="history.back()"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center">
                            <i class="fa-solid fa-angle-left mr-2"></i>
                            <span>Volver</span>
                        </button>
                    @endif
                </div>
            </header>
        @endisset

        <!-- Contenido principal de la página -->
        <main class="py-10">
            {{ $slot }}
        </main>
    </div>
    @stack('scripts')


    <!-- Scripts adicionales (si es necesario) -->
</body>

</html>
