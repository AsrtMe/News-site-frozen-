<nav class="bg-navy-800 border-b border-navy-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            {{-- Логотип --}}
            <div class="flex-shrink-0">
                <a href="{{ route('home') }}" class="text-white text-xl font-bold hover:text-violet-300 transition-colors">
                    Форум
                </a>
            </div>

            {{-- Правая часть навигации --}}
            <div class="flex items-center space-x-4">
                @auth
                    <div x-data="{ open: false }" class="relative">
                        {{-- Кнопка профиля --}}
                        <button @click="open = !open" class="flex items-center text-gray-300 hover:text-white transition-colors">
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        {{-- Выпадающее меню --}}
                        <div x-show="open" 
                             @click.away="open = false"
                             class="absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-navy-700 border border-navy-600"
                             style="display: none;">
                            @if (Route::has('profile.edit'))
                                <a href="{{ route('profile.edit') }}" 
                                   class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-navy-600">
                                    Профиль
                                </a>
                            @endif
                            @if (Route::has('logout'))
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" 
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-navy-600">
                                        Выйти
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                    @else
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition-colors">
                                Войти
                            </a>
                        @endif
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-gray-300 hover:text-white transition-colors">
                                Регистрация
                            </a>
                        @endif
                    @endauth
            </div>
        </div>
    </div>
</nav>