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
                                    <option value="{{ $id }}">{{ $nombre }} </option>
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
                                @foreach ($responsables as $id => $nombre)
                                    <option value="{{ $id }}">{{ $nombre }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('id_responsable')"
                                class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        {{-- Estado: fijo "Recepcionada" --}}
                        <div class="sm:col-span-2">
                            <x-input-label value="Estado de la Orden" />
                            <p
                                class="py-2 px-3 rounded border border-gray-300 dark:bg-gray-700 dark:text-gray-300 bg-gray-100 font-semibold">
                                Recepcionada</p>
                            <input type="hidden" name="id_estado" value="1" />
                        </div>

                        {{-- Fecha entrega --}}
                        <div>
                            <x-input-label for="fecha_entrega" value="Fecha Estimada de Entrega*" />
                            <x-text-input id="fecha_entrega" name="fecha_entrega" type="date" class="w-full"
                                min="{{ now()->format('Y-m-d') }}" max="{{ now()->addYears(5)->format('Y-m-d') }}" />
                            <x-input-error :messages="$errors->get('fecha_entrega')"
                                class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        {{-- Descripción --}}
                        <div class="sm:col-span-2">
                            <x-input-label for="descripcion" value="Descripción" />
                            <textarea id="descripcion" name="descripcion" rows="4"
                                class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white p-2"
                                placeholder="Escribe una descripción detallada..."></textarea>
                            <x-input-error :messages="$errors->get('descripcion')"
                                class="mt-1 text-sm text-red-600 dark:text-red-400" />
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
                        <x-input-error :messages="$errors->get('servicios')"
                            class="mt-1 text-sm text-red-600 dark:text-red-400" />
                    </div>

                    {{-- Archivos Adjuntos --}}
                    <div class="mt-6">
                        <x-input-label for="archivos" value="Archivos Adjuntos" />
                        <label for="archivos"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow cursor-pointer transition duration-200 ease-in-out">
                            <i class="fa-solid fa-upload mr-2"></i>
                            Elegir Archivos
                            <input id="archivos" name="archivos[]" type="file" multiple class="hidden"
                                accept=".jpg,.jpeg,.png,.webp,.gif,.pdf,.doc,.docx,.xls,.xlsx"
                                onchange="mostrarArchivosSeleccionados()" />
                        </label>

                        <div
                            class="mt-3 border border-blue-300 dark:border-blue-700 rounded-md p-3 bg-blue-50 dark:bg-blue-900/10">
                            <div class="font-semibold text-blue-800 dark:text-blue-300 mb-1">
                                Archivos seleccionados para subir
                            </div>
                            <div id="archivosSeleccionados"
                                class="text-sm text-gray-800 dark:text-gray-200 space-y-1 italic">
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('archivos')"
                            class="mt-1 text-sm text-red-600 dark:text-red-400" />
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
        document.addEventListener('DOMContentLoaded', function () {
            // Inicializar Select2
            $('select.select2').each(function () {
                inicializarSelect2(this, $(this).attr('data-placeholder') || 'Seleccione una opción');
            });

            // Validación sincrónica en tiempo real
            const campos = {
                'id_cliente': 'Debe seleccionar un cliente.',
                'id_responsable': 'Debe seleccionar un responsable.',
                'fecha_entrega': 'Debe seleccionar una fecha válida.',
                'descripcion': 'Debe escribir una descripción.',
                'servicios': 'Debe seleccionar al menos un servicio.',
            };

            Object.entries(campos).forEach(([id, mensaje]) => {
                const input = document.getElementById(id);
                if (!input) return;

                input.addEventListener('change', () => validarCampo(id, mensaje));
                input.addEventListener('input', () => validarCampo(id, mensaje));
            });

            function validarCampo(id, mensaje) {
                const campo = document.getElementById(id);
                const errorContainer = campo.closest('div').querySelector('.text-red-600, .text-red-400');

                let invalido = false;

                if (campo.tagName === 'SELECT' && campo.multiple) {
                    invalido = campo.selectedOptions.length === 0;
                } else if (campo.type === 'date') {
                    const hoy = new Date().toISOString().split('T')[0];
                    invalido = !campo.value || campo.value < hoy;
                } else {
                    invalido = !campo.value.trim();
                }

                if (invalido) {
                    errorContainer.textContent = mensaje;
                    campo.classList.add('border-red-500');

                }
                if (campo.type === 'date') {
                    const hoy = new Date().toISOString().split('T')[0];
                    const max = new Date();
                    max.setFullYear(max.getFullYear() + 5);
                    const limite = max.toISOString().split('T')[0];

                    invalido = !campo.value || campo.value < hoy || campo.value > limite;
                }
                else {
                    errorContainer.textContent = '';
                    campo.classList.remove('border-red-500');
                }
            }

            // Fecha mínima: hoy
            const fechaEntrega = document.getElementById('fecha_entrega');
            if (fechaEntrega) {
                const hoy = new Date().toISOString().split('T')[0];
                fechaEntrega.min = hoy;
                const cincoAnios = new Date();
                cincoAnios.setFullYear(cincoAnios.getFullYear() + 5);
                fechaEntrega.max = cincoAnios.toISOString().split('T')[0];
            }

            // Servicios con búsqueda personalizada
            $('#servicios').select2({
                placeholder: 'Seleccione uno o más servicios',
                width: '100%',
                language: {
                    noResults: function () {
                        return `
                        <div class="text-center">
                            <p class="text-gray-700 dark:text-gray-300">No se encontró ese servicio</p>
                            <a href="${crearServicioURL()}" target="_blank" class="mt-2 inline-block bg-blue-600 hover:bg-blue-700 text-white text-sm px-3 py-1 rounded">+ Crear nuevo servicio</a>
                        </div>`;
                    }
                },
                escapeMarkup: function (markup) {
                    return markup;
                }
            });

            function crearServicioURL() {
                return "{{ route('servicios.create') }}";
            }
        });
        function mostrarArchivosSeleccionados() {
            const input = document.getElementById('archivos');
            const info = document.getElementById('archivosSeleccionados');
            info.innerHTML = '';
            const archivos = input.files;
            if (archivos.length > 0) {
                const resumen = document.createElement('div');
                resumen.textContent = archivos.length === 1 ? `Archivo seleccionado:` : `${archivos.length} archivos seleccionados`;
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

        document.addEventListener('DOMContentLoaded', function () {
            $('select.select2').each(function () {
                inicializarSelect2(this, $(this).attr('data-placeholder') || 'Seleccione una opción');
            });
        });
    </script>

</x-app-layout>