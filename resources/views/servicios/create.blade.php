<x-app-layout>
    <div class="py-6 px-8">
        <div class="max-w-7xl mx-auto flex justify-between items-center mb-6">
            <x-title-section titulo="Nuevo Servicio" subtitulo="Completa los detalles para crear un servicio"
                rutaRegreso="{{ route('servicios.index') }}" />
        </div>
        <form action="{{ route('servicios.store') }}" method="POST" class="space-y-4 mt-8">
            @csrf
            <div>
                <x-input-label for="nombre" :value="__('Nombre del servicio')" class="text-lg" colorTexto='text-main-green' />
                <div class="relative flex items-center mt-2">
                    <span class="absolute left-5 pointer-events-none flex items-center text-muted/70">
                        <i class="fa-solid fa-scissors"></i>
                    </span>
                    <x-text-input id="nombre" class="block w-full py-3.5 pl-14 pr-4" type="text" name="nombre"
                        placeholder="Ej. Corte clásico" required />
                </div>
                <x-input-error :messages="$errors->get('nombre')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="duracion" :value="__('Duración')" class="text-lg" colorTexto='text-main-green' />
                <div class="relative flex items-center mt-2">
                    <span class="absolute left-5 pointer-events-none flex items-center text-muted/70">
                        <i class="fa-regular fa-clock"></i>
                    </span>
                    <x-text-input id="duracion" class="block w-full py-3.5 pl-14 pr-4" type="text" name="duracion"
                        placeholder="30 minutos" required />
                    <span class="absolute right-5 pointer-events-none flex items-center text-muted/70">
                        <i class="fa-solid fa-angle-down"></i>
                    </span>
                </div>
                <x-input-error :messages="$errors->get('duracion')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="precio" :value="__('Precio')" class="text-lg" colorTexto='text-main-green' />
                <div class="relative flex items-center mt-2">
                    <span class="absolute left-5 pointer-events-none flex items-center text-muted/70">
                        <i class="fa-solid fa-money-check-dollar"></i>
                    </span>
                    <span class="absolute left-15 pointer-events-none flex items-center text-muted/70">
                        $
                    </span>
                    <x-text-input id="precio" class="block w-full py-3.5 pl-18 pr-4" type="text" name="precio"
                        placeholder="20.000" required />
                </div>
                <x-input-error :messages="$errors->get('precio')" class="mt-1" />
            </div>
        </form>
        <x-action-button>
            <i class="fa-solid fa-plus"></i>
            Crear Servicio
        </x-action-button>
    </div>
</x-app-layout>
