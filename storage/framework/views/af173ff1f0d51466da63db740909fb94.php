<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'GOTIM')); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cairo-play:400,500,600" rel="stylesheet" />

    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script src="https://kit.fontawesome.com/f00d5bcc97.js" crossorigin="anonymous"></script>

    <!-- CDN para jQuery y Select2 -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">

    <div class="min-h-screen">
        <!-- Navegación principal -->
        <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Header opcional -->
        <?php if(isset($header)): ?>
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                    <!-- Título de la página -->
                    <div>
                        <?php echo e($header); ?>

                    </div>

                    <!-- Botón de Volver (se oculta en el dashboard) -->
                    <?php if(!request()->routeIs('dashboard')): ?>
                        <button onclick="history.back()"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center">
                            <i class="fa-solid fa-angle-left mr-2"></i>
                            <span>Volver</span>
                        </button>
                    <?php endif; ?>
                </div>
            </header>
        <?php endif; ?>

        <!-- Contenido principal de la página -->
        <main class="py-10">
            <?php echo e($slot); ?>

        </main>
    </div>
    <?php echo $__env->yieldPushContent('scripts'); ?>


    <!-- Scripts adicionales (si es necesario) -->
</body>

</html>
<?php /**PATH D:\CAPSTONE 2025\GOTIM\resources\views/layouts/app.blade.php ENDPATH**/ ?>