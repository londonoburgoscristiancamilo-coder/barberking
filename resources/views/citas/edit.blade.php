<x-app-layout>
    <div class="py-6 px-8 pb-20" x-data="citasForm(@js($servicios))">
        <div class="max-w-3xl mx-auto">
            <x-title-section titulo="Modificar Cita" subtitulo="Completa los detalles para modificar un espacio."
                rutaRegreso="{{ route('citas.index') }}" />

            <form action="{{ route('citas.update', $citas->id) }}" method="POST" class="space-y-6 mt-8" novalidate>
                @csrf
                @method('PUT')

                <!-- Cliente -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Cliente</h3>
                    <div class="grid grid-cols-1 xs:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="nombre_cliente" :value="__('Nombre')" class="text-sm font-semibold"
                                colorTexto='text-gray-700' />
                            <x-text-input id="nombre_cliente" class="block w-full py-3 px-4 mt-2" type="text"
                                name="nombre_cliente" value="{{ old('nombre_cliente', $citas->nombre_cliente) }}"
                                required placeholder="Ej. Carlos" />
                            <x-input-error :messages="$errors->get('nombre_cliente')" class="mt-1" />
                        </div>
                        <div>
                            <x-input-label for="apellido_cliente" :value="__('Apellido')" class="text-sm font-semibold"
                                colorTexto='text-gray-700' />
                            <x-text-input id="apellido_cliente" class="block w-full py-3 px-4 mt-2" type="text"
                                name="apellido_cliente" value="{{ old('apellido_cliente', $citas->apellido_cliente) }}"
                                required placeholder="Ej. Castro" />
                            <x-input-error :messages="$errors->get('apellido_cliente')" class="mt-1" />
                        </div>
                        <div>
                            <x-input-label for="telefono_cliente" :value="__('Teléfono')" class="text-sm font-semibold"
                                colorTexto='text-gray-700' />
                            <div class="relative flex items-center mt-2">
                                <span class="absolute left-4 pointer-events-none flex items-center text-muted/70 z-10">
                                    <i class="fa-solid fa-phone"></i>
                                </span>
                                <x-text-input id="telefono_cliente" class="block w-full py-3.5 pl-10 pr-4"
                                    type="tel" name="telefono_cliente" value="{{ old('telefono_cliente', $citas->telefono_cliente) }}"
                                    placeholder="Ej. 3001234567" />
                            </div>
                            <x-input-error :messages="$errors->get('telefono_cliente')" class="mt-1" />
                        </div>
                    </div>
                </div>

                <!-- Servicio -->
                <div>
                    <x-input-label for="servicio_id" :value="__('Servicios')" class="text-sm font-semibold"
                        colorTexto='text-gray-700' />
                    <div class="relative flex items-center mt-2">
                        <span class="absolute left-5 pointer-events-none flex items-center text-muted/70">
                            <i class="fa-solid fa-scissors"></i>
                        </span>
                        <select id="servicio_id" x-model="servicio_id" @change="fetchHoras"
                            class="block w-full py-3.5 pl-14 pr-4 appearance-none bg-white rounded-xl border border-border-light focus:outline-none focus:border-main/70 transition-colors"
                            name="servicio_id" required>
                            <option value="">Selecciona un servicio</option>
                            @foreach ($servicios as $servicio)
                                <option value="{{ $servicio->id }}" @selected(old('servicio_id', $citas->servicio_id) == $servicio->id)>
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
                            colorTexto='text-gray-700' />
                        <div class="relative flex items-center mt-2">
                            <span class="absolute left-4 pointer-events-none flex items-center text-muted/70 z-10">
                                <i class="fa-solid fa-user-tie"></i>
                            </span>
                            <select id="barbero_id" x-model="barbero_id" @change="fetchHoras"
                                class="block w-full py-3.5 pl-10 pr-8 appearance-none bg-white rounded-xl border border-border-light focus:outline-none focus:border-main/70 transition-colors truncate text-ellipsis"
                                name="barbero_id" required>
                                <option value="">Selecciona</option>
                                @foreach ($barberos as $barbero)
                                    <option value="{{ $barbero->id }}" @selected(old('barbero_id', $citas->barbero_id) == $barbero->id)>
                                        {{ $barbero->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="absolute right-4 pointer-events-none flex items-center text-muted/70 z-10">
                                <i class="fa-solid fa-angle-down"></i>
                            </span>
                        </div>
                        <x-input-error :messages="$errors->get('barbero_id')" class="mt-1" />
                    </div>

                    <!-- Fecha -->
                    <div>
                        <x-input-label for="fecha_cita" :value="__('Fecha')" class="text-sm font-semibold"
                            colorTexto='text-gray-700' />
                        <div class="relative flex items-center mt-2">
                            <span class="absolute left-4 pointer-events-none flex items-center text-muted/70 z-10">
                                <i class="fa-regular fa-calendar"></i>
                            </span>
                            <x-text-input id="fecha_cita" x-model="fecha_cita" @change="fetchHoras"
                                class="block w-full py-3.5 pl-10 pr-3 bg-white" type="date" name="fecha_cita"
                                min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" required />
                        </div>
                        <x-input-error :messages="$errors->get('fecha_cita')" class="mt-1" />
                    </div>
                </div>

                <div>
                    <x-input-label for="hora_cita" :value="__('Hora disponible')" class="text-sm font-semibold"
                        colorTexto='text-gray-700' />
                    <div class="relative flex items-center mt-2">
                        <span class="absolute left-5 pointer-events-none flex items-center text-muted/70">
                            <i class="fa-regular fa-clock"></i>
                        </span>
                        <select id="hora_cita" x-model="hora_cita"
                            class="block w-full py-3.5 pl-14 pr-4 appearance-none bg-white rounded-xl border border-border-light focus:outline-none focus:border-main/70 transition-colors disabled:bg-gray-50"
                            name="hora_cita" x-bind:disabled="!fecha_cita || !barbero_id || !servicio_id" required>
                            <option value="" x-show="cargando">Buscando espacios...</option>
                            <option value="" x-show="!cargando && horasDisponibles.length === 0"
                                x-text="(fecha_cita && barbero_id && servicio_id) ? 'Sin disponibilidad' : 'Selecciona opciones previas'">
                            </option>
                            <option value="" x-show="!cargando && horasDisponibles.length > 0">Inicio sugerido
                                para el servicio</option>

                            <option value="{{ \Carbon\Carbon::parse($citas->hora_cita)->format('H:i') }}"
                                x-show="!cargando && (!horasDisponibles.includes('{{ \Carbon\Carbon::parse($citas->hora_cita)->format('H:i') }}') && fecha_cita === '{{ \Carbon\Carbon::parse($citas->fecha_cita)->format('Y-m-d') }}' && barbero_id == '{{ $citas->barbero_id }}')">
                                {{ \Carbon\Carbon::parse($citas->hora_cita)->format('H:i') }} (Hora actual)
                            </option>

                            <template x-for="hora in horasDisponibles" :key="hora">
                                <option x-bind:value="hora" x-text="hora"></option>
                            </template>
                        </select>
                        <span class="absolute right-5 pointer-events-none flex items-center text-muted/70">
                            <i class="fa-solid fa-angle-down"></i>
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 mt-2 font-medium" x-text="textoResumenTime" x-show="servicio_id">
                    </p>
                    <x-input-error :messages="$errors->get('hora_cita')" class="mt-1" />
                </div>

                <div>
                    <div class="flex justify-between items-center mb-2">
                        <x-input-label for="notas" :value="__('Notas para la cita')" class="text-sm font-semibold"
                            colorTexto='text-gray-700' />
                        <span class="text-xs text-gray-400">Opcional</span>
                    </div>
                    <x-textarea id="notas" name="notas" rows="3"
                        placeholder="Añade preferencias o indicaciones...">{{ old('notas', $citas->notas) }}</x-textarea>
                    <x-input-error :messages="$errors->get('notas')" class="mt-1" />
                </div>

                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex justify-between items-center mb-6" x-show="servicio_id" style="display: none;">
                        <span class="text-sm text-gray-500"
                            x-text="`Total · ${servicioSeleccionado?.duracion_minutos || 0} min`"></span>
                        <span class="text-2xl font-bold text-gray-900" x-text="precioFormateado"></span>
                    </div>

                    <x-action-button type="submit"
                        class="w-full justify-center bg-[#A04E35] hover:bg-[#8B422B] py-4 text-base rounded-xl">
                        <i class="fa-solid fa-pen mr-2"></i>
                        Actualizar cita
                    </x-action-button>

                    <div class="text-center pt-4">
                        <a href="{{ route('citas.index') }}"
                            class="text-sm font-medium text-gray-500 hover:text-gray-800 transition-colors">
                            Cancelar
                        </a>
                    </div>
                </div>
            </form>

            <div class="mt-4 flex flex-col items-center space-y-4">
                <x-delete-modal action="{{ route('citas.destroy', $citas) }}" title="¿Eliminar esta cita?"
                    message="La cita se eliminará de tu agenda y el horario quedará disponible. Esta acción no se puede deshacer."
                    itemTitle="{{ $citas->nombre_cliente }} {{ $citas->apellido_cliente }}"
                    itemSubtitle="Cita de {{ $citas->servicio->nombre }}"
                    itemDate="{{ \Carbon\Carbon::parse($citas->fecha_cita)->format('d/m/Y') }} · {{ \Carbon\Carbon::parse($citas->hora_cita)->format('H:i') }}">
                    <button type="submit" class="text-action font-medium hover:underline">
                        Eliminar cita
                    </button>
                </x-delete-modal>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('citasForm', (serviciosCatalogo) => ({
            servicio_id: '{{ old('servicio_id', $citas->servicio_id) }}',
            barbero_id: '{{ old('barbero_id', $citas->barbero_id) }}',
            fecha_cita: '{{ old('fecha_cita', \Carbon\Carbon::parse($citas->fecha_cita)->format('Y-m-d')) }}',
            hora_cita: '{{ old('hora_cita', \Carbon\Carbon::parse($citas->hora_cita)->format('H:i')) }}',
            hora_original: '{{ \Carbon\Carbon::parse($citas->hora_cita)->format('H:i') }}',
            horasDisponibles: [],
            cargando: false,
            servicios: serviciosCatalogo,

            get servicioSeleccionado() {
                return this.servicios.find(s => s.id == this.servicio_id);
            },

            get textoResumenTime() {
                if (!this.servicioSeleccionado) return '';
                if (!this.hora_cita)
                    return `${this.servicioSeleccionado.duracion_minutos} min de servicio`;

                let [h, m] = this.hora_cita.split(':').map(Number);
                let date = new Date();
                date.setHours(h, m, 0);
                date.setMinutes(date.getMinutes() + this.servicioSeleccionado.duracion_minutos);

                let endH = String(date.getHours()).padStart(2, '0');
                let endM = String(date.getMinutes()).padStart(2, '0');

                return `Finaliza a las ${endH}:${endM} · ${this.servicioSeleccionado.duracion_minutos} min de servicio`;
            },

            get precioFormateado() {
                if (!this.servicioSeleccionado) return '$0';
                return '$' + new Intl.NumberFormat('es-CO').format(this.servicioSeleccionado
                    .precio);
            },

            async fetchHoras(evento) {
                if (!this.barbero_id || !this.fecha_cita || !this.servicio_id) {
                    this.horasDisponibles = [];
                    return;
                }

                this.cargando = true;
                this.horasDisponibles = [];

                if (evento) {
                    this.hora_cita = '';
                }

                try {
                    const url =
                        `/api/citas/disponibilidad?fecha_cita=${this.fecha_cita}&barbero_id=${this.barbero_id}&servicio_id=${this.servicio_id}&excluir_cita_id={{ $citas->id }}`;
                    const response = await fetch(url, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    this.horasDisponibles = await response.json();

                    if (!evento || this.horasDisponibles.includes(this.hora_original)) {
                        let hora_preseleccionada =
                            '{{ old('hora_cita', \Carbon\Carbon::parse($citas->hora_cita)->format('H:i')) }}';

                        if (hora_preseleccionada.split(':').length === 3) {
                            hora_preseleccionada = hora_preseleccionada.substring(0, 5);
                        }

                        this.hora_cita = hora_preseleccionada;
                    }

                } catch (error) {} finally {
                    this.cargando = false;
                }
            },

            init() {
                if (this.servicio_id && this.barbero_id && this.fecha_cita) {
                    this.fetchHoras();
                }
            }
        }));
    });
</script>
