<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8 space-y-6">

            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8 space-y-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
                    <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        @include('profile.partials.update-profile-information-form')
                    </dl>
                </div>
            </div>
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8 space-y-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
                    <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        @include('profile.partials.update-password-form')
                    </dl>
                </div>
            </div>

            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8 space-y-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
                    <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        @include('profile.partials.delete-user-form')
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>