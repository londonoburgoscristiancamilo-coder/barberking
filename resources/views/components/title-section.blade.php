@props(['titulo', 'subtitulo' => null])

<div>
    <h1 class="text-3xl font-medium text-main-green">{{ $titulo }}</h1>
    <p class="text-xs text-muted mt-1">{{ $subtitulo }}</p>
</div>