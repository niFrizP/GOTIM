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
                            <x-input-label for="nombre_cliente" value="Nombre" />
                            <x-text-input id="nombre_cliente" name="nombre_cliente" type="text" class="w-full"
                                value="{{ old('nombre_cliente') }}" required />
                            <x-input-error :messages="$errors->get('nombre_cliente')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        <!-- Apellido -->
                        <div>
                            <x-input-label for="apellido_cliente" value="Apellido" />
                            <x-text-input id="apellido_cliente" name="apellido_cliente" type="text" class="w-full"
                                value="{{ old('apellido_cliente') }}" required />
                            <x-input-error :messages="$errors->get('apellido_cliente')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        <!-- Correo -->
                        <div>
                            <x-input-label for="email" value="Correo Electrónico" />
                            <x-text-input id="email" name="email" type="email" class="w-full"
                                value="{{ old('email') }}" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <x-input-label for="nro_contacto" value="Teléfono" />
                            <x-text-input id="nro_contacto" name="nro_contacto" type="number" class="w-full"
                                value="{{ old('nro_contacto') }}" required />
                            <x-input-error :messages="$errors->get('nro_contacto')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        <!-- Giro -->
                        <div>
                            <x-input-label for="giro" value="Giro" />
                            <x-text-input id="giro" name="giro" type="text" class="w-full"
                                value="{{ old('giro') }}" required />
                            <x-input-error :messages="$errors->get('giro')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        <!-- Razón Social -->
                        <div>
                            <x-input-label for="razon_social" value="Razón Social" />
                            <x-text-input id="razon_social" name="razon_social" type="text" class="w-full"
                                value="{{ old('razon_social') }}" required />
                            <x-input-error :messages="$errors->get('razon_social')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        <!-- RUT -->
                        <div>
                            <x-input-label for="rut" value="RUT" />
                            <x-text-input id="rut" name="rut" type="text" class="w-full"
                                value="{{ old('rut') }}" maxlength="12" placeholder="Ej: 9.999.999-9" required />
                            <x-input-error :messages="$errors->get('rut')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        <!-- Dirección -->
                        <div>
                            <x-input-label for="direccion" value="Dirección" />
                            <x-text-input id="direccion" name="direccion" type="text" class="w-full select2"
                                value="{{ old('direccion') }}" />
                            <x-input-error :messages="$errors->get('direccion')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>
                        <!-- Región -->
                        <div>
                            <x-input-label for="region" value="Región" />
                            <select id="id_region" name="id_region" class="w-full select2" required>
                                <option value="" disabled selected>Seleccione una región</option>
                                @foreach ($regiones as $region)
                                    <option value="{{ $region->id_region }}">{{ $region->nombre_region }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Ciudad -->
                        <div>
                            <x-input-label for="ciudad" value="Ciudad" />
                            <select id="id_ciudad" name="id_ciudad" class="w-full select2" required>
                                <option value="" disabled selected>Seleccione una ciudad</option>
                                {{-- Las ciudades se cargarán dinámicamente --}}
                            </select>
                        </div>
                    </div>
                    <div class="mt-4">
                        <x-primary-button>
                            Crear Cliente
                        </x-primary-button>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('clientes.index') }}" class="text-blue-500 hover:text-blue-700">
                            Volver a la lista de clientes
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            function formatRut(input) {
                let value = input.value.toUpperCase().replace(/[^0-9K]/g, '');
                if (value.length === 9) {
                    value = value.replace(/^(\d{2})(\d{3})(\d{3})([\dkK])$/, '$1.$2.$3-$4');
                } else if (value.length === 8) {
                    value = value.replace(/^(\d{1})(\d{3})(\d{3})([\dkK])$/, '$1.$2.$3-$4');
                }
                input.value = value;
            };


            document.getElementById('rut').addEventListener('input', function() {
                formatRut(this);
            });

            document.addEventListener('DOMContentLoaded', function() {
                const $region = $('#id_region');
                const $ciudad = $('#id_ciudad');

                // Inicializar select2 por única vez
                $region.select2({
                    placeholder: "Seleccione una región",
                    allowClear: true,
                    width: 'resolve'
                });

                $ciudad.select2({
                    placeholder: "Seleccione una ciudad",
                    allowClear: true,
                    width: 'resolve'
                });

                // Al inicio: desactivar el campo ciudad
                $ciudad.prop('disabled', true);

                estilizarSelect2();

                // Evento cambio de región
                $region.on('change', async function() {
                    const regionId = $(this).val();

                    if (regionId) {
                        try {
                            const response = await fetch(`/cxr/${regionId}`);
                            if (!response.ok) throw new Error('Error al cargar ciudades');

                            const ciudades = await response.json();

                            // Destruir instancia previa para evitar conflictos
                            $ciudad.select2('destroy');

                            // Vaciar y cargar nuevas opciones
                            $ciudad.empty().append('<option value="">Seleccione una ciudad</option>');
                            ciudades.forEach(ciudad => {
                                $ciudad.append(
                                    `<option value="${ciudad.id_ciudad}">${ciudad.nombre_ciudad}</option>`
                                );
                            });
                            $ciudad.prop('disabled', false);

                            // Volver a inicializar select2 con las nuevas opciones
                            $ciudad.select2({
                                placeholder: "Seleccione una ciudad",
                                allowClear: true,
                                width: 'resolve'
                            });

                            // Aplicar estilos de nuevo
                            estilizarSelect2();

                        } catch (error) {
                            console.error('Error cargando ciudades:', error);
                        }
                    } else {
                        // Si se deselecciona la región, limpiar el campo ciudad
                        $ciudad.select2('destroy');
                        $ciudad.empty().append('<option value="">Seleccione una ciudad</option>');
                        $ciudad.select2({
                            placeholder: "Seleccione una ciudad",
                            allowClear: true,
                            width: 'resolve'
                        });
                        estilizarSelect2();
                    }
                });

                function estilizarSelect2() {
                    setTimeout(() => {
                        $('.select2-container--default .select2-selection--single').each(function() {
                            $(this).css({
                                'background-color': '#fff',
                                'border': '1px solid #d1d5db',
                                'border-radius': '0.5rem',
                                'height': '42px',
                                'padding': '0.5rem 0.75rem',
                                'font-size': '0.875rem',
                                'color': '#000'
                            });
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
        </script>
    @endpush

</x-app-layout>


<!-- Well begun is half done. - Aristotle -->
