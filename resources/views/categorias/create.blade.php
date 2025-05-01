<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            Crear Nueva Categoría
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="rounded-lg bg-white dark:bg-gray-800 p-6 shadow-md">
                <form action="{{ route('categorias.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Nombre de la categoría -->
                        <div>
                            <x-input-label for="nombre_categoria" value="Nombre de la Categoría"
                                class="dark:text-gray-300" />
                            <x-text-input id="nombre_categoria" name="nombre_categoria" type="text" class="w-full"
                                value="{{ old('nombre_categoria') }}" required />
                            <x-input-error :messages="$errors->get('nombre_categoria')" class="mt-1 text-sm text-red-600" />
                        </div>


                        <!-- Descripción (ocupa ambas columnas) -->
                        <div class="sm:col-span-2">
                            <x-input-label for="descripcion" value="Descripción" class="dark:text-gray-300" />
                            <textarea id="descripcion" name="descripcion" rows="4"
                                class="mt-1 block w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white">{{ old('descripcion') }}</textarea>
                            <x-input-error :messages="$errors->get('descripcion')" class="mt-1 text-sm text-red-600" />
                        </div>
                    </div>

                    <div class="mt-8 flex flex-col items-start gap-4 sm:flex-row sm:items-center">
                        <x-primary-button>Crear Categoría</x-primary-button>
                        <a href="{{ route('categorias.index') }}"
                            class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white underline">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>


<!-- Una vida no examinada no vale la pena ser vivida. - Sócrates -->
