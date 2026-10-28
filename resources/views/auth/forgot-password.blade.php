<x-guest-layout>
    <div class="mb-8 text-sm text-center font-light" style="opacity: 70%;">
        {{ __('¿Has olvidado tu contraseña? No hay problema. Solo indícanos tu dirección de correo electrónico y te enviaremos un enlace para restablecer la contraseña, el cual te permitirá elegir una nueva.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Correo -->
        <div>
            <x-input-label for="email" :value="__('Correo')" class="text-sm font-medium text-gray-700" />
            <div class="relative flex items-center mt-2">
                <span class="absolute left-5 pointer-events-none flex items-center" style="color: #4D4C4B; opacity: 70%;">
                    <i class="fa-regular fa-envelope text-xl"></i>
                </span>
                <x-email-input id="email" class="block w-full py-3.5 pl-14 pr-4" type="email" name="email" :value="old('email')" placeholder="correo@dominio.com" required autofocus/>
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Separador -->
        <div class="mt-8 mb-8">
            <div class="w-20 h-[3px] bg-[#A97351]/40 mx-auto"></div>
        </div>

        <!-- Botón -->
        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Restablecer Contraseña') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
