
<x-app-layout>
    <div class="py-6 px-8 pb-28">
        <div class="max-w-7xl mx-auto flex justify-between items-center mb-6">
            <x-title-section 
                titulo="Modificar horario"
                subtitulo="Modifica los detalles del turno de trabajo."
                rutaRegreso="{{ route('horarios.index') }}" />
        </div>

        <form action="{{ route('horarios.update', $horario->id) }}" method="POST" id="form-horario" class="space-y-6 mt-6" novalidate>
            @csrf
            @method('PUT')

            <!-- Barbero -->
            <div class="space-y-2">
                <x-input-label for="barbero_id" :value="__('Barbero')" class="text-lg" colorTexto='text-main-green' />
                <div class="relative flex items-center">
                    <span class="absolute left-5 pointer-events-none flex items-center text-muted/70">
                        <i class="fa-solid fa-scissors"></i>
                    </span>

                    <select id="barbero_id" name="barbero_id" class="block w-full py-3.5 pl-14 pr-10 bg-white rounded-xl border border-border-light/40 text-secundary shadow-sm focus:border-action focus:ring-action appearance-none" required>
                        <option value="" disabled>Selecciona un barbero</option>

                        @foreach($barberos as $barbero)
                            <option value="{{ $barbero->id }}"
                                @selected(old('barbero_id', $horario->barbero_id) == $barbero->id)>
                                {{ $barbero->name }}
                            </option>
                        @endforeach
                    </select>

                    <span class="absolute right-5 pointer-events-none flex items-center text-muted/70">
                        <i class="fa-solid fa-angle-down"></i>
                    </span>
                </div>
                <x-input-error :messages="$errors->get('barbero_id')" class="mt-1" />
            </div>

            <!-- Fecha de trabajo -->
            <div class="space-y-2">
                <x-input-label for="fecha" :value="__('Fecha de trabajo')" class="text-lg" colorTexto='text-main-green' />
                <div class="relative flex items-center">
                    <span class="absolute left-5 pointer-events-none flex items-center text-muted/70">
                        <i class="fa-regular fa-calendar"></i>
                    </span>

                    <x-text-input
                        id="fecha"
                        class="block w-full py-3.5 pl-14 pr-4"
                        type="date"
                        name="fecha"
                        value="{{ old('fecha', \Carbon\Carbon::parse($horario->fecha)->format('Y-m-d')) }}"
                        required />
                </div>
                <x-input-error :messages="$errors->get('fecha')" class="mt-1" />
            </div>

            <!-- Hora de inicio y Hora de fin -->
            <div class="grid grid-cols-2 gap-4 pt-1">
                <div class="space-y-2">
                    <x-input-label for="hora_inicio" :value="__('Hora de inicio')" class="text-base" colorTexto='text-main-green' />
                    <div class="relative flex items-center">
                        <span class="absolute left-4 pointer-events-none flex items-center text-muted/70">
                            <i class="fa-regular fa-clock"></i>
                        </span>

                        <x-text-input
                            id="hora_inicio"
                            class="block w-full py-3.5 pl-11 pr-3 text-sm"
                            type="time"
                            name="hora_inicio"
                            value="{{ old('hora_inicio', substr($horario->hora_inicio, 0, 5)) }}"
                            required />
                    </div>
                    <x-input-error :messages="$errors->get('hora_inicio')" class="mt-1" />
                </div>

                <div class="space-y-2">
                    <x-input-label for="hora_fin" :value="__('Hora de fin')" class="text-base" colorTexto='text-main-green' />
                    <div class="relative flex items-center">
                        <span class="absolute left-4 pointer-events-none flex items-center text-muted/70">
                            <i class="fa-regular fa-clock"></i>
                        </span>

                        <x-text-input
                            id="hora_fin"
                            class="block w-full py-3.5 pl-11 pr-3 text-sm"
                            type="time"
                            name="hora_fin"
                            value="{{ old('hora_fin', substr($horario->hora_fin, 0, 5)) }}"
                            required />
                    </div>
                    <x-input-error :messages="$errors->get('hora_fin')" class="mt-1" />
                </div>
            </div>

            <!-- Descanso / Almuerzo -->
            <div class="pt-3 space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-medium text-main-green">Descanso · Almuerzo</span>
                    <span class="text-xs text-muted">Opcional</span>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <x-input-label for="hora_inicio_descanso" :value="__('Inicio')" class="text-sm" />
                        <div class="relative flex items-center">
                            <span class="absolute left-4 pointer-events-none flex items-center text-muted/70">
                                <i class="fa-regular fa-clock"></i>
                            </span>

                            <x-text-input
                                id="hora_inicio_descanso"
                                class="block w-full py-3 pl-11 pr-3 text-sm"
                                type="time"
                                name="hora_inicio_descanso"
                                value="{{ old('hora_inicio_descanso', $horario->hora_inicio_descanso ? substr($horario->hora_inicio_descanso, 0, 5) : '') }}" />
                        </div>
                        <x-input-error :messages="$errors->get('hora_inicio_descanso')" class="mt-1" />
                    </div>

                    <div class="space-y-2">
                        <x-input-label for="hora_fin_descanso" :value="__('Fin')" class="text-sm" />
                        <div class="relative flex items-center">
                            <span class="absolute left-4 pointer-events-none flex items-center text-muted/70">
                                <i class="fa-regular fa-clock"></i>
                            </span>

                            <x-text-input
                                id="hora_fin_descanso"
                                class="block w-full py-3 pl-11 pr-3 text-sm"
                                type="time"
                                name="hora_fin_descanso"
                                value="{{ old('hora_fin_descanso', $horario->hora_fin_descanso ? substr($horario->hora_fin_descanso, 0, 5) : '') }}" />
                        </div>
                        <x-input-error :messages="$errors->get('hora_fin_descanso')" class="mt-1" />
                    </div>
                </div>

                <p class="text-xs text-muted pt-1">El descanso se excluye del tiempo de trabajo.</p>
            </div>

            <!-- Resumen y actualización -->
            <div class="mt-10 pt-6 border-t border-border-light/40 space-y-4">
                <div class="flex justify-between items-center text-sm font-medium text-secundary px-1">
                    <span id="resumen-descanso">Descanso · 0 h</span>
                    <span id="resumen-trabajo" class="font-bold text-base text-main-green">0 h de trabajo</span>
                </div>

                <button type="submit" class="w-full flex items-center justify-center gap-2 bg-action text-white py-3.5 px-4 rounded-2xl font-semibold shadow-sm hover:opacity-95 transition-opacity">
                    <i class="fa-solid fa-pen-to-square"></i>
                    Actualizar horario
                </button>

                <div class="text-center pt-2">
                    <a href="{{ route('horarios.index') }}" class="text-sm text-muted hover:text-secundary transition-colors">
                        Cancelar
                    </a>
                </div>
            </div>
        </form>

        <!-- Eliminar horario -->
        <div class="mt-6 flex flex-col items-center">
            <x-delete-modal
                action="{{ route('horarios.destroy', $horario->id) }}"
                title="¿Eliminar este horario?"
                message="
                    Se eliminará este turno de trabajo de {{ $horario->barbero->name }}. <br/>
                    Esta acción no se puede deshacer.
                "
                itemTitle="Horario de trabajo"
                itemSubtitle="
                    {{ substr($horario->hora_inicio, 0, 5) }} - {{ substr($horario->hora_fin, 0, 5) }} <br/>
                     Almuerzo: {{ substr($horario->hora_inicio_descanso, 0, 5) }} - {{ substr($horario->hora_fin_descanso, 0, 5) }}
                "
                itemDate="{{ ucfirst(\Carbon\Carbon::parse($horario->fecha)->locale('es')->translatedFormat('l, j M Y')) }}">

                <button type="submit" class="text-action font-medium">
                    Eliminar horario
                </button>
            </x-delete-modal>
        </div>
    </div>

    <!-- Cálculo dinámico de horas -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const horaInicio = document.getElementById('hora_inicio');
            const horaFin = document.getElementById('hora_fin');
            const inicioDescanso = document.getElementById('hora_inicio_descanso');
            const finDescanso = document.getElementById('hora_fin_descanso');

            const resumenDescanso = document.getElementById('resumen-descanso');
            const resumenTrabajo = document.getElementById('resumen-trabajo');

            function calcularHoras() {
                let hInicio = horaInicio.value;
                let hFin = horaFin.value;
                let dInicio = inicioDescanso.value;
                let dFin = finDescanso.value;

                let minutosTrabajo = 0;
                let minutosDescanso = 0;

                if (hInicio && hFin) {
                    let [iniH, iniM] = hInicio.split(':').map(Number);
                    let [finH, finM] = hFin.split(':').map(Number);

                    let totalMinInicio = iniH * 60 + iniM;
                    let totalMinFin = finH * 60 + finM;

                    if (totalMinFin > totalMinInicio) {
                        minutosTrabajo = totalMinFin - totalMinInicio;
                    }
                }

                if (dInicio && dFin) {
                    let [dIniH, dIniM] = dInicio.split(':').map(Number);
                    let [dFinH, dFinM] = dFin.split(':').map(Number);

                    let totalMinDInicio = dIniH * 60 + dIniM;
                    let totalMinDFin = dFinH * 60 + dFinM;

                    if (totalMinDFin > totalMinDInicio) {
                        minutosDescanso = totalMinDFin - totalMinDInicio;
                        minutosTrabajo -= minutosDescanso;
                    }
                }

                let horasDescanso = minutosDescanso / 60;

                resumenDescanso.textContent = `Descanso · ${horasDescanso % 1 === 0 ? horasDescanso : horasDescanso.toFixed(1)} h`;

                let horasTrabajo = minutosTrabajo > 0 ? minutosTrabajo / 60 : 0;

                resumenTrabajo.textContent = `${horasTrabajo % 1 === 0 ? horasTrabajo : horasTrabajo.toFixed(1)} h de trabajo`;
            }

            [horaInicio, horaFin, inicioDescanso, finDescanso].forEach(input => {
                input.addEventListener('input', calcularHoras);
                input.addEventListener('change', calcularHoras);
            });

            calcularHoras();
        });
    </script>
</x-app-layout>