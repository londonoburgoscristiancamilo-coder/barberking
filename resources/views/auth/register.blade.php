<x-guest-layout>
    <div class="w-full max-w-md mx-auto p-4 sm:p-6">
        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <!-- Nombre -->
            <div class="space-y-2">
                <x-input-label for="name" :value="__('Nombre')" class="text-sm font-medium text-gray-700" />
                <div class="relative flex items-center mt-2">
                    <span class="absolute left-5 pointer-events-none flex items-center" style="color: #4D4C4B; opacity: 70%;">
                        <i class="fa-regular fa-user text-xl"></i>
                    </span>
                    <x-text-input id="name" class="block w-full py-3.5 pl-14 pr-4" type="text" name="name" :value="old('name')" placeholder="Nombre Apellido" required autofocus autocomplete="name" />
                </div>
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

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
                    <x-password-input id="password" class="block w-full py-3.5 pl-14 pr-4" type="password" name="password" placeholder="********" required autocomplete="new-password" />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Repetir Contraseña -->
            <div class="space-y-2">
                <x-input-label for="password_confirmation" :value="__('Repetir Contraseña')" class="text-sm font-medium text-gray-700" />
                <div class="relative flex items-center mt-2">
                    <span class="absolute left-5 pointer-events-none flex items-center" style="color: #4D4C4B; opacity: 70%;">
                        <i class="fa-solid fa-lock text-xl"></i>
                    </span>
                    <x-password-input id="password_confirmation" class="block w-full py-3.5 pl-14 pr-4" type="password" name="password_confirmation" placeholder="********" required autocomplete="new-password" />
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
            </div>

            <!-- Separador -->
            <div class="mt-8 mb-8">
                <div class="w-20 h-[3px] bg-[#A97351]/40 mx-auto"></div>
            </div>

            <!-- Botón y Enlace -->
            <div class="flex flex-col items-center gap-2">
                <x-primary-button>
                    {{ __('Crear Cuenta') }}
                </x-primary-button>

                <a href="{{ route('login') }}" class="text-sm underline mt-2" style="color: #9B694C; opacity: 0.7;">
                    {{ __('¿Ya tienes una cuenta creada?') }}
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>