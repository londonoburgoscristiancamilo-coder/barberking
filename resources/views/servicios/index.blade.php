<x-app-layout>
    <div class="py-6 px-8">
        <div class="max-w-7xl mx-auto flex justify-between items-center mb-6">
            <x-title-section titulo="Servicios" subtitulo="Catálogo de la barbería" />
            <div>
                <x-action-rounded-link href="{{ route('servicios.create') }}">
                    <i class="fa-solid fa-plus"></i>
                </x-action-rounded-link>
            </div>
        </div>

        <div class="space-y-2">
            @foreach ($servicios as $servicio)
                <x-tarjeta-servicio :id="$servicio->id" :precio="$servicio->precio" :duracion="$servicio->duracion_minutos" :nombre="$servicio->nombre" />
            @endforeach ()
        </div>
    </div>
</x-app-layout>
