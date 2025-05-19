<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Usuario conectado -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ Auth::user()->nombre }} {{ Auth::user()->apellido }} {{ __(" está conectado!") }}
                </div>
            </div>

            <!-- Tarjetas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-blue-600 text-white p-4 rounded shadow">
                    <div class="text-lg">Clientes</div>
                    <div class="text-3xl font-bold">{{ $totalCliente }}</div>
                </div>
                <div class="bg-green-600 text-white p-4 rounded shadow">
                    <div class="text-lg">Órdenes Totales</div>
                    <div class="text-3xl font-bold">{{ $totalOrden }}</div>
                </div>
                <div class="bg-indigo-600 text-white p-4 rounded shadow">
                    <div class="text-lg">Órdenes Completadas</div>
                    <div class="text-3xl font-bold">{{ $completedOrden }}</div>
                </div>
            </div>

<!-- Gráficos en grilla de 2 columnas -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
        <form method="GET" action="{{ route('dashboard') }}" class="mb-4">
        <label for="mes" class="block text-gray-100 font-semibold mb-2">Filtrar por Mes:</label>
        <select name="mes" id="mes" onchange="this.form.submit()" class="w-full md:w-1/2 p-2 rounded text-gray-900">
            <option value="">-- Todos los Meses --</option>
            @foreach(range(1, 12) as $m)
                <option value="{{ $m }}" {{ request('mes') == $m ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create()->month($m)->locale('es')->translatedFormat('F') }}
                </option>
            @endforeach
        </select>
    </form>
        <div class="mb-4 font-bold text-gray-900 dark:text-gray-100">Órdenes por Mes</div>
        <canvas id="ordersChart"></canvas>
    </div>

    <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
        
        <div class="mb-4 font-bold text-gray-900 dark:text-gray-100">Órdenes por Estado</div>
        <canvas id="ordersByStatusChart"></canvas>
    </div>

    <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
        <div class="mb-4 font-bold text-gray-900 dark:text-gray-100">Productos por Categoría</div>
        <canvas id="productsByCategoryChart"></canvas>
    </div>

    <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
        <div class="mb-4 font-bold text-gray-900 dark:text-gray-100">Órdenes por Responsable</div>
        <canvas id="ordersByResponsableChart"></canvas>
    </div>
</div>  

            <!-- Productos con bajo stock -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
                <div class="mb-4 font-bold text-gray-900 dark:text-gray-100">Productos con Stock Bajo</div>
                <ul class="list-disc list-inside text-gray-100">
                    @forelse($lowStockProducts as $product)
                        <li>{{ $product->nombre_producto }} - Stock: {{ $product->cantidad }}</li>
                    @empty
                        <li>Todos los productos tienen stock suficiente.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ordersChart = new Chart(document.getElementById('ordersChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($ordersPerMonth->keys()) !!},
                    datasets: [{
                        label: 'Órdenes',
                        data: {!! json_encode($ordersPerMonth->values()) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.6)'
                    }]
                },
                options: { scales: { y: { beginAtZero: true } } }
            });

            const ordersByStatusChart = new Chart(document.getElementById('ordersByStatusChart'), {
                type: 'pie',
                data: {
                    labels: {!! json_encode($ordersByStatus->keys()) !!},
                    datasets: [{
                        data: {!! json_encode($ordersByStatus->values()) !!},
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0']
                    }]
                }
            });

            const productsByCategoryChart = new Chart(document.getElementById('productsByCategoryChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($productCategories->keys()) !!},
                    datasets: [{
                        label: 'Productos por Categoría',
                        data: {!! json_encode($productCategories->values()) !!},
                        backgroundColor: 'rgba(75, 192, 192, 0.6)'
                    }]
                }
            });

            const ordersByResponsableChart = new Chart(document.getElementById('ordersByResponsableChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($responsableOrders->keys()) !!},
                    datasets: [{
                        label: 'Órdenes por Responsable',
                        data: {!! json_encode($responsableOrders->values()) !!},
                        backgroundColor: 'rgba(153, 102, 255, 0.6)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: { scales: { y: { beginAtZero: true } } }
            });
        </script>
    @endpush
</x-app-layout>
