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
                            <x-input-label><input type="radio" name="tipo_cliente" value="natural"
                                    {{ old('tipo_cliente') == 'natural' ? 'checked' : '' }}> Persona
                                Natural</x-input-label>
                            <x-input-label><input type="radio" name="tipo_cliente" value="empresa"
                                    {{ old('tipo_cliente') == 'empresa' ? 'checked' : '' }}>
                                Empresa</x-input-label>
                        </div>
                    </div>
                    <br>
                    <!-- Nombre de la Empresa (Solo Empresa) -->
                    <div id="nombre_empresa_field" style="display: none;">
                        <x-input-label for="nombre_empresa_field" value="Nombre empresa" />
                        <span id="nombre_empresa_label"
                            class="inline-block mt-1 rounded px-3 py-1 text-sm font-semibold
               bg-blue-200 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300">
                        </span>
                    </div>
                    <input type="hidden" name="id_empresa" id="id_empresa" value="{{ old('id_empresa') }}">

                    <!-- Giro (Solo Empresa) -->
                    <div id="giro_field" style="display: none;">
                        <x-input-label for="giro" value="Giro" />
                        <x-text-input id="giro" name="giro" type="text" class="w-full"
                            value="{{ old('giro') }}" />
                        <x-input-error :messages="$errors->get('giro')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                    </div>
                    <br>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Razón Social (solo empresa) -->
                        <div id="razon_social_field" style="display: none;">
                            <x-input-label for="razon_social" value="Razón Social" />
                            <x-text-input id="razon_social" name="razon_social" type="text" class="w-full"
                                value="{{ old('razon_social') }}" />
                            <x-input-error :messages="$errors->get('razon_social')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        <!-- Tipo de Cliente -->
                        <!-- RUT Persona Natural -->
                        <div id="rut_field" style="display: none;">
                            <x-input-label for="rut_natural" value="RUT *" />
                            <x-text-input id="rut_natural" name="rut_natural" type="text" class="w-full"
                                value="{{ old('rut_natural') }}" maxlength="12" placeholder="Ej: 9.999.999-9" />
                        </div>
                        <x-input-error :messages="$errors->get('rut_natural')" class="mt-1 text-sm text-red-600 dark:text-red-400" />

                        <!-- RUT Empresa -->
                        <div id="rut_empresa_field" style="display: none;">
                            <x-input-label for="rut_empresa" value="RUT Empresa *" />
                            <x-text-input id="rut_empresa" name="rut_empresa" type="text" class="w-full"
                                value="{{ old('rut_empresa') }}" maxlength="12" placeholder="Ej: 99.999.999-9" />
                            <x-input-error :messages="$errors->get('rut')" class="mt-1 text-sm text-red-600 dark:text-red-400" />

                            <!-- Mostrar razón social si existe -->
                            <div id="razon_social_empresa" class="mt-2 text-green-600 font-semibold"
                                style="display: none;">
                            </div>

                            <!-- Si no existe, mostrar botón para crear -->
                            <div id="empresa_no_encontrada" class="mt-2 text-red-600" style="display: none;">
                                Empresa no registrada.
                                <button type="button" id="btn_crear_empresa"
                                    class="ml-2 underline text-blue-500 hover:text-blue-700">
                                    Crear nueva empresa
                                </button>
                            </div>
                        </div>
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
                            <a href="{{ route('clientes.index') }}" class="text-blue-500 hover:text-blue-700">Volver
                                a la
                                lista de clientes</a>
                        </x-secondary-button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        {{-- Select2 CSS --}}
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

        {{-- jQuery y Select2 JS --}}
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        {{-- Archivo utilitario personalizado --}}
        <script src="{{ asset('js/select2Utils.js') }}"></script>

        <script>
            $(document).ready(function() {
                const $region = $('#id_region');
                const $ciudad = $('#id_ciudad');

                // Mostrar/Ocultar campos según tipo de cliente
                function toggleFields() {
                    const tipo = $('input[name="tipo_cliente"]:checked').val();
                    $('#rut_field').toggle(tipo === 'natural');
                    $('#rut_empresa_field, #razon_social_field, #giro_field, #nombre_empresa_field').toggle(tipo ===
                        'empresa');

                    // 💣 Esta línea se asegura de limpiar campos si cambian a natural
                    limpiarIdEmpresaSiNatural();
                }
                // Limpiar campos de empresa si se selecciona persona natural
                function limpiarIdEmpresaSiNatural() {
                    const tipo = $('input[name="tipo_cliente"]:checked').val();
                    if (tipo === 'natural') {
                        $('#id_empresa').val('');
                        $('#razon_social').val('').prop('readonly', false).removeClass('bg-gray-100');
                        $('#giro').val('').prop('readonly', false).removeClass('bg-gray-100');
                        $('#nombre_empresa_label').hide();
                        $('#empresa_no_encontrada').hide();
                    }
                }

                $('input[name="tipo_cliente"]').on('change', toggleFields);
                toggleFields(); // Ejecutar al cargar

                // Formateo de RUT
                function formatRut(input) {
                    let value = input.value.toUpperCase().replace(/[^0-9K]/g, '');
                    if (value.length === 9) {
                        value = value.replace(/^(\d{2})(\d{3})(\d{3})([\dkK])$/, '$1.$2.$3-$4');
                    } else if (value.length === 8) {
                        value = value.replace(/^(\d{1})(\d{3})(\d{3})([\dkK])$/, '$1.$2.$3-$4');
                    }
                    input.value = value;
                }

                $('#rut_natural, #rut_empresa').on('input', function() {
                    formatRut(this);
                });

                // Buscar empresa por RUT
                $('#rut_empresa').on('input', async function() {
                    formatRut(this);
                    const rut = $(this).val().toUpperCase();

                    if (/^\d{1,2}\.\d{3}\.\d{3}-[\dkK]$/.test(rut)) {
                        try {
                            const response = await fetch(`/empresas/comprobar/${rut}`);
                            const data = await response.json();

                            if (data.existe) {
                                $('#razon_social').val(data.razon_social).prop('readonly', true).addClass(
                                    'bg-gray-100');
                                $('#giro').val(data.giro).prop('readonly', false).removeClass(
                                    'bg-gray-100');
                                $('#id_empresa').val(data.id_empresa);
                                $('#nombre_empresa_label').text(data.nom_emp);
                                $('#empresa_no_encontrada').hide();
                                $('#id_empresa').val(data.id_empresa);
                            } else {
                                $('#razon_social').val('').prop('readonly', false).removeClass(
                                    'bg-gray-100');
                                $('#nombre_empresa_label').hide();
                                $('#giro').val('').prop('readonly', false).removeClass('bg-gray-100');
                                $('#empresa_no_encontrada').show();
                            }
                        } catch (error) {
                            console.error('Error al buscar empresa:', error);
                        }
                    } else {
                        $('#razon_social').val('').prop('readonly', false).removeClass('bg-gray-100');
                        $('#giro').val('').prop('readonly', false).removeClass('bg-gray-100');
                        $('#nombre_empresa_label').text('Empresa no encontrada o campo inválido');
                        $('#empresa_no_encontrada').show();
                        $('#id_empresa').val('');

                    }
                });

                // Botón para abrir nueva pestaña
                $('#btn_crear_empresa').on('click', function() {
                    window.open('{{ route('empresas.create') }}', '_blank');
                });

                // Inicializar Select2
                $('select.select2').each(function() {
                    inicializarSelect2(this, $(this).attr('data-placeholder') || 'Seleccione una opción');
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

                    if (regionId) {
                        try {
                            const response = await fetch(`/cxr/${regionId}`);
                            if (!response.ok) throw new Error('No se pudo cargar');

                            const ciudades = await response.json();
                            $ciudad.empty().append('<option value="">Seleccione una ciudad</option>');
                            ciudades.forEach(c => {
                                $ciudad.append(
                                    `<option value="${c.id_ciudad}">${c.nombre_ciudad}</option>`
                                );
                            });
                            $ciudad.prop('disabled', false);
                        } catch (err) {
                            console.error('Error cargando ciudades:', err);
                            $ciudad.empty().append('<option value="">Error al cargar</option>').prop(
                                'disabled', true);
                        }
                    } else {
                        $ciudad.empty().append('<option value="">Seleccione una ciudad</option>').prop(
                            'disabled', true);
                    }

                    $ciudad.select2({
                        placeholder: "Seleccione una ciudad",
                        allowClear: true,
                        width: 'resolve'
                    });
                    estilizarSelect2();
                });

                function estilizarSelect2() {
                    setTimeout(() => {
                        $('.select2-container--default .select2-selection--single').css({
                            'background-color': '#fff',
                            'border': '1px solid #d1d5db',
                            'border-radius': '0.5rem',
                            'height': '42px',
                            'padding': '0.5rem 0.75rem',
                            'font-size': '0.875rem',
                            'color': '#000'
                        });

                        $('.select2-selection__rendered').css({
                            'color': '#000',
                            'line-height': '1.5rem',
                        });

                        $('.select2-selection__arrow').css({
                            'top': '8px',
                            'right': '0.75rem'
                        });
                    }, 10);
                }
            });
            document.addEventListener('DOMContentLoaded', () => {

        // Validar solo números en teléfono de cliente
        const telefonoCliente = document.getElementById('nro_contacto');
        if (telefonoCliente) {
            telefonoCliente.addEventListener('input', function () {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        }
    });
        </script>
    @endpush
</x-app-layout>


<!-- Well begun is half done. - Aristotle -->
