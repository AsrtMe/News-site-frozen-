<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    /**
     * Отображает список постов
     */
    public function index(): View
    {
        $posts = Post::with(['user', 'comments'])
            ->withCount('comments')
            ->latest()
            ->paginate(10);
            
        return view('posts.index', compact('posts'));
    }

    /**
     * Показывает форму создания поста
     */
    public function create(): View
    {
        return view('posts.create');
    }

    /**
     * Сохраняет новый пост
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:10000',
            'image_url' => 'nullable|url|max:255'
        ]);

        $post = Post::create([
            'user_id' => Auth::id(),
            ...$validated
        ]);

        return redirect()
            ->route('posts.show', $post)
            ->with('success', 'Пост успешно создан');
    }

    /**
     * Отображает пост
     */
    public function show(Post $post): View
    {
        // Жадно загружаем автора поста, верхнеуровневые комментарии и их ответы, включая пользователей и лайки
        $post->load([
            'user',
            'comments' => function ($query) {
                $query->with(['user', 'likes', 'replies.user', 'replies.likes']);
            },
        ]);

        $post->incrementViewsCount();

        return view('posts.show', compact('post'));
    }

    /**
     * Показывает форму редактирования поста
     */
    public function edit(Post $post): View|RedirectResponse
    {
        if ($post->user_id !== Auth::id()) {
            return redirect()
                ->route('posts.show', $post)
                ->with('error', 'У вас нет прав на редактирование этого поста');
        }

        return view('posts.edit', compact('post'));
    }

    /**
     * Обновляет пост
     */
    public function update(Request $request, Post $post): RedirectResponse
    {
        if ($post->user_id !== Auth::id()) {
            return redirect()
                ->route('posts.show', $post)
                ->with('error', 'У вас нет прав на редактирование этого поста');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:10000',
            'image_url' => 'nullable|url|max:255'
        ]);

        $post->update($validated);

        return redirect()
            ->route('posts.show', $post)
            ->with('success', 'Пост успешно обновлен');
    }

    /**
     * Удаляет пост
     */
    public function destroy(Post $post): RedirectResponse|JsonResponse
    {
        if ($post->user_id !== Auth::id()) {
            if (request()->wantsJson()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            return redirect()
                ->route('posts.show', $post)
                ->with('error', 'У вас нет прав на удаление этого поста');
        }

        $post->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Пост удален']);
        }

        return redirect()
            ->route('posts.index')
            ->with('success', 'Пост успешно удален');
    }
}

