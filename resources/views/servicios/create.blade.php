<x-app-layout>
    <div class="py-6 px-8">
        <div class="mx-auto flex justify-between items-center mb-6">
            <x-title-section
                titulo="Nuevo Servicio"
                subtitulo="Completa los detalles para crear un servicio"
                rutaRegreso="{{ route('servicios.index') }}" />
        </div>

        <form
            action="{{ route('servicios.store') }}"
            method="POST"
            class="space-y-4 mt-8"
            novalidate>
            @csrf

            @include('servicios.partials.form')

            <div class="text-center pt-2">
                <a
                    href="{{ route('servicios.index') }}"
                    class="text-sm text-muted hover:text-secundary transition-colors">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</x-app-layout>