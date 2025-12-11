@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-navy-900">
    {{-- Навигация --}}
    @include('layouts.navigation')

    <div class="max-w-4xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8 text-white">Обсуждение</h1>

        {{-- Форма создания комментария --}}
        @auth
            @include('comments.partials.comment-form')
        @else
            @include('comments.partials.auth-prompt')
        @endauth

        {{-- Список комментариев --}}
        <div class="space-y-6" id="comments-list">
            @foreach($comments as $comment)
                <div class="bg-navy-800 rounded-lg shadow-lg p-6 border border-navy-700" id="comment-{{ $comment->id }}">
                    {{-- Заголовок комментария --}}
                    <div class="flex justify-between items-start">
                        <div class="flex-grow">
                            <div class="flex items-center">
                                <span class="font-semibold text-white">{{ $comment->user->name }}</span>
                                <span class="text-gray-400 text-sm ml-2">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="mt-2">
                                <p class="text-gray-200 break-words">{{ $comment->content }}</p>
                                @if($comment->gif_url)
                                    <div class="mt-2 max-w-md">
                                        <img src="{{ $comment->gif_url }}" 
                                             alt="GIF" 
                                             class="rounded-lg shadow-md w-full object-cover"
                                             loading="lazy">
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Кнопки управления --}}
                        @auth
                            @if($comment->user_id === Auth::id())
                                <div class="flex space-x-2">
                                    <button onclick="editComment({{ $comment->id }})" 
                                            class="text-violet-400 hover:text-violet-300 transition-colors">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('comments.destroy', $comment) }}" 
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

                    {{-- Лайки и ответы --}}
                    <div class="mt-4 flex items-center space-x-4">
                        @auth
                            <button onclick="toggleLike({{ $comment->id }}, true)" 
                                    id="like-btn-{{ $comment->id }}" 
                                    class="flex items-center space-x-1 {{ $comment->user_reaction === 'like' ? 'text-violet-400' : 'text-gray-400' }} hover:text-violet-300 transition-colors">
                                <i class="fas fa-thumbs-up"></i>
                                <span id="likes-count-{{ $comment->id }}">{{ $comment->likes_count }}</span>
                            </button>
                            <button onclick="toggleLike({{ $comment->id }}, false)" 
                                    id="dislike-btn-{{ $comment->id }}" 
                                    class="flex items-center space-x-1 {{ $comment->user_reaction === 'dislike' ? 'text-red-400' : 'text-gray-400' }} hover:text-red-300 transition-colors">
                                <i class="fas fa-thumbs-down"></i>
                                <span id="dislikes-count-{{ $comment->id }}">{{ $comment->dislikes_count }}</span>
                            </button>
                            <button onclick="showReplyForm({{ $comment->id }})" 
                                    class="text-gray-400 hover:text-violet-300 transition-colors">
                                <i class="fas fa-reply"></i> Ответить
                            </button>
                        @else
                            <div class="flex items-center space-x-4 text-gray-400">
                                <span><i class="fas fa-thumbs-up"></i> {{ $comment->likes_count }}</span>
                                <span><i class="fas fa-thumbs-down"></i> {{ $comment->dislikes_count }}</span>
                            </div>
                        @endauth
                    </div>

                    {{-- Форма ответа --}}
                    <div id="reply-form-{{ $comment->id }}" class="hidden mt-4">
                        @auth
                            @include('comments.partials.reply-form', ['comment' => $comment])
                        @endauth
                    </div>

                    {{-- Ответы на комментарий --}}
                    @if($comment->replies->count() > 0)
                        <div class="ml-8 mt-4 space-y-4">
                            @foreach($comment->replies as $reply)
                                @include('comments.partials.reply', ['reply' => $reply])
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach

            {{-- Пагинация --}}
            <div class="mt-6">
                {{ $comments->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showReplyForm(commentId) {
    const form = document.getElementById(`reply-form-${commentId}`);
    form.classList.toggle('hidden');
    if (!form.classList.contains('hidden')) {
        form.querySelector('textarea').focus();
    }
}

function editComment(commentId) {
    // Реализация редактирования комментария
    console.log('Редактирование комментария', commentId);
}

function toggleLike(commentId, isLike) {
    fetch(`/comments/${commentId}/toggle-like`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ is_like: isLike.toString() })
    })
    .then(response => response.json())
    .then(data => {
        // Обновляем счетчики
        document.getElementById(`likes-count-${commentId}`).textContent = data.likes_count;
        document.getElementById(`dislikes-count-${commentId}`).textContent = data.dislikes_count;

        // Обновляем стили кнопок
        const likeBtn = document.getElementById(`like-btn-${commentId}`);
        const dislikeBtn = document.getElementById(`dislike-btn-${commentId}`);

        likeBtn.className = `flex items-center space-x-1 ${data.user_reaction === 'like' ? 'text-violet-400' : 'text-gray-400'} hover:text-violet-300 transition-colors`;
        dislikeBtn.className = `flex items-center space-x-1 ${data.user_reaction === 'dislike' ? 'text-red-400' : 'text-gray-400'} hover:text-red-300 transition-colors`;
    })
    .catch(error => console.error('Ошибка:', error));
}
</script>
@endpush
@endsection