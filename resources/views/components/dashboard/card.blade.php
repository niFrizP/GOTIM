@props(['title'])

<div class="p-4 rounded-lg shadow bg-white dark:bg-gray-800">
    <h2 class="text-lg font-semibold mb-2 text-gray-700 dark:text-gray-200">{{ $title }}</h2>
    <div>
        {{ $slot }}
    </div>
</div>
