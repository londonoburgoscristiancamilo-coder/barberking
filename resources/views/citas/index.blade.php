<x-app-layout>
    <div class="py-6 px-8">
        <div class="mx-auto flex justify-between items-center mb-6">
            <x-title-section titulo="Citas" subtitulo="" />
            <div>
                <x-action-rounded-link href="{{ route('citas.create') }}">
                    <i class="fa-solid fa-plus"></i>
                </x-action-rounded-link>
            </div>
        </div>

        <div class="space-y-2">
            @forelse ($citas as $cita)
                <x-tarjeta-cita 
                    :id="$cita->id"
                    fecha="{{ $cita->fecha_cita->translatedFormat('d M') }}"
                    hora_inicio="{{ substr($cita->hora_cita, 0, 5) }}"
                    hora_fin="{{ \Carbon\Carbon::parse($cita->hora_cita)->addMinutes($cita->servicio->duracion_minutos)->format('H:i') }}"
                    cliente="{{ $cita->cliente->nombres }} {{ $cita->cliente->apellidos }}"
                    servicio="{{ $cita->servicio->nombre }}"
                    :precio="$cita->servicio->precio"
                    barbero="{{ $cita->barbero->name }}"
                    estado="{{ $cita->estado }}" />
            @empty
                <div class="bg-white rounded-2xl border border-border-light/40 p-8 text-center text-muted">
                    <p>No hay citas creadas en el sistema.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>