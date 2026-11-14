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
    $estadoClean = strtolower($estado);
    
    $estadoColors = match($estadoClean) {
        'confirmada', 'confirmado' => 'bg-[#EEF3E9] text-[#446A46]',
        'pendiente' => 'bg-[#FFF3E0] text-[#E65100]',
        'completada', 'completado' => 'bg-gray-100 text-gray-700',
        'cancelada', 'cancelado' => 'bg-[#F5EBE6] text-[#A04E35]',
        default => 'bg-gray-100 text-gray-500',
    };

    $estadoIcon = match($estadoClean) {
        'confirmada', 'confirmado' => 'fa-solid fa-check',
        'pendiente' => 'fa-regular fa-clock',
        'completada', 'completado' => 'fa-solid fa-check-double',
        'cancelada', 'cancelado' => 'fa-solid fa-xmark',
        default => 'fa-solid fa-circle-info',
    };
@endphp

<a href="{{ route('citas.edit', $id) }}"
    class="block bg-[#FFFCF8] rounded-2xl border border-border-light/60 px-4 py-4 shadow-sm hover:shadow-md transition-shadow">
    <div class="flex items-center flex-col xs:flex-row text-left">

        <div class="flex flex-col min-w-[70px] xs:min-w-[80px] shrink-0 justify-center">
            <span class="text-xs font-medium text-gray-500 mb-1 capitalize">{{ $fecha }}</span>
            <span class="text-xl font-bold text-[#A04E35] leading-none">{{ $hora_inicio }}</span>
            <span class="text-sm text-muted mt-1">{{ $hora_fin }}</span>
            
            @if($mensaje_tiempo)
                <span class="text-[10px] font-bold text-[#A04E35] mt-2 uppercase tracking-wide">
                    {{ $mensaje_tiempo }}
                </span>
            @endif
        </div>

        <div class="border-border-light mx-4 h-16 border-l shrink-0 max-xs:h-0 max-xs:w-full max-xs:border-l-0 max-xs:border-t max-xs:mx-0 max-xs:my-3">
        </div>

        <div class="flex-1 min-w-0 w-full">
            
            <div class="flex items-center justify-between gap-2">
                <h3 class="font-semibold text-gray-900 text-base truncate">{{ $cliente }}</h3>
                <div class="text-gray-400 shrink-0">
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </div>
            </div>

            <div class="text-sm text-gray-500 mt-0.5 truncate">
                {{ $servicio }} · ${{ number_format($precio, 0, ',', '.') }}
            </div>

            <div class="flex items-center justify-between mt-3 gap-2">
                <div class="flex items-center text-sm text-gray-500 min-w-0">
                    <i class="fa-solid fa-scissors mr-1.5 text-xs shrink-0"></i>
                    <span class="truncate">{{ $barbero }}</span>
                </div>
                
                <div class="flex items-center rounded-md {{ $estadoColors }} px-2 py-1 text-xs font-medium shrink-0">
                    <i class="{{ $estadoIcon }} mr-1.5"></i>
                    {{ ucfirst($estado) }}
                </div>
            </div>
            
        </div>

    </div>
</a>