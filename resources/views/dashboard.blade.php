<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                window.Swal.fire({
                    icon: 'success',
                    title: 'Iniciaste Sesión Correctamente',
                    text: @json(session('success')),
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#B46846',
                    timer: 5000,
                    timerProgressBar: true
                });
            });
        </script>
    @endif
</x-app-layout>
