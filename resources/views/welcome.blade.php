<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'BarberKing') }}</title>

    <!-- Logo BarberKing -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('storage/images/logo.ico') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Scripts y Estilos -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-auth-background text-auth-text flex items-center justify-center min-h-dvh px-4 py-6 sm:px-6 sm:py-10 selection:bg-auth-accent selection:text-white">

    <!-- Contenedor Principal Responsive -->
    <main class="w-full max-w-md mx-auto bg-auth-background flex flex-col items-center px-4 sm:px-6 py-4 sm:py-6 text-center rounded-3xl">

        <!-- Espacio superior / Navegación -->
        <div class="w-full flex justify-end">
            @if (Route::has('login'))
                <nav class="flex gap-3 text-sm">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-medium text-auth-accent hover:underline">
                            Dashboard
                        </a>
                    @endauth
                </nav>
            @endif
        </div>

        <!-- Sección del Logo y Marca -->
        <div class="flex flex-col items-center w-full">

            <!-- Logo -->
            <div class="flex justify-center w-full">
                <a href="/" class="inline-flex items-center justify-center">
                    <x-application-logo class="w-20 h-20 fill-current text-muted" />
                </a>
            </div>

            <!-- Separador -->
            <div class="w-20 h-0.75 bg-auth-divider/40 mx-auto mt-6 sm:mt-8 mb-6 sm:mb-8"></div>

            <!-- Copy / Eslogan -->
            <h2 class="text-5xl font-semibold text-auth-heading mb-3">
                Descubre Tu Estilo Personal.
            </h2>
            <p class="text-xl text-auth-description max-w-xs leading-snug mt-4 mb-8 opacity-70">
                Reserva citas, encuentra barberos expertos y explora guías de estilo.
            </p>

            <!-- Separador -->
            <div class="w-20 h-0.75 bg-auth-divider/40 mx-auto mb-8 sm:mb-12"></div>
        </div>

        <!-- Botones de Acción con Jerarquía -->
        <div class="w-full flex flex-col gap-3.5">
            @if (Route::has('register'))
                <a href="{{ route('register') }}"
                   class="w-full py-3.5 px-6 bg-auth-primary hover:bg-auth-primary-hover text-white text-xl font-medium rounded-full shadow-md transition duration-200 text-center block">
                    Crear Cuenta
                </a>
            @endif

            @if (Route::has('login'))
                <a href="{{ route('login') }}"
                   class="w-full py-3.5 px-6 bg-auth-secondary border-2 border-auth-link text-auth-link text-xl hover:bg-auth-accent/10 font-medium rounded-full transition duration-200 text-center block">
                    Iniciar Sesión
                </a>
            @endif
        </div>

    </main>

</body>
</html>
