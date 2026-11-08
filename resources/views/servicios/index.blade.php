<x-app-layout>
    <div class="py-6 px-8">
        <div class="mx-auto flex justify-between items-center mb-6">
            <x-title-section titulo="Servicios" subtitulo="Catálogo de la barbería" />
            <div>
                <x-action-rounded-link href="{{ route('servicios.create') }}">
                    <i class="fa-solid fa-plus"></i>
                </x-action-rounded-link>
            </div>
        </div>

        <div class="space-y-2">
            @forelse ($servicios as $servicio)
                <x-tarjeta-servicio 
                    :id="$servicio->id"
                    :precio="$servicio->precio"
                    :duracion_minutos="$servicio->duracion_minutos"
                    :nombre="$servicio->nombre"
                />
            @empty
                <div class="bg-white rounded-2xl border border-border-light/40 p-8 text-center text-muted">
                    <p>No hay servicios creados en el sistema.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
