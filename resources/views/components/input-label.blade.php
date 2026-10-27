@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-sm text-main-green/80']) }}>
    {{ $value ?? $slot }}
</label>
