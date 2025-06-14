{{-- resources/views/ot/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
            Lista de Órdenes de Trabajo
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- Nueva OT --}}
            <div class="flex items-center justify-between mb-4">
                <a href="{{ route('ot.create') }}"
                    class="inline-block bg-blue-500 px-4 py-2 text-white rounded hover:bg-blue-600">
                    + Nueva OT
                </a>

                <a href="{{ route('ot.historial.global') }}"
                    class="inline-block bg-gray-600 px-4 py-2 text-white rounded hover:bg-gray-700">
                    <i class="fa-solid fa-clock-rotate-left"></i> Ver Historial
                </a>
            </div>

            {{-- Éxito --}}
            @if (session('success'))
                <div class="mb-4 bg-green-100 p-4 text-green-800 rounded dark:bg-green-900/20">
                    {{ session('success') }}
                </div>
            @endif

            {{-- FILTROS --}}
            <div class="mb-6 bg-white dark:bg-gray-700 p-4 rounded shadow-md">
                <form method="GET" action="{{ route('ot.index') }}"
                    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">

                    {{-- Cliente --}}
                    <div>
                        <label for="cliente" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                            Cliente
                        </label>
                        <select id="cliente" name="cliente"
                            class="w-full p-2 border rounded dark:bg-gray-800 dark:text-gray-100">
                            <option value="">Todos</option>
                            @foreach ($clientes as $id => $nombre)
                                <option value="{{ $id }}" @selected(request('cliente') == $id)>{{ $nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Responsable --}}
                    <div>
                        <label for="responsable" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                            Responsable
                        </label>
                        <select id="responsable" name="responsable"
                            class="w-full p-2 border rounded dark:bg-gray-800 dark:text-gray-100">
                            <option value="">Todos</option>
                            @foreach ($responsables as $id => $nombre)
                                <option value="{{ $id }}" @selected(request('responsable') == $id)>{{ $nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Estado --}}
                    <div>
                        <label for="estado" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                            Estado
                        </label>
                        <select id="estado" name="estado"
                            class="w-full p-2 border rounded dark:bg-gray-800 dark:text-gray-100">
                            <option value="">Todos</option>
                            @foreach ($estados as $id => $nombre)
                                <option value="{{ $id }}" @selected(request('estado') == $id)>{{ $nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Fecha de Creación --}}
                    <div>
                        <label for="fecha_creacion" class="block text-sm font-medium  text-gray-800 dark:text-gray-100">
                            Fecha de Creación
                        </label>
                        <input type="date" id="fecha_creacion" name="fecha_creacion"
                            value="{{ request('fecha_creacion') }}"
                            class="w-full p-2 border rounded dark:bg-gray-800 dark:text-gray-100" />
                    </div>

                    {{-- Botones --}}
                    <div class="col-span-1 sm:col-span-2 md:col-span-4 flex justify-end space-x-2 mt-2">
                        <a href="{{ route('ots.exportar.pdf', request()->query()) }}"
                            class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                            Exportar Reporte PDF
                        </a>

                        <a href="{{ route('ot.index') }}"
                            class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                            Limpiar
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Filtrar
                        </button>
                    </div>
                </form>
            </div>

            {{-- TABLA --}}
            <div class="overflow-x-auto bg-white dark:bg-gray-800 p-4 rounded shadow">
                <table class="w-full table-auto text-left text-sm text-gray-800 dark:text-gray-100">
                    <thead>
                        <tr>
                            <th class="border-b p-2">ID</th>
                            <th class="border-b p-2">Cliente</th>
                            <th class="border-b p-2">Responsable</th>
                            <th class="border-b p-2">Estado</th>
                            <th class="border-b p-2">Creación</th>
                            <th class="border-b p-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ordenes as $ot)
                            <tr>
                                <td class="border-b p-2">{{ $ot->id_ot }}</td>
                                <td class="border-b p-2">{{ $ot->cliente->nombre_cliente }}
                                    {{ $ot->cliente->apellido_cliente }} ({{ $ot->cliente->tipo_cliente }})
                                </td>
                                <td class="border-b p-2">
                                    {{ $ot->responsable->nombre }} {{ $ot->responsable->apellido }}
                                    ({{ $ot->responsable->rol }})
                                </td>

                                <td class="border-b p-2">
                                    <span class="inline-block rounded px-2 py-1 text-xs font-semibold">
                                        {{ $ot->estadoOT->nombre_estado }}
                                    </span>
                                </td>
                                <td class="border-b p-2">
                                    {{ \Carbon\Carbon::parse($ot->fecha_creacion)->format('d/m/Y') }}
                                </td>
                                <td class="border-b p-2 space-x-2">
                                    {{-- Ver --}}
                                    <a href="{{ route('ot.show', $ot->id_ot) }}"
                                        class="text-gray-600 hover:underline dark:text-gray-300">
                                        Ver
                                    </a>

                                    {{-- Editar --}}
                                    <a href="{{ route('ot.edit', $ot->id_ot) }}" class="text-blue-500 hover:underline">
                                        Editar
                                    </a>

                                    {{-- Exportar PDF --}}
                                    <a href="{{ route('ot.export', $ot->id_ot) }}" target="_blank"
                                        class="text-green-600 hover:underline">
                                        Exportar PDF
                                    </a>
                                    @if (Auth::user()->rol === 'administrador')
                                        {{-- Inhabilitar si no está finalizada --}}
                                        @if ($ot->fase === 'Habilitado')
                                            <form action="{{ route('ot.desactivar', $ot->id_ot) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-red-500 hover:underline"
                                                    onclick="return confirm('¿Inhabilitar esta OT?')">
                                                    Inhabilitar
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('ot.reactivar', $ot->id_ot) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-500 hover:underline"
                                                    onclick="return confirm('¿Reactivar esta OT?')">
                                                    Reactivar
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="border-b p-4 text-center">
                                    No se encontraron órdenes.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- PAGINACIÓN --}}
                <div class="mt-4">
                    {{ $ordenes->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>