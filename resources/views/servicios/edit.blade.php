<x-app-layout>
    <div class="py-6 px-8 pb-20">
        <div class="max-w-7xl mx-auto flex justify-between items-center mb-6">
            <x-title-section titulo="Modificar servicio" subtitulo="Modifica los detalles del servicio."
                rutaRegreso="{{ route('servicios.index') }}" />
        </div>

        <form action="{{ route('servicios.update', $servicio->id) }}" method="POST" class="space-y-4 mt-8">
            @csrf
            @method('PUT')

            <!-- Nombre -->
            <div>
                <x-input-label for="nombre" :value="__('Nombre del servicio')" class="text-lg" colorTexto='text-main-green' />
                <div class="relative flex items-center mt-2">
                    <span class="absolute left-5 pointer-events-none flex items-center text-muted/70">
                        <i class="fa-solid fa-scissors"></i>
                    </span>
                    <x-text-input id="nombre" class="block w-full py-3.5 pl-14 pr-4" type="text" name="nombre"
                        value="{{ old('nombre', $servicio->nombre) }}" placeholder="Ej. Corte clásico" required />
                </div>
                <x-input-error :messages="$errors->get('nombre')" class="mt-1" />
            </div>

            <!-- Duración -->
            <div>
                <x-input-label for="duracion_minutos" :value="__('Duración')" class="text-lg" colorTexto='text-main-green' />
                <div class="relative flex items-center mt-2">
                    <span class="absolute left-5 pointer-events-none flex items-center text-muted/70">
                        <i class="fa-regular fa-clock"></i>
                    </span>

                    <x-select-input id="duracion_minutos" class="block w-full py-3.5 pl-14 pr-4 appearance-none"
                        name="duracion_minutos" required>
                        <option value="">Selecciona una duración</option>
                        <option value="30" @selected(old('duracion_minutos', $servicio->duracion_minutos) == 30)>30 minutos</option>
                        <option value="45" @selected(old('duracion_minutos', $servicio->duracion_minutos) == 45)>45 minutos</option>
                        <option value="60" @selected(old('duracion_minutos', $servicio->duracion_minutos) == 60)>1 hora</option>
                        <option value="90" @selected(old('duracion_minutos', $servicio->duracion_minutos) == 90)>1 hora 30 minutos</option>
                    </x-select-input>

                    <span class="absolute right-5 pointer-events-none flex items-center text-muted/70">
                        <i class="fa-solid fa-angle-down"></i>
                    </span>
                </div>
                <x-input-error :messages="$errors->get('duracion_minutos')" class="mt-1" />
            </div>

            <!-- Precio -->
            <div>
                <x-input-label for="precio" :value="__('Precio')" class="text-lg" colorTexto='text-main-green' />
                <div class="relative flex items-center mt-2">
                    <span class="absolute left-5 pointer-events-none flex items-center text-muted/70">
                        <i class="fa-solid fa-money-check-dollar"></i>
                    </span>
                    <span class="absolute left-15 pointer-events-none flex items-center text-muted/70">
                        $
                    </span>
                    <x-text-input id="precio" class="block w-full py-3.5 pl-18 pr-4" type="text" name="precio"
                        value="{{ old('precio', number_format($servicio->precio, 0, '', '.')) }}" placeholder="20.000"
                        required />
                </div>
                <x-input-error :messages="$errors->get('precio')" class="mt-1" />
            </div>

            <!-- Descripción -->
            <div>
                <x-input-label for="descripcion" :value="__('Descripción')" class="text-lg" colorTexto='text-main-green' />
                <!-- Usando tu componente textarea -->
                <x-textarea name="notas" rows="3">{{ old('notas', $servicio->descripcion) }}</x-textarea>
                <x-input-error :messages="$errors->get('notas')" class="mt-1" />
            </div>

            <div class="mt-10 pt-6 border-t border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-main-green">Duración · {{ $servicio->duracion_minutos }} min</span>
                    <span
                        class="text-2xl font-bold text-main-green">${{ number_format($servicio->precio, 0, '', '.') }}</span>
                </div>

                <x-action-button type="submit"
                    class="w-full justify-center bg-action py-3.5 text-base">
                    <i class="fa-solid fa-pen-to-square mr-2"></i>
                    Actualizar servicio
                </x-action-button>
            </div>
        </form>

        <div class="mt-4 flex flex-col items-center space-y-4">
            <a href="{{ route('servicios.index') }}" class="text-muted transition">
                Cancelar
            </a>

            <x-delete-modal action="{{ route('servicios.destroy', $servicio) }}" title="¿Eliminar este servicio?"
                message="Se eliminará {{ $servicio->nombre }} del catálogo. Esta acción no se puede deshacer."
                itemTitle="{{ $servicio->nombre }}" itemSubtitle="{{ $servicio->duracion_minutos }} minutos"
                itemDate="Precio · ${{ number_format($servicio->precio, 0, '', '.') }}">
                <button type="submit" class="text-action font-medium">
                    Eliminar servicio
                </button>
            </x-delete-modal>
        </div>
    </div>
</x-app-layout>
