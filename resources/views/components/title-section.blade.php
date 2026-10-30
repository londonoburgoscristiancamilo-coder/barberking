@props(['titulo', 'subtitulo' => null, 'rutaRegreso' => null])

<div>
    <div class="flex items-center gap-3">
        
        @if($rutaRegreso)
            <a href="{{ $rutaRegreso }}" class="text-secundary hover:text-action transition-colors mt-1">
               <i class="fa-solid fa-arrow-left"></i>
            </a>
        @endif
        
        <h1 class="text-3xl font-medium text-main-green">{{ $titulo }}</h1>
    </div>
    
    @if($subtitulo)
        <p class="text-xs text-muted mt-1">{{ $subtitulo }}</p>
    @endif
</div>