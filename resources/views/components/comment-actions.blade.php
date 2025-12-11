<div class="flex items-center space-x-3">
    @if($comment->user_id === Auth::id())
        <a href="{{ route('comments.edit', $comment) }}" 
           class="inline-flex items-center text-violet-400 hover:text-violet-300 transition-colors"
           title="Редактировать">
            <i class="fas fa-edit"></i>
        </a>
        <button type="button" 
                onclick="confirmDelete({{ $comment->id }})"
                class="inline-flex items-center text-red-400 hover:text-red-300 transition-colors"
                title="Удалить">
            <i class="fas fa-trash-alt"></i>
        </button>
        <form id="delete-form-{{ $comment->id }}" 
              action="{{ route('comments.destroy', $comment) }}" 
              method="POST" 
              class="hidden">
            @csrf
            @method('DELETE')
        </form>
    @endif

    <button onclick="toggleLike({{ $comment->id }}, true)" 
            id="like-btn-{{ $comment->id }}" 
            class="inline-flex items-center space-x-1 {{ $comment->user_reaction === 'like' ? 'text-violet-400' : 'text-gray-400' }} hover:text-violet-400 transition-colors"
            title="Нравится">
        <i class="fas fa-thumbs-up"></i>
        <span id="likes-count-{{ $comment->id }}">{{ $comment->likes_count }}</span>
    </button>

    <button onclick="toggleLike({{ $comment->id }}, false)" 
            id="dislike-btn-{{ $comment->id }}" 
            class="inline-flex items-center space-x-1 {{ $comment->user_reaction === 'dislike' ? 'text-red-400' : 'text-gray-400' }} hover:text-red-400 transition-colors"
            title="Не нравится">
        <i class="fas fa-thumbs-down"></i>
        <span id="dislikes-count-{{ $comment->id }}">{{ $comment->dislikes_count }}</span>
    </button>

    @auth
        <button onclick="toggleReplyForm({{ $comment->id }})" 
                class="inline-flex items-center space-x-1 text-gray-400 hover:text-violet-400 transition-colors"
                title="Ответить">
            <i class="fas fa-reply"></i>
            <span>Ответить</span>
        </button>
    @endauth
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    window.confirmDelete = function(commentId) {
        if (confirm('Вы уверены, что хотите удалить этот комментарий?')) {
            document.getElementById(`delete-form-${commentId}`).submit();
        }
    };
});
</script>