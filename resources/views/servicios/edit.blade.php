<x-app-layout>
    <div class="py-6 px-8 pb-20">
        <div class="mx-auto flex justify-between items-center mb-6">
            <x-title-section
                titulo="Modificar servicio"
                subtitulo="Modifica los detalles del servicio."
                rutaRegreso="{{ route('servicios.index') }}" />
        </div>

        <form
            action="{{ route('servicios.update', $servicio->id) }}"
            method="POST"
            class="space-y-4 mt-8"
            novalidate>
            @csrf
            @method('PUT')

            @include('servicios.partials.form')
        </form>

        <!-- Acciones adicionales -->
        <div class="mt-4 flex flex-col items-center space-y-4">
            <a
                href="{{ route('servicios.index') }}"
                class="text-muted transition">
                Cancelar
            </a>

            <!-- Eliminar servicio -->
            <x-delete-modal
                action="{{ route('servicios.destroy', $servicio) }}"
                title="¿Eliminar este servicio?"
                message="Se eliminará {{ $servicio->nombre }} del catálogo. Esta acción no se puede deshacer."
                itemTitle="{{ $servicio->nombre }}"
                itemSubtitle="{{ $servicio->duracion_minutos }} minutos"
                itemDate="Precio · ${{ number_format($servicio->precio, 0, '', '.') }}">

                <button type="submit" class="text-action font-medium">
                    Eliminar servicio
                </button>
            </x-delete-modal>
        </div>
    </div>
</x-app-layout>