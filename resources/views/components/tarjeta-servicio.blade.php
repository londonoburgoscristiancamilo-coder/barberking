@props(['id', 'precio', 'duracion', 'nombre', 'categoria' => 'Servicio de barbería'])

<a href="{{ route('servicios.edit', $id) }}"
    class="block bg-white rounded-2xl border border-border-light/40 px-4 py-6 shadow-sm hover:shadow-md transition-shadow">
    <div class="flex items-center justify-between flex-col xs:flex-row">

        <!-- Precio y Duración (Columna Izquierda) -->
        <div class="flex flex-col min-w-22.5">
            <span class="text-lg font-bold text-action">${{ number_format($precio, 0, ',', '.') }}</span>
            <div class="flex items-center text-xs text-muted mt-1">
                <i class="fa-regular fa-clock mr-1"></i>
                {{ $duracion }} min
            </div>
        </div>
        
        <div
            class="border-border-light mx-4 h-10 border-l max-xs:h-0 max-xs:w-full max-xs:border-l-0 max-xs:border-t max-xs:mx-0 max-xs:my-3">
        </div>

        <div class="flex-1">
            <h3 class="font-semibold text-secundary">{{ $nombre }}</h3>
            <div class="flex items-center text-xs text-muted mt-1">
                <i class="fa-solid fa-scissors mr-1"></i>
                {{ $categoria }}
            </div>
        </div>

        <div class="text-muted-light ml-2">
            <i class="fa-solid fa-chevron-right text-sm"></i>
        </div>

    </div>
</a>
