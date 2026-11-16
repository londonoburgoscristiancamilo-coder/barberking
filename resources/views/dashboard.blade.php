<x-app-layout>
    <!-- Contenedor superior con fondo oscuro -->
    <div class="bg-secundary text-white py-8 px-6 sm:px-8">
        <div class="mx-auto">

            <!-- Saludo y Fecha -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-2 mb-8">
                <div class="w-full">
                    <h1 class="text-3xl sm:text-4xl tracking-tight text-white mb-2">
                        Buen día, {{ explode(' ', $nombreUsuario)[0] }}.
                    </h1>

                    <div class="flex items-center justify-between w-full text-sm text-dashboard-muted">
                        <span>{{ $fechaActual }}</span>

                        <div class="flex items-center gap-2">
                            <span class="inline-block w-1.5 h-1.5 rounded-full bg-main"></span>
                            <span class="text-main text-sm">Abierto hasta las 20:00</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Separador -->
            <div class="w-auto h-0.5 bg-divider mx-auto"></div>

            <!-- Métricas -->
            <div class="grid grid-cols-3 gap-4 pt-4 border-t border-dashboard-border">

                <!-- Citas de hoy -->
                <div>
                    <div class="text-2xl sm:text-3xl text-white">
                        {{ $citasHoyCount }}
                    </div>
                    <div class="text-xs sm:text-sm text-dashboard-muted mt-0.5">
                        Citas de hoy
                    </div>
                </div>

                <!-- Por atender -->
                <div>
                    <div class="text-2xl sm:text-3xl text-white">
                        {{ $porAtenderCount }}
                    </div>
                    <div class="text-xs sm:text-sm text-dashboard-muted mt-0.5">
                        Por atender
                    </div>
                </div>

                <!-- Ingresos previstos -->
                <div>
                    <div class="text-2xl sm:text-3xl text-white">
                        ${{ number_format($ingresosPrevistos, 0, ',', '.') }}
                    </div>
                    <div class="text-xs sm:text-sm text-dashboard-muted mt-0.5">
                        Ingresos previstos
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Agenda -->
    <div class="py-8 px-6 sm:px-8 mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-secundary">Tu agenda</h2>
        </div>
        
        <div class="border-2 border-dashed border-border-light/60 rounded-2xl p-12 text-center text-muted bg-white">
            <p class="text-sm">Espacio reservado para el módulo de agenda.</p>
        </div>
    </div>
</x-app-layout>