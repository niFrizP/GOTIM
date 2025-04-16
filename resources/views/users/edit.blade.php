<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            Editar Usuario
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="rounded-lg bg-white dark:bg-gray-800 p-6 shadow-md">
                <form method="POST" action="{{ route('users.update', $user->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Nombre -->
                        <div>
                            <x-input-label for="nombre" value="Nombre" class="dark:text-gray-300" />
                            <x-text-input id="nombre" name="nombre" type="text" class="w-full"
                                value="{{ old('nombre', $user->nombre) }}" required />
                            <x-input-error :messages="$errors->get('nombre')" class="mt-1 text-sm text-red-600" />
                        </div>

                        <!-- Apellido -->
                        <div>
                            <x-input-label for="apellido" value="Apellido" class="dark:text-gray-300" />
                            <x-text-input id="apellido" name="apellido" type="text" class="w-full"
                                value="{{ old('apellido', $user->apellido) }}" required />
                            <x-input-error :messages="$errors->get('apellido')" class="mt-1 text-sm text-red-600" />
                        </div>

                        <!-- Correo -->
                        <div>
                            <x-input-label for="email" value="Correo Electrónico" class="dark:text-gray-300" />
                            <x-text-input id="email" name="email" type="email" class="w-full"
                                value="{{ old('email', $user->email) }}" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-1 text-sm text-red-600" />
                        </div>

                        <!-- Rol -->
                        <div>
                            <x-input-label for="rol" value="Rol" class="dark:text-gray-300" />
                            <select name="rol" id="rol" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" required>
                                <option value="">Seleccione un rol</option>
                                @foreach ($roles as $rol)
                                <option value="{{ $rol }}" {{ old('rol', $user->rol) === $rol ? 'selected' : '' }}>
                                    {{ ucfirst($rol) }}
                                </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('rol')" class="mt-1 text-sm text-red-600" />
                        </div>

                        <!-- Estado -->
                        <div>
                            <x-input-label for="estado" value="Estado" class="dark:text-gray-300" />
                            <select name="estado" id="estado" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" required>
                                <option value="1" {{ old('estado', $user->estado) == '1' ? 'selected' : '' }}>Activo</option>
                                <option value="0" {{ old('estado', $user->estado) == '0' ? 'selected' : '' }}>Inhabilitado</option>
                            </select>
                            <x-input-error :messages="$errors->get('estado')" class="mt-1 text-sm text-red-600" />
                        </div>

                        <!-- Cambiar Contraseña (opcional) -->
                        <div>
                            <x-input-label for="password" value="Nueva Contraseña (opcional)" class="dark:text-gray-300" />
                            <x-text-input id="password" name="password" type="password" class="w-full" />
                            <x-input-error :messages="$errors->get('password')" class="mt-1 text-sm text-red-600" />
                        </div>
                    </div>

                    <div class="mt-8 flex flex-col items-start gap-4 sm:flex-row sm:items-center">
                        <x-primary-button>Actualizar Usuario</x-primary-button>
                        <a href="{{ route('users.index') }}"
                            class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white underline">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>