<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            Historial de Cambios en Órdenes de Trabajo
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Filtros -->
            <form method="GET" action="{{ route('ot.historial.global') }}"
                class="mb-6 grid grid-cols-1 md:grid-cols-5 lg:grid-cols-6 gap-4 items-end bg-white dark:bg-gray-800 p-4 rounded shadow">

                <div>
                    <label for="ot" class="block text-sm text-gray-700 dark:text-gray-300">ID OT</label>
                    <input type="text" name="ot" id="ot" value="{{ request('ot') }}" placeholder="ID OT"
                        class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white p-2" />
                </div>

                <div>
                    <label for="campo" class="block text-sm text-gray-700 dark:text-gray-300">Campo</label>
                    <input type="text" name="campo" id="campo" value="{{ request('campo') }}"
                        placeholder="Campo Modificado"
                        class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white p-2" />
                </div>

                <div>
                    <label for="cliente" class="block text-sm text-gray-700 dark:text-gray-300">Cliente</label>
                    <input type="text" name="cliente" id="cliente" value="{{ request('cliente') }}"
                        placeholder="Nombre Cliente"
                        class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white p-2" />
                </div>

                <div>
                    <label for="rut" class="block text-sm text-gray-700 dark:text-gray-300">RUT</label>
                    <input type="text" name="rut" id="rut" value="{{ request('rut') }}" placeholder="RUT Cliente"
                        class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white p-2" />
                </div>

                <div>
                    <label for="responsable" class="block text-sm text-gray-700 dark:text-gray-300">Responsable</label>
                    <input type="text" name="responsable" id="responsable" value="{{ request('responsable') }}"
                        placeholder="Responsable"
                        class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white p-2" />
                </div>

                <div class="md:col-span-2 lg:col-span-2 flex gap-4">
                    <div class="w-full">
                        <label for="fecha_inicio" class="block text-sm text-gray-700 dark:text-gray-300">Fecha
                            Inicio</label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio" value="{{ request('fecha_inicio') }}"
                            class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white p-2" />
                    </div>
                    <div class="w-full">
                        <label for="fecha_fin" class="block text-sm text-gray-700 dark:text-gray-300">Fecha Fin</label>
                        <input type="date" name="fecha_fin" id="fecha_fin" value="{{ request('fecha_fin') }}"
                            class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white p-2" />
                    </div>
                </div>

                <div class="md:col-span-3 lg:col-span-2 flex gap-2 justify-end pt-2">
                    <button type="submit" class="rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600 transition">
                        Filtrar
                    </button>
                    <a href="{{ route('ot.historial.global') }}"
                        class="rounded bg-gray-500 px-4 py-2 text-white hover:bg-gray-600 transition">
                        Limpiar
                    </a>
                </div>
            </form>


            <!-- Tabla Consolidada -->
            <div class="overflow-x-auto rounded bg-white dark:bg-gray-800 p-6 shadow-md">
                <table class="min-w-full table-auto text-left text-sm text-gray-800 dark:text-gray-100">
                    <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-200">
                        <tr>
                            <th class="px-4 py-3 whitespace-nowrap">ID Historial</th>
                            <th class="px-4 py-3 whitespace-nowrap">ID OT</th>
                            <th class="px-4 py-3">Cliente</th>
                            <th class="px-4 py-3 whitespace-nowrap min-w-[140px]">RUT</th>
                            <th class="px-4 py-3">Usuario</th>
                            <th class="px-4 py-3">Campo Modificado</th>
                            <th class="px-4 py-3 whitespace-nowrap">Fecha</th>
                            <th class="px-4 py-3">Descripción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($historial as $grupo)
                            <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-2 align-top">{{ $grupo['id_historial'] }}</td>
                                <td class="px-4 py-2 align-top">{{ $grupo['id_ot'] }}</td>
                                <td class="px-4 py-2 align-top">{{ $grupo['cliente'] }}</td>
                                <td class="px-4 py-2 align-top min-w-[140px]">{{ $grupo['rut'] }}</td>
                                <td class="px-4 py-2 align-top">{{ $grupo['usuario'] }}</td>
                                <td class="px-4 py-2 align-top">
                                    <ul class="list-disc list-inside space-y-1">
                                        @foreach(is_array($grupo['campos']) ? $grupo['campos'] : explode(',', $grupo['campos']) as $campo)
                                            <li>{{ trim($campo) }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="px-4 py-2 align-top whitespace-nowrap">{{ $grupo['fecha_modificacion'] }}</td>
                                <td class="px-4 py-2 align-top">
                                    <ul class="list-disc ps-4">
                                        {!! implode('', $grupo['descripciones']) !!}
                                    </ul>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center p-4 text-gray-500 dark:text-gray-400">
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