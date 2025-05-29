{{-- resources/views/ot/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            Crear Orden de Trabajo
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded shadow" x-data="otForm()">
                <form method="POST" action="{{ route('ot.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        {{-- Cliente --}}
                        <div class="sm:col-span-2">
                            <x-input-label for="id_cliente" value="Cliente" />
                            <select id="id_cliente" name="id_cliente"
                                class="select2 w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white"
                                required>
                                <option value="">Seleccione un cliente</option>
                                @foreach ($clientes as $id => $nombre)
                                    <option value="{{ $id }}">{{ $nombre }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('id_cliente')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        {{-- Responsable --}}
                        <div class="sm:col-span-2">
                            <x-input-label for="id_responsable" value="Responsable" />
                            <select id="id_responsable" name="id_responsable"
                                class="select2 w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white"
                                required>
                                <option value="">Seleccione un responsable</option>
                                @foreach ($responsables as $id => $nombre)
                                    <option value="{{ $id }}">{{ $nombre }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('id_responsable')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        {{-- Estado --}}
                        <div>
                            <x-input-label for="id_estado" value="Estado" />
                            <select id="id_estado" name="id_estado"
                                class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white p-2" required>
                                <option value="">Seleccione un estado</option>
                                @foreach ($estados as $id => $nombre)
                                    <option value="{{ $id }}">{{ $nombre }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('id_estado')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        {{-- Fecha entrega --}}
                        <div>
                            <x-input-label for="fecha_entrega" value="Fecha Estimada de Entrega" />
                            <x-text-input id="fecha_entrega" name="fecha_entrega" type="date" class="w-full" />
                            <x-input-error :messages="$errors->get('fecha_entrega')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        {{-- Descripción --}}
                        <div class="sm:col-span-2">
                            <x-input-label for="descripcion" value="Descripción" />
                            <textarea id="descripcion" name="descripcion" rows="4"
                                class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white p-2"
                                placeholder="Escribe una descripción detallada..."></textarea>
                            <x-input-error :messages="$errors->get('descripcion')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>
                    </div>

                    {{-- Servicios múltiples --}}
                    <div class="mt-6">
                        <x-input-label for="servicios" value="Tipos de Servicio" />
                        <select id="servicios" name="servicios[]"
                            class="select2 w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" multiple>
                            @foreach ($servicios as $id => $nombre)
                                <option value="{{ $id }}">{{ $nombre }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('servicios')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                    </div>

                    {{-- Productos asociados con cantidad --}}
                    <div class="mt-6">
                        <x-input-label for="productos" value="Productos Asociados" />
                        <div class="grid gap-2" id="productosContainer"></div>
                        <button type="button" class="mt-2 px-4 py-2 bg-green-500 text-white rounded"
                            onclick="addProductoSelect()">+ Agregar Producto</button>
                        <x-input-error :messages="$errors->get('productos')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                    </div>

                    {{-- Archivos Adjuntos --}}
                    <div class="mt-6">
                        <x-input-label for="archivos" value="Archivos Adjuntos" />
                        <input id="archivos" name="archivos[]" type="file" multiple
                            class="w-full text-sm text-gray-900 dark:text-gray-200" />
                        <x-input-error :messages="$errors->get('archivos')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                    </div>

                    {{-- Botones --}}
                    <div class="mt-6 flex justify-end space-x-2">
                        <x-secondary-button type="reset">Limpiar</x-secondary-button>
                        <a href="{{ route('ot.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded">Cancelar</a>
                        <x-primary-button type="submit">Crear Orden</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- CDN Select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js/select2Utils.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('select.select2').each(function() {
                inicializarSelect2(this, $(this).attr('data-placeholder') || 'Seleccione una opción');
            });
        });

        function addProductoSelect() {
            let idx = $('#productosContainer .producto-item').length;
            const html = `
            <div class="producto-item flex items-center gap-2 mb-2">
                <select name="productos[${idx}][id]" class="select2 w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" required>
                    <option value="">Seleccione un producto</option>
                    @foreach ($productos as $prod)
                        <option value="{{ $prod->id_producto }}">{{ $prod->nombre_producto }} {{ $prod->marca }} {{ $prod->modelo }} — Stock: {{ optional($prod->inventario->first())->cantidad ?? 0 }}</option>
                    @endforeach
                </select>
                <input type="number" name="productos[${idx}][cantidad]" min="1" value="1" class="w-20 rounded p-2 border-gray-300 dark:bg-gray-700 dark:text-white" placeholder="Cant." required />
                <button type="button" class="bg-red-500 text-white rounded px-2 py-1" onclick="$(this).parent().remove()">✕</button>
            </div>`;
            $('#productosContainer').append(html);
            $('#productosContainer .producto-item:last-child .select2').select2({
                width: '100%'
            });
        }
    </script>

</x-app-layout>
