<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Управление пользователями') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <h3 class="text-lg font-medium mb-4">Список пользователей</h3>

                <div class="space-y-4">
                    @foreach($users as $user)
                        <div class="p-4 border rounded-md flex items-center justify-between">
                            <div>
                                <p class="font-semibold">{{ $user->name }}</p>
                                <p class="text-sm text-gray-600">{{ $user->email }}</p>
                            </div>

                            <div class="flex space-x-2">
                                @if($user->is_active)
                                    <form method="POST" action="{{ route('admin.users.block', $user) }}">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 bg-yellow-500 text-white rounded-md hover:bg-yellow-600">
                                            Заблокировать
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.users.unblock', $user) }}">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 bg-green-500 text-white rounded-md hover:bg-green-600">
                                            Разблокировать
                                        </button>
                                    </form>
                                @endif

                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600">
                                        Удалить
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
