@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    {{-- Flash messages --}}
    @if(session('success'))
        <div class="mb-6 px-4 py-3 rounded bg-green-600 text-white">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-6 px-4 py-3 rounded bg-red-600 text-white">{{ session('error') }}</div>
    @endif

    {{-- Пост: большой карточный блок --}}
    <article class="bg-navy-800 rounded-2xl shadow-xl overflow-hidden mb-10 border border-navy-700">
        @if($post->image_url)
            <div class="h-64 w-full overflow-hidden">
                <img src="{{ $post->image_url }}" alt="Изображение поста" class="w-full h-full object-cover">
            </div>
        @endif

        <div class="p-8">
            <div class="flex items-start justify-between">
                <div class="pr-4">
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-navy-100 leading-tight mb-2">{{ $post->title }}</h1>
                    <div class="mt-3 flex flex-wrap items-center gap-4 text-base text-navy-200">
                        <div class="flex items-center space-x-2">
                            @php
                                $email = $post->user->email ?? null;
                                $hash = $email ? md5(strtolower(trim($email))) : null;
                                $avatar = $hash ? "https://www.gravatar.com/avatar/{$hash}?s=40&d=identicon" : null;
                            @endphp
                            <img src="{{ $avatar }}" alt="avatar" class="w-10 h-10 rounded-full object-cover border-2 border-violet-600 shadow-md">
                            <span class="font-semibold text-navy-100">{{ $post->user->name }}</span>
                        </div>
                        <span>{{ $post->created_at->format('d.m.Y H:i') }}</span>
                        <span>&middot;</span>
                        <span>{{ $post->comments->count() }} комм.</span>
                        <span>&middot;</span>
                        <span>{{ $post->views_count }} просмотров</span>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    @auth
                        @if($post->user_id === Auth::id())
                            <a href="{{ route('posts.edit', $post) }}" class="text-violet-600 hover:text-violet-500"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Удалить пост?')">@csrf @method('DELETE')<button class="text-red-500 hover:text-red-400"><i class="fas fa-trash"></i></button></form>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="mt-8 prose prose-invert text-navy-100 max-w-none text-lg leading-relaxed">
                {!! nl2br(e($post->content)) !!}
            </div>
        </div>
    </article>

    {{-- Комментарии --}}
    <section class="space-y-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-navy-100 tracking-wide">Комментарии ({{ $post->comments->count() }})</h2>
            @auth
                <a href="#comment-form" class="text-base text-violet-400 hover:text-violet-300 transition-colors">Оставить комментарий</a>
            @endauth
        </div>

        {{-- Форма комментария --}}
        <div id="comment-form" class="bg-navy-800 p-6 rounded-xl shadow-md border border-navy-700 mb-8">
            @auth
                <form action="{{ route('comments.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="post_id" value="{{ $post->id }}">
                    <div>
                        <textarea name="content" rows="3" required maxlength="1000" class="w-full px-5 py-3 rounded-xl border border-navy-700 bg-navy-900 text-navy-100 placeholder-navy-300 focus:outline-none focus:border-violet-500 transition-all" placeholder="Напишите комментарий..."></textarea>
                        @error('content')
                            <div class="mt-2 text-sm text-red-400">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="flex items-center justify-between">
                        <input type="url" name="gif_url" placeholder="URL GIF (опционально)" class="text-sm px-4 py-2 rounded-xl border border-navy-700 bg-navy-900 text-navy-200 placeholder-navy-300 w-2/3 transition-all"/>
                        @error('gif_url')
                            <div class="ml-2 text-sm text-red-400">{{ $message }}</div>
                        @enderror
                        <div class="flex items-center space-x-2">
                            <button type="submit" class="px-6 py-2 bg-violet-600 text-navy-100 rounded-xl hover:bg-violet-500 transition-all shadow">Отправить</button>
                        </div>
                    </div>
                </form>
            @else
                <div class="text-sm text-navy-300">Чтобы оставить комментарий, <a href="{{ route('login') }}" class="text-violet-400">войдите</a> или <a href="{{ route('register') }}" class="text-violet-400">зарегистрируйтесь</a>.</div>
            @endauth
        </div>

        {{-- Список комментариев --}}
        <div class="space-y-6">
            @forelse($post->comments as $comment)
                @include('components.comment', ['comment' => $comment])
            @empty
                <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                    <i class="fas fa-comments text-3xl mb-2"></i>
                    <p>Пока нет комментариев — будьте первым!</p>
                </div>
            @endforelse
        </div>
    </section>

</div>

@push('scripts')
<script>
function toggleReplyForm(commentId) {
    const el = document.getElementById(`reply-form-${commentId}`);
    if (!el) return;
    el.classList.toggle('hidden');
    if (!el.classList.contains('hidden')) el.querySelector('textarea')?.focus();
}

function editComment(commentId) {
    window.location.href = `/comments/${commentId}/edit`;
}

function toggleLike(commentId, isLike) {
    (async () => {
        try {
            const token = document.querySelector('meta[name="csrf-token"]').content;
            const resp = await fetch(`/comments/${commentId}/toggle-like`, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ is_like: isLike })
            });

            if (resp.status === 401 || resp.status === 302 || resp.status === 419) {
                window.location.href = '/login';
                return;
            }

            const ct = resp.headers.get('content-type') || '';
            if (!ct.includes('application/json')) {
                alert('Требуется авторизация для этой операции.');
                return;
            }

            const data = await resp.json();
            const likesEl = document.getElementById(`likes-count-${commentId}`);
            const dislikesEl = document.getElementById(`dislikes-count-${commentId}`);
            if (likesEl) likesEl.textContent = data.likes_count;
            if (dislikesEl) dislikesEl.textContent = data.dislikes_count;

            const likeBtn = document.getElementById(`like-btn-${commentId}`);
            const dislikeBtn = document.getElementById(`dislike-btn-${commentId}`);
            if (likeBtn) likeBtn.className = `flex items-center space-x-1 ${data.user_reaction === 'like' ? 'text-violet-500' : 'text-gray-400'} hover:text-violet-400`;
            if (dislikeBtn) dislikeBtn.className = `flex items-center space-x-1 ${data.user_reaction === 'dislike' ? 'text-red-500' : 'text-gray-400'} hover:text-red-400`;

        } catch (err) {
            console.error('Like error', err);
            alert('Не удалось выполнить действие — проверьте соединение.');
        }
    })();
}

// Edit modal handlers (used by component)
function openEditModal(commentId, content) {
    const modal = document.getElementById('edit-modal');
    if (!modal) return;
    modal.classList.remove('hidden');
    document.getElementById('edit-content').value = content.replace(/^\"|\"$/g, '');
    // Установим gif (если передано) — input с id edit-gif
    const gifInput = document.getElementById('edit-gif');
    const gifPreview = document.getElementById('edit-gif-preview');
    if (typeof arguments[2] !== 'undefined' && arguments[2]) {
        gifInput.value = arguments[2];
        gifPreview.src = arguments[2];
        gifPreview.classList.remove('hidden');
    } else {
        gifInput.value = '';
        gifPreview.classList.add('hidden');
    }
    document.getElementById('edit-form').action = `/comments/${commentId}`;
}
function closeEditModal() { document.getElementById('edit-modal')?.classList.add('hidden'); }
</script>
@endpush

@endsection

{{-- Редактирование комментария — модальное окно, отображается на странице поста --}}
@push('scripts')
<div id="edit-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-navy-800 rounded-lg shadow-lg p-6 w-full max-w-2xl mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white">Редактирование комментария</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-white">✕</button>
        </div>
        <form id="edit-form" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="edit-content" class="sr-only">Текст комментария</label>
                <textarea id="edit-content" name="content" rows="4" class="w-full px-4 py-3 bg-navy-900 border border-navy-700 rounded-lg text-navy-100 placeholder-navy-400 focus:outline-none focus:ring-2 focus:ring-violet-500" required></textarea>
            </div>
            <div>
                <label for="edit-gif" class="sr-only">URL GIF</label>
                <input type="url" id="edit-gif" name="gif_url" placeholder="URL GIF (опционально)" class="w-full px-4 py-2 bg-navy-900 border border-navy-700 rounded-lg text-navy-100 placeholder-navy-400 focus:outline-none focus:ring-2 focus:ring-violet-500" />
                <img id="edit-gif-preview" src="#" alt="GIF preview" class="mt-2 rounded-lg max-h-40 w-full object-cover hidden border border-navy-700" />
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-navy-700 text-gray-300 rounded">Отмена</button>
                <button type="submit" class="px-6 py-2 bg-violet-600 text-white rounded">Сохранить</button>
            </div>
        </form>
    </div>
</div>
@endpush