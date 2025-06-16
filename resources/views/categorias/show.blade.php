<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
            {{ __('Detalles de la Categoría') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="rounded bg-white dark:bg-gray-800 p-6 shadow-md dark:shadow">
                <div class="mb-4">
                    <h3 class="text-lg font-bold text-gray-700 dark:text-gray-200">Nombre</h3>
                    <p class="text-gray-800 dark:text-gray-300">{{ $categoria->nombre_categoria }}</p>
                </div>

                <div class="mb-4">
                    <h3 class="text-lg font-bold text-gray-700 dark:text-gray-200">Descripción</h3>
                    <p class="text-gray-800 dark:text-gray-300">
                        {{ $categoria->descripcion ?? 'Sin descripción' }}
                    </p>
                </div>

                <div class="mb-4">
                    <h3 class="text-lg font-bold text-gray-700 dark:text-gray-200">Estado</h3>
                    <span class="inline-block rounded px-2 py-1 text-sm font-semibold
                        {{ $categoria->estado === 'Inactiva'
    ? 'bg-red-200 text-red-800 dark:bg-red-900/20 dark:text-red-300'
    : 'bg-green-200 text-green-800 dark:bg-green-900/20 dark:text-green-300' }}">
                        {{ $categoria->estado }}
                    </span>
                </div>

                <div class="mt-6 flex flex-wrap items-center gap-4">
                    <a href="{{ route('categorias.index') }}"
                        class="inline-block rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600">
                        ← Volver a la lista
                    </a>
                    @if (Auth::user()->rol === 'administrador')
                        {{-- Editar --}}
                        @if ($categoria->estado === 'Inactiva')
                            <button
                                onclick="mostrarModalReactivar('{{ route('categorias.reactivar', $categoria->id_categoria) }}', '{{ $categoria->nombre_categoria }}')"
                                class="inline-block rounded bg-green-600 px-4 py-2 text-white hover:bg-green-700">
                                Reactivar Categoría
                            </button>
                        @else
                            <button
                                onclick="mostrarModalDesactivar('{{ route('categorias.desactivar', $categoria->id_categoria) }}', '{{ $categoria->nombre_categoria }}')"
                                class="inline-block rounded bg-red-600 px-4 py-2 text-white hover:bg-red-700">
                                Desactivar Categoría
                            </button>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- Modal de confirmación de reactivación --}}
    <div id="modalReactivar"
        class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center transition-opacity">
        <div
            class="bg-white dark:bg-gray-900 p-6 rounded-lg shadow-lg transform transition-all scale-95 w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">Confirmar Reactivación</h3>
            <p id="mensajeReactivar" class="mb-4 text-gray-700 dark:text-gray-300">
                ¿Deseas reactivar esta categoría?
            </p>
            <form method="POST" id="formReactivar">
                @csrf
                <div class="flex justify-end gap-4">
                    <button type="button" onclick="cerrarModalReactivar()"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                        Sí, Reactivar
                    </button>
                </div>
            </form>
        </div>
    </div>
    {{-- Modal de confirmación de desactivación --}}
    <div id="modalDesactivar"
        class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center transition-opacity">
        <div
            class="bg-white dark:bg-gray-900 p-6 rounded-lg shadow-lg transform transition-all scale-95 w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">Confirmar Desactivación</h3>
            <p id="mensajeDesactivar" class="mb-4 text-gray-700 dark:text-gray-300">
                ¿Deseas desactivar esta categoría?
            </p>
            <form method="POST" id="formDesactivar">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-4">
                    <button type="button" onclick="cerrarModalDesactivar()"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        Sí, Desactivar
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
<script>
    function mostrarModalReactivar(url, nombre) {
        const modal = document.getElementById('modalReactivar');
        const form = document.getElementById('formReactivar');
        const mensaje = document.getElementById('mensajeReactivar');

        form.action = url;
        mensaje.innerHTML = `¿Estás seguro de que deseas reactivar <strong>${nombre}</strong>?`;

        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.add('opacity-100');
            modal.querySelector('div').classList.add('scale-100');
            modal.querySelector('div').classList.remove('scale-95');
        }, 10);
    }

    function cerrarModalReactivar() {
        const modal = document.getElementById('modalReactivar');
        modal.classList.remove('opacity-100');
        modal.querySelector('div').classList.remove('scale-100');
        modal.querySelector('div').classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.getElementById('mensajeReactivar').innerHTML = '';
        }, 300);
    }

    function mostrarModalDesactivar(url, nombre) {
        const modal = document.getElementById('modalDesactivar');
        const form = document.getElementById('formDesactivar');
        const mensaje = document.getElementById('mensajeDesactivar');

        form.action = url;
        mensaje.innerHTML = `¿Estás seguro de que deseas desactivar <strong>${nombre}</strong>?`;

        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.add('opacity-100');
            modal.querySelector('div').classList.add('scale-100');
            modal.querySelector('div').classList.remove('scale-95');
        }, 10);
    }

    function cerrarModalDesactivar() {
        const modal = document.getElementById('modalDesactivar');
        modal.classList.remove('opacity-100');
        modal.querySelector('div').classList.remove('scale-100');
        modal.querySelector('div').classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.getElementById('mensajeDesactivar').innerHTML = '';
        }, 300);
    }
</script>


<!-- No es el hombre que tiene poco, sino el hombre que desea más, el que es pobre. - Séneca -->