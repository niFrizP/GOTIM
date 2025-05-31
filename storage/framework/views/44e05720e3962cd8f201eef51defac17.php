<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <?php echo e(__('Dashboard')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Usuario conectado -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <?php echo e(Auth::user()->nombre); ?> <?php echo e(Auth::user()->apellido); ?> <?php echo e(__(' está conectado!')); ?>

                </div>
            </div>

            <!-- Tarjetas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php
                    $cards = [
                        [
                            'title' => 'Clientes',
                            'value' => $totalCliente,
                            'bg' => 'bg-gray-600',
                            'icon' => 'users',
                        ],
                        [
                            'title' => 'Órdenes Totales',
                            'value' => $totalOrden,
                            'bg' => 'bg-red-600',
                            'icon' => 'clipboard-list',
                        ],
                        [
                            'title' => 'Órdenes Completadas',
                            'value' => $completedOrden,
                            'bg' => 'bg-indigo-600',
                            'icon' => 'check-circle',
                        ],
                    ];
                ?>

                <?php $__currentLoopData = $cards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="<?php echo e($card['bg']); ?> text-white p-6 rounded shadow flex items-center justify-between">
                        <div>
                            <div class="text-sm font-medium uppercase tracking-wide"><?php echo e($card['title']); ?></div>
                            <div class="text-3xl font-bold"><?php echo e($card['value']); ?></div>
                        </div>
                        <i data-lucide="<?php echo e($card['icon']); ?>" class="w-10 h-10 opacity-80"></i>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>


            <!-- Gráficos -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php
                    $charts = [
                        ['id' => 'ordersChart', 'title' => 'Órdenes por Mes', 'icon' => 'calendar'],
                        ['id' => 'ordersByStatusChart', 'title' => 'Órdenes por Estado', 'icon' => 'bar-chart-3'],
                        ['id' => 'productsByCategoryChart', 'title' => 'Productos por Categoría', 'icon' => 'layers'],
                        ['id' => 'ordersByResponsableChart', 'title' => 'Órdenes por Responsable', 'icon' => 'users'],
                    ];
                ?>

                <?php $__currentLoopData = $charts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chart): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
                        <div class="mb-4 flex items-center gap-2 text-gray-900 dark:text-gray-100 font-bold">
                            <i data-lucide="<?php echo e($chart['icon']); ?>" class="w-5 h-5"></i>
                            <?php echo e($chart['title']); ?>

                        </div>
                        <canvas id="<?php echo e($chart['id']); ?>" class="h-64 w-full"></canvas>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Productos con bajo stock -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
                <div class="mb-4 font-bold text-gray-900 dark:text-gray-100 text-lg flex items-center gap-2">
                    <i data-lucide="alert-triangle" class="w-5 h-5 text-yellow-400"></i>
                    Productos con Stock Bajo
                </div>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    Los siguientes productos tienen un stock de 3 unidades o menos. Por favor, considere reabastecerlos.
                </p>


                <?php if($lowStockProducts->where('cantidad', '<=', 3)->isNotEmpty()): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead
                                class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-100 uppercase text-xs tracking-wider">
                                <tr>
                                    <th scope="col" class="px-4 py-2 text-left">Producto</th>
                                    <th scope="col" class="px-4 py-2 text-left">Stock</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <?php
                                    $filteredProducts = $lowStockProducts->where('cantidad', '<=', 3);
                                ?>

                                <?php $__empty_1 = true; $__currentLoopData = $filteredProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        <td class="px-4 py-2">
                                            <a href="<?php echo e(route('productos.show', $product->id_producto)); ?>"
                                                class="text-blue-400 hover:underline">
                                                <span class="font-medium"><?php echo e($product->nombre_producto); ?></span>
                                            </a>
                                        </td>
                                        <td class="px-4 py-2 font-bold text-red-600 dark:text-red-400">
                                            <?php echo e($product->cantidad); ?>

                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="2" class="px-4 py-2 text-center text-gray-500">
                                            No hay productos con stock bajo.
                                        </td>
                                    </tr>
                                <?php endif; ?>

                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-gray-700 dark:text-gray-300 font-medium">Todos los productos tienen stock
                        suficiente.</div>
                <?php endif; ?>
            </div>

        </div>
    </div>


    <?php $__env->startPush('scripts'); ?>
        <script type="application/json" id="dashboard-data">
        {
            "ordersByStatus": <?php echo json_encode($ordersByStatus, 15, 512) ?>,
            "ordersByMonth": <?php echo json_encode($ordersByMonth, 15, 512) ?>,
            "productCategories": <?php echo json_encode($productCategories, 15, 512) ?>,
            "responsableOrders": <?php echo json_encode($responsableOrders, 15, 512) ?>
        }
    </script>
    <?php $__env->stopPush(); ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH D:\CAPSTONE 2025\GOTIM\resources\views/dashboard.blade.php ENDPATH**/ ?>