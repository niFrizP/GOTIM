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
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Archivos Adjuntos</h3>

                @if($ot->archivosAdjuntos->isEmpty())
                    <p class="text-gray-500">No hay archivos adjuntos.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($ot->archivosAdjuntos as $file)
                            @php
                                $ext = strtolower(pathinfo($file->ruta_archivo, PATHINFO_EXTENSION));
                                $url = asset('storage/' . $file->ruta_archivo);
                            @endphp

                            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded shadow space-y-2">
                                <p class="text-sm text-gray-800 dark:text-gray-100 font-medium">
                                    {{ $file->nombre_original }}
                                </p>

                                @if(in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                    <div class="flex gap-2 flex-wrap">
                                        <button onclick="mostrarImagenModal('{{ $url }}')"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                            🖼️ Ver Imagen
                                        </button>
                                        <a href="{{ $url }}" download
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                            ⬇️ Descargar Imagen
                                        </a>
                                    </div>

                                @elseif($ext === 'pdf')
                                    <div class="flex gap-2 flex-wrap">
                                        <button onclick="mostrarPDFModal('{{ $url }}')"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                            📄 Ver PDF
                                        </button>
                                        <a href="{{ $url }}" download
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                                            ⬇️ Descargar PDF
                                        </a>
                                    </div>

                                @else
                                    <a href="{{ $url }}" download
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                                        📎 Descargar Archivo
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            <!-- Modal para ver imagen -->
            <div id="imagenModal"
                class="fixed inset-0 z-50 hidden bg-black bg-opacity-80 flex items-center justify-center">
                <div class="relative max-w-3xl mx-auto">
                    <img id="imagenAmpliada" src="" class="max-h-[80vh] rounded-lg shadow-xl" alt="Imagen ampliada" />
                    <button onclick="cerrarImagenModal()"
                        class="absolute top-2 right-2 text-white text-2xl font-bold">&times;</button>
                </div>
            </div>

            <!-- Modal para ver PDF -->
            <div id="pdfModal"
                class="fixed inset-0 z-50 hidden bg-black bg-opacity-80 flex items-center justify-center">
                <div class="relative w-full max-w-5xl h-[90vh] bg-white rounded-lg shadow-lg overflow-hidden">
                    <iframe id="pdfViewer" src="" class="w-full h-full" frameborder="0"></iframe>
                    <button onclick="cerrarPDFModal()"
                        class="absolute top-2 right-2 text-black text-2xl font-bold z-50">&times;</button>
                </div>
            </div>
            {{-- Historial de Cambios --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">Historial de Cambios</h3>

                @if ($historial->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400">No hay historial de cambios para esta orden.</p>
                @else
                    <div class="overflow-x-auto rounded bg-white dark:bg-gray-800">
                        <table class="min-w-full table-auto text-left text-sm text-gray-800 dark:text-gray-100">
                            <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-200">
                                <tr>
                                    <th class="px-4 py-3 whitespace-nowrap">ID Historial</th>
                                    <th class="px-4 py-3">Usuario</th>
                                    <th class="px-4 py-3 whitespace-nowrap">Fecha</th>
                                    <th class="px-4 py-3">Campo(s)</th>
                                    <th class="px-4 py-3">Descripción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($historial as $h)
                                    <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-4 py-2 align-top">{{ $h['id_historial'] }}</td>
                                        <td class="px-4 py-2 align-top">{{ $h['usuario'] }}</td>
                                        <td class="px-4 py-2 align-top whitespace-nowrap">{{ $h['fecha_modificacion'] }}</td>
                                        <td class="px-4 py-2 align-top">
                                            <ul class="list-disc ps-5 space-y-1">
                                                @foreach($h['campos'] as $campo)
                                                    <li class="break-words">{{ $campo }}</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td class="px-4 py-2 align-top">
                                            <ul class="list-disc ps-4">
                                                {!! implode('', $h['descripciones']) !!}
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

    <script>
        // mostrar y ocultar modales para imágenes 
        function mostrarImagenModal(src) {
            document.getElementById('imagenAmpliada').src = src;
            document.getElementById('imagenModal').classList.remove('hidden');
        }

        function cerrarImagenModal() {
            document.getElementById('imagenModal').classList.add('hidden');
            document.getElementById('imagenAmpliada').src = '';
        }
        // mostrar y ocultar modales para  PDFs

        function mostrarPDFModal(src) {
            document.getElementById('pdfViewer').src = src;
            document.getElementById('pdfModal').classList.remove('hidden');
        }

        function cerrarPDFModal() {
            document.getElementById('pdfModal').classList.add('hidden');
            document.getElementById('pdfViewer').src = '';
        }
    </script>
</x-app-layout>