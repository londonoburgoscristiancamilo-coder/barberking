<x-app-layout>
    <div class="py-6 px-8 pb-28">
        <div class="mx-auto flex justify-between items-center mb-6">
            <x-title-section
                titulo="Nuevo horario"
                subtitulo="Completa los detalles para crear un turno de trabajo."
                rutaRegreso="{{ route('horarios.index') }}" />
        </div>

        <form action="{{ route('horarios.store') }}" method="POST"
            id="form-horario" class="space-y-6 mt-6" novalidate>
            @csrf

            @include('horarios.partials.form')
        </form>
    </div>
</x-app-layout>