<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-main-green">
            {{ __('Eliminar Cuenta') }}
        </h2>

        <p class="mt-1 text-sm text-muted">
            {{ __('Una vez que se elimine tu cuenta, todos sus recursos y datos se borrarán de forma permanente. Antes de continuar, asegúrate de descargar cualquier información que desees conservar.') }}
        </p>
    </header>

    <form
        id="delete-account-form"
        method="post"
        action="{{ route('profile.destroy') }}"
        autocomplete="off"
    >
        @csrf
        @method('delete')

        <input
            type="hidden"
            name="password"
            id="delete-account-password"
        >

        <button
            type="button"
            id="delete-account-button"
            class="inline-flex items-center px-4 py-2 bg-error border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-error-hover focus:bg-error-hover active:bg-error-active focus:outline-none focus:ring-2 focus:ring-error-hover focus:ring-offset-2 transition ease-in-out duration-150"
        >
            Eliminar cuenta
        </button>
    </form>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const button = document.getElementById('delete-account-button');
        const form = document.getElementById('delete-account-form');
        const passwordInput = document.getElementById('delete-account-password');

        if (!button || !form || !passwordInput || !window.Swal) return;

        const deletionError = @json($errors->userDeletion->first('password'));

        const openDeleteConfirmation = async (error = null) => {
            const result = await Swal.fire({
                title: '¿Eliminar tu cuenta?',
                text: 'Esta acción es permanente y no podrás recuperar tu cuenta ni sus datos.',
                icon: 'warning',
                input: 'password',
                inputLabel: 'Confirma tu identidad',
                inputPlaceholder: 'Ingresa tu contraseña actual',
                inputAttributes: {
                    autocapitalize: 'off',
                    autocorrect: 'off',
                    autocomplete: 'new-password',
                    'data-1p-ignore': 'true',
                    'data-lpignore': 'true',
                    'data-bwignore': 'true'
                },
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar cuenta',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: 'var(--color-error)',
                cancelButtonColor: 'var(--color-action-cancel)',
                reverseButtons: true,
                focusCancel: true,
                inputValidator: (value) => {
                    if (!value) {
                        return 'Debes ingresar tu contraseña para continuar.';
                    }
                },
                didOpen: () => {
                    if (error) {
                        Swal.showValidationMessage(error);
                    }
                }
            });

            if (result.isConfirmed) {
                passwordInput.value = result.value;
                form.submit();
            }
        };

        button.addEventListener('click', () => {
            openDeleteConfirmation();
        });

        // Si Laravel devuelve un error de contraseña, volvemos a mostrar la alerta.
        if (deletionError) {
            openDeleteConfirmation(deletionError);
        }
    });
</script>