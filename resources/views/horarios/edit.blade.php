<x-app-layout>
    <div class="py-6 px-8 pb-28">
        <div class="mx-auto flex justify-between items-center mb-6">
            <x-title-section 
                titulo="Modificar horario"
                subtitulo="Modifica los detalles del turno de trabajo."
                rutaRegreso="{{ route('horarios.index') }}" />
        </div>

        <form action="{{ route('horarios.update', $horario->id) }}" method="POST"
            id="form-horario" class="space-y-6 mt-6" novalidate>
            @csrf
            @method('PUT')

            @include('horarios.partials.form')
        </form>

        <!-- Eliminar horario -->
        <div class="mt-6 flex flex-col items-center">
            <x-delete-modal
                action="{{ route('horarios.destroy', $horario->id) }}"
                title="¿Eliminar este horario?"
                message="
                    Se eliminará este turno de trabajo de {{ $horario->barbero->name }}. <br/>
                    Esta acción no se puede deshacer.
                "
                itemTitle="Horario de trabajo"
                itemSubtitle="
                    {{ substr($horario->hora_inicio, 0, 5) }} - {{ substr($horario->hora_fin, 0, 5) }} <br/>
                    Almuerzo: {{ $horario->hora_inicio_descanso ? substr($horario->hora_inicio_descanso, 0, 5) : 'Sin descanso' }}
                    {{ $horario->hora_fin_descanso ? ' - ' . substr($horario->hora_fin_descanso, 0, 5) : '' }}
                "
                itemDate="{{ ucfirst(\Carbon\Carbon::parse($horario->fecha)->locale('es')->translatedFormat('l, j M Y')) }}">

                <button type="submit" class="text-action font-medium">
                    Eliminar horario
                </button>
            </x-delete-modal>
        </div>
    </div>
</x-app-layout>