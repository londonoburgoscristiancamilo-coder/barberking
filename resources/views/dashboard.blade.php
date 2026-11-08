<x-app-layout>
    <!-- Contenedor superior con fondo oscuro -->
    <div class="bg-[#252C27] text-white py-8 px-6 sm:px-8">
        <div class="max-w-7xl mx-auto">
            
            <!-- Saludo y Fecha -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-2 mb-8">
                <div class="w-full">
                    <h1 class="text-3xl sm:text-4xl tracking-tight text-white mb-2">
                        Buen día, {{ explode(' ', $nombreUsuario)[0] }}.
                    </h1>

                    <div class="flex items-center justify-between w-full text-sm text-gray-400">
                        <span>{{ $fechaActual }}</span>

                        <div class="flex items-center gap-2">
                            <span class="inline-block w-1.5 h-1.5 rounded-full bg-[#D9A382]"></span>
                            <span class="text-[#D9A382] text-sm">Abierto hasta las 20:00</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Separador -->
            <div class="w-auto h-0.5 bg-[#444B43] mx-auto"></div>

            <!-- Métricas -->
            <div class="grid grid-cols-3 gap-4 pt-4 border-t border-gray-800">
                <!-- Citas de hoy -->
                <div>
                    <div class="text-2xl sm:text-3xl text-white">
                        {{ $citasHoyCount }}
                    </div>
                    <div class="text-xs sm:text-sm text-gray-400 mt-0.5">
                        Citas de hoy
                    </div>
                </div>

                <!-- Por atender -->
                <div>
                    <div class="text-2xl sm:text-3xl text-white">
                        {{ $porAtenderCount }}
                    </div>
                    <div class="text-xs sm:text-sm text-gray-400 mt-0.5">
                        Por atender
                    </div>
                </div>

                <!-- Ingresos previstos -->
                <div>
                    <div class="text-2xl sm:text-3xl text-white">
                        ${{ number_format($ingresosPrevistos, 0, ',', '.') }}
                    </div>
                    <div class="text-xs sm:text-sm text-gray-400 mt-0.5">
                        Ingresos previstos
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Agenda -->
    <div class="py-8 px-6 sm:px-8 max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-secundary">Tu agenda</h2>
        </div>
        
        <div class="border-2 border-dashed border-border-light/60 rounded-2xl p-12 text-center text-muted bg-white">
            <p class="text-sm">Espacio reservado para el módulo de agenda.</p>
        </div>
    </div>

    <!-- Script de Alerta de inicio de sesión -->
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