<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            Detalle del Movimiento de Inventario
        </h2>
    </x-slot>
    <div class="py-6">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="rounded-lg bg-white dark:bg-gray-800 p-6 shadow-md">
                <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Producto</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $inventario->producto->nombre_producto ?? '' }} {{ $inventario->producto->marca ?? '-' }}
                            {{ $inventario->producto->modelo ?? '' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Cantidad</dt>
                        <dd class="mt-1 text-gray-800 dark:text-gray-200">{{ $inventario->cantidad }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Fecha Ingreso</dt>
                        <dd class="mt-1 text-gray-800 dark:text-gray-200">{{ $inventario->fecha_ingreso ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Fecha Salida</dt>
                        <dd class="mt-1 text-gray-800 dark:text-gray-200">{{ $inventario->fecha_salida ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Estado</dt>
                        <dd class="mt-1">
                            <span
                                class="inline-block rounded px-2 py-1 text-xs font-semibold
                                {{ $inventario->estado === 'activo' ? 'bg-green-200 text-green-800 dark:bg-green-900/20 dark:text-green-300' : 'bg-red-200 text-red-800 dark:bg-red-900/20 dark:text-red-300' }}">
                                {{ ucfirst($inventario->estado) }}
                            </span>
                        </dd>
                    </div>
                </dl>
                <div class="mt-8">
                    <a href="{{ route('inventario.index') }}"
                        class="inline-block rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600">
                        ← Volver a la lista
                    </a>
                    @if (Auth::user()->rol === 'administrador')
                        @if ($inventario->estado === 'activo')
                            <button onclick="mostrarModal({{ $inventario->id_inventario }})"
                                class="ml-2 inline-block rounded bg-red-600 px-4 py-2 text-white hover:bg-red-700">
                                Desactivar
                            </button>
                        @else
                            <a href="{{ route('inventario.reactivar', $inventario->id_inventario) }}"
                                class="ml-2 inline-block rounded bg-green-600 px-4 py-2 text-white hover:bg-green-700">
                                Reactivar
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de Confirmación -->
    <div id="confirmModal"
        class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center transition-opacity duration-300">
        <div
            class="transform rounded-lg bg-white dark:bg-gray-800 p-8 shadow-md scale-95 transition-transform duration-300 ease-in-out w-full max-w-md">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white text-center">
                Confirmar Desactivación
            </h3>
            <p id="confirmMessage" class="mt-4 text-center text-gray-600 dark:text-gray-300">
                ¿Estás seguro de que deseas desactivar este registro de inventario?
            </p>
            <div class="mt-6 flex justify-center space-x-4">
                <button onclick="cerrarModal()" class="rounded bg-gray-300 px-4 py-2 text-gray-800 hover:bg-gray-400">
                    Cancelar
                </button>
                <form id="deleteForm" method="GET" action="">
                    @csrf
                    <button type="submit" class="rounded bg-red-600 px-4 py-2 text-white hover:bg-red-700">
                        Desactivar
                    </button>
                </form>
            </div>
        </div>
    </div>
    <script>
        function mostrarModal(id) {
            const modal = document.getElementById('confirmModal');
            const form = document.getElementById('deleteForm');

            form.action = `/inventario/${id}/desactivar`;
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