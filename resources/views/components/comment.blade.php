@props(['comment' => null, 'ancestors' => []])

@php
    // Поддерживаем использование как Blade include и как <x-comment>
    $ancestors = $ancestors ?? [];
    $comment = $comment ?? null;
    if (!$comment) {
        // Если компонент вызван без comment — ничего не делаем
        return;
    }
@endphp
    <div class="flex space-x-6 py-2">
        <!-- Gravatar аватар -->
        <div class="flex-shrink-0">
            @php
                $email = $comment->user->email ?? null;
                $hash = $email ? md5(strtolower(trim($email))) : null;
                $avatar = $hash ? "https://www.gravatar.com/avatar/{$hash}?s=80&d=identicon" : null;
            @endphp
            <div class="w-14 h-14 rounded-full overflow-hidden bg-navy-900 shadow-md border-2 border-violet-600">
                @if($avatar)
                    <img src="{{ $avatar }}" alt="avatar" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-600 font-bold">{{ strtoupper(substr($comment->user->name,0,2)) }}</div>
                @endif
            </div>
        </div>

        <div class="flex-grow">
            <div class="bg-navy-800 border border-navy-700 rounded-xl p-6 shadow">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center gap-3">
                            <span class="font-semibold text-navy-100 text-lg">{{ $comment->user->name }}</span>
                            <span class="text-sm text-navy-300">{{ $comment->created_at->diffForHumans() }}</span>
                            @if($comment->updated_at && $comment->updated_at->gt($comment->created_at))
                                <span class="ml-2 text-xs text-navy-300 italic">(отредактировано)</span>
                            @endif
                        </div>
                        @if($comment->post_id)
                            <div class="text-sm text-navy-300 mt-2">Ответов: {{ $comment->replies->count() }}</div>
                        @endif
                    </div>

                    <div class="flex items-center space-x-2">
                        @auth
                            @if($comment->user_id === Auth::id())
                                <button onclick="openEditModal({{ $comment->id }}, {{ json_encode($comment->content) }}, {{ json_encode($comment->gif_url) }})" class="text-gray-300 hover:text-white">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Вы уверены?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>

                    <div class="mt-4 text-navy-100 break-words text-base leading-relaxed">
                        {{ $comment->content }}
                    </div>

                @if($comment->gif_url)
                    <div class="mt-4">
                        <img src="{{ $comment->gif_url }}" alt="GIF" class="rounded-xl max-h-56 w-full object-cover border-2 border-violet-600 shadow-md">
                    </div>
                @endif

                <div class="mt-5 flex items-center gap-6 text-base">
                    @auth
                        <button onclick="toggleLike({{ $comment->id }}, true)" id="like-btn-{{ $comment->id }}" class="flex items-center gap-2 px-3 py-1 rounded-lg {{ $comment->user_reaction === 'like' ? 'bg-violet-600 text-navy-100' : 'bg-navy-900 text-navy-300' }} hover:bg-violet-500 transition-all">
                            <i class="fas fa-thumbs-up"></i>
                            <span id="likes-count-{{ $comment->id }}">{{ $comment->likes_count }}</span>
                        </button>
                        <button onclick="toggleLike({{ $comment->id }}, false)" id="dislike-btn-{{ $comment->id }}" class="flex items-center gap-2 px-3 py-1 rounded-lg {{ $comment->user_reaction === 'dislike' ? 'bg-red-600 text-navy-100' : 'bg-navy-900 text-navy-300' }} hover:bg-red-500 transition-all">
                            <i class="fas fa-thumbs-down"></i>
                            <span id="dislikes-count-{{ $comment->id }}">{{ $comment->dislikes_count }}</span>
                        </button>
                        <button onclick="toggleReplyForm({{ $comment->id }})" class="px-3 py-1 rounded-lg bg-navy-900 text-navy-300 hover:bg-violet-600 hover:text-navy-100 transition-all">
                            <i class="fas fa-reply"></i> Ответить
                        </button>
                    @else
                        <div class="text-gray-500">Лайки: {{ $comment->likes_count }} • Дизлайки: {{ $comment->dislikes_count }}</div>
                    @endauth
                </div>

                @auth
                    <div id="reply-form-{{ $comment->id }}" class="hidden mt-4 pl-4 border-l border-navy-700">
                        <form action="{{ route('comments.store') }}" method="POST" class="space-y-3">
                            @csrf
                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                            <input type="hidden" name="post_id" value="{{ $comment->post_id }}">
                            <div>
                                <textarea name="content" rows="2" maxlength="1000" required class="w-full px-4 py-2 bg-navy-900 border border-navy-700 rounded-xl text-navy-100 placeholder-navy-300 transition-all" placeholder="Напишите ответ..."></textarea>
                            </div>
                            <div class="flex justify-end gap-2">
                                <button type="button" onclick="toggleReplyForm({{ $comment->id }})" class="px-4 py-1 rounded-lg bg-navy-900 text-navy-300 hover:bg-violet-600 hover:text-navy-100 transition-all">Отмена</button>
                                <button type="submit" class="px-5 py-1 rounded-lg bg-violet-600 text-navy-100 hover:bg-violet-500 transition-all shadow">Ответить</button>
                            </div>
                        </form>
                    </div>
                @endauth

                @php
                    // Фильтруем реплаи, чтобы избежать циклов (reply->id не должен быть в цепочке предков)
                    $safeReplies = $comment->replies->filter(function ($reply) use ($ancestors) {
                        return !in_array($reply->id, $ancestors);
                    });
                @endphp

                @if($safeReplies->count() > 0)
                    <div class="mt-6 ml-8 space-y-6">
                        @foreach($safeReplies as $reply)
                            <x-comment :comment="$reply" :ancestors="array_merge($ancestors, [$comment->id])" />
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
{{-- component end --}}