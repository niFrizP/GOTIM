<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            Lista de Servicios
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <a href="{{ route('servicios.create') }}"
                class="mb-4 inline-block rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600">
                <i class="fa-solid fa-plus"></i> Nuevo Servicio
            </a>

            @if (session('success'))
            <div class="mb-4 rounded bg-green-100 p-4 text-green-800 dark:text-green-300 dark:bg-green-900/20">
                {{ session('success') }}
            </div>
            @endif

            <div class="overflow-x-auto rounded bg-white dark:bg-gray-800 p-4 shadow dark:shadow-md">
                <table class="w-full table-auto text-left text-sm text-gray-800 dark:text-gray-100">
                    <thead>
                        <tr>
                            <th class="border-b p-2">Nombre</th>
                            <th class="border-b p-2">Descripción</th>
                            <th class="border-b p-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($servicios as $servicio)
                        <tr>
                            <td class="border-b p-2">{{ $servicio->nombre_servicio }}</td>
                            <td class="border-b p-2">{{ $servicio->descripcion }}</td>
                            <td class="border-b p-2 space-x-2">
                                <a href="{{ route('servicios.show', $servicio->id_servicio) }}" class="text-gray-600 hover:underline dark:text-gray-300">Ver</a>
                                <a href="{{ route('servicios.edit', $servicio->id_servicio) }}" class="text-blue-500 hover:underline">Editar</a>
                                <button onclick="mostrarModal('{{ $servicio->id_servicio }}', '{{ $servicio->nombre_servicio }}')" class="text-red-500 hover:underline">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación -->
    <div id="confirmModal"
        class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center transition-opacity duration-300">

        <div class="transform rounded-lg bg-white dark:bg-gray-800 p-8 shadow-md scale-95 transition-transform duration-300 ease-in-out w-full max-w-md">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white text-center">Confirmar Eliminación</h3>
            <p id="confirmMessage" class="mt-4 text-center text-gray-600 dark:text-gray-300">
                ¿Estás seguro de que deseas eliminar este servicio?
            </p>
            <div class="mt-6 flex justify-center space-x-4">
                <button onclick="cerrarModal()"
                    class="rounded bg-gray-300 px-4 py-2 text-gray-800 hover:bg-gray-400">
                    Cancelar
                </button>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="rounded bg-red-600 px-4 py-2 text-white hover:bg-red-700">
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

            form.action = `/servicios/${id}`; // ✅ Ruta correcta
            message.innerHTML = `¿Estás seguro de que deseas eliminar el servicio <strong>${nombreServicio}</strong>?`; // ✅ Nombre dinámico

            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.add('opacity-100');
                modal.querySelector('div').classList.add('scale-100');
                modal.querySelector('div').classList.remove('scale-95');
            }, 10);
        }

        function cerrarModal() {
            const modal = document.getElementById('confirmModal');
            const message = document.getElementById('confirmMessage');
            modal.classList.remove('opacity-100');
            modal.querySelector('div').classList.remove('scale-100');
            modal.querySelector('div').classList.add('scale-95');

            setTimeout(() => {
                modal.classList.add('hidden');
                // Resetea el mensaje
                message.innerHTML = `¿Estás seguro de que deseas eliminar este servicio?`;
            }, 300);
        }
    </script>
</x-app-layout>