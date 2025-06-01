<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            Crear Producto
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="rounded bg-white dark:bg-gray-800 p-6 shadow">
                <form method="POST" action="{{ route('productos.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 gap-4">
                        <!-- Nombre del Producto -->
                        <div>
                            <x-input-label for="nombre_producto" value="Nombre" />
                            <x-text-input id="nombre_producto" name="nombre_producto" type="text" class="w-full"
                                value="{{ old('nombre_producto') }}" />
                            <x-input-error :messages="$errors->get('nombre_producto')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        <!-- Categoría y Tipo de Producto -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <!-- Categoría -->
                            <div>
                                <x-input-label for="categorias" value="Categoría" />
                                <select id="id_categoria" name="id_categoria" class="w-full" required>
                                    <option value="">Seleccione una categoría</option>
                                    @foreach ($categorias as $categoria)
                                        <option value="{{ $categoria->id_categoria }}">
                                            {{ $categoria->nombre_categoria }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('id_categoria')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                            </div>

                            <!-- Tipo de Producto -->
                            <div>
                                <x-input-label for="tipo_producto_id" value="Tipo de Producto" />
                                <select id="tipo_producto_id" name="tipo_producto_id" class="w-full">
                                    <option value="">Seleccione un tipo</option>
                                    @foreach ($tiposProducto as $tipo)
                                        <option value="{{ $tipo->tipo_producto_id }}">{{ $tipo->nombre }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('tipo_producto_id')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                            </div>
                        </div>

                        <!-- Marca y Modelo -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Marca -->
                            <div>
                                <x-input-label for="marca" value="Marca" />
                                <x-text-input id="marca" name="marca" type="text" class="w-full"
                                    value="{{ old('marca') }}" />
                                <x-input-error :messages="$errors->get('marca')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                            </div>

                            <!-- Modelo -->
                            <div>
                                <x-input-label for="modelo" value="Modelo" />
                                <x-text-input id="modelo" name="modelo" type="text" class="w-full"
                                    value="{{ old('modelo') }}" />
                                <x-input-error :messages="$errors->get('modelo')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                            </div>
                        </div>

                        <!-- Descripción -->
                        <div>
                            <x-input-label for="descripcion" value="Descripción" />
                            <textarea id="descripcion" name="descripcion" rows="3" class="w-full rounded dark:bg-gray-700">{{ old('descripcion') }}</textarea>
                            <x-input-error :messages="$errors->get('descripcion')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                        </div>

                        <!-- Código -->
                        <div>
                            <x-input-label for="codigo" value="Código de producto *" />
                            <x-text-input id="codigo" name="codigo" type="text" class="w-full"
                                value="{{ old('codigo') }}"
                                maxlength="13" minlength="8" required />
                            <x-input-error :messages="$errors->get('codigo')" class="mt-1 text-sm text-red-600 dark:text-red-400" />
                            <p id="mensaje-codigo" class="text-sm mt-1"></p>
                        </div>

                        <!-- Imagen -->
                        <div class="mb-4">
                            <label for="imagen"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Imagen del producto
                                (opcional)</label>
                            <input type="file" name="imagen" id="imagen"
                                class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-200">
                        </div>
                    </div>

                    <div class="mt-6">
                        <x-primary-button>
                            Crear Producto
                        </x-primary-button>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('productos.index') }}" class="text-blue-500 hover:text-blue-700">
                            Volver a la lista de productos
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>



<script>
document.addEventListener('DOMContentLoaded', function () {
    const codigoInput = document.getElementById('codigo');
    const mensajeCodigo = document.getElementById('mensaje-codigo');

    codigoInput.addEventListener('input', function () {
        const codigo = this.value;

        // Validar que solo haya números
        if (!/^\d*$/.test(codigo)) {
            mensajeCodigo.textContent = 'Solo se permiten números.';
            mensajeCodigo.style.color = 'orange';
            return;
        }

        // Validar longitud (8 o 13 dígitos)
        if (codigo.length !== 8 && codigo.length !== 13) {
            mensajeCodigo.textContent = 'El código debe tener 8 o 13 dígitos.';
            mensajeCodigo.style.color = 'orange';
            return;
        }
        // Validar disponibilidad del código
        fetch(`/productos/validar-codigo?codigo=${codigo}`)
            .then(response => response.json())
            .then(data => {
                if (data.disponible) {
                    mensajeCodigo.textContent = '✔ Código disponible';
                    mensajeCodigo.style.color = 'green';
                } else {
                    mensajeCodigo.textContent = '✖ Este código ya está registrado';
                    mensajeCodigo.style.color = 'red';
                }
            })
            .catch(() => {
                mensajeCodigo.textContent = 'Error al verificar el código.';
                mensajeCodigo.style.color = 'red';
            });
    });
});
</script>


</x-app-layout>
