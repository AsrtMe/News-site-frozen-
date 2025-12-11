<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium">Добро пожаловать в админ-панель!</h3>
                <p class="mt-4">Выберите раздел:</p>

                <ul class="list-disc pl-6 mt-2">
                    <li>
                        <a href="{{ route('admin.users') }}" class="text-blue-600 hover:underline">
                            Управление пользователями
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-blue-600 hover:underline">
                            Управление комментариями (позже)
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
