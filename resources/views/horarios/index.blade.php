<x-app-layout>
    <div class="py-6 px-8">
        <div class="mx-auto flex justify-between items-center mb-6">
            <x-title-section titulo="Horarios" :subtitulo="$subtitulo" />
            <div>
                <x-action-rounded-link href="{{ route('horarios.create') }}">
                    <i class="fa-solid fa-plus"></i>
                </x-action-rounded-link>
            </div>
        </div>

        <div class="space-y-3">
            @forelse ($horarios as $horario)
                <x-tarjeta-horario 
                    :id="$horario->id" 
                    :fecha="$horario->fecha" 
                    :barbero="$horario->barbero->name" 
                    :horaInicio="$horario->hora_inicio" 
                    :horaFin="$horario->hora_fin" 
                    :inicioDescanso="$horario->hora_inicio_descanso" 
                    :finDescanso="$horario->hora_fin_descanso" 
                />
            @empty
                <div class="bg-white rounded-2xl border border-border-light/40 p-8 text-center text-muted">
                    <p>No hay turnos programados para esta semana.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>