<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            Editar Servicio
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="rounded bg-white dark:bg-gray-800 p-6 shadow">
                <form method="POST" action="{{ route('servicios.update', $servicio->id_servicio) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Nombre Servicio -->
                        <div class="sm:col-span-2">
                            <x-input-label for="nombre_servicio" value="Nombre del Servicio" />
                            <x-text-input id="nombre_servicio" name="nombre_servicio" type="text" class="w-full"
                                value="{{ old('nombre_servicio', $servicio->nombre_servicio) }}" required />
                            <x-input-error :messages="$errors->get('nombre_servicio')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        <!-- Descripción -->
                        <div class="sm:col-span-2">
                            <x-input-label for="descripcion" value="Descripción" />
                            <textarea id="descripcion" name="descripcion"
                                class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white"
                                rows="4">{{ old('descripcion', $servicio->descripcion) }}</textarea>
                            <x-input-error :messages="$errors->get('descripcion')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>
                    </div>

                    <div class="mt-6">
                        <x-primary-button>Actualizar Servicio</x-primary-button>
                        <a href="{{ route('servicios.index') }}"
                            class="ml-4 text-sm text-gray-600 dark:text-gray-300 hover:underline">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>