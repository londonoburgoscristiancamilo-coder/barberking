@php
    $esEdicion = isset($servicio);

    $nombre = old(
        'nombre',
        $esEdicion ? $servicio->nombre : ''
    );

    $duracion = old(
        'duracion_minutos',
        $esEdicion ? $servicio->duracion_minutos : ''
    );

    $precio = old(
        'precio',
        $esEdicion ? number_format($servicio->precio, 0, '', '.') : ''
    );

    $descripcion = old(
        'descripcion',
        $esEdicion ? $servicio->descripcion : ''
    );
@endphp

<!-- Nombre del servicio -->
<div>
    <x-input-label
        for="nombre"
        :value="__('Nombre del servicio')"
        class="text-lg"
        colorTexto="text-main-green" />

    <div class="relative flex items-center mt-2">
        <span class="absolute left-5 pointer-events-none flex items-center text-muted/70">
            <i class="fa-solid fa-scissors"></i>
        </span>

        <x-text-input
            id="nombre"
            class="block w-full py-3.5 pl-14 pr-4"
            type="text"
            name="nombre"
            value="{{ $nombre }}"
            placeholder="Ej. Corte clásico"
            required />
    </div>

    <x-input-error :messages="$errors->get('nombre')" class="mt-1" />
</div>

<!-- Duración -->
<div>
    <x-input-label
        for="duracion_minutos"
        :value="__('Duración')"
        class="text-lg"
        colorTexto="text-main-green" />

    <div class="relative flex items-center mt-2">
        <span class="absolute left-5 pointer-events-none flex items-center text-muted/70">
            <i class="fa-regular fa-clock"></i>
        </span>

        <x-select-input
            id="duracion_minutos"
            class="block w-full py-3.5 pl-14 pr-4 appearance-none"
            name="duracion_minutos"
            required>
            <option value="" @selected($duracion === '')>
                Selecciona una duración
            </option>

            <option value="30" @selected($duracion == 30)>
                30 minutos
            </option>

            <option value="45" @selected($duracion == 45)>
                45 minutos
            </option>

            <option value="60" @selected($duracion == 60)>
                1 hora
            </option>

            <option value="90" @selected($duracion == 90)>
                1 hora 30 minutos
            </option>
        </x-select-input>

        <span class="absolute right-5 pointer-events-none flex items-center text-muted/70">
            <i class="fa-solid fa-angle-down"></i>
        </span>
    </div>

    <x-input-error :messages="$errors->get('duracion_minutos')" class="mt-1" />
</div>

<!-- Precio -->
<div>
    <x-input-label
        for="precio"
        :value="__('Precio')"
        class="text-lg"
        colorTexto="text-main-green" />

    <div class="relative flex items-center mt-2">
        <span class="absolute left-5 pointer-events-none flex items-center text-muted/70">
            <i class="fa-solid fa-money-check-dollar"></i>
        </span>

        <span class="absolute left-15 pointer-events-none flex items-center text-muted/70">
            $
        </span>

        <x-text-input
            id="precio"
            class="block w-full py-3.5 pl-18 pr-4"
            type="text"
            name="precio"
            value="{{ $precio }}"
            placeholder="20.000"
            required />
    </div>

    <x-input-error :messages="$errors->get('precio')" class="mt-1" />
</div>

<!-- Descripción -->
<div>
    <x-input-label
        for="descripcion"
        :value="__('Descripción')"
        class="text-lg"
        colorTexto="text-main-green" />

    <x-textarea name="descripcion" rows="3">{{ $descripcion }}</x-textarea>

    <x-input-error :messages="$errors->get('descripcion')" class="mt-1" />
</div>


<!-- Acciones del formulario -->
@if ($esEdicion)
    <!-- Separador -->
    <div class="py-5">
        <div class="w-20 h-[3px] bg-[#A97351]/40 mx-auto"></div>
    </div>

    <div class="py-2">
        <!-- Resumen del servicio -->
        <div class="flex justify-between items-center mb-4">
            <span class="text-main-green">
                Duración · {{ $servicio->duracion_minutos }} min
            </span>

            <span class="text-2xl font-bold text-main-green">
                ${{ number_format($servicio->precio, 0, '', '.') }}
            </span>
        </div>

        <x-action-button
            type="submit"
            class="w-full justify-center bg-action py-3.5 text-base">
            <i class="fa-solid fa-pen-to-square mr-2"></i>
            Actualizar servicio
        </x-action-button>
    </div>
@else
    <!-- Separador -->
    <div class="py-5">
        <div class="w-20 h-[3px] bg-[#A97351]/40 mx-auto"></div>
    </div>

    <x-action-button type="submit">
        <i class="fa-solid fa-plus"></i>
        Crear servicio
    </x-action-button>
@endif