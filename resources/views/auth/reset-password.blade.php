<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}" novalidate>
        @csrf

        <!-- Token de restablecimiento -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Correo -->
        <div class="space-y-2">
            <x-input-label for="email" :value="__('Correo')" class="text-sm font-medium text-secundary" />

            <div class="relative flex items-center mt-2">
                <span class="absolute left-5 pointer-events-none flex items-center text-auth-icon opacity-70">
                    <i class="fa-regular fa-envelope text-xl"></i>
                </span>

                <x-email-input
                    id="email"
                    class="block w-full py-3.5 pl-14 pr-4"
                    type="email"
                    name="email"
                    :value="old('email', $request->email)"
                    placeholder="correo@dominio.com"
                    required
                    autofocus
                    autocomplete="username"
                />
            </div>

            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Contraseña -->
        <div class="space-y-2 mt-4">
            <x-input-label for="password" :value="__('Nueva Contraseña')" class="text-sm font-medium text-secundary" />

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
                    autocomplete="new-password"
                />
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirmar Contraseña -->
        <div class="space-y-2 mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" class="text-sm font-medium text-secundary" />

            <div class="relative flex items-center mt-2">
                <span class="absolute left-5 pointer-events-none flex items-center text-auth-icon opacity-70">
                    <i class="fa-solid fa-lock text-xl"></i>
                </span>

                <x-password-input
                    id="password_confirmation"
                    class="block w-full py-3.5 pl-14 pr-4"
                    type="password"
                    name="password_confirmation"
                    placeholder="********"
                    required
                    autocomplete="new-password"
                />
            </div>

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <!-- Botón -->
        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Restablecer Contraseña') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>