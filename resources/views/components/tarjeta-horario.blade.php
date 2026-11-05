@props(['id', 'fecha', 'barbero', 'horaInicio', 'horaFin', 'inicioDescanso', 'finDescanso'])

@php
    $carbonFecha = \Carbon\Carbon::parse($fecha);
    $carbonFecha->setLocale('es'); // Configurar locale en español para que devuelva "Lunes", "Martes", etc., con la primera letra en mayúscula
    $diaSemana = ucfirst($carbonFecha->isoFormat('dddd')); 
    $numeroDia = $carbonFecha->format('j M');
    $ano = $carbonFecha->format('Y');
@endphp

<a href="{{ route('horarios.edit', $id) }}"
    class="block bg-white rounded-2xl border border-border-light/40 px-4 py-5 shadow-sm hover:shadow-md transition-shadow">
    <div class="flex items-center justify-between flex-col xs:flex-row">

        <!-- Fecha -->
        <div class="flex flex-col min-w-22.5">
            <span class="text-xs font-semibold text-action uppercase tracking-wide">{{ $diaSemana }}</span>
            <span class="text-lg font-bold text-secundary">{{ $numeroDia }}</span>
            <span class="text-xs text-muted">{{ $ano }}</span>
        </div>
        
        <!-- Separador -->
        <div class="border-border-light mx-4 h-12 border-l max-xs:h-0 max-xs:w-full max-xs:border-l-0 max-xs:border-t max-xs:mx-0 max-xs:my-3"></div>

        <!-- Información del Barbero y Turnos (Columna Central) -->
        <div class="flex-1">
            <h3 class="font-semibold text-secundary text-base">{{ $barbero }}</h3>
            
            <!-- Horario de Trabajo -->
            <div class="flex items-center text-xs text-muted mt-1">
                <i class="fa-regular fa-clock mr-1.5"></i>
                {{ \Carbon\Carbon::parse($horaInicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($horaFin)->format('H:i') }}
            </div>

            <!-- Horario de Almuerzo / Descanso -->
            <div class="flex items-center text-xs text-muted mt-0.5">
                <i class="fa-solid fa-utensils mr-1.5"></i>
                @if($inicioDescanso && $finDescanso && $inicioDescanso != '00:00:00')
                    Almuerzo: {{ \Carbon\Carbon::parse($inicioDescanso)->format('H:i') }} - {{ \Carbon\Carbon::parse($finDescanso)->format('H:i') }}
                @else
                    Sin descanso
                @endif
            </div>
        </div>

        <!-- Icono Flecha -->
        <div class="text-muted-light ml-2">
            <i class="fa-solid fa-chevron-right text-sm"></i>
        </div>

    </div>
</a>