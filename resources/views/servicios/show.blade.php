<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            Detalle del Servicio
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="rounded-lg bg-white dark:bg-gray-800 p-6 shadow-md">
                <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Nombre</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $servicio->nombre_servicio }}
                        </dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Descripción</dt>
                        <dd class="mt-1 text-gray-800 dark:text-gray-200">
                            {{ $servicio->descripcion }}
                        </dd>
                    </div>
                </dl>

                <div class="mt-8">
                    <a href="{{ route('servicios.index') }}"
                        class="inline-block rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600">
                        ← Volver a la lista
                    </a>
                    @if (Auth::user()->rol === 'Administrador')
                        @if (isset($servicio))
                            <button
                                onclick="mostrarModal('{{ $servicio->id_servicio }}', '{{ $servicio->nombre_servicio }}')"
                                class="inline-block rounded bg-red-600 px-4 py-2 text-white hover:bg-red-700 ml-2">
                                Eliminar Servicio
                            </button>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación-->
    <div id="confirmModal"
        class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center transition-opacity duration-300">

        <div
            class="transform rounded-lg bg-white dark:bg-gray-800 p-8 shadow-md scale-95 transition-transform duration-300 ease-in-out w-full max-w-md">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white text-center">Confirmar Eliminación</h3>
            <p id="confirmMessage" class="mt-4 text-center text-gray-600 dark:text-gray-300">
                ¿Estás seguro de que deseas eliminar este servicio?
            </p>

            <div class="mt-6 flex justify-center space-x-4">
                <button onclick="cerrarModal()" class="rounded bg-gray-300 px-4 py-2 text-gray-800 hover:bg-gray-400">
                    Cancelar
                </button>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded bg-red-600 px-4 py-2 text-white hover:bg-red-700">
                        Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>



    <script>
        function mostrarModal(id, nombreServicio) {
            const modal = document.getElementById('confirmModal');
            const form = document.getElementById('deleteForm');
            const message = document.getElementById('confirmMessage');

            form.action = `/servicios/${id}`; // Actualizamos la acción del formulario
            message.innerHTML = `¿Estás seguro de que deseas eliminar el servicio <strong>${nombreServicio}</strong>?`;

            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.add('opacity-100');
                modal.querySelector('div').classList.add('scale-100');
                modal.querySelector('div').classList.remove('scale-95');
            }, 10);
        }

        function cerrarModal() {
            const modal = document.getElementById('confirmModal');
            modal.classList.remove('opacity-100');
            modal.querySelector('div').classList.remove('scale-100');
            modal.querySelector('div').classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
    </script>


</x-app-layout>
