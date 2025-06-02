<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
            Lista Clientes
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <a href="{{ route('clientes.create') }}"
                class="mb-4 inline-block rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600">
                <i class="fa-solid fa-square-plus"></i> Nuevo Cliente
            </a>

            @if (session('success'))
                <div class="mb-4 rounded bg-green-100 p-4 text-green-800 dark:text-green-300 dark:bg-green-900/20">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-x-auto rounded bg-white dark:bg-gray-800 p-4 shadow dark:shadow-md">
                <table class="w-full table-auto text-left text-sm text-gray-800 dark:text-gray-100">
                    <thead>
                        <tr>
                            <th class="border-b p-2">Nombre y Apellido</th>
                            <th class="border-b p-2">Correo</th>
                            <th class="border-b p-2">RUT</th>
                            <th class="border-b p-2">Teléfono</th>
                            <th class="border-b p-2">Dirección</th>
                            <th class="border-b p-2">Tipo</th>
                            <th class="border-b p-2">Estado</th>
                            <th class="border-b p-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clientes as $cliente)
                            <x-cliente-row :cliente="$cliente" />
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('partials.confirm-modal')

    <script>
        function mostrarModal(id, nombreCompleto) {
            const modal = document.getElementById('confirmModal');
            const form = document.getElementById('deleteForm');
            const message = document.getElementById('confirmMessage');

            form.action = `/clientes/${id}`;
            message.innerHTML = `¿Estás seguro de que deseas inhabilitar a <strong>${nombreCompleto}</strong>?`;

            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.add('opacity-100');
                modal.querySelector('div').classList.add('scale-100');
                modal.querySelector('div').classList.remove('scale-95');
            }, 10);
        }

        function cerrarModal() {
            const modal = document.getElementById('confirmModal');
            const message = document.getElementById('confirmMessage');
            modal.classList.remove('opacity-100');
            modal.querySelector('div').classList.remove('scale-100');
            modal.querySelector('div').classList.add('scale-95');

            setTimeout(() => {
                modal.classList.add('hidden');
                message.innerHTML = `¿Estás seguro de que deseas inhabilitar este cliente?`;
            }, 300);
        }
    </script>
</x-app-layout>
