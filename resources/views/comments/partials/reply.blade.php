{{-- Один ответ в списке ответов --}}
<div class="bg-navy-700 rounded-lg p-4 border border-navy-600" id="reply-{{ $reply->id }}">
    <div class="flex justify-between items-start">
        <div class="flex-grow">
            <div class="flex items-center">
                <span class="font-semibold text-white">{{ $reply->user->name }}</span>
                <span class="text-gray-400 text-sm ml-2">{{ $reply->created_at->diffForHumans() }}</span>
            </div>
            <p class="mt-2 text-gray-200 break-words">{{ $reply->content }}</p>
            @if($reply->gif_url)
                <div class="mt-2 max-w-md">
                    <img src="{{ $reply->gif_url }}" 
                         alt="GIF" 
                         class="rounded-lg shadow-md w-full object-cover"
                         loading="lazy">
                </div>
            @endif
        </div>

        @auth
            @if($reply->user_id === Auth::id())
                <div class="flex space-x-2">
                    <button onclick="editComment({{ $reply->id }})" 
                            class="text-violet-400 hover:text-violet-300 transition-colors">
                        <i class="fas fa-edit"></i>
                    </button>
                    <form action="{{ route('comments.destroy', $reply) }}" 
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

    <div class="mt-4 flex items-center space-x-4">
        @auth
            <button onclick="toggleLike({{ $reply->id }}, true)" 
                    id="like-btn-{{ $reply->id }}" 
                    class="flex items-center space-x-1 {{ $reply->user_reaction === 'like' ? 'text-violet-400' : 'text-gray-400' }} hover:text-violet-300 transition-colors">
                <i class="fas fa-thumbs-up"></i>
                <span id="likes-count-{{ $reply->id }}">{{ $reply->likes_count }}</span>
            </button>
            <button onclick="toggleLike({{ $reply->id }}, false)" 
                    id="dislike-btn-{{ $reply->id }}" 
                    class="flex items-center space-x-1 {{ $reply->user_reaction === 'dislike' ? 'text-red-400' : 'text-gray-400' }} hover:text-red-300 transition-colors">
                <i class="fas fa-thumbs-down"></i>
                <span id="dislikes-count-{{ $reply->id }}">{{ $reply->dislikes_count }}</span>
            </button>
        @else
            <div class="flex items-center space-x-4 text-gray-400">
                <span><i class="fas fa-thumbs-up"></i> {{ $reply->likes_count }}</span>
                <span><i class="fas fa-thumbs-down"></i> {{ $reply->dislikes_count }}</span>
            </div>
        @endauth
    </div>
</div>