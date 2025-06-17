<div id="cliente-alert-msg" class="hidden mb-4 p-3 rounded font-semibold"></div>

<form id="formCrearCliente" method="POST" action="{{ route('clientes.store') }}" novalidate>
    @csrf
    <input type="hidden" name="popup" value="0">
    <input type="hidden" id="id_empresa" name="id_empresa" value="">
    <input type="hidden" id="nombre_empresa_label" name="nombre_empresa_label" value="">

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
        <!-- Nombre -->
        <div>
            <x-input-label for="nombre_cliente" value="Nombre *" />
            <x-text-input id="nombre_cliente" name="nombre_cliente" type="text" class="w-full" required
                maxlength="255" />
            <x-input-error :messages="$errors->get('nombre_cliente')"
                class="mt-1 text-sm text-red-600 dark:text-red-400" />
        </div>

        <!-- Apellido -->
        <div>
            <x-input-label for="apellido_cliente" value="Apellido *" />
            <x-text-input id="apellido_cliente" name="apellido_cliente" type="text" class="w-full" required
                maxlength="255" />
            <x-input-error :messages="$errors->get('apellido_cliente')"
                class="mt-1 text-sm text-red-600 dark:text-red-400" />
        </div>

        <!-- Correo -->
        <div>
            <x-input-label for="email" value="Correo Electrónico *" />
            <x-text-input id="email" name="email" type="email" class="w-full" required maxlength="255" />
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
        </div>

        <!-- Teléfono -->
        <div>
            <x-input-label for="nro_contacto" value="Teléfono *" />
            <x-text-input id="nro_contacto" name="nro_contacto" type="tel" class="w-full" maxlength="9" minlength="9"
                required placeholder="Ej: 912345678" />
            <small class="text-gray-500">Ingrese el número sin el prefijo +56</small>
            <x-input-error :messages="$errors->get('nro_contacto')"
                class="mt-1 text-sm text-red-600 dark:text-red-400" />
        </div>
    </div>

    <!-- Tipo de Cliente -->
    <div class="mt-4">
        <x-input-label value="Tipo de Cliente *" />
        <div class="flex items-center gap-6">
            <x-input-label>
                <input type="radio" name="tipo_cliente" value="natural" checked> Persona Natural
            </x-input-label>
            <x-input-label>
                <input type="radio" name="tipo_cliente" value="empresa"> Empresa
            </x-input-label>
        </div>
    </div>

    <!-- RUT Natural -->
    <div id="rut_natural_field" class="mt-4">
        <x-input-label for="rut_natural" value="RUT *" />
        <x-text-input id="rut_natural" name="rut_natural" type="text" class="w-full pr-10" maxlength="12"
            placeholder="Ej: 12.345.678-9" />
        <div id="rut_natural_icon" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none"></div>
        <p id="rut_natural_feedback" class="mt-1 text-sm"></p>
        <x-input-error :messages="$errors->get('rut_natural')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
    </div>

    <!-- RUT Empresa -->
    <div id="rut_empresa_field" class="hidden mt-4 relative">
        <x-input-label for="rut_empresa" value="RUT Empresa *" />
        <x-text-input id="rut_empresa" name="rut_empresa" type="text" class="w-full pr-10" maxlength="12"
            placeholder="Ej: 99.999.999-9" />
        <div id="rut_empresa_icon" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none"></div>
        <p id="rut_empresa_feedback" class="mt-1 text-sm"></p>
        <x-input-error :messages="$errors->get('rut_empresa')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
    </div>
    <!-- Empresa no encontrada -->
    <div id="empresa_no_encontrada" class="hidden mt-2 text-red-600 dark:text-red-400">
        Empresa no registrada.
        <a href="javascript:void(0);" id="btn_crear_empresa" class="ml-2 underline text-blue-600 hover:text-blue-800">
            Crear nueva empresa
        </a>
    </div>
    <!-- Razón Social y Giro -->
    <div id="razon_social_field" class="hidden mt-4">
        <x-input-label for="razon_social" value="Razón Social" />
        <x-text-input id="razon_social" name="razon_social" type="text" class="w-full" maxlength="255" />
        <x-input-error :messages="$errors->get('razon_social')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
    </div>
    <div id="giro_field" class="hidden mt-4">
        <x-input-label for="giro" value="Giro" />
        <x-text-input id="giro" name="giro" type="text" class="w-full" maxlength="255" />
        <x-input-error :messages="$errors->get('giro')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
    </div>

    <!-- Dirección -->
    <div class="mt-4">
        <x-input-label for="direccion" value="Dirección *" />
        <x-text-input id="direccion" name="direccion" type="text" class="w-full" maxlength="255" required />
        <x-input-error :messages="$errors->get('direccion')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
    </div>

    <!-- Región y Ciudad -->
    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <x-input-label for="id_region" value="Región *" />
            <select id="id_region" name="id_region"
                class="select2 w-full border-gray-300 dark:bg-gray-700 dark:text-white rounded" required>
                <option value="">Seleccione una región</option>
                @foreach ($regiones as $region)
                    <option value="{{ $region->id_region }}">{{ $region->nombre_region }}</option>
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
                    <option value="{{ $ciudad->id_ciudad }}" {{ old('id_ciudad') == $ciudad->id_ciudad ? 'selected' : '' }}>
                        {{ $ciudad->nombre_ciudad }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('id_ciudad')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
        </div>
    </div>

    <!-- Botones -->
    <div class="mt-8 flex flex-col items-start gap-4 sm:flex-row sm:items-center">
        <x-primary-button type="submit" id="btnGuardarCliente">Crear Cliente</x-primary-button>
        <x-secondary-button type="button" id="cerrarModalCliente">Cancelar</x-secondary-button>
    </div>
</form>
<script>
    $(document).on('click', '#btn_crear_empresa', function () {
        // Mostrar modal
        $('#modalCrearEmpresa').removeClass('hidden');

        // Cargar contenido del formulario de empresa vía AJAX
        $('#contenidoModalEmpresa').html('<div class="text-center text-gray-600 dark:text-gray-300">Cargando...</div>');
        $.get('{{ route('empresas.create') }}?popup=1', function (data) {
            $('#contenidoModalEmpresa').html(data);
            if (typeof initRutEmpresaValidation === 'function') {
                initRutEmpresaValidation();
            }
        });
    });

    // Cerrar modal al hacer clic en botón o fondo
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

    // Escuchar envío del formulario de empresa desde el modal
    $(document).on('submit', '#formCrearEmpresa', function (e) {
        e.preventDefault();

        const $form = $(this);
        $.post($form.attr('action'), $form.serialize(), function (resp) {
            if (resp.success) {
                $('#empresa-success-msg').removeClass('hidden').text('Empresa creada correctamente');
                $form.find('input, button, select, textarea').prop('disabled', true);

                // ACTUALIZA el rut, id, nombre, razon social, giro en el form cliente (sea modal o vista)
                let rutFormateado = resp.empresa.rut_empresa;
                if (typeof formatearRut === "function") {
                    rutFormateado = formatearRut(resp.empresa.rut_empresa);
                }
                $('#rut_empresa').val(rutFormateado).trigger('input').trigger('blur');
                $('#id_empresa').val(resp.empresa.id);
                $('#nombre_empresa_label').val(resp.empresa.nombre);
                $('#razon_social').val(resp.empresa.razon_social).prop('readonly', true).addClass('bg-gray-100');
                $('#giro').val(resp.empresa.giro).prop('readonly', false).removeClass('bg-gray-100');
                $('#empresa_no_encontrada').hide();

                // Cierra el modal después de 1.5s
                setTimeout(() => {
                    $('#modalCrearEmpresa').addClass('hidden');
                    $('#contenidoModalEmpresa').empty();
                }, 1500);
            }
        }).fail(function (xhr) {
            if (xhr.status === 422) {
                $('#contenidoModalEmpresa').html(xhr.responseText);
            }
        });
    });
</script>

<script>
    function initClienteModalScripts() {
        const $region = $('#id_region');
        const $ciudad = $('#id_ciudad');

        // Inicializa Select2 para Región y Ciudad
        $region.select2({
            dropdownParent: $('#modalCrearCliente'),
            placeholder: "Seleccione una región",
            allowClear: true,
            width: 'resolve'
        });
        $ciudad.select2({
            dropdownParent: $('#modalCrearCliente'), // 🔧 Importante para modales
            placeholder: "Seleccione una ciudad",
            allowClear: true,
            width: 'resolve'
        });

        // 🔥 Aplica estilo desde el inicio también
        estilizarSelect2();


        // Evento al cambiar región
        $region.on('change', async function () {
            const regionId = $(this).val();

            $ciudad.select2('destroy').empty().append('<option value="">Cargando ciudades...</option>');

            if (!regionId) {
                $ciudad.empty().append('<option value="">Seleccione una ciudad</option>').prop('disabled', true);
                return reinicializarCiudad();
            }

            try {
                const res = await fetch(`/cxr/${regionId}`);
                if (!res.ok) throw new Error('Error al cargar ciudades');

                const ciudades = await res.json();

                $ciudad.empty().append('<option value="">Seleccione una ciudad</option>');
                ciudades.forEach(c => {
                    $ciudad.append(`<option value="${c.id_ciudad}">${c.nombre_ciudad}</option>`);
                });

                $ciudad.prop('disabled', false);
            } catch (err) {
                console.error('Error al cargar ciudades:', err);
                $ciudad.empty().append('<option value="">Error al cargar</option>').prop('disabled', true);
            }

            reinicializarCiudad();
        });

        function reinicializarCiudad() {
            $ciudad.select2({
                placeholder: "Seleccione una ciudad",
                allowClear: true,
                width: 'resolve'
            });

            // 🔥 Asegúrate de aplicar el estilo DESPUÉS de inicializar
            estilizarSelect2();
        }

        // Estilo opcional (puedes quitarlo si usas Tailwind y ya se ve bien)
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

        // Mostrar/ocultar campos según tipo de cliente
        function toggleTipoCliente() {
            const tipo = document.querySelector('input[name="tipo_cliente"]:checked')?.value;
            document.getElementById('rut_natural_field').style.display = tipo === 'natural' ? 'block' : 'none';
            document.getElementById('rut_empresa_field').style.display = tipo === 'empresa' ? 'block' : 'none';
            document.getElementById('razon_social_field').style.display = tipo === 'empresa' ? 'block' : 'none';
            document.getElementById('giro_field').style.display = tipo === 'empresa' ? 'block' : 'none';
        }

        document.querySelectorAll('input[name="tipo_cliente"]').forEach(el => {
            el.addEventListener('change', toggleTipoCliente);
        });
        toggleTipoCliente(); // Aplicar al cargar

        // Validar teléfono
        const tel = document.getElementById('nro_contacto');
        if (tel) {
            tel.addEventListener('input', () => {
                tel.value = tel.value.replace(/\D/g, '').slice(0, 9);
            });
        }

        // Validar RUT
        if (typeof window.initRutValidation === 'function') {
            window.initRutValidation();
        }

    }
</script>