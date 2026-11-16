@props([
    'tipo' => 'success'
])

@if (session()->has($tipo))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const mensaje = @json(session($tipo));

            const mostrarToast = () => {
                if (typeof window.Swal === 'undefined') {
                    console.error('SweetAlert2 no está disponible.');
                    return;
                }

                window.Swal.fire({
                    toast: true,
                    position: 'bottom-end',
                    icon: @json($tipo),
                    title: mensaje,
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    showCloseButton: true,
                    background: 'var(--color-white)',
                    color: 'var(--color-main-green)',
                    iconColor: 'var(--bk-color-accent)',
                    customClass: {
                        popup: 'barberking-toast'
                    }
                });
            };

            mostrarToast();
        });
    </script>
@endif