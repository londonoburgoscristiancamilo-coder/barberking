@props(['action', 'title', 'message', 'itemTitle', 'itemSubtitle', 'itemDate'])

<div x-data="{
    confirmDelete(event) {
        event.preventDefault();

        Swal.fire({
            html: `
                <div class='flex flex-col text-left'>
                    <div class='mb-4 inline-flex h-12 w-12 items-center justify-center rounded-full bg-action/10 text-action'>
                        <i class='fa-regular fa-trash-can text-xl'></i>
                    </div>

                    <h2 class='text-2xl font-semibold text-main-green mb-2'>{{ $title }}</h2>
                    <p class='text-sm text-muted mb-6'>{{ $message }}</p>

                    <div class='rounded-xl bg-[#F5F2EB] p-4'>
                        <p class='font-semibold text-main-green'>{{ $itemTitle }}</p>
                        @if($itemSubtitle)
                            <p class='text-sm text-muted mt-1'>{{ $itemSubtitle }}</p>
                        @endif
                        @if($itemDate)
                            <p class='text-sm text-muted mt-2 flex items-center'>
                                <i class='fa-regular fa-calendar mr-2'></i>
                                {{ $itemDate }}
                            </p>
                        @endif
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Eliminar',
            cancelButtonText: 'Cancelar',
            customClass: {
                popup: 'barber-modal rounded-2xl p-6 w-full max-w-sm',
                htmlContainer: 'm-0',
                actions: 'barber-actions w-full mt-0 flex flex-row flex-nowrap gap-3',
                confirmButton: 'm-0 w-1/3 rounded-lg bg-action px-4 py-3 text-sm font-semibold text-white transition-colors',
                cancelButton: 'm-0 w-1/3 rounded-lg border border-border-light bg-white px-4 py-3 text-sm font-semibold text-muted transition-colors'
            },
            buttonsStyling: false,
            showCloseButton: false,
            focusConfirm: false,
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                this.$refs.form.submit();
            }
        });
    }
}">
    <!-- El formulario que envuelve el botón original -->
    <form x-ref="form" action="{{ $action }}" method="POST" @submit="confirmDelete">
        @csrf
        @method('DELETE')

        <!-- El slot permite poner cualquier botón como disparador -->
        {{ $slot }}
    </form>
</div>
