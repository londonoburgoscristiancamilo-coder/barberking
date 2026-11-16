<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'w-full py-3.5 px-6 bg-auth-primary hover:bg-auth-primary-hover text-white text-xl font-medium rounded-full shadow-md transition duration-200 text-center block'
]) }}>
    {{ $slot }}
</button>