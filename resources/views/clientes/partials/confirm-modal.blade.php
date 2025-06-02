<div id="confirmModal"
    class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center transition-opacity duration-300">

    <div
        class="transform rounded-lg bg-white dark:bg-gray-800 p-8 shadow-md scale-95 transition-transform duration-300 ease-in-out w-full max-w-md">
        <h3 class="text-xl font-semibold text-gray-900 dark:text-white text-center">
            Confirmar Inhabilitación
        </h3>
        <p id="confirmMessage" class="mt-4 text-center text-gray-600 dark:text-gray-300">
            ¿Estás seguro de que deseas inhabilitar al cliente <strong>{{ $cliente->nombre_cliente }}
                {{ $cliente->apellido_cliente }}</strong>?
        </p>
        <div class="mt-6 flex justify-center space-x-4">
            <button onclick="cerrarModal()" class="rounded bg-gray-300 px-4 py-2 text-gray-800 hover:bg-gray-400">
                Cancelar
            </button>
            <form method="POST" action="{{ route('clientes.destroy', $cliente->id_cliente) }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="rounded bg-red-600 px-4 py-2 text-white hover:bg-red-700">
                    Inhabilitar
                </button>
            </form>
        </div>
    </div>
</div>
