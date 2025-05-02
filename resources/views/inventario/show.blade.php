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
                            {{ $inventario->producto->marca ?? '-' }} {{ $inventario->producto->modelo ?? '' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Cantidad</dt>
                        <dd class="mt-1 text-gray-800 dark:text-gray-200">
                            {{ $inventario->cantidad }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Fecha Ingreso</dt>
                        <dd class="mt-1 text-gray-800 dark:text-gray-200">
                            {{ $inventario->fecha_ingreso ?? '-' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Fecha Salida</dt>
                        <dd class="mt-1 text-gray-800 dark:text-gray-200">
                            {{ $inventario->fecha_salida ?? '-' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Estado</dt>
                        <dd class="mt-1">
                            <span class="inline-block rounded px-2 py-1 text-xs font-semibold
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

                    @if ($inventario->estado === 'activo')
                    <a href="{{ route('inventario.desactivar', $inventario->id_inventario) }}"
                        class="ml-2 inline-block rounded bg-red-600 px-4 py-2 text-white hover:bg-red-700"
                        onclick="return confirm('¿Estás seguro de desactivar este registro?')">
                        Desactivar
                    </a>
                    @else
                    <a href="{{ route('inventario.reactivar', $inventario->id_inventario) }}"
                        class="ml-2 inline-block rounded bg-green-600 px-4 py-2 text-white hover:bg-green-700">
                        Reactivar
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>