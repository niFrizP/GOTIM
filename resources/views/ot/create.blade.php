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
                {{-- Alerta global para éxito (cliente u orden) --}}
                @if (session('success'))
                    <div id="global-success-alert"
                        class="mb-4 p-3 rounded font-semibold bg-green-100 text-green-700 flex items-center gap-2 shadow">
                        <i class="fa-solid fa-circle-check text-green-500"></i>
                        {{ session('success') }}
                    </div>
                @else
                    <div id="global-success-alert"
                        class="mb-4 p-3 rounded font-semibold bg-green-100 text-green-700 flex items-center gap-2 shadow hidden">
                    </div>
                @endif

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
    <!-- MODAL PARA CREAR CLIENTE -->
    <div id="modalCrearCliente" class="fixed inset-0 z-50 flex items-center justify-center modal-overlay hidden"
        aria-modal="true" role="dialog">
        <!-- Overlay de fondo oscuro -->
        <div class="modal-backdrop fixed inset-0 bg-black bg-opacity-50 dark:bg-opacity-70 transition-opacity"></div>
        <!-- Contenido del modal -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-3xl relative modal-content">
            <div
                class="sticky top-0 bg-white dark:bg-gray-800 p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Crear Nuevo Cliente</h3>
                <button id="cerrarModalCliente"
                    class="text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    aria-label="Cerrar modal">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div id="contenidoModalCliente" class="p-6"></div>
        </div>
    </div>

    <!-- Modal contenedor (una sola vez en la vista principal) -->
    <div id="modalCrearEmpresa" class="fixed inset-0 z-50 flex items-center justify-center modal-overlay hidden"
        aria-modal="true" role="dialog">
        <div class="modal-backdrop fixed inset-0 bg-black bg-opacity-50 dark:bg-opacity-70 transition-opacity"></div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-3xl relative modal-content">
            <div
                class="sticky top-0 bg-white dark:bg-gray-800 p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Crear Nueva Empresa</h3>
                <button id="cerrarModalCliente"
                    class="text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    aria-label="Cerrar modal">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div id="contenidoModalEmpresa" class="p-6"></div>
        </div>
    </div>

    {{-- CDN Select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js/select2Utils.js') }}"></script>
    <script src="{{ asset('js/rutUtils.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inicializa select2 para todos los que no sean #id_cliente
            $('select.select2').not('#id_cliente').each(function () {
                inicializarSelect2(this, $(this).attr('data-placeholder') || 'Seleccione una opción');
            });

            // Inicializa #id_cliente con botón de "Crear nuevo cliente"
            $('#id_cliente').select2({
                placeholder: 'Seleccione un cliente',
                width: '100%',
                language: {
                    noResults: function () {
                        return `
            <div class="text-center">
                <p class="text-gray-700 dark:text-gray-300">No se encontró el cliente</p>
                <a href="javascript:void(0);" id="btn_crear_cliente_from_select"
                    class="mt-2 inline-block bg-blue-600 hover:bg-blue-700 text-white text-sm px-3 py-1 rounded">
                    + Crear nuevo cliente
                </a>
            </div>`;
                    }
                },
                escapeMarkup: function (markup) {
                    return markup;
                }
            });

            // --- Modal de cliente ---
            document.addEventListener('DOMContentLoaded', function () {
                // Cierre de modal con botón
                document.querySelectorAll('[id^="cerrarModal"]').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const modalType = btn.id.replace('cerrarModal', '').toLowerCase();
                        ModalHandlers.closeModal(modalType);
                    });
                });

                // Cierre de modal haciendo clic en overlay
                document.querySelectorAll('.modal-overlay').forEach(overlay => {
                    overlay.addEventListener('click', function (e) {
                        if (e.target === this) {
                            const modalType = this.id.replace('modal', '').toLowerCase();
                            ModalHandlers.closeModal(modalType);
                        }
                    });
                });

                // Botón para crear nuevo cliente
                $(document).on('click', '#btn_crear_cliente_from_select', function () {
                    $('#id_cliente').select2('close');
                    ModalHandlers.openModal('cliente');
                });

                // Botón para crear nueva empresa desde cliente
                // 1. Cuando hacen clic en "Crear nueva empresa" desde el modal de cliente:
                $(document).on('click', '#btn_crear_empresa', function () {
                    // Abre el modal de empresa
                    $('#modalCrearEmpresa').removeClass('hidden');
                    $('#contenidoModalEmpresa').html('<div class="text-center text-gray-600 dark:text-gray-300">Cargando...</div>');
                    $.get('{{ route('empresas.create') }}?popup=1', function (data) {
                        $('#contenidoModalEmpresa').html(data);

                        // Inicializar validación/funciones del formulario de empresa
                        if (typeof initRutEmpresaValidation === 'function') {
                            initRutEmpresaValidation();
                        }
                    });
                });

                // 2. Cerrar modal de empresa
                $(document).on('click', '#cerrarModalEmpresa, .btnCerrarEmpresaModal', function () {
                    $('#modalCrearEmpresa').addClass('hidden');
                    $('#contenidoModalEmpresa').empty();
                });
                $('#modalCrearEmpresa').on('click', function (e) {
                    if (e.target === this) {
                        $('#modalCrearEmpresa').addClass('hidden');
                        $('#contenidoModalEmpresa').empty();
                    }
                });

                // 3. Guardar empresa desde el modal
                $(document).on('submit', '#formCrearEmpresa', function (e) {
                    e.preventDefault();
                    const $form = $(this);

                    $.post($form.attr('action'), $form.serialize(), function (resp) {
                        if (resp.success) {
                            // Mensaje en el modal empresa
                            $('#empresa-alert-msg').removeClass('hidden').text('Empresa creada correctamente');
                            $form.find('input, button, select, textarea').prop('disabled', true);

                            // PASO CRUCIAL: Cargar los datos de empresa en el modal de cliente
                            setTimeout(() => {
                                // Cierra modal empresa
                                $('#modalCrearEmpresa').addClass('hidden');
                                $('#contenidoModalEmpresa').empty();

                                // Actualiza campos en el formulario de cliente (en el DOM padre)
                                $('#id_empresa').val(resp.empresa.id); // input hidden
                                $('#nombre_empresa_label').val(resp.empresa.nombre);
                                $('#razon_social').val(resp.empresa.razon_social).prop('readonly', true).addClass('bg-gray-100');
                                $('#giro').val(resp.empresa.giro).prop('readonly', true).addClass('bg-gray-100');
                                $('#empresa_no_encontrada').hide();

                                // Mensaje visual en el modal cliente (puedes personalizarlo)
                                $('#cliente-alert-msg')
                                    .removeClass('hidden')
                                    .removeClass('bg-red-100 text-red-700')
                                    .addClass('bg-green-100 text-green-700')
                                    .html('<i class="fa-solid fa-circle-check text-green-500"></i> Empresa creada correctamente.');

                                setTimeout(() => {
                                    $('#cliente-alert-msg').addClass('hidden');
                                }, 2000);
                            }, 1200); // tiempo de confirmación visual
                        }
                    }).fail(function (xhr) {
                        if (xhr.status === 422) {
                            $('#contenidoModalEmpresa').html(xhr.responseText);
                        }
                    });
                });

            });

            $(document).on('click', '#cerrarModalCliente, .btnCerrarClienteModal', cerrarModalCliente);
            $('#modalCrearCliente').on('click', function (e) {
                if (e.target === this) cerrarModalCliente();
            });

            function cerrarModalCliente() {
                $('#modalCrearCliente').addClass('hidden');
                $('#contenidoModalCliente').empty();
            }

            $(document).on('submit', '#formCrearCliente', function (e) {
                e.preventDefault();
                const $form = $(this);

                $.post($form.attr('action'), $form.serialize(), function (resp) {
                    if (resp.success) {
                        // ESTE ES EL CAMBIO:
                        $('#global-success-alert')
                            .removeClass('hidden')
                            .html('<i class="fa-solid fa-circle-check text-green-500"></i> Cliente creado correctamente.');

                        $form.find('input,button,select,textarea').prop('disabled', true);

                        const texto = resp.cliente.nombre + ' (' + resp.cliente.rut + ')';
                        const nuevoOption = new Option(texto, resp.cliente.id, true, true);
                        $('#id_cliente').append(nuevoOption).val(resp.cliente.id).trigger('change');
                        $('#id_cliente option[value=""]').remove();

                        setTimeout(() => {
                            cerrarModalCliente();
                            setTimeout(() => $('#global-success-alert').addClass('hidden'), 3000);
                        }, 1500);
                    }
                }).fail(function (xhr) {
                    if (xhr.status === 422) {
                        $('#contenidoModalCliente').html(xhr.responseText);
                        window.initRutValidation(); // para reactivar validaciones
                    }
                });
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
            // Botón "Crear nuevo cliente" desde el select2
            $(document).on('click', '#btn_crear_cliente_from_select', function () {
                $('#id_cliente').select2('close');
                $('#modalCrearCliente').removeClass('hidden');
                $('#contenidoModalCliente').html('<div class="text-center text-gray-600 dark:text-gray-300">Cargando...</div>');
                $.get('{{ route('clientes.create') }}?popup=1', function (data) {
                    $('#contenidoModalCliente').html(data);

                    // Ahora sí: inicializar scripts para los campos cargados por AJAX
                    if (typeof window.initClienteModalScripts === 'function') {
                        window.initClienteModalScripts();
                    }
                });
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
            $('select.select2').not('#id_cliente').each(function () {
                inicializarSelect2(this, $(this).attr('data-placeholder') || 'Seleccione una opción');
            });

        });
        // CIERRA EL MODAL AL HACER CLICK EN EL OVERLAY
        document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
            backdrop.addEventListener('click', function (e) {
                const modalOverlay = this.closest('.modal-overlay');
                if (modalOverlay) {
                    modalOverlay.classList.add('hidden');
                    if (modalOverlay.id === 'modalCrearCliente') {
                        document.getElementById('contenidoModalCliente').innerHTML = '';
                    }
                    if (modalOverlay.id === 'modalCrearEmpresa') {
                        document.getElementById('contenidoModalEmpresa').innerHTML = '';
                    }
                }
            });
        });
    </script>
</x-app-layout>