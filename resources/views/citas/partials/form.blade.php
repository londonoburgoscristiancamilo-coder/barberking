@php
    $esEdicion = isset($cita);
@endphp

<!-- Cliente -->
<div>
    <h3 class="text-lg font-medium text-gray-900 mb-3">Cliente</h3>

    @if (!$esEdicion)
        <div class="mb-4">
            <x-input-label for="tipo_cliente" :value="__('Tipo de cliente')" class="text-sm font-semibold"
                colorTexto="text-gray-700" />

            <select id="tipo_cliente" name="tipo_cliente" x-model="tipo_cliente"
                class="block w-full py-3.5 px-4 mt-2 appearance-none bg-white rounded-xl border border-border-light focus:outline-none focus:border-main/70">
                <option value="existente">Cliente registrado</option>
                <option value="nuevo">Registrar nuevo cliente</option>
            </select>

            <x-input-error :messages="$errors->get('tipo_cliente')" class="mt-1" />
        </div
    @endif

    <!-- Seleccionar cliente registrado -->
    <div x-show="tipo_cliente === 'existente'">
        <x-input-label for="cliente_id" :value="__('Cliente registrado')" class="text-sm font-semibold"
            colorTexto="text-gray-700" />

        <div class="relative flex items-center mt-2">
            <span class="absolute left-4 pointer-events-none flex items-center text-muted/70">
                <i class="fa-solid fa-user"></i>
            </span>

            <select id="cliente_id" name="cliente_id" x-bind:disabled="tipo_cliente !== 'existente'"
                class="block w-full py-3.5 pl-10 pr-8 appearance-none bg-white rounded-xl border border-border-light focus:outline-none focus:border-main/70">
                <option value="">Selecciona un cliente</option>

                @foreach ($clientes as $cliente)
                    <option value="{{ $cliente->id }}"
                        @selected(old('cliente_id', $cita->cliente_id ?? '') == $cliente->id)>
                        {{ $cliente->nombres }} {{ $cliente->apellidos }} - CC {{ $cliente->documento_identidad }}
                    </option>
                @endforeach
            </select>

            <span class="absolute right-4 pointer-events-none flex items-center text-muted/70">
                <i class="fa-solid fa-angle-down"></i>
            </span>
        </div>

        <x-input-error :messages="$errors->get('cliente_id')" class="mt-1" />

        @if ($esEdicion)
            <p class="text-xs text-gray-500 mt-2">
                Cambiar el cliente asociará esta cita a otra persona registrada.
            </p>
        @endif
    </div>

    @if (!$esEdicion)
        <!-- Registrar nuevo cliente -->
        <div x-show="tipo_cliente === 'nuevo'" x-cloak>
            <div class="grid grid-cols-1 xs:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="documento_identidad" :value="__('Cédula de ciudadanía')"
                        class="text-sm font-semibold" colorTexto="text-gray-700" />

                    <x-text-input id="documento_identidad" class="block w-full py-3 px-4 mt-2"
                        type="text" inputmode="numeric" name="documento_identidad"
                        value="{{ old('documento_identidad') }}" maxlength="10"
                        placeholder="Ej. 1234567890" x-bind:disabled="tipo_cliente !== 'nuevo'" />

                    <x-input-error :messages="$errors->get('documento_identidad')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="telefono" :value="__('Teléfono')" class="text-sm font-semibold"
                        colorTexto="text-gray-700" />

                    <div class="relative flex items-center mt-2">
                        <span class="absolute left-4 pointer-events-none flex items-center text-muted/70">
                            <i class="fa-solid fa-phone"></i>
                        </span>

                        <x-text-input id="telefono" class="block w-full py-3.5 pl-10 pr-4"
                            type="tel" name="telefono" value="{{ old('telefono') }}"
                            placeholder="Ej. 3001234567" x-bind:disabled="tipo_cliente !== 'nuevo'" />
                    </div>

                    <x-input-error :messages="$errors->get('telefono')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="nombres" :value="__('Nombres')" class="text-sm font-semibold"
                        colorTexto="text-gray-700" />

                    <x-text-input id="nombres" class="block w-full py-3 px-4 mt-2"
                        type="text" name="nombres" value="{{ old('nombres') }}"
                        placeholder="Ej. Carlos Andrés" x-bind:disabled="tipo_cliente !== 'nuevo'" />

                    <x-input-error :messages="$errors->get('nombres')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="apellidos" :value="__('Apellidos')" class="text-sm font-semibold"
                        colorTexto="text-gray-700" />

                    <x-text-input id="apellidos" class="block w-full py-3 px-4 mt-2"
                        type="text" name="apellidos" value="{{ old('apellidos') }}"
                        placeholder="Ej. Castro Gómez" x-bind:disabled="tipo_cliente !== 'nuevo'" />

                    <x-input-error :messages="$errors->get('apellidos')" class="mt-1" />
                </div>
            </div>

            <p class="text-xs text-gray-500 mt-3">
                El cliente quedará registrado y podrá utilizar sus datos para futuras citas.
            </p>
        </div>
    @endif
</div>

<!-- Servicio -->
<div>
    <x-input-label for="servicio_id" :value="__('Servicio')" class="text-sm font-semibold"
        colorTexto="text-gray-700" />

    <div class="relative flex items-center mt-2">
        <span class="absolute left-5 pointer-events-none flex items-center text-muted/70">
            <i class="fa-solid fa-scissors"></i>
        </span>

        <select id="servicio_id" name="servicio_id" x-model="servicio_id" @change="fetchHoras()"
            class="block w-full py-3.5 pl-14 pr-4 appearance-none bg-white rounded-xl border border-border-light focus:outline-none focus:border-main/70">
            <option value="">Selecciona un servicio</option>

            @foreach ($servicios as $servicio)
                <option value="{{ $servicio->id }}">
                    {{ $servicio->nombre }} ({{ $servicio->duracion_minutos }} min -
                    ${{ number_format($servicio->precio, 0, ',', '.') }})
                </option>
            @endforeach
        </select>

        <span class="absolute right-5 pointer-events-none flex items-center text-muted/70">
            <i class="fa-solid fa-angle-down"></i>
        </span>
    </div>

    <x-input-error :messages="$errors->get('servicio_id')" class="mt-1" />
</div>

<div class="grid grid-cols-1 xs:grid-cols-2 gap-4">
    <!-- Barbero -->
    <div>
        <x-input-label for="barbero_id" :value="__('Barbero')" class="text-sm font-semibold"
            colorTexto="text-gray-700" />

        <div class="relative flex items-center mt-2">
            <span class="absolute left-4 pointer-events-none flex items-center text-muted/70">
                <i class="fa-solid fa-user-tie"></i>
            </span>

            <select id="barbero_id" name="barbero_id" x-model="barbero_id" @change="fetchHoras()"
                class="block w-full py-3.5 pl-10 pr-8 appearance-none bg-white rounded-xl border border-border-light focus:outline-none focus:border-main/70">
                <option value="">Selecciona</option>

                @foreach ($barberos as $barbero)
                    <option value="{{ $barbero->id }}">{{ $barbero->name }}</option>
                @endforeach
            </select>

            <span class="absolute right-4 pointer-events-none flex items-center text-muted/70">
                <i class="fa-solid fa-angle-down"></i>
            </span>
        </div>

        <x-input-error :messages="$errors->get('barbero_id')" class="mt-1" />
    </div>

    <!-- Fecha -->
    <div>
        <x-input-label for="fecha_cita" :value="__('Fecha')" class="text-sm font-semibold"
            colorTexto="text-gray-700" />

        <div class="relative flex items-center mt-2">
            <span class="absolute left-4 pointer-events-none flex items-center text-muted/70">
                <i class="fa-regular fa-calendar"></i>
            </span>

            <x-text-input id="fecha_cita" name="fecha_cita" x-model="fecha_cita"
                @change="fetchHoras()" class="block w-full py-3.5 pl-10 pr-3 bg-white"
                type="date" min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" />
        </div>

        <x-input-error :messages="$errors->get('fecha_cita')" class="mt-1" />
    </div>
</div>

<!-- Hora -->
<div>
    <x-input-label for="hora_cita" :value="__('Hora disponible')" class="text-sm font-semibold"
        colorTexto="text-gray-700" />

    <div class="relative flex items-center mt-2">
        <span class="absolute left-5 pointer-events-none flex items-center text-muted/70">
            <i class="fa-regular fa-clock"></i>
        </span>

        <select id="hora_cita" name="hora_cita" x-model="hora_cita"
            x-bind:disabled="cargando || !fecha_cita || !barbero_id || !servicio_id"
            class="block w-full py-3.5 pl-14 pr-4 appearance-none bg-white rounded-xl border border-border-light focus:outline-none focus:border-main/70 transition-colors disabled:bg-gray-50">

            <option value="" x-text="cargando ? 'Buscando espacios...' :
                (fecha_cita && barbero_id && servicio_id ?
                (horasDisponibles.length ? 'Selecciona una hora' : 'Sin disponibilidad') :
                'Selecciona las opciones previas')">
            </option>

            <template x-for="hora in horasDisponibles" :key="hora">
                <option :value="hora" x-text="hora"></option>
            </template>
        </select>

        <span class="absolute right-5 pointer-events-none flex items-center text-muted/70">
            <i class="fa-solid fa-angle-down"></i>
        </span>
    </div>

    <p class="text-xs text-gray-500 mt-2 font-medium" x-show="servicio_id"
        x-text="textoResumenTime"></p>

    <x-input-error :messages="$errors->get('hora_cita')" class="mt-1" />

    <p x-show="errorDisponibilidad" x-text="errorDisponibilidad"
        class="text-sm text-red-600 mt-1" x-cloak></p>
</div>

<!-- Notas -->
<div>
    <div class="flex justify-between items-center mb-2">
        <x-input-label for="notas" :value="__('Notas para la cita')"
            class="text-sm font-semibold" colorTexto="text-gray-700" />

        <span class="text-xs text-gray-400">Opcional</span>
    </div>

    <x-textarea id="notas" name="notas" rows="3"
        placeholder="Añade preferencias o indicaciones...">{{ old('notas', $cita->notas ?? '') }}</x-textarea>

    <x-input-error :messages="$errors->get('notas')" class="mt-1" />
</div>

<!-- Resumen y acciones -->
<div class="flex flex-col gap-3">

    <!-- Separador -->
    <div class="flex justify-center py-2">
        <div class="w-20 h-[3px] bg-[#A97351]/40"></div>
    </div>

    <!-- Resumen del servicio -->
    <div class="flex justify-between items-center" x-show="servicio_id" x-cloak>
        <span class="text-sm text-gray-500"
            x-text="`Total · ${servicioSeleccionado?.duracion_minutos || 0} min`"></span>

        <span class="text-2xl font-bold text-gray-900"
            x-text="precioFormateado"></span>
    </div>

    <!-- Botón principal -->
    <x-action-button type="submit"
        class="w-full justify-center bg-[#A04E35] hover:bg-[#8B422B] py-4 text-base rounded-xl">
        @if ($esEdicion)
            <i class="fa-solid fa-pen mr-2"></i>
            Actualizar cita
        @else
            <i class="fa-solid fa-plus mr-2"></i>
            Crear cita
        @endif
    </x-action-button>

    <!-- Cancelar -->
    <div class="text-center">
        <a href="{{ route('citas.index') }}"
            class="text-sm font-medium text-gray-500 hover:text-gray-800 transition-colors">
            Cancelar
        </a>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('citasForm', () => ({
            tipo_cliente: @js(old('tipo_cliente', 'existente')),
            servicio_id: @js((string) old('servicio_id', $cita->servicio_id ?? '')),
            barbero_id: @js((string) old('barbero_id', $cita->barbero_id ?? '')),
            fecha_cita: @js(old('fecha_cita', $esEdicion ? $cita->fecha_cita->format('Y-m-d') : '')),
            hora_cita: @js(substr(old('hora_cita', $cita->hora_cita ?? ''), 0, 5)),
            hora_original: @js($esEdicion ? substr($cita->hora_cita, 0, 5) : ''),
            fecha_original: @js($esEdicion ? $cita->fecha_cita->format('Y-m-d') : ''),
            servicio_original: @js($esEdicion ? (string) $cita->servicio_id : ''),
            barbero_original: @js($esEdicion ? (string) $cita->barbero_id : ''),
            cita_id: @js($esEdicion ? $cita->id : null),
            servicios: @js($servicios),
            horasDisponibles: [],
            cargando: false,
            errorDisponibilidad: '',
            solicitudActual: 0,

            get servicioSeleccionado() {
                return this.servicios.find(s => s.id == this.servicio_id);
            },

            get precioFormateado() {
                if (!this.servicioSeleccionado) return '$0';

                return '$' + new Intl.NumberFormat('es-CO')
                    .format(this.servicioSeleccionado.precio);
            },

            get textoResumenTime() {
                if (!this.servicioSeleccionado) return '';

                const duracion = Number(this.servicioSeleccionado.duracion_minutos);

                if (!this.hora_cita) {
                    return `${duracion} min de servicio`;
                }

                const [horas, minutos] = this.hora_cita.split(':').map(Number);
                const total = horas * 60 + minutos + duracion;

                const finHoras = String(Math.floor(total / 60) % 24).padStart(2, '0');
                const finMinutos = String(total % 60).padStart(2, '0');

                return `Finaliza a las ${finHoras}:${finMinutos} · ${duracion} min de servicio`;
            },

            async fetchHoras(preservarHora = false) {
                const solicitud = ++this.solicitudActual;
                const horaAnterior = preservarHora ? this.hora_cita : '';

                this.hora_cita = '';
                this.horasDisponibles = [];
                this.errorDisponibilidad = '';

                if (!this.barbero_id || !this.fecha_cita || !this.servicio_id) {
                    this.cargando = false;
                    return;
                }

                this.cargando = true;

                try {
                    const parametros = new URLSearchParams({
                        fecha_cita: this.fecha_cita,
                        barbero_id: this.barbero_id,
                        servicio_id: this.servicio_id
                    });

                    if (this.cita_id) {
                        parametros.set('excluir_cita_id', this.cita_id);
                    }

                    const response = await fetch(`/api/citas/disponibilidad?${parametros}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (!response.ok) {
                        throw new Error(`Error HTTP ${response.status}`);
                    }

                    const horas = await response.json();

                    if (solicitud !== this.solicitudActual) return;

                    this.horasDisponibles = horas;

                    // Conservar la hora original al editar sin cambiar el horario
                    const mismoHorario =
                        this.fecha_cita === this.fecha_original &&
                        this.servicio_id === this.servicio_original &&
                        this.barbero_id === this.barbero_original;

                    if (this.cita_id && mismoHorario &&
                        !this.horasDisponibles.includes(this.hora_original)) {
                        this.horasDisponibles.push(this.hora_original);
                        this.horasDisponibles.sort();
                    }

                    if (preservarHora && this.horasDisponibles.includes(horaAnterior)) {
                        this.hora_cita = horaAnterior;
                    }
                } catch (error) {
                    if (solicitud !== this.solicitudActual) return;

                    console.error('Error consultando disponibilidad:', error);

                    this.errorDisponibilidad =
                        'No fue posible consultar los horarios disponibles.';
                } finally {
                    if (solicitud === this.solicitudActual) {
                        this.cargando = false;
                    }
                }
            },

            init() {
                if (this.servicio_id && this.barbero_id && this.fecha_cita) {
                    this.fetchHoras(true);
                }
            }
        }));
    });
</script>