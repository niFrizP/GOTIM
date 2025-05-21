<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        <i class="fa-solid fa-house"></i>
                        <span class="ml-3">{{ __('Dashboard') }}</span>
                    </x-nav-link>
                    <x-nav-link :href="route('ot.index')" :active="request()->routeIs('ot.*')">
                        <i class="fa-solid fa-file-lines"></i>
                        <span class="ml-3">{{ __('Órdenes de Trabajo') }}</span>
                    </x-nav-link>
                    <x-nav-link :href="route('clientes.index')" :active="request()->routeIs('clientes.index')">
                        <i class="fa-solid fa-user"></i>
                        <span class="ml-3">{{ __('Clientes') }}</span>
                    </x-nav-link>
                    @auth
                        @if (Auth::user()->rol === 'administrador')
                            <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                                <i class="fa-solid fa-users"></i>
                                <span class="ml-3">{{ __('Usuarios') }}</span>
                            </x-nav-link>
                            <!-- Dropdown de Recursos -->
                            <div class="hidden sm:flex sm:items-center sm:ms-6">
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                            <i class="fa-solid fa-cogs"></i> <!-- Icono para el dropdown -->
                                            <span class="ml-3">{{ __('Recursos') }}</span>
                                            <div class="ms-1">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </button>
                                    </x-slot>

                                    <x-slot name="content">
                                        <!-- Enlace a Servicios -->
                                        <x-dropdown-link :href="route('servicios.index')" :active="request()->routeIs('servicios.*')">
                                            <i class="fa-solid fa-wrench"></i> <!-- Icono para Servicios -->
                                            <span class="ml-3">{{ __('Servicios') }}</span>
                                        </x-dropdown-link>

                                        <!-- Enlace a Categorías -->
                                        <x-dropdown-link :href="route('categorias.index')" :active="request()->routeIs('categorias.*')">
                                            <i class="fa-solid fa-tags"></i> <!-- Icono para Categorías -->
                                            <span class="ml-3">{{ __('Categorías') }}
                                        </x-dropdown-link>
                                        <!-- Enlace a Tipos de Productos -->
                                        <x-dropdown-link :href="route('tipo_productos.index')" :active="request()->routeIs('tipo_productos.*')">
                                            <i class="fa-solid fa-box"></i> <!-- Icono para Tipos de Productos -->
                                            <span class="ml-3">{{ __('Tipos de Productos') }}</span>
                                        </x-dropdown-link>

                                        <!-- Enlace a Producto -->
                                        <x-dropdown-link :href="route('productos.index')" :active="request()->routeIs('productos.*')">
                                            <i class="fa-solid fa-boxes-stacked"></i> <!-- Icono para producto -->
                                            <span class="ml-3">{{ __('Productos') }}
                                        </x-dropdown-link>
                                        <!-- Enlace a inventario -->
                                        <x-dropdown-link :href="route('inventario.index')" :active="request()->routeIs('inventario.*')">
                                            <i class="fa-solid fa-boxes-stacked"></i> <!-- Icono para Inventario -->
                                            <span class="ml-3">{{ __('Inventario') }}</span>
                                        </x-dropdown-link>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->nombre }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            <i class="fa-solid fa-user"></i>
                            <span class="ml-3"> {{ __('Profile') }} </span>
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                <i class="fa-solid fa-right-from-bracket"></i>
                                <span class="ml-3"> {{ __('Cerrar Sesión') }} </span>
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Menú Hamburguesa -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('clientes.index')" :active="request()->routeIs('clientes.index')">
                {{ __('Clientes') }}
            </x-responsive-nav-link>
            @auth
                @if (Auth::user()->rol === 'administrador')
                    <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                        {{ __('Usuarios') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('servicios.index')" :active="request()->routeIs('servicios.*')">
                        {{ __('Servicios') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('categorias.index')" :active="request()->routeIs('categorias.*')">
                        {{ __('Categorías') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('tipo_productos.index')" :active="request()->routeIs('tipo_productos.*')">
                        {{ __('Tipos de Productos') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('productos.index')" :active="request()->routeIs('productos.*')">
                        {{ __('Productos') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('inventario.index')" :active="request()->routeIs('inventario.*')">
                        {{ __('Inventario') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('ot.index')" :active="request()->routeIs('ot.*')">
                        {{ __('Órdenes de Trabajo') }}
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->nombre }}
                    {{ Auth::user()->apellido }}
                </div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
