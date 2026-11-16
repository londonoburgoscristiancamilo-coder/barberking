<x-guest-layout>
    <div class="mb-4 text-sm text-muted">
        {{ __('Esta es una zona segura de la aplicación. Por favor, confirma tu contraseña antes de continuar.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" novalidate>
        @csrf

        <!-- Contraseña -->
        <div class="space-y-2">
            <x-input-label for="password" :value="__('Contraseña')" class="text-sm font-medium text-secundary" />

            <div class="relative flex items-center mt-2">
                <span class="absolute left-5 pointer-events-none flex items-center text-auth-icon opacity-70">
                    <i class="fa-solid fa-lock text-xl"></i>
                </span>

                <x-password-input
                    id="password"
                    class="block w-full py-3.5 pl-14 pr-4"
                    type="password"
                    name="password"
                    placeholder="********"
                    required
                    autocomplete="current-password"
                />
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Botón -->
        <div class="flex justify-end mt-4">
            <x-primary-button>
                {{ __('Confirmar') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>