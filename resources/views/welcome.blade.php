<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'BarberKing') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Tailwind CSS v4 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-[#FDFBF7] text-[#5C4033] flex items-center justify-center min-h-screen p-4 selection:bg-[#B87333] selection:text-white">

    <!-- Contenedor Principal Estilo Móvil / Tarjeta Central -->
    <main class="w-full max-w-md bg-[#FDFBF7] flex flex-col items-center justify-between px-6 py-10 text-center min-h-[85vh] rounded-3xl">
        
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
        <div class="flex flex-col items-center my-auto w-full">
            <!-- Logo Circular -->
            <div class="w-32 h-32 rounded-full border border-[#D4A373]/50 flex items-center justify-center mb-6 shadow-sm bg-white/40 overflow-hidden p-2">
                <img src="{{ asset('storage/images/logo.webp') }}" alt="Logo BarberKing" class="w-full h-full object-contain">
            </div>

            <!-- Títulos -->
            <h1 class="text-3xl font-bold tracking-tight text-[#4A3525]">Barber<span class="text-[#B87333]">King</span></h1>
            <p class="text-xs uppercase tracking-widest text-[#B87333] font-semibold mt-1 mb-8">Barbería & Estilo</p>

            <div class="w-12 h-[1px] bg-[#D4A373]/40 mx-auto mb-8"></div>

            <!-- Copy / Eslogan -->
            <h2 class="text-2xl font-semibold text-[#4A3525] mb-3">
                Descubre Tu Estilo Personal.
            </h2>
            <p class="text-sm text-[#7F6A55] max-w-xs leading-relaxed mb-10">
                Reserva citas, encuentra barberos expertos y explora guías de estilo.
            </p>
        </div>

        <!-- Botones de Acción con Jerarquía -->
        <div class="w-full flex flex-col gap-3.5 mt-auto">
            @if (Route::has('register'))
                <a href="{{ route('register') }}" 
                   class="w-full py-3.5 px-6 bg-[#B87333] hover:bg-[#A0622A] text-white font-medium rounded-full shadow-md transition duration-200 text-center block">
                    Crear Cuenta
                </a>
            @endif

            @if (Route::has('login'))
                <a href="{{ route('login') }}" 
                   class="w-full py-3.5 px-6 bg-transparent border border-[#B87333] text-[#B87333] hover:bg-[#B87333]/10 font-medium rounded-full transition duration-200 text-center block">
                    Iniciar Sesión
                </a>
            @endif
        </div>

    </main>

</body>
</html>