<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            Historial de Cambios en Inventario
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Filtros -->
            <form method="GET" action="{{ route('inventario.historial') }}" class="mb-4 flex flex-wrap gap-4">
                <input type="text" name="producto" value="{{ request('producto') }}" placeholder="Producto"
                    class="rounded border-gray-300 dark:bg-gray-700 dark:text-white" />
                <input type="text" name="campo" value="{{ request('campo') }}" placeholder="Campo Modificado"
                    class="rounded border-gray-300 dark:bg-gray-700 dark:text-white" />
                <input type="text" name="responsable" value="{{ request('responsable') }}" placeholder="Responsable"
                    class="rounded border-gray-300 dark:bg-gray-700 dark:text-white" />
                <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}"
                    class="rounded border-gray-300 dark:bg-gray-700 dark:text-white" />
                <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}"
                    class="rounded border-gray-300 dark:bg-gray-700 dark:text-white" />
                <button type="submit"
                    class="rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600">Filtrar</button>
                <a href="{{ route('inventario.historial') }}"
                    class="rounded bg-gray-500 px-4 py-2 text-white hover:bg-gray-600">Limpiar</a>
            </form>

            <!-- Tabla -->
            <div class="overflow-x-auto rounded bg-white dark:bg-gray-800 p-6 shadow-md">
                <table class="w-full table-auto text-left text-sm text-gray-800 dark:text-gray-100">
                    <thead>
                        <tr>
                            <th class="border-b p-2">Producto</th>
                            <th class="border-b p-2">Campo Modificado</th>
                            <th class="border-b p-2">Valor Anterior</th>
                            <th class="border-b p-2">Valor Nuevo</th>
                            <th class="border-b p-2">Responsable</th>
                            <th class="border-b p-2">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($historial as $registro)
                        <tr>
                            <td class="border-b p-2">
                                {{ $registro->inventario->producto->descripcion ?? 'Producto eliminado' }}
                            </td>
                            <td class="border-b p-2">{{ ucfirst($registro->campo_modificado) }}</td>
                            <td class="border-b p-2">{{ $registro->valor_anterior }}</td>
                            <td class="border-b p-2">{{ $registro->valor_nuevo }}</td>
                            <td class="border-b p-2">{{ $registro->responsable->nombre ?? 'N/A' }}</td>
                            <td class="border-b p-2">{{ $registro->fecha_modificacion }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-4 text-center text-gray-500 dark:text-gray-400">
                                No se encontraron registros.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $historial->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>