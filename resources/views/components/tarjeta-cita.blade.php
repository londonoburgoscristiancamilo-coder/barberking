@props([
    'id',
    'fecha',
    'hora_inicio',
    'hora_fin',
    'mensaje_tiempo' => null,
    'cliente',
    'servicio',
    'precio',
    'barbero',
    'estado'
])

@php
    $estadoClean = strtolower(trim($estado));

    $estadoColors = match($estadoClean) {
        'confirmada', 'confirmado' => 'bg-status-confirmed-bg text-status-confirmed-text',
        'pendiente' => 'bg-status-pending-bg text-status-pending-text',
        'completada', 'completado' => 'bg-status-completed-bg text-status-completed-text',
        'cancelada', 'cancelado' => 'bg-status-cancelled-bg text-action-primary',
        default => 'bg-status-completed-bg text-card-muted',
    };

    $estadoIcon = match($estadoClean) {
        'confirmada', 'confirmado' => 'fa-solid fa-check',
        'pendiente' => 'fa-regular fa-clock',
        'completada', 'completado' => 'fa-solid fa-check-double',
        'cancelada', 'cancelado' => 'fa-solid fa-xmark',
        default => 'fa-solid fa-circle-info',
    };
@endphp

<!-- Tarjeta principal -->
<div class="relative bg-card-surface rounded-2xl border border-border-light/60 px-4 py-4 shadow-sm hover:shadow-md transition-shadow">

    <!-- Contenido de la tarjeta -->
    <div class="flex items-center flex-col xs:flex-row text-left">

        <!-- Fecha y hora -->
        <div class="flex flex-col min-w-[70px] xs:min-w-[80px] shrink-0 justify-center">
            <span class="text-xs font-medium text-card-muted mb-1 capitalize">
                {{ $fecha }}
            </span>

            <span class="text-xl font-bold text-action-primary leading-none">
                {{ $hora_inicio }}
            </span>

            <span class="text-sm text-muted mt-1">
                {{ $hora_fin }}
            </span>

            @if($mensaje_tiempo)
                <span class="text-[10px] font-bold text-action-primary mt-2 uppercase tracking-wide">
                    {{ $mensaje_tiempo }}
                </span>
            @endif
        </div>

        <!-- Separador -->
        <div class="border-border-light mx-4 h-16 border-l shrink-0 max-xs:h-0 max-xs:w-full max-xs:border-l-0 max-xs:border-t max-xs:mx-0 max-xs:my-3">
        </div>

        <!-- Información -->
        <div class="flex-1 min-w-0 w-full">

            <div class="flex items-center justify-between gap-2">
                <h3 class="font-semibold text-main-green text-base truncate">
                    {{ $cliente }}
                </h3>

                <div class="text-dashboard-muted shrink-0">
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </div>
            </div>

            <div class="text-sm text-card-muted mt-0.5 truncate">
                {{ $servicio }} · ${{ number_format($precio, 0, ',', '.') }}
            </div>

            <div class="flex items-center justify-between mt-3 gap-2">

                <!-- Barbero -->
                <div class="flex items-center text-sm text-card-muted min-w-0 flex-1">
                    <i class="fa-solid fa-scissors mr-1.5 text-xs shrink-0"></i>
                    <span class="truncate">{{ $barbero }}</span>
                </div>

                <!-- Selector de estado -->
                <form action="{{ route('citas.estado', $id) }}"
                      method="POST"
                      class="relative z-20 shrink-0">

                    @csrf
                    @method('PATCH')

                    <div class="relative inline-flex items-center justify-center gap-2 rounded-md {{ $estadoColors }} px-3 py-2 cursor-pointer focus-within:ring-2 focus-within:ring-auth-accent">

                        <!-- Ícono del estado -->
                        <span class="flex items-center justify-center w-4 h-4 pointer-events-none">
                            <i class="{{ $estadoIcon }} text-sm leading-none"></i>
                        </span>

                        <!-- Flecha del desplegable -->
                        <span class="flex items-center justify-center w-3 h-4 pointer-events-none">
                            <i class="fa-solid fa-chevron-down text-[10px] leading-none"></i>
                        </span>

                        <!-- Selector invisible sobre los íconos -->
                        <select name="estado"
                                aria-label="Cambiar estado de la cita {{ $id }}. Estado actual: {{ ucfirst($estadoClean) }}"
                                title="Estado: {{ ucfirst($estadoClean) }}"
                                onchange="this.form.requestSubmit()"
                                class="absolute inset-0 z-10 w-full h-full opacity-0 cursor-pointer">

                            <option value="pendiente"
                                    @selected($estadoClean === 'pendiente')>
                                Pendiente
                            </option>

                            <option value="confirmada"
                                    @selected(in_array($estadoClean, ['confirmada', 'confirmado']))>
                                Confirmada
                            </option>

                            <option value="completada"
                                    @selected(in_array($estadoClean, ['completada', 'completado']))>
                                Completada
                            </option>

                            <option value="cancelada"
                                    @selected(in_array($estadoClean, ['cancelada', 'cancelado']))>
                                Cancelada
                            </option>

                        </select>

                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Enlace de edición -->
    <a href="{{ route('citas.edit', $id) }}"
       class="absolute inset-0 z-0 rounded-2xl"
       aria-label="Editar cita de {{ $cliente }}">
    </a>

</div>
