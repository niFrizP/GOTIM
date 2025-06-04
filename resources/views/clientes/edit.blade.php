<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            Editar Cliente
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="rounded-lg bg-white dark:bg-gray-800 p-6 shadow-md">
                <form method="POST" action="{{ route('clientes.update', $cliente->id_cliente) }}">
                    @csrf
                    @method('PUT')

                    <!-- Tipo cliente oculto -->
                    <input type="hidden" id="tipo_cliente" name="tipo_cliente" value="{{ $cliente->tipo_cliente }}">

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Nombre -->
                        <div>
                            <x-input-label for="nombre_cliente" value="Nombre *" class="dark:text-gray-300" />
                            <x-text-input id="nombre_cliente" name="nombre_cliente" type="text" class="w-full"
                                value="{{ old('nombre_cliente', $cliente->nombre_cliente) }}" required />
                            <x-input-error :messages="$errors->get('nombre_cliente')" class="mt-1 text-sm text-red-600" />
                        </div>

                        <!-- Apellido -->
                        <div>
                            <x-input-label for="apellido_cliente" value="Apellido *" class="dark:text-gray-300" />
                            <x-text-input id="apellido_cliente" name="apellido_cliente" type="text" class="w-full"
                                value="{{ old('apellido_cliente', $cliente->apellido_cliente) }}" required />
                            <x-input-error :messages="$errors->get('apellido_cliente')" class="mt-1 text-sm text-red-600" />
                        </div>

                        <!-- Correo -->
                        <div>
                            <x-input-label for="email" value="Correo Electrónico *" class="dark:text-gray-300" />
                            <x-text-input id="email" name="email" type="email" class="w-full"
                                value="{{ old('email', $cliente->email) }}" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-1 text-sm text-red-600" />
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <x-input-label for="nro_contacto" value="Teléfono *" class="dark:text-gray-300" />
                            <x-text-input id="nro_contacto" name="nro_contacto" type="tel" class="w-full"
                                value="{{ old('nro_contacto', $cliente->nro_contacto) }}" maxlength="9" minlength="9"
                                required />
                            <small class="text-gray-500 dark:text-gray-400">Ingrese el número sin el prefijo +56</small>
                            <x-input-error :messages="$errors->get('nro_contacto')" class="mt-1 text-sm text-red-600" />
                        </div>

                        <!-- Campos para clientes naturales -->
                        <div id="natural_fields" class="col-span-2 grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- RUT -->
                            <div>
                                <x-input-label for="rut" value="RUT *" class="dark:text-gray-300" />
                                <x-text-input id="rut" name="rut" type="text" class="w-full"
                                    value="{{ old('rut', $cliente->rut) }}" maxlength="12"
                                    placeholder="Ej: 9.999.999-9" />
                                <x-input-error :messages="$errors->get('rut')" class="mt-1 text-sm text-red-600" />
                            </div>

                            <!-- Razón Social -->
                            <div id="razon_social_field" style="display: none;">
                                <x-input-label for="razon_social" value="Razón Social" />
                                <x-text-input id="razon_social" name="razon_social" type="text" class="w-full"
                                    value="{{ old('razon_social', $cliente->razon_social) }}" />
                                <x-input-error :messages="$errors->get('razon_social')" class="mt-1 text-sm text-red-600" />
                            </div>
                        </div>

                        <!-- Campos para clientes empresa -->
                        <div id="empresa_fields" class="col-span-2 hidden">
                            <div>
                                <x-input-label for="id_empresa" value="Empresa/ Razón social/ RUT *"
                                    class="dark:text-gray-300" />
                                <select id="id_empresa" name="id_empresa"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300">
                                    <option value="">Seleccione una empresa</option>
                                    @foreach ($empresas as $empresa)
                                        <option value="{{ $empresa->id_empresa }}"
                                            {{ old('id_empresa', $cliente->id_empresa) == $empresa->id_empresa ? 'selected' : '' }}>
                                            {{ $empresa->nom_emp }} / {{ $empresa->razon_social }} /
                                            ({{ $empresa->rut_empresa }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('id_empresa')" class="mt-1 text-sm text-red-600" />
                            </div>
                        </div>

                        <!-- Giro -->
                        <div id="giro_field" style="display: none;">
                            <x-input-label for="giro" value="Giro" />
                            <x-text-input id="giro" name="giro" type="text" class="w-full"
                                value="{{ old('giro', $empresa->giro) }}" required />
                            <x-input-error :messages="$errors->get('giro')" class="mt-1 text-sm text-red-600" />
                        </div>

                        <!-- Dirección -->
                        <div>
                            <x-input-label for="direccion" value="Dirección" class="dark:text-gray-300" />
                            <x-text-input id="direccion" name="direccion" type="text" class="w-full"
                                value="{{ old('direccion', $cliente->direccion) }}" />
                            <x-input-error :messages="$errors->get('direccion')" class="mt-1 text-sm text-red-600" />
                        </div>

                        <!-- Región -->
                        <div>
                            <x-input-label for="id_region" value="Región *" />
                            <select id="id_region" name="id_region" class="w-full select2" required>
                                @foreach ($regiones as $region)
                                    <option value="{{ $region->id_region }}"
                                        {{ old('id_region', $cliente->id_region) == $region->id_region ? 'selected' : '' }}>
                                        {{ $region->nombre_region }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('id_region')" class="mt-1 text-sm text-red-600" />
                        </div>

                        <!-- Ciudad -->
                        <div>
                            <x-input-label for="id_ciudad" value="Ciudad *" />
                            <select id="id_ciudad" name="id_ciudad" class="w-full select2" required>
                                @foreach ($ciudades as $ciudad)
                                    <option value="{{ $ciudad->id_ciudad }}"
                                        {{ old('id_ciudad', $cliente->id_ciudad) == $ciudad->id_ciudad ? 'selected' : '' }}>
                                        {{ $ciudad->nombre_ciudad }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('id_ciudad')" class="mt-1 text-sm text-red-600" />
                        </div>
                    </div>

                    <div class="mt-8 flex flex-col items-start gap-4 sm:flex-row sm:items-center">
                        <x-primary-button>Actualizar Cliente</x-primary-button>
                        <a href="{{ route('clientes.index') }}"
                            class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white underline">
                            Cancelar
                        </a>
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

                const tipoCliente = document.getElementById('tipo_cliente')?.value;
                const naturalFields = document.getElementById('natural_fields');
                const empresaFields = document.getElementById('empresa_fields');

                if (tipoCliente === 'empresa') {
                    empresaFields.classList.remove('hidden');
                    naturalFields.classList.add('hidden');
                    document.getElementById('razon_social_field').style.display = 'none';
                    document.getElementById('giro_field').style.display = 'block';
                } else {
                    empresaFields.classList.add('hidden');
                    naturalFields.classList.remove('hidden');
                }

                // Validar solo números en teléfono
                document.getElementById('nro_contacto')?.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });

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
            });
        </script>
    @endpush
</x-app-layout>
