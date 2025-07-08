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
                    <x-detail-item label="Nombre" :value="$cliente->nombre_cliente" />
                    <x-detail-item label="Apellido" :value="$cliente->apellido_cliente" />
                    <x-detail-item label="Correo" :value="$cliente->email" />
                    <x-detail-item label="{{ $cliente->tipo_cliente === 'empresa' ? 'RUT Empresa' : 'RUT' }}"
                        :value="($cliente->tipo_cliente === 'empresa' ? $cliente->empresa->rut_empresa : $cliente->rut)" />
                    <x-detail-item label="Nombre de Empresa" :value="($cliente->tipo_cliente === 'empresa' ? $cliente->empresa->nom_emp : 'No aplica')" />
                    <x-detail-item label="Razón Social" :value="($cliente->tipo_cliente === 'empresa' ? $cliente->razon_social : 'No aplica')" />
                    <x-detail-item label="Giro" :value="($cliente->tipo_cliente === 'empresa' ? $cliente->giro : 'No aplica')" />
                    <x-detail-item label="Teléfono" :value="$cliente->nro_contacto" />
                    <x-detail-item label="Dirección" :value="$cliente->direccion" />
                    <x-detail-item label="Región" :value="$cliente->Region->nombre_region" />
                    <x-detail-item label="Ciudad" :value="$cliente->Ciudad->nombre_ciudad" />
                    <div class="space-y-4">
                        <!-- Fecha de creación -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Fecha de Creación</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $cliente->created_at->format('d/m/Y H:i') }}
                            </dd>
                        </div>

                        <!-- Tipo de Cliente y Estado -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Tipo de Cliente y Estado
                            </dt>
                            <dd class="mt-2 flex flex-wrap gap-2">
                                <!-- Tipo de Cliente -->
                                <span
                                    class="inline-block rounded px-2 py-1 text-xs font-semibold
                {{ $cliente->tipo_cliente === 'empresa'
                    ? 'bg-blue-200 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300'
                    : 'bg-yellow-200 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300' }}">
                                    {{ ucfirst($cliente->tipo_cliente) }}
                                </span>

                                <!-- Estado -->
                                <span
                                    class="inline-block rounded px-2 py-1 text-xs font-semibold
                {{ $cliente->estado === 'activo'
                    ? 'bg-green-200 text-green-800 dark:bg-green-900/20 dark:text-green-300'
                    : 'bg-red-200 text-red-800 dark:bg-red-900/20 dark:text-red-300' }}">
                                    {{ ucfirst($cliente->estado) }}
                                </span>
                            </dd>
                        </div>
                    </div>
                </dl>

                <div class="mt-8">
                    <a href="{{ route('clientes.index') }}"
                        class="inline-block rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600">
                        ← Volver a la lista
                    </a>
                    @if (Auth::user()->rol === 'Administrador')
                        @if ($cliente->estado === 'activo')
                            <button onclick="mostrarModal()"
                                class="inline-block rounded bg-red-600 px-4 py-2 text-white hover:bg-red-700 ml-2">
                                Inhabilitar Cliente
                            </button>
                        @else
                            <form action="{{ route('clientes.reactivar', $cliente->id_cliente) }}" method="POST"
                                class="inline-block">
                                @csrf
                                <button type="submit"
                                    class="inline-block rounded bg-green-600 px-4 py-2 text-white hover:bg-green-700">
                                    Reactivar Cliente
                                </button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('partials.confirm-modal')

    <script>
        function mostrarModal() {
            const modal = document.getElementById('confirmModal');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.add('opacity-100');
                modal.querySelector('div').classList.add('scale-100');
                modal.querySelector('div').classList.remove('scale-95');
            }, 10);
        }

        function cerrarModal() {
            const modal = document.getElementById('confirmModal');
            modal.classList.remove('opacity-100');
            modal.querySelector('div').classList.remove('scale-100');
            modal.querySelector('div').classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
    </script>
</x-app-layout>
