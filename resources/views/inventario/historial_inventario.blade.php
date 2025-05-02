<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            Historial de Cambios en Inventario
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-x-auto rounded bg-white dark:bg-gray-800 p-6 shadow-md">
                <table>
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Campo Modificado</th>
                            <th>Valor Anterior</th>
                            <th>Valor Nuevo</th>
                            <th>Responsable</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($historial as $registro)
                        <tr>
                            <td>{{ $registro->inventario->producto->descripcion ?? 'Producto eliminado' }}</td>
                            <td>{{ ucfirst($registro->campo_modificado) }}</td>
                            <td>{{ $registro->valor_anterior }}</td>
                            <td>{{ $registro->valor_nuevo }}</td>
                            <td>{{ $registro->responsable->nombre ?? 'N/A' }}</td>
                            <td>{{ $registro->fecha_modificacion }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>