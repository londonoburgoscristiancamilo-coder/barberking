<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="w-full max-w-md mx-auto p-4 sm:p-6">
        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Correo -->
            <div class="space-y-2">
                <x-input-label for="email" :value="__('Correo')" class="text-sm font-medium text-gray-700" />
                <div class="relative flex items-center mt-2">
                    <span class="absolute left-5 pointer-events-none flex items-center" style="color: #4D4C4B; opacity: 70%;">
                        <i class="fa-regular fa-envelope text-xl"></i>
                    </span>
                    <x-email-input id="email" class="block w-full py-3.5 pl-14 pr-4" type="email" name="email" :value="old('email')" placeholder="correo@dominio.com" required autocomplete="username" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <!-- Contraseña -->
            <div class="space-y-2">
                <x-input-label for="password" :value="__('Contraseña')" class="text-sm font-medium text-gray-700" />
                <div class="relative flex items-center mt-2">
                    <span class="absolute left-5 pointer-events-none flex items-center" style="color: #4D4C4B; opacity: 70%;">
                        <i class="fa-solid fa-lock text-xl"></i>
                    </span>
                    <x-password-input id="password" class="block w-full py-3.5 pl-14 pr-4" type="password" name="password" placeholder="********" required autocomplete="current-password" />
                </div>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="underline text-sm rounded-md" style="color: #9B694C; opacity: 0.7;">
                        {{ __('¿Olvidaste tu contraseña?') }}
                    </a>
                @endif
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Remember Me 
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ms-2 text-sm text-gray-600">{{ __('Recuérdame') }}</span>
                </label>
            </div> -->

            <!-- Separador -->
            <div class="mt-8 mb-8">
                <div class="w-20 h-[3px] bg-[#A97351]/40 mx-auto"></div>
            </div>

            <!-- Botón y Enlace -->
            <div class="flex flex-col items-center gap-2">
                <x-primary-button>
                    {{ __('Iniciar Sesión') }}
                </x-primary-button>

                <a href="{{ route('register') }}" class="text-sm underline mt-2" style="color: #9B694C; opacity: 0.7;">
                    {{ __('¿No tienes una cuenta creada?') }}
                </a>
            </div>

        </form>
    </div>
</x-guest-layout>
