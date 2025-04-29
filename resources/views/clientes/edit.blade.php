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

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Nombre -->
                        <div>
                            <x-input-label for="nombre_cliente" value="Nombre" class="dark:text-gray-300" />
                            <x-text-input id="nombre_cliente" name="nombre_cliente" type="text" class="w-full"
                                value="{{ old('nombre_cliente', $cliente->nombre_cliente) }}" required />
                            <x-input-error :messages="$errors->get('nombre_cliente')" class="mt-1 text-sm text-red-600" />
                        </div>

                        <!-- Apellido -->
                        <div>
                            <x-input-label for="apellido_cliente" value="Apellido" class="dark:text-gray-300" />
                            <x-text-input id="apellido_cliente" name="apellido_cliente" type="text" class="w-full"
                                value="{{ old('apellido_cliente', $cliente->apellido_cliente) }}" required />
                            <x-input-error :messages="$errors->get('apellido_cliente')" class="mt-1 text-sm text-red-600" />
                        </div>

                        <!-- Correo -->
                        <div>
                            <x-input-label for="email" value="Correo Electrónico" class="dark:text-gray-300" />
                            <x-text-input id="email" name="email" type="email" class="w-full"
                                value="{{ old('email', $cliente->email) }}" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-1 text-sm text-red-600" />
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <x-input-label for="nro_contacto" value="Teléfono" class="dark:text-gray-300" />
                            <x-text-input id="nro_contacto" name="nro_contacto" type="text" class="w-full"
                                value="{{ old('nro_contacto', $cliente->nro_contacto) }}" required />
                            <x-input-error :messages="$errors->get('nro_contacto')" class="mt-1 text-sm text-red-600" />
                        </div>
                        <!-- Razón Social -->
                        <div>
                            <x-input-label for="razon_social" value="Razón Social" class="dark:text-gray-300" />
                            <x-text-input id="razon_social" name="razon_social" type="text" class="w-full"
                                value="{{ old('razon_social', $cliente->razon_social) }}" required />
                            <x-input-error :messages="$errors->get('razon_social')" class="mt-1 text-sm text-red-600" />
                        </div>
                        <!-- Giro -->
                        <div>
                            <x-input-label for="giro" value="Giro" class="dark:text-gray-300" />
                            <x-text-input id="giro" name="giro" type="text" class="w-full"
                                value="{{ old('giro', $cliente->giro) }}" required />
                            <x-input-error :messages="$errors->get('giro')" class="mt-1 text-sm text-red-600" />
                        </div>

                        <!-- RUT -->
                        <div>
                            <x-input-label for="rut" value="RUT" class="dark:text-gray-300" />
                            <x-text-input id="rut" name="rut" type="text" class="w-full"
                                value="{{ old('rut', $cliente->rut) }}" maxlength="12" placeholder="Ej: 9.999.999-9"
                                required />
                            <x-input-error :messages="$errors->get('rut')" class="mt-1 text-sm text-red-600" />
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
                            <x-input-label for="id_region" value="Región" class="dark:text-gray-300" />
                            <select id="id_region" name="id_region"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300">
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
                            <x-input-label for="id_ciudad" value="Ciudad" class="dark:text-gray-300" />
                            <select id="id_ciudad" name="id_ciudad"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300">
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
    <script>
        function formatRut(input) {
            let value = input.value.toUpperCase().replace(/[^0-9K]/g, '');
            if (value.length === 9) {
                value = value.replace(/^(\d{2})(\d{3})(\d{3})([\dkK])$/, '$1.$2.$3-$4');
            } else if (value.length === 8) {
                value = value.replace(/^(\d{1})(\d{3})(\d{3})([\dkK])$/, '$1.$2.$3-$4');
            }
            input.value = value;
        }

        document.getElementById('rut').addEventListener('input', function() {
            formatRut(this);
        });
    </script>
</x-app-layout>

<!-- Simplicity is the ultimate sophistication. - Leonardo da Vinci -->
