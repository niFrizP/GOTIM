<x-app-layout> 
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            Editar Producto
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="rounded-lg bg-white dark:bg-gray-800 p-6 shadow-md">
                <form method="POST" action="{{ route('productos.update', $producto->id_producto) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-6">
                        <!-- Nombre -->
                        <div>
                            <x-input-label for="nombre_producto" value="Nombre" />
                            <x-text-input id="nombre_producto" name="nombre_producto" type="text" class="w-full"
                                value="{{ old('nombre_producto', $producto->nombre_producto) }}" required />
                            <x-input-error :messages="$errors->get('nombre_producto')" class="mt-1 text-sm text-red-600" />
                        </div>

                        <!-- Categoría y Tipo de Producto -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="categorias" value="Categoría" />
                                <select id="id_categoria" name="id_categoria" class="w-full" required>
                                    <option value="">Seleccione una categoría</option>
                                    @foreach ($categorias as $categoria)
                                        <option value="{{ $categoria->id_categoria }}"
                                            {{ old('id_categoria', $producto->id_categoria) == $categoria->id_categoria ? 'selected' : '' }}>
                                            {{ $categoria->nombre_categoria }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('id_categoria')" class="mt-1 text-sm text-red-600" />
                            </div>

                            <div>
                                <x-input-label for="tipo_producto_id" value="Tipo de Producto" />
                                <select id="tipo_producto_id" name="tipo_producto_id" class="w-full">
                                    <option value="">Seleccione un tipo</option>
                                    @foreach ($tiposProducto as $tipo)
                                        <option value="{{ $tipo->tipo_producto_id }}"
                                            {{ old('tipo_producto_id', $producto->tipo_producto_id) == $tipo->tipo_producto_id ? 'selected' : '' }}>
                                            {{ $tipo->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('tipo_producto_id')" class="mt-1 text-sm text-red-600" />
                            </div>
                        </div>

                        <!-- Marca y Modelo -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="marca" value="Marca" />
                                <x-text-input id="marca" name="marca" type="text" class="w-full"
                                    value="{{ old('marca', $producto->marca) }}" />
                                <x-input-error :messages="$errors->get('marca')" class="mt-1 text-sm text-red-600" />
                            </div>

                            <div>
                                <x-input-label for="modelo" value="Modelo" />
                                <x-text-input id="modelo" name="modelo" type="text" class="w-full"
                                    value="{{ old('modelo', $producto->modelo) }}" />
                                <x-input-error :messages="$errors->get('modelo')" class="mt-1 text-sm text-red-600" />
                            </div>
                        </div>

                        <!-- Descripción -->
                        <div>
                            <x-input-label for="descripcion" value="Descripción" />
                            <textarea id="descripcion" name="descripcion" rows="3"
                                class="w-full rounded dark:bg-gray-700">{{ old('descripcion', $producto->descripcion) }}</textarea>
                            <x-input-error :messages="$errors->get('descripcion')" class="mt-1 text-sm text-red-600" />
                        </div>

                        <!-- Código -->
                        <div>
                            <x-input-label for="codigo" value="Código" />
                            <x-text-input id="codigo" name="codigo" type="text" class="w-full"
                                value="{{ old('codigo', $producto->codigo) }}" required />
                            <x-input-error :messages="$errors->get('codigo')" class="mt-1 text-sm text-red-600" />
                        </div>

                        <!-- Imagen -->
                        <div>
                            <x-input-label for="imagen" value="Imagen (opcional)" />
                            <input id="imagen" name="imagen" type="file" accept="image/*"
                                class="w-full mt-1 dark:bg-gray-700" />
                            <x-input-error :messages="$errors->get('imagen')" class="mt-1 text-sm text-red-600" />
                            @if ($producto->imagen)
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                    Imagen actual: <br>
                                    <img src="{{ asset('storage/' . $producto->imagen) }}" alt="Imagen actual" class="h-32 mt-2 rounded border">
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="mt-8 flex flex-col items-start gap-4 sm:flex-row sm:items-center">
                        <x-primary-button>Actualizar Producto</x-primary-button>
                        <a href="{{ route('productos.index') }}"
                            class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white underline">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
