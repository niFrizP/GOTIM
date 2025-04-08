<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Crear Cliente
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="rounded bg-white p-6 shadow">
                <form method="POST" action="{{ route('clientes.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Nombre -->
                        <div>
                            <x-input-label for="nombre" value="Nombre" />
                            <x-text-input id="nombre" name="nombre" type="text" class="w-full"
                                value="{{ old('nombre') }}" required />
                            <x-input-error :messages="$errors->get('nombre')" class="mt-1 text-sm text-red-600" />
                        </div>

                        <!-- Apellido -->
                        <div>
                            <x-input-label for="apellido" value="Apellido" />
                            <x-text-input id="apellido" name="apellido" type="text" class="w-full"
                                value="{{ old('apellido') }}" required />
                            <x-input-error :messages="$errors->get('apellido')" class="mt-1 text-sm text-red-600" />
                        </div>

                        <!-- Correo -->
                        <div>
                            <x-input-label for="email" value="Correo Electrónico" />
                            <x-text-input id="email" name="email" type="email" class="w-full"
                                value="{{ old('email') }}" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-1 text-sm text-red-600" />
                        </div>

                        <!-- RUT -->
                        <div>
                            <x-input-label for="rut" value="RUT" />
                            <x-text-input id="rut" name="rut" type="text" class="w-full"
                                value="{{ old('rut') }}" required />
                            <x-input-error :messages="$errors->get('rut')" class="mt-1 text-sm text-red-600" />
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <x-input-label for="telefono" value="Teléfono" />
                            <x-text-input id="telefono" name="telefono" type="text" class="w-full"
                                value="{{ old('telefono') }}" required />
                            <x-input-error :messages="$errors->get('telefono')" class="mt-1 text-sm text-red-600" />
                        </div>

                        <!-- Dirección -->
                        <div>
                            <x-input-label for="direccion" value="Dirección" />
                            <x-text-input id="direccion" name="direccion" type="text" class="w-full"
                                value="{{ old('direccion') }}" />
                            <x-input-error :messages="$errors->get('direccion')" class="mt-1 text-sm text-red-600" />
                        </div>
                    </div>

                    <div class="mt-6">
                        <x-primary-button>Guardar Cliente</x-primary-button>
                        <a href="{{ route('clientes.index') }}"
                            class="ml-4 text-sm text-gray-600 hover:underline">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- Well begun is half done. - Aristotle -->
