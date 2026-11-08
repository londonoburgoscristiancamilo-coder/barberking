@props(['disabled' => false])

<textarea
    @disabled($disabled)
    {{ $attributes->merge([
        'class' => 'bg-white w-full no-resize rounded-xl px-4 py-2 outline-none transition-colors border border-border-light focus:outline-none focus:border-main/70'
    ]) }}
>{{ $slot }}</textarea>
