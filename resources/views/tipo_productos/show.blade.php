<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            Detalle del Tipo de Producto: {{ $tipo->nombre }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="rounded-lg bg-white dark:bg-gray-800 p-6 shadow-md">
                <h3 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100">Detalles</h3>

                <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Nombre</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $tipo->nombre }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Estado</dt>
                        <dd class="mt-2">
                            <span
                                class="inline-block rounded px-2 py-1 text-xs font-semibold
                                {{ $tipo->estado ? 'bg-green-200 text-green-800 dark:bg-green-900/20 dark:text-green-300' : 'bg-red-200 text-red-800 dark:bg-red-900/20 dark:text-red-300' }}">
                                {{ $tipo->estado ? 'Activo' : 'Inactivo' }}
                            </span>
                        </dd>
                    </div>
                </dl>

                <div class="mt-8 flex gap-3">
                    <a href="{{ route('tipo_productos.index') }}"
                        class="inline-block rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600">
                        ← Volver a la lista
                    </a>

                    <a href="{{ route('tipo_productos.edit', $tipo->tipo_producto_id) }}"
                        class="inline-block rounded bg-yellow-500 px-4 py-2 text-white hover:bg-yellow-600">
                        Editar
                    </a>
                    @if (Auth::user()->rol === 'administrador')
                        {{-- Botón para desactivar/activar tipo de producto --}}
                        @if ($tipo->estado)
                            <button onclick="mostrarModal()"
                                class="inline-block rounded bg-red-600 px-4 py-2 text-white hover:bg-red-700">
                                Desactivar
                            </button>
                        @else
                            <form action="{{ route('tipo_productos.activar', $tipo->tipo_producto_id) }}" method="POST"
                                class="inline-block">
                                @csrf
                                <button type="submit" class="rounded bg-green-600 px-4 py-2 text-white hover:bg-green-700">
                                    Activar
                                </button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>

            {{-- Tabla de productos --}}
            <div class="mt-8 rounded-lg bg-white dark:bg-gray-800 p-6 shadow-md">
                <h3 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100">Productos Asociados</h3>
                @if ($productos->count())
                    <table class="w-full table-auto border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-left dark:bg-gray-700">
                                <th class="p-2 text-sm text-gray-700 dark:text-gray-300">Nombre</th>
                                <th class="p-2 text-sm text-gray-700 dark:text-gray-300">Modelo</th>
                                <th class="p-2 text-sm text-gray-700 dark:text-gray-300">Código</th>
                                <th class="p-2 text-sm text-gray-700 dark:text-gray-300">Categoria</th>
                                <th class="p-2 text-sm text-gray-700 dark:text-gray-300">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-900 dark:text-gray-100">
                            @foreach ($productos as $producto)
                                <tr class="border-t border-gray-200 dark:border-gray-700">
                                    <td class="p-2">{{ $producto->nombre_producto }}</td>
                                    <td class="p-2">{{ $producto->modelo }}</td>
                                    <td class="p-2">{{ $producto->codigo }}</td>
                                    <td class="p-2">{{ $producto->categoria->nombre_categoria }}</td>
                                    <td class="p-2">
                                        <span class="{{ $producto->estado ? 'text-green-500' : 'text-red-500' }}">
                                            {{ $producto->estado ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $productos->links() }}
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400">No hay productos asociados a este tipo.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal de confirmación -->
    <div id="confirmModal"
        class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center transition-opacity duration-300">
        <div
            class="transform rounded-lg bg-white dark:bg-gray-800 p-8 shadow-md scale-95 transition-transform duration-300 ease-in-out w-full max-w-md">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white text-center">
                Confirmar Desactivación
            </h3>
            <p class="mt-4 text-center text-gray-600 dark:text-gray-300">
                ¿Estás seguro de que deseas desactivar el tipo de producto <strong>{{ $tipo->nombre }}</strong>?
            </p>
            <div class="mt-6 flex justify-center space-x-4">
                <button onclick="cerrarModal()" class="rounded bg-gray-300 px-4 py-2 text-gray-800 hover:bg-gray-400">
                    Cancelar
                </button>
                <form method="POST" action="{{ route('tipo_productos.desactivar', $tipo->tipo_producto_id) }}">
                    @csrf
                    @method('PATCH') <!-- Aquí está el cambio -->
                    <button type="submit" class="rounded bg-red-600 px-4 py-2 text-white hover:bg-red-700">
                        Desactivar
                    </button>
                </form>
            </div>
        </div>
    </div>


    <script>
        function mostrarModal() {
            const modal = document.getElementById('confirmModal');
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

<!-- It is never too late to be what you might have been. - George Eliot -->