<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
            {{ __('Categorías Inactivas') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            <a href="{{ route('categorias.index') }}"
                class="mb-4 inline-block rounded bg-gray-500 px-4 py-2 text-white hover:bg-gray-600">
                <i class="fa-solid fa-arrow-left"></i> Volver a Activas
            </a>

            @if (session('success'))
                <div class="mb-4 rounded bg-green-100 p-4 text-green-800 dark:text-green-300 dark:bg-green-900/20">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-x-auto rounded bg-white dark:bg-gray-800 p-4 shadow dark:shadow-md">
                <table class="w-full table-auto text-left text-sm text-gray-800 dark:text-gray-100">
                    <thead>
                        <tr class="border-b text-gray-700 dark:text-gray-300">
                            <th class="p-2">Nombre</th>
                            <th class="p-2">Descripción</th>
                            <th class="p-2 text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categorias as $categoria)
                            <tr class="border-b hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <td class="p-2">{{ $categoria->nombre_categoria }}</td>
                                <td class="p-2">{{ $categoria->descripcion ?? 'Sin descripción' }}</td>
                                <td class="p-2 text-center">
                                    <button
                                        onclick="mostrarModalReactivar('{{ route('categorias.reactivar', $categoria->id_categoria) }}', '{{ $categoria->nombre_categoria }}')"
                                        class="inline-flex items-center gap-1 rounded bg-green-500 px-3 py-1.5 text-sm text-white hover:bg-green-600 transition">
                                        <i class="fa-solid fa-rotate-left"></i> Reactivar
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="p-4 text-center text-gray-500 dark:text-gray-400">
                                    No hay categorías inactivas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
</script>


<!-- Bien comenzado es estar a mitad de camino. - Aristóteles -->
