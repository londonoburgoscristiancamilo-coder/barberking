<div x-data="{ open: false }">
    
    <!-- Botón Hamburguesa Absoluto (se desplaza con la página) -->
    <button @click="open = !open" :class="{ 'border-main hover:bg-main/5': open, 'border-main hover:bg-main/10': !open }"
        class="absolute top-4.5 right-4 sm:right-6 lg:right-8 z-60 w-10 h-10 rounded-full border flex items-center justify-center focus:outline-none transition-colors duration-300">

        <div class="relative w-5 h-3.5">
            <!-- Línea superior -->
            <span class="absolute left-0 block w-5 h-0.5 rounded-full transition-all duration-300 origin-center"
                :class="{ 'bg-main top-1.5 rotate-45': open, 'bg-main top-0': !open }"></span>

            <!-- Línea central -->
            <span class="absolute left-0 top-1.5 block w-5 h-0.5 rounded-full transition-all duration-300"
                :class="{ 'bg-main opacity-0 scale-50': open, 'bg-main opacity-100 scale-100': !open }"></span>

            <!-- Línea inferior -->
            <span class="absolute left-0 block h-0.5 rounded-full transition-all duration-300 origin-center"
                :class="{ 'bg-main w-5 top-1.5 -rotate-45': open, 'bg-main w-3 top-3': !open }"></span>
        </div>
    </button>

    <!-- Overlay oscuro (Fondo negro semitransparente al abrir el menú) -->
    <div x-show="open" x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="open = false" style="display: none;"
        class="fixed inset-0 bg-black/40 z-10]">
    </div>

    <!-- Panel Lateral (Off-canvas que desliza desde la derecha) -->
    <div x-show="open" x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-300 transform" x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full" style="display: none;"
        class="fixed top-0 right-0 w-[75vw] sm:w-87.5 h-screen bg-[#F4F1EA] shadow-2xl z-50 pt-28 px-4">

        <!-- Lista de enlaces con borde derecho -->
        <div class="flex flex-col items-end gap-3 border-r border-main pr-4 mr-2">
            <a href="{{ route('dashboard') }}"
                class="text-[10px] font-medium tracking-[0.2em] text-secundary hover:text-main active:text-main focus:text-main transition-colors uppercase">Inicio</a>
            <a href="#"
                class="text-[10px] font-medium tracking-[0.2em] text-secundary hover:text-main active:text-main focus:text-main transition-colors uppercase">Citas</a>
            <a href="#"
                class="text-[10px] font-medium tracking-[0.2em] text-secundary hover:text-main active:text-main focus:text-main transition-colors uppercase">Horarios</a>
            <a href="#"
                class="text-[10px] font-medium tracking-[0.2em] text-secundary hover:text-main active:text-main focus:text-main transition-colors uppercase">Servicios</a>
            <a href="{{ route('profile.edit') }}"
                class="text-[10px] font-medium tracking-[0.2em] text-secundary hover:text-main active:text-main focus:text-main transition-colors uppercase">Perfil</a>

            <!-- Cerrar Sesión -->
            <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                @csrf
                <button type="submit"
                    class="text-[10px] font-medium tracking-[0.2em] text-secundary hover:text-main active:text-main focus:text-main transition-colors uppercase focus:outline-none cursor-pointer">
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </div>
</div>
