<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class CommentApiController extends Controller
{
    /**
     * Список всех комментариев для поста (пагинация)
     * GET /api/posts/{post_id}/comments
     */
    public function indexByPost($postId): JsonResponse
    {
        $comments = Comment::whereNull('parent_id')
            ->where('post_id', $postId)
            ->with(['user', 'replies.user', 'replies.likes', 'likes'])
            ->withCount('replies')
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $comments->items(),
            'pagination' => [
                'total' => $comments->total(),
                'per_page' => $comments->perPage(),
                'current_page' => $comments->currentPage(),
                'last_page' => $comments->lastPage(),
                'next_page_url' => $comments->nextPageUrl(),
                'prev_page_url' => $comments->previousPageUrl(),
            ]
        ]);
    }

    /**
     * Получить одиночный комментарий со всеми ответами
     * GET /api/comments/{id}
     */
    public function show($id): JsonResponse
    {
        $comment = Comment::with(['user', 'replies.user', 'replies.likes', 'likes'])
            ->find($id);

        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Комментарий не найден'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $comment
        ]);
    }

    /**
     * Создать новый комментарий или ответ
     * POST /api/comments
     *
     * Тело запроса (JSON):
     * {
     *   "post_id": 1,
     *   "content": "Текст комментария",
     *   "parent_id": null,  // null для корневого комментария, либо id родительского комментария для ответа
     *   "gif_url": "https://..."  // необязательно
     * }
     */
    public function store(Request $request): JsonResponse
    {
        // Разрешаем создание комментариев даже без авторизации.
        // В этом случае используем гостевого пользователя.
        $userId = Auth::check() ? Auth::id() : \App\Models\User::guest()->id;

        $validated = $request->validate([
            'post_id' => 'required|exists:posts,id',
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
            'gif_url' => 'nullable|url|max:1024'
        ]);

        try {
            // Создаём комментарий в БД
            $comment = Comment::create([
                'user_id' => $userId,
                'post_id' => $validated['post_id'],
                'content' => $validated['content'],
                'parent_id' => $validated['parent_id'] ?? null,
                'gif_url' => $validated['gif_url'] ?? null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Комментарий создан',
                'data' => $comment->load('user', 'likes')
            ], 201);
        } catch (\Throwable $e) {
            // Возвращаем подробную ошибку в non-production среде
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при создании комментария',
                'error' => app()->environment('production') ? null : $e->getMessage()
            ], 500);
        }
    }

    /**
     * Обновить комментарий
     * PUT /api/comments/{id}
     *
     * Тело запроса (JSON):
     * {
     *   "content": "Обновлённый текст",
     *   "gif_url": "https://..."  // необязательно
     * }
     */
    public function update(Request $request, $id): JsonResponse
    {
        $comment = Comment::find($id);

        // Проверяем существование
        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Комментарий не найден'
            ], 404);
        }

        // Проверяем право собственности (только автор может редактировать)
        if ($comment->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'У вас нет прав на редактирование этого комментария'
            ], 403);
        }

        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'gif_url' => 'nullable|url|max:1024'
        ]);

        try {
            $comment->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Комментарий обновлен',
                'data' => $comment->fresh()->load('user', 'likes')
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при обновлении комментария',
                'error' => app()->environment('production') ? null : $e->getMessage()
            ], 500);
        }
    }

    /**
     * Удалить комментарий
     * DELETE /api/comments/{id}
     */
    public function destroy($id): JsonResponse
    {
        $comment = Comment::find($id);

        // Проверки: существование и право на удаление
        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Комментарий не найден'
            ], 404);
        }

        if ($comment->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'У вас нет прав на удаление этого комментария'
            ], 403);
        }

        try {
            $postId = $comment->post_id;
            $comment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Комментарий удален'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при удалении комментария',
                'error' => app()->environment('production') ? null : $e->getMessage()
            ], 500);
        }
    }

    /**
     * Поставить/снять лайк или дизлайк для комментария (переключатель)
     * POST /api/comments/{id}/like
     *
     * Тело запроса (JSON):
     * {
     *   "is_like": true  // true = лайк, false = дизлайк
     * }
     */
    public function toggleLike(Request $request, $id): JsonResponse
    {
        // Требуется авторизация
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Требуется авторизация'
            ], 401);
        }

        $comment = Comment::find($id);

        // Проверяем существование комментария
        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Комментарий не найден'
            ], 404);
        }

        $validated = $request->validate([
            'is_like' => 'required|boolean'
        ]);

        try {
            $isLike = $validated['is_like'];

            // Ищем существующую запись реакции пользователя на этот комментарий
            $like = Like::where('user_id', Auth::id())
                ->where('comment_id', $comment->id)
                ->first();

            if (!$like) {
                // Если реакции ещё нет — создаём
                Like::create([
                    'user_id' => Auth::id(),
                    'comment_id' => $comment->id,
                    'is_like' => $isLike
                ]);
                $action = $isLike ? 'лайк поставлен' : 'дизлайк поставлен';
            } else {
                // Если уже есть и совпадает — удаляем (toggle)
                if ($like->is_like === $isLike) {
                    $like->delete();
                    $action = $isLike ? 'лайк удален' : 'дизлайк удален';
                } else {
                    // Если был другой тип реакции — обновляем
                    $like->is_like = $isLike;
                    $like->save();
                    $action = $isLike ? 'лайк поставлен' : 'дизлайк поставлен';
                }
            }

            // Обновляем счётчики
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
                'success' => true,
                'message' => $action,
                'data' => [
                    'comment_id' => $comment->id,
                    'likes_count' => $likesCount,
                    'dislikes_count' => $dislikesCount,
                    'user_reaction' => $userReaction
                ]
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при обновлении лайка',
                'error' => app()->environment('production') ? null : $e->getMessage()
            ], 500);
        }
    }
}
