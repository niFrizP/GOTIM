<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            Crear Usuario
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="rounded bg-white dark:bg-gray-800 p-6 shadow">
                <form method="POST" action="{{ route('users.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Nombre -->
                        <div>
                            <x-input-label for="nombre" value="Nombre" />
                            <x-text-input id="nombre" name="nombre" type="text" class="w-full"
                                value="{{ old('nombre') }}" required />
                            <x-input-error :messages="$errors->get('nombre')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        <!-- Apellido -->
                        <div>
                            <x-input-label for="apellido" value="Apellido" />
                            <x-text-input id="apellido" name="apellido" type="text" class="w-full"
                                value="{{ old('apellido') }}" required />
                            <x-input-error :messages="$errors->get('apellido')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        <!-- Correo -->
                        <div>
                            <x-input-label for="email" value="Correo Electrónico" />
                            <x-text-input id="email" name="email" type="email" class="w-full"
                                value="{{ old('email') }}" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        <!-- Rol -->
                        <div>
                            <x-input-label for="rol" value="Rol" />
                            <select name="rol" id="rol" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" required>
                                <option value="">Seleccione un rol</option>
                                @foreach ($roles as $rol)
                                    <option value="{{ $rol }}" {{ old('rol') == $rol ? 'selected' : '' }}>
                                        {{ ucfirst($rol) }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('rol')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        <!-- Estado -->
                        <div>
                            <x-input-label for="estado" value="Estado" />
                            <select name="estado" id="estado" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" required>
                                <option value="1" {{ old('estado') == '1' ? 'selected' : '' }}>Activo</option>
                                <option value="0" {{ old('estado') == '0' ? 'selected' : '' }}>Inhabilitado</option>
                            </select>
                            <x-input-error :messages="$errors->get('estado')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        <!-- Contraseña -->
                        <div>
                            <x-input-label for="password" value="Contraseña" />
                            <x-text-input id="password" name="password" type="password" class="w-full" required />
                            <x-input-error :messages="$errors->get('password')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        <!-- Confirmar contraseña -->
                        <div>
                            <x-input-label for="password_confirmation" value="Confirmar Contraseña" />
                            <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="w-full" required />
                        </div>
                    </div>

                    <div class="mt-6">
                        <x-primary-button>Guardar Usuario</x-primary-button>
                        <a href="{{ route('users.index') }}"
                            class="ml-4 text-sm text-gray-600 dark:text-gray-300 hover:underline">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
