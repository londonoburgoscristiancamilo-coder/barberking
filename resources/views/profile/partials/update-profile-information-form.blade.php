<section>
    <header>
        <h2 class="text-lg font-medium text-main-green">
            {{ __('Información del perfil') }}
        </h2>

        <p class="mt-1 text-sm text-muted">
            {{ __("Actualiza la información de perfil y la dirección de correo electrónico de tu cuenta.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}" novalidate>
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" novalidate>
        @csrf
        @method('patch')

        <!-- Nombre -->
        <div>
            <x-input-label for="name" :value="__('Nombre')" />

            <x-text-input
                id="name"
                name="name"
                type="text"
                class="mt-1 block w-full"
                :value="old('name', $user->name)"
                required
                autofocus
                autocomplete="name"
            />

            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- Correo -->
        <div>
            <x-input-label for="email" :value="__('Correo')" />

            <x-text-input
                id="email"
                name="email"
                type="email"
                class="mt-1 block w-full"
                :value="old('email', $user->email)"
                required
                autocomplete="username"
            />

            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-main-green">
                        {{ __('Tu dirección de correo electrónico no está verificada.') }}

                        <button
                            type="submit"
                            form="send-verification"
                            class="underline text-sm text-muted hover:text-main-green rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-action"
                        >
                            {{ __('Haz clic aquí para reenviar el correo electrónico de verificación.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-success">
                            {{ __('Se ha enviado un nuevo enlace de verificación a tu dirección de correo electrónico.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Botón y confirmación -->
        <div class="flex items-center gap-4">
            <x-primary-button>
                {{ __('Guardar') }}
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-muted"
                >
                    {{ __('Información guardada.') }}
                </p>
            @endif
        </div>
    </form>
</section>