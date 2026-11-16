<x-app-layout>
    <div class="py-6 px-8 pb-20">
        <div class="max-w-3xl mx-auto">
            <x-title-section titulo="Modificar cita"
                subtitulo="Completa los detalles para modificar un espacio."
                rutaRegreso="{{ route('citas.index') }}" />

            <form action="{{ route('citas.update', $citas->id) }}" method="POST"
                class="space-y-6 mt-8" x-data="citasForm" novalidate>
                @csrf
                @method('PUT')

                @include('citas.partials.form', ['cita' => $citas])
            </form>

            <div class="flex flex-col items-center">
                <x-delete-modal action="{{ route('citas.destroy', $citas) }}"
                    title="¿Eliminar esta cita?"
                    message="La cita se eliminará de tu agenda y el horario quedará disponible. Esta acción no se puede deshacer."
                    itemTitle="{{ $citas->cliente->nombres }} {{ $citas->cliente->apellidos }}"
                    itemSubtitle="Cita de {{ $citas->servicio->nombre }}"
                    itemDate="{{ $citas->fecha_cita->format('d/m/Y') }} · {{ substr($citas->hora_cita, 0, 5) }}">

                    <button type="submit" class="text-action font-medium hover:underline">
                        Eliminar cita
                    </button>
                </x-delete-modal>
            </div>
        </div>
    </div>
</x-app-layout>