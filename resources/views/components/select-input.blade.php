@props(['disabled' => false])

<select
    @disabled($disabled)
    {{ $attributes->merge([
        'class' => 'appearance-none bg-white rounded-xl px-4 py-2 outline-none transition-colors border border-border-light focus:outline-none focus:border-main/70'
    ]) }}
>
    {{ $slot }}
</select>
