{{-- resources/views/ot/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
            Orden de Trabajo #{{ $ot->id_ot }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8 space-y-6">

            {{-- Datos generales --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="font-medium text-gray-700 dark:text-gray-300">Cliente</dt>
                        <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ $ot->cliente->nombre_cliente }}
                            {{ $ot->cliente->apellido_cliente }}
                        </dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-700 dark:text-gray-300">Rut</dt>
                        <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ $ot->cliente->rut }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-700 dark:text-gray-300">Tipo Cliente</dt>
                        <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ $ot->cliente->tipo_cliente }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-700 dark:text-gray-300">Responsable</dt>
                        <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ $ot->responsable->nombre }}
                            {{ $ot->responsable->apellido }}
                        </dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-700 dark:text-gray-300">Estado</dt>
                        <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ $ot->estadoOT->nombre_estado }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-700 dark:text-gray-300">Creación</dt>
                        <dd class="mt-1 text-gray-900 dark:text-gray-100">
                            {{ \Carbon\Carbon::parse($ot->fecha_creacion)->format('d/m/Y') }}
                        </dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-700 dark:text-gray-300">Entrega Estimada</dt>
                        <dd class="mt-1 text-gray-900 dark:text-gray-100">
                            {{ $ot->fecha_entrega ? $ot->fecha_entrega->format('d/m/Y') : '—' }}
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- Descripción --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">Descripción</h3>
                @if($ot->detalleOT->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400">—</p>
                @else
                    @foreach($ot->detalleOT as $detalle)
                        <p class="text-gray-900 dark:text-gray-100">{{ $detalle->descripcion_actividad }}</p>
                    @endforeach
                @endif
            </div>

            {{-- Tipos de Trabajo --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">Tipos de Trabajo</h3>
                <ul class="list-disc pl-5 space-y-1">
                    @forelse($ot->servicios as $s)
                        <li class="text-gray-900 dark:text-gray-100">{{ $s->nombre_servicio }}</li>
                    @empty
                        <li class="text-gray-500">No hay tipos de trabajo asignados.</li>
                    @endforelse
                </ul>
            </div>

            {{-- Productos Asociados --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">Productos Asociados</h3>

                @if($ot->detalleProductos->isEmpty())
                    <p class="text-gray-500">No hay productos asociados.</p>
                @else
                    <table class="w-full table-auto text-left text-gray-800 dark:text-gray-100">
                        <thead>
                            <tr>
                                <th class="border-b p-2">Producto</th>
                                <th class="border-b p-2">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ot->detalleProductos as $d)
                                <tr>
                                    <td class="border-b p-2">{{ $d->producto->nombre_producto }} {{ $d->producto->marca }}
                                        {{ $d->producto->modelo }}
                                    </td>
                                    <td class="border-b p-2">{{ $d->cantidad }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            {{-- Archivos Adjuntos --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">Archivos Adjuntos</h3>

                @if($ot->archivosAdjuntos->isEmpty())
                    <p class="text-gray-500">No hay archivos adjuntos.</p>
                @else
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($ot->archivosAdjuntos as $file)
                            <li>
                                <a href="{{ asset('storage/' . $file->ruta_archivo) }}" target="_blank"
                                    class="text-blue-600 hover:underline dark:text-blue-400">
                                    {{ $file->nombre_original }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- Historial de Cambios --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">Historial de Cambios</h3>
                @if ($historial->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400">No hay historial de cambios para esta orden.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto text-left text-sm text-gray-800 dark:text-gray-100">
                            <thead>
                                <tr>
                                    <th class="border-b p-2">ID Hist.</th>
                                    <th class="border-b p-2">Usuario</th>
                                    <th class="border-b p-2">Fecha</th>
                                    <th class="border-b p-2">Campos</th>
                                    <th class="border-b p-2">Descripción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($historial as $h)
                                    <tr>
                                        <td class="border-b p-2">{{ $h['id_historial'] }}</td>
                                        <td class="border-b p-2">{{ $h['usuario'] }}</td>
                                        <td class="border-b p-2">{{ $h['fecha_modificacion'] }}</td>
                                        <td class="border-b p-2">
                                            <ul class="list-disc list-inside space-y-1">
                                                @foreach(is_array($h['campos']) ? $h['campos'] : explode(',', $h['campos']) as $campo)
                                                    <li>{{ trim($campo) }}</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td class="border-b p-2">
                                            <ul class="list-disc list-inside space-y-1">
                                                @foreach($h['descripciones'] as $desc)
                                                    {!! $desc !!}
                                                @endforeach
                                            </ul>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- Acciones --}}
            <div class="flex justify-end space-x-2">
                <a href="{{ route('ot.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                    Volver
                </a>
                <a href="{{ route('ot.edit', $ot->id_ot) }}"
                    class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    Editar
                </a>
                <a href="{{ route('ot.export', $ot->id_ot) }}" target="_blank"
                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Exportar PDF
                </a>
            </div>
        </div>
    </div>
</x-app-layout>