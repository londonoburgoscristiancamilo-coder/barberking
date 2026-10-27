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
<body class="bg-[#F3EFE4] text-[#5C4033] flex items-center justify-center min-h-screen p-4 selection:bg-[#B87333] selection:text-white">

    <!-- Contenedor Principal Estilo Móvil / Tarjeta Central -->
    <main class="w-full max-w-md bg-[#F3EFE4] flex flex-col items-center justify-between px-6 pb-10 -mt-15 text-center rounded-3xl"> <!-- min-h-[85vh] -->
        
        <!-- Espacio superior / Navegación -->
        <div class="w-full flex justify-end">
            @if (Route::has('login'))
                <nav class="flex gap-3 text-sm">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-medium text-[#B87333] hover:underline">Dashboard</a>
                    @endauth
                </nav>
            @endif
        </div>

        <!-- Sección del Logo y Marca -->
        <div class="flex flex-col items-center w-full">
            
            <!-- Logo -->
            <div class="w-60 h-60 flex items-center justify-center overflow-hidden">
                <img src="{{ asset('storage/images/logo_cropped.webp') }}" alt="Logo BarberKing" class="w-full h-full object-contain">
            </div>
            
            <!-- Separador -->
            <div class="w-20 h-[3px] bg-[#A97351]/40 mx-auto mt-8 mb-8"></div>

            <!-- Copy / Eslogan -->
            <h2 class="text-5xl font-semibold text-[#A66F4F] mb-3">
                Descubre Tu Estilo Personal.
            </h2>
            <p class="text-xl text-[#201917] max-w-xs leading-snug mt-4 mb-8">
                Reserva citas, encuentra barberos expertos y explora guías de estilo.
            </p>

            <!-- Separador -->
            <div class="w-20 h-[3px] bg-[#A97351]/40 mx-auto mb-12"></div>
        </div>

        <!-- Botones de Acción con Jerarquía -->
        <div class="w-full flex flex-col gap-3.5">
            @if (Route::has('register'))
                <a href="{{ route('register') }}" 
                   class="w-full py-3.5 px-6 bg-[#B46846] hover:bg-[#A0622A] text-white text-xl font-medium rounded-full shadow-md transition duration-200 text-center block">
                    Crear Cuenta
                </a>
            @endif

            @if (Route::has('login'))
                <a href="{{ route('login') }}" 
                   class="w-full py-3.5 px-6 bg-[#F1EFE2] border-2 border-[#9B694C] text-[#9B694C] text-xl hover:bg-[#B87333]/10 font-medium rounded-full transition duration-200 text-center block">
                    Iniciar Sesión
                </a>
            @endif
        </div>

    </main>

</body>
</html>