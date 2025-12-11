@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-white">Посты</h1>
        @auth
            <a href="{{ route('posts.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-violet-600 text-white rounded-lg hover:bg-violet-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Создать пост
            </a>
        @endauth
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        @foreach($posts as $post)
            <article class="bg-navy-800 rounded-lg shadow-lg border border-navy-700 overflow-hidden">
                @if($post->image_url)
                    <img src="{{ $post->image_url }}" 
                         alt="Изображение к посту" 
                         class="w-full h-48 object-cover">
                @endif

                <div class="p-6">
                    <div class="flex items-center justify-between mb-2">
                        <h2 class="text-xl font-bold text-white hover:text-violet-400">
                            <a href="{{ route('posts.show', $post) }}">
                                {{ $post->title }}
                            </a>
                        </h2>
                        @auth
                            @if($post->user_id === Auth::id())
                                <div class="flex space-x-2">
                                    <a href="{{ route('posts.edit', $post) }}" 
                                       class="text-violet-400 hover:text-violet-300 transition-colors">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('posts.destroy', $post) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Вы уверены?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-400 hover:text-red-300 transition-colors">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endauth
                    </div>

                    <div class="flex items-center text-gray-400 text-sm mb-4">
                        <span class="mr-4">
                            <i class="fas fa-user mr-1"></i>
                            {{ $post->user->name }}
                        </span>
                        <span class="mr-4">
                            <i class="fas fa-calendar mr-1"></i>
                            {{ $post->created_at->diffForHumans() }}
                        </span>
                        <span class="mr-4">
                            <i class="fas fa-comment mr-1"></i>
                            {{ $post->comments_count }}
                        </span>
                        <span>
                            <i class="fas fa-eye mr-1"></i>
                            {{ $post->views_count }}
                        </span>
                    </div>

                    <p class="text-gray-300 mb-4">
                        {{ Str::limit($post->content, 150) }}
                    </p>

                    <a href="{{ route('posts.show', $post) }}" 
                       class="inline-flex items-center text-violet-400 hover:text-violet-300 transition-colors">
                        Читать далее
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </article>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $posts->links() }}
    </div>
</div>
@endsection