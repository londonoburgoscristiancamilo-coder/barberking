<x-app-layout>
    <div class="py-6 px-8 pb-20">
        <div class="max-w-3xl mx-auto">
            <x-title-section titulo="Nueva cita"
                subtitulo="Completa los detalles para reservar un espacio."
                rutaRegreso="{{ route('citas.index') }}" />

            <form action="{{ route('citas.store') }}" method="POST"
                class="space-y-5 mt-8" x-data="citasForm" novalidate>
                @csrf

                @include('citas.partials.form')
            </form>
        </div>
    </div>
</x-app-layout>