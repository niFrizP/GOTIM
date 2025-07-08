<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            {{ __('Lista de Categorías') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            {{-- Botones principales --}}
            <div class="mb-4 flex flex-wrap gap-3">
                <a href="{{ route('categorias.create') }}"
                    class="inline-flex items-center gap-2 rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600">
                    <i class="fa-solid fa-square-plus"></i> Nueva Categoría
                </a>
                <a href="{{ route('categorias.inactivas') }}"
                    class="inline-flex items-center gap-2 rounded bg-yellow-500 px-4 py-2 text-white hover:bg-yellow-600">
                    <i class="fa-solid fa-eye-slash"></i> Ver Inactivas
                </a>
            </div>

            {{-- Mensaje de éxito --}}
            @if (session('success'))
                <div class="mb-4 rounded bg-green-100 p-4 text-green-800 dark:text-green-300 dark:bg-green-900/20">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Tabla de categorías --}}
            <div class="overflow-x-auto rounded bg-white dark:bg-gray-800 p-4 shadow dark:shadow-md">
                <table class="w-full table-auto text-left text-sm text-gray-800 dark:text-gray-100">
                    <thead>
                        <tr>
                            <th class="border-b p-2">Nombre</th>
                            <th class="border-b p-2">Descripción</th>
                            <th class="border-b p-2">Estado</th>
                            <th class="border-b p-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categorias as $categoria)
                            <tr>
                                <td class="border-b p-2">{{ $categoria->nombre_categoria }}</td>
                                <td class="border-b p-2">{{ $categoria->descripcion }}</td>
                                <td class="border-b p-2">
                                    <span
                                        class="inline-block rounded px-2 py-1 text-sm font-semibold
                                                        {{ $categoria->estado === 'Inactiva'
                                                            ? 'bg-red-200 text-red-800 dark:bg-red-900/20 dark:text-red-300'
                                                            : 'bg-green-200 text-green-800 dark:bg-green-900/20 dark:text-green-300' }}">
                                        {{ $categoria->estado }}
                                    </span>
                                </td>
                                <td class="border-b p-2">
                                    <div class="flex items-center gap-4">
                                        {{-- Ver --}}
                                        <a href="{{ route('categorias.show', $categoria->id_categoria) }}"
                                            class="text-blue-600 hover:underline dark:text-blue-400">Ver</a>

                                        {{-- Editar --}}
                                        <a href="{{ route('categorias.edit', $categoria->id_categoria) }}"
                                            class="text-yellow-500 hover:underline dark:text-yellow-400">Editar</a>

                                        {{-- Botón de desactivación lógica con confirmación --}}
                                        {{-- Este usa el modal --}}
                                        @if (Auth::user()->rol === 'Administrador')
                                            @if ($categoria->estado === 'Activa')
                                                <button
                                                    onclick="mostrarModal(event, '{{ route('categorias.desactivar', $categoria->id_categoria) }}', '{{ $categoria->nombre_categoria }}')"
                                                    class="text-red-500 hover:underline">
                                                    Desactivar
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal de confirmación --}}
    <div id="confirmModal"
        class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center transition-opacity">
        <div
            class="bg-white dark:bg-gray-900 p-6 rounded-lg shadow-lg transform transition-all scale-95 w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">Confirmar Desactivación</h3>
            <p id="confirmMessage" class="mb-4 text-gray-700 dark:text-gray-300">
                ¿Estás seguro de que deseas desactivar esta categoría?
            </p>
            <form method="POST" id="deleteForm">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-4">
                    <button type="button" onclick="cerrarModal()"
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

    {{-- Script del modal --}}
    <script>
        function mostrarModal(event, url, nombre) {
            event.preventDefault(); // Evita que el botón recargue la página si es tipo submit
            const modal = document.getElementById('confirmModal');
            const form = document.getElementById('deleteForm');
            const message = document.getElementById('confirmMessage');

            form.action = url;
            message.innerHTML = `¿Estás seguro de que deseas desactivar <strong>${nombre}</strong>?`;

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
                document.getElementById('confirmMessage').innerHTML = '';
            }, 300);
        }
    </script>
</x-app-layout>

<!-- La vida es lo que pasa mientras estás ocupado haciendo otros planes. - John Lennon -->
