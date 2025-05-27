{{-- resources/views/ot/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
            Editar Orden de Trabajo #{{ $ot->id_ot }}
        </h2>
    </x-slot>

    @php
        // Sacamos la descripción de detalle_ot (o cadena vacía)
        $descripcionGuardada = optional($ot->detalleOT->first())->descripcion_actividad ?? '';

        // Preparamos el array inicial para Alpine
        $initial = [
            'clienteId' => old('id_cliente', $ot->id_cliente),
            'clienteLabel' => old('cliente_label', $ot->cliente->nombre_cliente),
            'responsableId' => old('id_responsable', $ot->id_responsable),
            'responsableLabel' => old('responsable_label', $ot->responsable->nombre),
            'form' => [
                'id_estado' => old('id_estado', $ot->id_estado),
                'fecha_entrega' => old('fecha_entrega', optional($ot->fecha_entrega)->format('Y-m-d')),
                'descripcion' => old('descripcion', $descripcionGuardada),
                'servicios' => $ot->servicios
                    ->map(fn($s) => ['id' => $s->id_servicio, 'label' => $s->nombre_servicio])
                    ->toArray(),
                'productos' => $ot->detalleProductos
                    ->map(fn($p) => [
                        'id' => $p->id_producto,
                        'label' => $p->producto->marca . ' ' . $p->producto->modelo,
                        'cantidad' => $p->cantidad
                    ])->toArray(),
            ],
        ];
    @endphp

    <div class="py-6">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded shadow" x-data="otForm()">
                <form method="POST" action="{{ route('ot.update', $ot->id_ot) }}" enctype="multipart/form-data">
                    @csrf @method('PUT')

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        {{-- Cliente --}}
                        <div class="sm:col-span-2">
                            <x-input-label for="id_cliente" value="Cliente" />
                            <select id="id_cliente" name="id_cliente"
                                class="select2 w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white"
                                required>
                                <option value="">Seleccione un cliente</option>
                                @foreach($clientes as $id => $nombre)
                                    <option value="{{ $id }}" {{ $id == $ot->id_cliente ? 'selected' : '' }}>{{ $nombre }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('id_cliente')"
                                class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        {{-- Responsable --}}
                        <div class="sm:col-span-2">
                            <x-input-label for="id_responsable" value="Responsable" />
                            <select id="id_responsable" name="id_responsable"
                                class="select2 w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white"
                                required>
                                <option value="">Seleccione un responsable</option>
                                @foreach($responsables as $id => $nombre)
                                    <option value="{{ $id }}" {{ $id == $ot->id_responsable ? 'selected' : '' }}>{{ $nombre }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('id_responsable')"
                                class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        {{-- Estado --}}
                        <div>
                            <x-input-label for="id_estado" value="Estado" />
                            <select id="id_estado" name="id_estado"
                                class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white p-2" required>
                                <option value="">Seleccione un estado</option>
                                @foreach($estados as $id => $nombre)
                                    <option value="{{ $id }}" {{ $id == $ot->id_estado ? 'selected' : '' }}>{{ $nombre }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('id_estado')"
                                class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        {{-- Fecha entrega --}}
                        <div>
                            <x-input-label for="fecha_entrega" value="Fecha Estimada de Entrega" />
                            <x-text-input id="fecha_entrega" name="fecha_entrega" type="date" class="w-full"
                                :value="old('fecha_entrega', optional($ot->fecha_entrega)->format('Y-m-d'))" />
                            <x-input-error :messages="$errors->get('fecha_entrega')"
                                class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        {{-- Descripción --}}
                        <div class="sm:col-span-2">
                            <x-input-label for="descripcion" value="Descripción" />
                            <textarea id="descripcion" name="descripcion" rows="4"
                                class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white p-2"
                                placeholder="Escribe una descripción detallada...">{{ old('descripcion', $descripcionGuardada) }}</textarea>
                            <x-input-error :messages="$errors->get('descripcion')"
                                class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>
                    </div>

                    {{-- Servicios múltiples --}}
                    <div class="mt-6">
                        <x-input-label for="servicios" value="Tipos de Servicio" />
                        <select id="servicios" name="servicios[]"
                            class="select2 w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" multiple>
                            @foreach($servicios as $id => $nombre)
                                <option value="{{ $id }}" {{ in_array($id, $ot->servicios->pluck('id_servicio')->toArray()) ? 'selected' : '' }}>{{ $nombre }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('servicios')"
                            class="mt-1 text-sm text-red-600 dark:text-red-400" />
                    </div>

                    {{-- Productos asociados con cantidad --}}
                    <div class="mt-6">
                        <x-input-label for="productos" value="Productos Asociados" />
                        <div class="grid gap-2" id="productosContainer">
                            @foreach($ot->detalleProductos as $i => $p)
                                <div class="producto-item flex items-center gap-2 mb-2">
                                    <select name="productos[{{ $i }}][id]"
                                        class="select2 w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white"
                                        required>
                                        <option value="">Seleccione un producto</option>
                                        @foreach($productos as $prod)
                                            <option value="{{ $prod->id_producto }}" {{ $prod->id_producto == $p->id_producto ? 'selected' : '' }}>
                                                {{ $prod->marca }} {{ $prod->modelo }} — Stock:
                                                {{ optional($prod->inventario->first())->cantidad ?? 0 }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="number" name="productos[{{ $i }}][cantidad]" min="1"
                                        value="{{ $p->cantidad }}"
                                        class="w-20 rounded p-2 border-gray-300 dark:bg-gray-700 dark:text-white"
                                        placeholder="Cant." required />
                                    <button type="button" class="bg-red-500 text-white rounded px-2 py-1"
                                        onclick="$(this).parent().remove()">✕</button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="mt-2 px-4 py-2 bg-green-500 text-white rounded"
                            onclick="addProductoSelect()">+ Agregar Producto</button>
                        <x-input-error :messages="$errors->get('productos')"
                            class="mt-1 text-sm text-red-600 dark:text-red-400" />
                    </div>

                    {{-- Archivos Adjuntos --}}
                    <div class="mt-6">
                        <x-input-label for="archivos" value="Archivos Adjuntos" />
                        <input id="archivos" name="archivos[]" type="file" multiple
                            class="w-full text-sm text-gray-900 dark:text-gray-200" />
                        <x-input-error :messages="$errors->get('archivos')"
                            class="mt-1 text-sm text-red-600 dark:text-red-400" />
                    </div>
                    {{-- Archivos ya cargados --}}
                    @if ($ot->archivosAdjuntos && $ot->archivosAdjuntos->count())
                        <div class="mt-4">
                            <x-input-label value="Archivos ya cargados" />
                            <ul class="list-disc pl-6 text-sm text-gray-800 dark:text-gray-200">
                                @foreach ($ot->archivosAdjuntos as $archivo)
                                    <li>
                                        <a href="{{ Storage::url($archivo->ruta_archivo) }}" target="_blank"
                                            class="text-blue-500 underline">
                                            {{ $archivo->nombre_original }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif


                    {{-- Botones --}}
                    <div class="mt-6 flex justify-end space-x-2">
                        <a href="{{ route('ot.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded">Cancelar</a>
                        <x-primary-button type="submit">Guardar Cambios</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- CDN Select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            $('.select2').select2({
                placeholder: 'Seleccione una opción',
                allowClear: true,
                width: '100%'
            });
        });

        function addProductoSelect() {
            let idx = $('#productosContainer .producto-item').length;
            const html = `
            <div class="producto-item flex items-center gap-2 mb-2">
                <select name="productos[${idx}][id]" class="select2 w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" required>
                    <option value="">Seleccione un producto</option>
                    @foreach($productos as $prod)
                        <option value="{{ $prod->id_producto }}">{{ $prod->marca }} {{ $prod->modelo }} — Stock: {{ optional($prod->inventario->first())->cantidad ?? 0 }}</option>
                    @endforeach
                </select>
                <input type="number" name="productos[${idx}][cantidad]" min="1" value="1" class="w-20 rounded p-2 border-gray-300 dark:bg-gray-700 dark:text-white" placeholder="Cant." required />
                <button type="button" class="bg-red-500 text-white rounded px-2 py-1" onclick="$(this).parent().remove()">✕</button>
            </div>`;
            $('#productosContainer').append(html);
            $('#productosContainer .producto-item:last-child .select2').select2({ width: '100%' });
        }
    </script>

</x-app-layout>