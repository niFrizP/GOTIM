<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            Editar Movimiento de Inventario
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="rounded bg-white dark:bg-gray-800 p-6 shadow">
                <form method="POST" action="{{ route('inventario.update', $inventario->id_inventario) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Producto -->
                        <div class="sm:col-span-2">
                            <x-input-label for="id_producto" value="Producto" />
                            <select name="id_producto" id="id_producto"
                                class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" required>
                                <option value="">Seleccione un producto</option>
                                @foreach ($productos as $producto)
                                <option value="{{ $producto->id_producto }}"
                                    {{ old('id_producto', $inventario->id_producto) == $producto->id_producto ? 'selected' : '' }}>
                                    {{ $producto->marca }} {{ $producto->modelo }}
                                </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('id_producto')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        <!-- Cantidad -->
                        <div>
                            <x-input-label for="cantidad" value="Cantidad" />
                            <x-text-input id="cantidad" name="cantidad" type="number" min="1" class="w-full"
                                value="{{ old('cantidad', $inventario->cantidad) }}" required />
                            <x-input-error :messages="$errors->get('cantidad')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        <!-- Fecha Ingreso (solo lectura) -->
                        <div>
                        <x-input-label for="fecha_ingreso" value="Fecha Ingreso" />
                        <x-text-input id="fecha_ingreso" name="fecha_ingreso" type="datetime-local" class="w-full bg-gray-100 dark:bg-gray-700"
                            value="{{ \Carbon\Carbon::parse($inventario->fecha_ingreso)->format('Y-m-d\TH:i') }}" disabled readonly />
                        <small class="text-gray-500 dark:text-gray-400">Esta fecha se establece automáticamente y no puede editarse.</small>
                        </div>

                        <!-- Fecha Salida -->
                        <div>
                            <x-input-label for="fecha_salida" value="Fecha Salida (opcional)" />
                            <x-text-input id="fecha_salida" name="fecha_salida" type="datetime-local" class="w-full"
                                value="{{ old('fecha_salida', $inventario->fecha_salida ? \Carbon\Carbon::parse($inventario->fecha_salida)->format('Y-m-d\TH:i') : '') }}" />
                            <x-input-error :messages="$errors->get('fecha_salida')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>
                    </div>

                    <div class="mt-6">
                        <x-primary-button>Actualizar Movimiento</x-primary-button>
                        <a href="{{ route('inventario.index') }}"
                            class="ml-4 text-sm text-gray-600 dark:text-gray-300 hover:underline">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>