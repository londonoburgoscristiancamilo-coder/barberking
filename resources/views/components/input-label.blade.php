@props(['value', 'colorTexto' => 'text-main-green/80', 'fuerzaTexto' => 'font-semibold'])

<label {{ $attributes->merge(['class' => "block text-sm {$colorTexto} {$fuerzaTexto}"]) }}>
    {{ $value ?? $slot }}
</label>
