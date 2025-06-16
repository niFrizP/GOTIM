<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            Crear Cliente
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="rounded bg-white dark:bg-gray-800 p-6 shadow">
                <form method="POST" action="{{ route('clientes.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Nombre -->
                        <div>
                            <x-input-label for="nombre_cliente" value="Nombre *" />
                            <x-text-input id="nombre_cliente" name="nombre_cliente" type="text" class="w-full"
                                value="{{ old('nombre_cliente') }}" required />
                            <x-input-error :messages="$errors->get('nombre_cliente')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        <!-- Apellido -->
                        <div>
                            <x-input-label for="apellido_cliente" value="Apellido *" />
                            <x-text-input id="apellido_cliente" name="apellido_cliente" type="text" class="w-full"
                                value="{{ old('apellido_cliente') }}" required />
                            <x-input-error :messages="$errors->get('apellido_cliente')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        <!-- Correo -->
                        <div>
                            <x-input-label for="email" value="Correo Electrónico *" />
                            <x-text-input id="email" name="email" type="email" class="w-full"
                                value="{{ old('email') }}" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <x-input-label for="nro_contacto" value="Teléfono *" />
                            <x-text-input id="nro_contacto" name="nro_contacto" type="tel" class="w-full"
                                maxlength="9" minlength="9" value="{{ old('nro_contacto') }}" required />
                            <small class="text-gray-500 dark:text-gray-400">Ingrese el número sin el prefijo +56</small>
                            <x-input-error :messages="$errors->get('nro_contacto')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>
                    </div>

                    <!-- Tipo de Cliente -->
                    <div class="mt-4">
                        <x-input-label value="Tipo de Cliente *" />
                        <div class="flex items-center gap-6">
                            <x-input-label>
                                <input type="radio" name="tipo_cliente" value="natural"
                                    {{ old('tipo_cliente') == 'natural' ? 'checked' : '' }}> Persona Natural
                            </x-input-label>
                            <x-input-label>
                                <input type="radio" name="tipo_cliente" value="empresa"
                                    {{ old('tipo_cliente') == 'empresa' ? 'checked' : '' }}> Empresa
                            </x-input-label>
                        </div>
                    </div>
                    <br>

                    <!-- Campos Empresa -->
                    <div id="rut_empresa_field" style="display: none;">
                        <x-input-label for="rut_empresa" value="RUT Empresa *" />
                        <p id="rut_empresa_feedback" class="mt-1 text-sm"></p>
                        <x-text-input id="rut_empresa" name="rut_empresa" type="text" class="w-full"
                            value="{{ old('rut_empresa') }}" maxlength="12" placeholder="Ej: 99.999.999-9" />
                        <div id="empresa_no_encontrada" class="mt-2 text-red-600" style="display: none;">
                            Empresa no registrada.
                            <a href="javascript:void(0);" id="btn_crear_empresa"
                               class="ml-2 underline text-blue-500 hover:text-blue-700">
                                Crear nueva empresa
                            </a>
                        </div>
                    </div>

                    <!-- Hidden fields para guardar id y nombre de empresa seleccionada -->
                    <input type="hidden" id="id_empresa" name="id_empresa" value="{{ old('id_empresa') }}">
                    <input type="hidden" id="nombre_empresa_label" name="nombre_empresa_label" value="">

                    <div id="razon_social_field" style="display: none;">
                        <x-input-label for="razon_social" value="Razón Social" />
                        <x-text-input id="razon_social" name="razon_social" type="text" class="w-full"
                            value="{{ old('razon_social') }}" />
                        <x-input-error :messages="$errors->get('razon_social')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                    </div>

                    <div id="giro_field" style="display: none;">
                        <x-input-label for="giro" value="Giro" />
                        <x-text-input id="giro" name="giro" type="text" class="w-full"
                            value="{{ old('giro') }}" />
                        <x-input-error :messages="$errors->get('giro')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                    </div>

                    <!-- RUT Persona Natural -->
                    <div id="rut_field" style="display: none;">
                        <x-input-label for="rut_natural" value="RUT *" />
                        <x-text-input id="rut_natural" name="rut_natural" type="text" class="w-full"
                            value="{{ old('rut_natural') }}" maxlength="12" placeholder="Ej: 9.999.999-9" />
                        <p id="rut_natural_feedback" class="mt-1 text-sm"></p>
                    </div>

                    <!-- Dirección -->
                    <div>
                        <x-input-label for="direccion" value="Dirección *" />
                        <x-text-input id="direccion" name="direccion" type="text" class="w-full"
                            value="{{ old('direccion') }}" />
                        <x-input-error :messages="$errors->get('direccion')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                    </div>
                    <br>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Región -->
                        <div>
                            <x-input-label for="id_region" value="Región *" />
                            <select id="id_region" name="id_region" class="w-full select2" required>
                                <option value="">Seleccione una región</option>
                                @foreach ($regiones as $region)
                                    <option value="{{ $region->id_region }}"
                                        {{ old('id_region') == $region->id_region ? 'selected' : '' }}>
                                        {{ $region->nombre_region }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('id_region')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        <!-- Ciudad -->
                        <div>
                            <x-input-label for="id_ciudad" value="Ciudad *" />
                            <select id="id_ciudad" name="id_ciudad" class="w-full select2" required>
                                <option value="">Seleccione una ciudad</option>
                                @foreach ($ciudades as $ciudad)
                                    <option value="{{ $ciudad->id_ciudad }}"
                                        {{ old('id_ciudad') == $ciudad->id_ciudad ? 'selected' : '' }}>
                                        {{ $ciudad->nombre_ciudad }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('id_ciudad')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>
                        <x-input-label value="Los campos marcados con * son obligatorios." />
                    </div>
                    @if ($errors->any())
                        <div class="mb-4 text-red-600">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>⚠️ {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <br>
                    <div class="mt-6">
                        <x-primary-button>Crear Cliente</x-primary-button>
                    </div>

                    <div class="mt-4">
                        <x-secondary-button>
                            <a href="{{ route('clientes.index') }}" class="text-blue-500 hover:text-blue-700">
                                Volver a la lista de clientes</a>
                        </x-secondary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL PARA CREAR EMPRESA -->
    <div id="modalCrearEmpresa"
         class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-2xl relative">
            <button id="cerrarModalEmpresa"
                    class="absolute top-2 right-2 text-gray-600 hover:text-red-500 text-2xl">&times;</button>
            <div id="contenidoModalEmpresa" class="p-6">
                <!-- Este contenido se carga dinámicamente con el formulario de empresa -->
                <div class="text-center text-gray-600 dark:text-gray-300">Cargando...</div>
            </div>
        </div>
    </div>
    <script>
        window.initRutValidation();
    </script>

    @push('scripts')
        <!-- Dependencias externas -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <!-- Utilidades propias -->
        <script src="{{ asset('js/select2Utils.js') }}"></script>
        <script src="{{ asset('js/rutUtils.js') }}"></script>

        <script>
            $(function() {
                // <--- Selectores comunes --->
                const $region = $('#id_region');
                const $ciudad = $('#id_ciudad');
                const $rutEmpresa = $('#rut_empresa');
                const $nombreEmpresaLabel = $('#nombre_empresa_label');
                const $idEmpresa = $('#id_empresa');
                const $razonSocial = $('#razon_social');
                const $giro = $('#giro');

                // <--- Función para formatear RUT con guion y dígito verificador --->
                function formatRut(rut) {
                    rut = rut.replace(/\./g, '').replace(/-/g, '').toUpperCase();
                    if (rut.length < 2) return rut;
                    let cuerpo = rut.slice(0, -1);
                    let dv = rut.slice(-1);
                    return cuerpo + '-' + dv;
                }

                // <--- Mostrar/ocultar campos según tipo cliente --->
                function toggleTipoCliente() {
                    const tipo = $('input[name="tipo_cliente"]:checked').val();
                    $('#rut_field').toggle(tipo === 'natural');
                    $('#rut_empresa_field, #razon_social_field, #giro_field, #nombre_empresa_field').toggle(tipo ===
                        'empresa');
                    if (tipo === 'natural') {
                        $idEmpresa.val('');
                        $razonSocial.add($giro).val('').prop('readonly', false).removeClass('bg-gray-100');
                        $nombreEmpresaLabel.add('#empresa_no_encontrada').hide();
                    }
                }
                $('input[name="tipo_cliente"]').on('change', toggleTipoCliente);
                toggleTipoCliente();

                // <--- Componentes de RUT --->
                // Rut Natural
                rutUtils.rutComponent({
                    idRut: 'rut_natural',
                    idFeedback: 'rut_natural_feedback',
                    comprobarRutRemoto: async rut => {
                        try {
                            const res = await fetch(`/clientes/comprobar-rut/${rut}`);
                            return res.ok ? await res.json() : {
                                existe: false
                            };
                        } catch {
                            return {
                                existe: false
                            };
                        }
                    },
                    caso: 'natural'
                });

                // Rut Empresa
                rutUtils.rutComponent({
                    idRut: 'rut_empresa',
                    idFeedback: 'rut_empresa_feedback',
                    comprobarRutRemoto: async rut => {
                        try {
                            const res = await fetch(`/empresas/comprobar-rut/${rut}`);
                            return res.ok ? await res.json() : {
                                existe: false
                            };
                        } catch {
                            return {
                                existe: false
                            };
                        }
                    },
                    caso: 'empresa'
                });

                // --- Autocompletar datos de empresa por RUT ---
                $rutEmpresa.on('input', function() {
                    let rut = $(this).val();
                    rut = formatRut(rut);
                    if (rut.length < 3) return limpiarCamposEmpresa();

                    fetch(`/empresas/comprobar/${rut}`)
                        .then(res => res.ok ? res.json() : Promise.reject())
                        .then(data => {
                            if (data.existe) {
                                $nombreEmpresaLabel.text(data.nom_emp).show();
                                $idEmpresa.val(data.id_empresa);
                                $razonSocial.val(data.razon_social).prop('readonly', true).addClass(
                                    'bg-gray-100');
                                $giro.val(data.giro).prop('readonly', false).removeClass('bg-gray-100');
                            } else {
                                limpiarCamposEmpresa();
                            }
                        })
                        .catch(limpiarCamposEmpresa);
                });

                function limpiarCamposEmpresa() {
                    $nombreEmpresaLabel.text('').hide();
                    $idEmpresa.val('');
                    $razonSocial.add($giro).val('').prop('readonly', false).removeClass('bg-gray-100');
                }

                // --- Teléfonos ---
                rutUtils.telefonoComponent('nro_contacto');
                rutUtils.telefonoComponent('telefono');

                // --- Modal de empresa ---
                $(document).on('click', '#btn_crear_empresa', function() {
                    $('#modalCrearEmpresa').removeClass('hidden');
                    $('#contenidoModalEmpresa').html(
                        '<div class="text-center text-gray-600 dark:text-gray-300">Cargando...</div>');
                    $.get('{{ route('empresas.create') }}?popup=1', function(data) {
                        $('#contenidoModalEmpresa').html(data);
                        initRutEmpresaValidation();
                    });
                });
                $(document).on('click', '#cerrarModalEmpresa, .btnCerrarEmpresaModal', cerrarModalEmpresa);
                $('#modalCrearEmpresa').on('click', function(e) {
                    if (e.target === this) cerrarModalEmpresa();
                });

                function cerrarModalEmpresa() {
                    $('#modalCrearEmpresa').addClass('hidden');
                    $('#contenidoModalEmpresa').empty();
                }

                $(document).on('submit', '#formCrearEmpresa', function(e) {
                    e.preventDefault();
                    const $form = $(this);
                    $.post($form.attr('action'), $form.serialize(), function(resp) {
                        if (resp.success) {
                            $('#empresa-success-msg').removeClass('hidden').text(
                                'Empresa creada correctamente');
                            $form.find('input,button,select,textarea').prop('disabled', true);
                            $idEmpresa.val(resp.empresa.id);
                            $razonSocial.val(resp.empresa.razon_social).prop('readonly', true).addClass(
                                'bg-gray-100');
                            $giro.val(resp.empresa.giro).prop('readonly', false).removeClass(
                                'bg-gray-100');
                            $nombreEmpresaLabel.text(resp.empresa.nombre).show();
                            $('#empresa_no_encontrada').hide();
                            setTimeout(cerrarModalEmpresa, 1500);
                        }
                    }).fail(function(xhr) {
                        if (xhr.status === 422) $('#contenidoModalEmpresa').html(xhr.responseText);
                    });
                });

                // --- Select2: inicialización y dependencias región/ciudad ---
                $('select.select2').each(function() {
                    if (typeof inicializarSelect2 === 'function') {
                        inicializarSelect2(this, $(this).data('placeholder') || 'Seleccione una opción');
                    }
                });
                $region.select2({
                    placeholder: "Seleccione una región",
                    allowClear: true,
                    width: 'resolve'
                });
                $ciudad.select2({
                    placeholder: "Seleccione una ciudad",
                    allowClear: true,
                    width: 'resolve'
                }).prop('disabled', true);

                $region.on('change', async function() {
                    const regionId = $(this).val();
                    $ciudad.select2('destroy').empty().append(
                        '<option value="">Cargando ciudades...</option>');
                    if (!regionId) {
                        $ciudad.empty().append('<option value="">Seleccione una ciudad</option>').prop(
                            'disabled', true);
                        return reinicializarCiudad();
                    }
                    try {
                        const res = await fetch(`/cxr/${regionId}`);
                        if (!res.ok) throw new Error();
                        const ciudades = await res.json();
                        $ciudad.empty().append('<option value="">Seleccione una ciudad</option>');
                        ciudades.forEach(c => $ciudad.append(
                            `<option value="${c.id_ciudad}">${c.nombre_ciudad}</option>`));
                        $ciudad.prop('disabled', false);
                    } catch {
                        $ciudad.empty().append('<option value="">Error al cargar</option>').prop('disabled',
                            true);
                    }
                    reinicializarCiudad();
                });

                function reinicializarCiudad() {
                    $ciudad.select2({
                        placeholder: "Seleccione una ciudad",
                        allowClear: true,
                        width: 'resolve'
                    });
                    if (typeof estilizarSelect2 === 'function') estilizarSelect2();
                }

                function estilizarSelect2() {
                    setTimeout(() => {
                        $('.select2-container--default .select2-selection--single').css({
                            backgroundColor: '#fff',
                            border: '1px solid #d1d5db',
                            borderRadius: '0.5rem',
                            height: '42px',
                            padding: '0.5rem 0.75rem',
                            fontSize: '0.875rem',
                            color: '#000'
                        });
                        $('.select2-selection__rendered').css({
                            color: '#000',
                            lineHeight: '1.5rem'
                        });
                        $('.select2-selection__arrow').css({
                            top: '8px',
                            right: '0.75rem'
                        });
                    }, 10);
                }
            });
        </script>
    @endpush
</x-app-layout>
