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

            document.getElementById('id_region').addEventListener('change', async function() {
                const regionId = this.value;
                console.log('Seleccionaste región ID:', regionId);

                const $ciudad = $('#id_ciudad');

                if (regionId) {
                    try {
                        const response = await fetch(`/cxr/${regionId}`);

                        if (!response.ok) {
                            throw new Error('Error al cargar ciudades');
                        }

                        const ciudades = await response.json();
                        console.log('Ciudades recibidas:', ciudades);

                        // Vaciar y actualizar las opciones
                        $ciudad.empty();
                        $ciudad.append('<option value="">Seleccione una ciudad</option>');

                        ciudades.forEach(function(ciudad) {
                            $ciudad.append(
                                `<option value="${ciudad.id_ciudad}">${ciudad.nombre_ciudad}</option>`
                            );
                        });

                        // Refrescar Select2 solo para el selector de ciudades
                        $ciudad.select2({
                            placeholder: "Seleccione una opción",
                            allowClear: true
                        });

                        // Resetear selección
                        $ciudad.val(null).trigger('change');

                    } catch (error) {
                        console.error('Error cargando ciudades:', error);
                    }
                }
            });
        </script>
    @endpush

</x-app-layout>


<!-- Well begun is half done. - Aristotle -->
