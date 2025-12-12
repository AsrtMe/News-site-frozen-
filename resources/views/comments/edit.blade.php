@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-navy-900">
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="mb-6">
            <a href="{{ route('posts.show', $comment->post_id) }}" class="inline-flex items-center text-gray-300 hover:text-white">
                <i class="fas fa-arrow-left mr-2"></i>
                Вернуться к посту
            </a>
        </div>

        <div class="bg-navy-800 rounded-lg shadow-lg p-6 border border-navy-700">
            <h2 class="text-xl font-semibold text-white mb-6">Редактирование комментария</h2>
            
            <form action="{{ route('comments.update', $comment) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div>
                    <label for="content" class="block text-gray-300 mb-2">Текст комментария</label>
                    <textarea 
                        name="content" 
                        id="content" 
                        rows="4" 
                        class="w-full px-4 py-2 bg-navy-950 border border-navy-700 rounded-lg text-gray-100 placeholder-gray-500 focus:outline-none focus:border-violet-500"
                        required
                    >{{ old('content', $comment->content) }}</textarea>
                    @error('content')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="gif_url" class="block text-gray-300 mb-2">URL GIF-изображения (необязательно)</label>
                    <input 
                        type="url" 
                        name="gif_url" 
                        id="gif_url" 
                        value="{{ old('gif_url', $comment->gif_url) }}"
                        class="w-full px-4 py-2 bg-navy-950 border border-navy-700 rounded-lg text-gray-100 placeholder-gray-500 focus:outline-none focus:border-violet-500"
                    >
                    @error('gif_url')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                @if($comment->gif_url)
                    <div class="mt-4">
                        <p class="text-gray-300 mb-2">Текущее GIF-изображение:</p>
                        <img src="{{ $comment->gif_url }}" alt="Current GIF" class="max-h-48 rounded-lg">
                    </div>
                @endif

                <div class="flex justify-end space-x-3 mt-6">
                    <a href="{{ route('posts.show', $comment->post_id) }}" 
                       class="px-4 py-2 text-gray-300 hover:text-white transition-colors">
                        Отмена
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-violet-600 text-white rounded-lg hover:bg-violet-700 transition-colors">
                        Сохранить изменения
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection