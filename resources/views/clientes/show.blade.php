<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            Detalle del Cliente
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="rounded-lg bg-white dark:bg-gray-800 p-6 shadow-md">
                <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Nombre</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $cliente->nombre_cliente }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Apellido</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $cliente->apellido_cliente }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Correo</dt>
                        <dd class="mt-1 text-gray-800 dark:text-gray-200">
                            {{ $cliente->email }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">RUT</dt>
                        <dd class="mt-1 text-gray-800 dark:text-gray-200" id="rut">
                            {{ $cliente->rut }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Teléfono</dt>
                        <dd class="mt-1 text-gray-800 dark:text-gray-200">
                            {{ $cliente->nro_contacto }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Dirección</dt>
                        <dd class="mt-1 text-gray-800 dark:text-gray-200">
                            {{ $cliente->direccion }}
                        </dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Estado</dt>
                        <dd class="mt-2">
                            <span
                                class="inline-block rounded px-2 py-1 text-xs font-semibold
                            {{ $cliente->estado === 'activo' ? 'bg-green-200 text-green-800 dark:bg-green-900/20 dark:text-green-300' : 'bg-red-200 text-red-800 dark:bg-red-900/20 dark:text-red-300' }}">
                                {{ $cliente->estado === 'activo' ? 'Activo' : 'Inhabilitado' }}
                            </span>
                        </dd>
                    </div>
                </dl>

                <div class="mt-8">
                    <a href="{{ route('clientes.index') }}"
                        class="inline-block rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600">
                        ← Volver a la lista
                    </a>
                    @if ($cliente->estado === 'activo')
                        <!-- Botón que abre el modal -->
                        <button onclick="mostrarModal()"
                            class="inline-block rounded bg-red-600 px-4 py-2 text-white hover:bg-red-700">
                            Inhabilitar Cliente
                        </button>
                    @else
                        <!-- Botón de reactivar -->
                        <form action="{{ route('clientes.reactivar', $cliente->id_cliente) }}" method="POST"
                            class="inline-block">
                            @csrf
                            <button type="submit"
                                class="inline-block rounded bg-green-600 px-4 py-2 text-white hover:bg-green-700">
                                Reactivar Cliente
                            </button>
                        </form>
                    @endif
                </div>
                <!-- Modal de confirmación -->
                <div id="confirmModal"
                    class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
                    <div class="rounded-lg bg-white dark:bg-gray-800 p-6 shadow-md">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Confirmar Inhabilitación</h3>
                        <p class="mt-2 text-gray-600 dark:text-gray-300">¿Estás seguro de que deseas inhabilitar a este
                            cliente?</p>
                        <div class="mt-4 flex justify-end space-x-2">
                            <button onclick="document.getElementById('confirmModal').classList.add('hidden')"
                                class="rounded bg-gray-300 px-4 py-2 text-gray-800 hover:bg-gray-400">
                                Cancelar
                            </button>
                            <form action="{{ route('clientes.destroy', $cliente->id_cliente) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded bg-red-600 px-4 py-2 text-white hover:bg-red-700">
                                    Inhabilitar
                                </button>
                            </form>
                        </div>
                    </div>
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

            function mostrarModal() {
                const modal = document.getElementById('confirmModal');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        </script>
</x-app-layout>


<!-- The biggest battle is the war against ignorance. - Mustafa Kemal Atatürk -->
