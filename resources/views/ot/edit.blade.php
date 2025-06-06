<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
            Editar Orden de Trabajo #{{ $ot->id_ot }}
        </h2>
    </x-slot>

    @php
        $descripcionGuardada = optional($ot->detalleOT->first())->descripcion_actividad ?? '';

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
                    ->map(
                        fn($p) => [
                            'id' => $p->id_producto,
                            'label' => $p->producto->marca . ' ' . $p->producto->modelo,
                            'cantidad' => $p->cantidad,
                        ],
                    )
                    ->toArray(),
            ],
        ];
    @endphp

    <div class="py-6">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded shadow" x-data="otForm()">
                <form method="POST" action="{{ route('ot.update', $ot->id_ot) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        {{-- Cliente --}}
                        <div class="sm:col-span-2">
                            <x-input-label for="id_cliente" value="Cliente" />
                            <select id="id_cliente" name="id_cliente"
                                class="select2 w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white p-2"
                                data-placeholder="Seleccione un cliente" required>
                                <option value="">Seleccione un cliente</option>
                                @foreach ($clientes as $id => $nombre)
                                    <option value="{{ $id }}" {{ $id == $ot->id_cliente ? 'selected' : '' }}>
                                        {{ $nombre }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('id_cliente')" class="mt-1" />
                        </div>

                        {{-- Responsable --}}
                        <div class="sm:col-span-2">
                            <x-input-label for="id_responsable" value="Responsable" />
                            <select id="id_responsable" name="id_responsable"
                                class="select2 w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white"
                                required>
                                <option value="">Seleccione un responsable</option>
                                @foreach ($responsables as $id => $nombre)
                                    <option value="{{ $id }}"
                                        {{ $id == $ot->id_responsable ? 'selected' : '' }}>{{ $nombre }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('id_responsable')" class="mt-1" />
                        </div>

                        {{-- Estado --}}
                        <div>
                            <x-input-label for="id_estado" value="Estado" />
                            <select id="id_estado" name="id_estado"
                                class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white p-2" required>
                                <option value="">Seleccione un estado</option>
                                @foreach ($estados as $id => $nombre)
                                    <option value="{{ $id }}" {{ $id == $ot->id_estado ? 'selected' : '' }}>
                                        {{ $nombre }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('id_estado')" class="mt-1" />
                        </div>

                        {{-- Fecha entrega --}}
                        <div>
                            <x-input-label for="fecha_entrega" value="Fecha Estimada de Entrega" />
                            <x-text-input id="fecha_entrega" name="fecha_entrega" type="date" class="w-full"
                                :value="old('fecha_entrega', optional($ot->fecha_entrega)->format('Y-m-d'))" />
                            <x-input-error :messages="$errors->get('fecha_entrega')" class="mt-1" />
                        </div>

                        {{-- Descripción --}}
                        <div class="sm:col-span-2">
                            <x-input-label for="descripcion" value="Descripción" />
                            <textarea id="descripcion" name="descripcion" rows="4"
                                class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white p-2"
                                placeholder="Escribe una descripción detallada...">{{ old('descripcion', $descripcionGuardada) }}</textarea>
                            <x-input-error :messages="$errors->get('descripcion')" class="mt-1" />
                        </div>
                    </div>

                    {{-- Servicios múltiples --}}
                    <div class="mt-6">
                        <x-input-label for="servicios" value="Tipos de Servicio" />
                        <select id="servicios" name="servicios[]"
                            class="select2 w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" multiple>
                            @foreach ($servicios as $id => $nombre)
                                <option value="{{ $id }}"
                                    {{ in_array($id, $ot->servicios->pluck('id_servicio')->toArray()) ? 'selected' : '' }}>
                                    {{ $nombre }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('servicios')" class="mt-1" />
                    </div>

                    {{-- Productos asociados --}}
                    <div class="mt-6">
                        <x-input-label for="productos" value="Productos Asociados" />
                        <div class="grid gap-2" id="productosContainer">
                            @foreach ($ot->detalleProductos as $i => $p)
                                <div class="producto-item flex items-center gap-2 mb-2">
                                    <select name="productos[{{ $i }}][id]"
                                        class="select2 w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white"
                                        required>
                                        <option value="">Seleccione un producto</option>
                                        @foreach ($productos as $prod)
                                            <option value="{{ $prod->id_producto }}"
                                                {{ $prod->id_producto == $p->id_producto ? 'selected' : '' }}>
                                                {{ $prod->nombre_producto }} {{ $prod->marca }} {{ $prod->modelo }} — Stock:
                                                {{ optional($prod->inventario->first())->cantidad ?? 0 }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="number" name="productos[{{ $i }}][cantidad]"
                                        min="1" value="{{ $p->cantidad }}"
                                        class="w-20 rounded p-2 border-gray-300 dark:bg-gray-700 dark:text-white"
                                        required />
                                    <button type="button" class="bg-red-500 text-white rounded px-2 py-1"
                                        onclick="$(this).parent().remove()">✕</button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button"
                            class="mt-2 flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition duration-200 ease-in-out shadow"
                            onclick="addProductoSelect()">
                            <i class="fa-solid fa-plus"></i>
                            Agregar Producto
                        </button>

                        <x-input-error :messages="$errors->get('productos')" class="mt-1" />
                    </div>

                    {{-- Archivos Adjuntos --}}
                    <div class="mt-6">
                        <x-input-label for="archivos" value="Archivos Adjuntos" />

                        {{-- Botón de selección de archivos --}}
                        <label for="archivos"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow cursor-pointer transition duration-200 ease-in-out">
                            <i class="fa-solid fa-upload mr-2"></i>
                            Elegir Archivos
                            <input id="archivos" name="archivos[]" type="file" multiple class="hidden"
                                onchange="mostrarArchivosSeleccionados()" />
                        </label>

                        {{-- Vista previa de archivos seleccionados --}}
                        <div class="mt-3 border border-blue-300 dark:border-blue-700 rounded-md p-3 bg-blue-50 dark:bg-blue-900/10">
                            <div class="font-semibold text-blue-800 dark:text-blue-300 mb-1">
                                Archivos seleccionados para subir
                            </div>
                            <div id="archivosSeleccionados"
                                class="text-sm text-gray-800 dark:text-gray-200 space-y-1 italic">
                                <!-- JS insertará aquí -->
                            </div>
                        </div>

                        <x-input-error :messages="$errors->get('archivos')" class="mt-1" />
                    </div>

                    {{-- Archivos ya cargados --}}
                    @if ($ot->archivosAdjuntos->count())
                        <div class="mt-6 border border-gray-300 dark:border-gray-600 rounded-md p-3 bg-gray-50 dark:bg-gray-800/50">
                            <div class="font-semibold text-gray-900 dark:text-gray-100 mb-1">
                                Archivos ya cargados
                            </div>
                            <ul class="list-disc pl-6 text-sm text-gray-800 dark:text-gray-200 space-y-2">
                                @foreach ($ot->archivosAdjuntos as $archivo)
                                    <li class="flex items-center justify-between gap-2">
                                        <a href="{{ Storage::url($archivo->ruta_archivo) }}" target="_blank"
                                            class="text-blue-500 underline hover:text-blue-700 flex-1">
                                            {{ $archivo->nombre_original }}
                                        </a>
                                        <button type="button"
                                            class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded text-xs"
                                            onclick="eliminarArchivo('{{ route('archivos_ot.eliminar', $archivo->id_archivo) }}', this)">
                                            Eliminar
                                        </button>
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

    @push('scripts')
    {{-- Select2 CSS y JS --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- Archivo de utilidades personalizado --}}
    <script src="{{ asset('js/select2Utils.js') }}"></script>
    <script>
        // Esta función evita el error de Alpine x-data="otForm()"
        function otForm() {
            return {
                // Si más adelante necesitas estados, los agregas aquí
                mensaje: '',
                mostrarMensaje(texto) {
                    this.mensaje = texto;
                }
            };
        }
    </script>

    <script>
        function mostrarArchivosSeleccionados() {
            const input = document.getElementById('archivos');
            const info = document.getElementById('archivosSeleccionados');
            info.innerHTML = '';

            const archivos = input.files;

            if (archivos.length > 0) {
                const resumen = document.createElement('div');
                resumen.textContent = archivos.length === 1
                    ? `Archivo seleccionado:`
                    : `${archivos.length} archivos seleccionados`;
                info.appendChild(resumen);

                const lista = document.createElement('ul');
                lista.classList.add('list-disc', 'pl-6');

                Array.from(archivos).forEach(file => {
                    const li = document.createElement('li');
                    li.textContent = file.name;
                    lista.appendChild(li);
                });

                info.appendChild(lista);
            }
        }
        function eliminarArchivo(url, boton) {
            if (!confirm('¿Estás seguro de eliminar este archivo?')) return;

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    _method: 'DELETE'
                })
            })
            .then(res => {
                if (res.ok) {
                    // Eliminar visualmente el archivo
                    const li = boton.closest('li');
                    li.remove();
                } else {
                    console.error('Error al eliminar:', res);
                    alert('Ocurrió un error al eliminar el archivo.');
                }
            })
            .catch(error => {
                console.error('Error en la petición:', error);
                alert('Ocurrió un error en la comunicación con el servidor.');
            });
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Inicializar todos los select2 visibles
            $('select.select2').each(function () {
                inicializarSelect2(this, $(this).attr('data-placeholder') || 'Seleccione una opción');
            });

            // Función para agregar un nuevo producto con estilo select2
            window.addProductoSelect = function () {
                let idx = $('#productosContainer .producto-item').length;
                const html = `
                    <div class="producto-item flex items-center gap-2 mb-2">
                        <select name="productos[${idx}][id]"
                            class="select2 w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white p-2"
                            data-placeholder="Seleccione un producto" required>
                            <option value="">Seleccione un producto</option>
                            @foreach ($productos as $prod)
                                <option value="{{ $prod->id_producto }}">
                                    {{ $prod->nombre_producto }} {{ $prod->marca }} {{ $prod->modelo }} — Stock: {{ optional($prod->inventario->first())->cantidad ?? 0 }}
                                </option>
                            @endforeach
                        </select>
                        <input type="number" name="productos[${idx}][cantidad]" min="1" value="1"
                            class="w-20 rounded p-2 border-gray-300 dark:bg-gray-700 dark:text-white" required />
                        <button type="button" class="bg-red-500 text-white rounded px-2 py-1"
                            onclick="$(this).parent().remove()">✕</button>
                    </div>`;
                $('#productosContainer').append(html);

                // Reaplicar Select2
                $('#productosContainer .producto-item:last-child .select2').each(function () {
                    inicializarSelect2(this, $(this).attr('data-placeholder') || 'Seleccione una opción');
                });
            };
        });
    </script>
    @endpush
</x-app-layout>
