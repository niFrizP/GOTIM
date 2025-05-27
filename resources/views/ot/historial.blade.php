<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            Historial de Cambios en Órdenes de Trabajo
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Filtros -->
            <form method="GET" action="{{ route('ot.historial.global') }}" class="mb-4 flex flex-wrap gap-4">
                <input type="text" name="ot" value="{{ request('ot') }}" placeholder="ID OT"
                    class="rounded border-gray-300 dark:bg-gray-700 dark:text-white p-2" />
                <input type="text" name="campo" value="{{ request('campo') }}" placeholder="Campo Modificado"
                    class="rounded border-gray-300 dark:bg-gray-700 dark:text-white p-2" />
                <input type="text" name="responsable" value="{{ request('responsable') }}" placeholder="Responsable"
                    class="rounded border-gray-300 dark:bg-gray-700 dark:text-white p-2" />
                <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}"
                    class="rounded border-gray-300 dark:bg-gray-700 dark:text-white p-2" />
                <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}"
                    class="rounded border-gray-300 dark:bg-gray-700 dark:text-white p-2" />
                <button type="submit" class="rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600">
                    Filtrar
                </button>
                <a href="{{ route('ot.historial.global') }}"
                    class="rounded bg-gray-500 px-4 py-2 text-white hover:bg-gray-600">
                    Limpiar
                </a>
            </form>

            <!-- Tabla Consolidada -->
            <div class="overflow-x-auto rounded bg-white dark:bg-gray-800 p-6 shadow-md">
                <table class="w-full table-auto text-left text-sm text-gray-800 dark:text-gray-100">
                    <thead>
                        <tr>
                            <th class="border-b p-2">ID Historial</th>
                            <th class="border-b p-2">ID OT</th>
                            <th class="border-b p-2">Usuario</th>
                            <th class="border-b p-2">Campo Modificado</th>
                            <th class="border-b p-2">Fecha Modificación</th>
                            <th class="border-b p-2">Descripción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($historial as $grupo)
                            <tr>
                                <td class="border-b p-2">{{ $grupo['id_historial'] }}</td>
                                <td class="border-b p-2">{{ $grupo['id_ot'] }}</td>
                                <td class="border-b p-2">{{ $grupo['usuario'] }}</td>
                                <td class="border-b p-2">
                                    <ul class="list-disc list-inside space-y-1">
                                        @foreach(is_array($grupo['campos']) ? $grupo['campos'] : explode(',', $grupo['campos']) as $campo)
                                            <li>{{ trim($campo) }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="border-b p-2">{{ $grupo['fecha_modificacion'] }}</td>
                                <td class="border-b p-2">
                                    <ul class="list-disc ps-4">
                                        {!! implode('', $grupo['descripciones']) !!}
                                    </ul>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center p-4 text-gray-500">
                                    No se encontraron registros.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Paginación -->
                <div class="mt-4">
                    {{ $historial->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>