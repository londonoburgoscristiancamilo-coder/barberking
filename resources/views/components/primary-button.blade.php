<button {{ $attributes->merge(['type' => 'submit', 'class' => 'w-full py-3.5 px-6 bg-[#B46846] hover:bg-[#A0622A] text-white text-xl font-medium rounded-full shadow-md transition duration-200 text-center block']) }}>
    {{ $slot }}
</button>
