@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-navy-800 rounded-lg shadow-lg border border-navy-700 p-6">
        <h1 class="text-2xl font-bold text-white mb-6">Редактировать пост</h1>

        <form action="{{ route('posts.update', $post) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="block text-sm font-medium text-gray-300">
                    Заголовок
                </label>
                <input type="text" 
                       id="title" 
                       name="title" 
                       value="{{ old('title', $post->title) }}"
                       required
                       maxlength="255"
                       class="mt-1 block w-full rounded-md bg-navy-900 border-navy-700 text-white focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50">
                @error('title')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="content" class="block text-sm font-medium text-gray-300">
                    Содержание
                </label>
                <textarea id="content" 
                          name="content" 
                          rows="10"
                          required
                          maxlength="10000"
                          class="mt-1 block w-full rounded-md bg-navy-900 border-navy-700 text-white focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50">{{ old('content', $post->content) }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="image_url" class="block text-sm font-medium text-gray-300">
                    URL изображения (необязательно)
                </label>
                <input type="url" 
                       id="image_url" 
                       name="image_url" 
                       value="{{ old('image_url', $post->image_url) }}"
                       maxlength="255"
                       class="mt-1 block w-full rounded-md bg-navy-900 border-navy-700 text-white focus:border-violet-500 focus:ring focus:ring-violet-500 focus:ring-opacity-50">
                @error('image_url')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('posts.show', $post) }}" 
                   class="inline-flex items-center px-4 py-2 bg-navy-700 text-white rounded-lg hover:bg-navy-600 transition-colors">
                    Отмена
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-violet-600 text-white rounded-lg hover:bg-violet-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Сохранить
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
