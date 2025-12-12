<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Like;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    /**
     * Отображает список комментариев
     */
    public function index(): View|JsonResponse
    {
        $comments = Comment::whereNull('parent_id')
            ->with(['replies.user', 'replies.likes', 'user', 'likes'])
            ->latest()
            ->paginate(15);
            
        if (request()->wantsJson()) {
            return response()->json($comments);
        }
        
        return view('comments.index', compact('comments'));
    }

    /**
     * Сохраняет новый комментарий
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        try {
            $validated = $request->validate([
                'post_id' => 'required|exists:posts,id',
                'content' => 'required|string|max:5000',
                'parent_id' => 'nullable|exists:comments,id',
                'gif_url' => 'nullable|url|max:1024'
            ]);

            // Если пользователь не авторизован — используем гостевого пользователя
            $userId = Auth::check() ? Auth::id() : User::guest()->id;

            $comment = Comment::create([
                'user_id' => $userId,
                'post_id' => $validated['post_id'],
                'content' => $validated['content'],
                'parent_id' => $validated['parent_id'] ?? null,
                'gif_url' => $validated['gif_url'] ?? null
            ]);

            if ($request->wantsJson()) {
                return response()->json($comment->load('user'));
            }

            return redirect()->route('posts.show', $comment->post_id)
                ->with('success', 'Комментарий успешно добавлен');
        } catch (\Throwable $e) {
            $message = app()->environment('production')
                ? 'Ошибка при добавлении комментария. Попробуйте позже.'
                : $e->getMessage();
            return redirect()->back()
                ->withInput()
                ->with('error', $message);
        }
    }

    /**
     * Переключает лайк/дизлайк для комментария
     */
    public function toggleLike(Request $request, Comment $comment): JsonResponse
    {
        $request->validate([
            'is_like' => 'required'
        ]);

        // Поддерживаем как булевы значения, так и строковые 'true'/'false' или '1'/'0'
        $raw = $request->input('is_like');
        $isLike = filter_var($raw, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        if ($isLike === null) {
            // Если преобразование не удалось — попробуем строгую проверку на строку
            $isLike = $raw === 'true' || $raw === '1';
        }
        $message = $this->handleLikeToggle($comment, $isLike);

        // Обновим модель и посчитаем актуальные значения
        $comment = $comment->fresh();
        $likesCount = $comment->likes()->where('is_like', true)->count();
        $dislikesCount = $comment->likes()->where('is_like', false)->count();
        $userReaction = null;
        if (Auth::check()) {
            $userLike = $comment->likes()->where('user_id', Auth::id())->first();
            if ($userLike) {
                $userReaction = $userLike->is_like ? 'like' : 'dislike';
            }
        }

        return response()->json([
            'message' => $message,
            'likes_count' => $likesCount,
            'dislikes_count' => $dislikesCount,
            'user_reaction' => $userReaction
        ]);
    }

    /**
     * Показывает форму редактирования комментария
     */
    public function edit(Comment $comment): View|RedirectResponse
    {
        if (!$this->canModifyComment($comment)) {
            return redirect()->back()
                ->with('error', 'У вас нет прав на редактирование этого комментария');
        }

        return view('comments.edit', compact('comment'));
    }

    /**
     * Обновляет комментарий
     */
    public function update(Request $request, Comment $comment): JsonResponse|RedirectResponse
    {
        if (!$this->canModifyComment($comment)) {
            return $this->unauthorizedResponse($request);
        }

        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'gif_url' => 'nullable|url|max:1024'
        ]);

        $comment->update($validated);
        
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Комментарий обновлен',
                'comment' => $comment->fresh()->load('user')
            ]);
        }

        return redirect()->route('posts.show', $comment->post_id)
            ->with('success', 'Комментарий обновлен');
    }

    /**
     * Удаляет комментарий
     */
    public function destroy(Request $request, Comment $comment): JsonResponse|RedirectResponse
    {
        if (!$this->canModifyComment($comment)) {
            return $this->unauthorizedResponse($request);
        }

        // Сохраним id поста на случай, если комментарий будет удалён
        $postId = $comment->post_id;
        $comment->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Комментарий удален']);
        }

        return redirect()->route('posts.show', $postId)->with('success', 'Комментарий удален');
    }

    /**
     * Обрабатывает переключение лайка/дизлайка
     */
    private function handleLikeToggle(Comment $comment, bool $isLike): string
    {
        $like = Like::where('user_id', Auth::id())
            ->where('comment_id', $comment->id)
            ->first();
            
        if (!$like) {
            Like::create([
                'user_id' => Auth::id(),
                'comment_id' => $comment->id,
                'is_like' => $isLike
            ]);
            return $isLike ? 'Лайк поставлен' : 'Дизлайк поставлен';
        }

        if ($like->is_like === $isLike) {
            $like->delete();
            return $isLike ? 'Лайк удален' : 'Дизлайк удален';
        }

        $like->is_like = $isLike;
        $like->save();
        return $isLike ? 'Лайк поставлен' : 'Дизлайк поставлен';
    }

    /**
     * Проверяет права на модификацию комментария
     */
    private function canModifyComment(Comment $comment): bool
    {
        return $comment->user_id === Auth::id();
    }

    /**
     * Возвращает ответ о недостаточных правах
     */
    private function unauthorizedResponse(Request $request): JsonResponse|RedirectResponse
    {
        if ($request->wantsJson()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        return redirect()->back()
            ->with('error', 'У вас нет прав на эту операцию');
    }
}