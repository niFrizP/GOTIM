{{-- resources/views/empresa/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            Detalle de la Empresa
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="rounded-lg bg-white dark:bg-gray-800 p-6 shadow-md">
                <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <x-detail-item label="Nombre de Empresa" :value="$empresa->nom_emp" />
                    <x-detail-item label="RUT Empresa" :value="$empresa->rut_empresa" />
                    <x-detail-item label="Razón Social" :value="$empresa->razon_social" />
                    <x-detail-item label="Giro" :value="$empresa->giro" />
                    <x-detail-item label="Teléfono" :value="$empresa->telefono" />
                    <div class="space-y-4 sm:col-span-2">
                        <!-- Fecha de creación -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Fecha de Creación</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $empresa->created_at->format('d/m/Y H:i') }}
                            </dd>
                        </div>

                        <!-- Estado -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Estado</dt>
                            <dd class="mt-2">
                                <span
                                    class="inline-block rounded px-2 py-1 text-xs font-semibold
                                    {{ $empresa->estado === 'activo'
                                        ? 'bg-green-200 text-green-800 dark:bg-green-900/20 dark:text-green-300'
                                        : 'bg-red-200 text-red-800 dark:bg-red-900/20 dark:text-red-300' }}">
                                    {{ ucfirst($empresa->estado) }}
                                </span>
                            </dd>
                        </div>
                    </div>
                </dl>

                <div class="mt-8">
                    <a href="{{ route('empresas.index') }}"
                        class="inline-block rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600">
                        ← Volver a la lista
                    </a>
                    @if (Auth::user()->rol === 'Administrador')
                        @if ($empresa->estado === 'activo')
                            <button onclick="mostrarModal()"
                                class="inline-block rounded bg-red-600 px-4 py-2 text-white hover:bg-red-700 ml-2">
                                Inhabilitar Empresa
                            </button>
                        @else
                            <form action="{{ route('empresas.reactivar', $empresa->id_empresa) }}" method="POST"
                                class="inline-block">
                                @csrf
                                <button type="submit"
                                    class="inline-block rounded bg-green-600 px-4 py-2 text-white hover:bg-green-700">
                                    Reactivar Empresa
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
