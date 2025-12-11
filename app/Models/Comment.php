<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\Post;

class Comment extends Model
{
    use HasFactory, SoftDeletes;
    
    /**
     * Атрибуты, которые можно массово присваивать
     */
    protected $fillable = [
        'user_id',
        'post_id',
        'parent_id',
        'content',
        'gif_url'
    ];

    /**
     * Отношения, которые всегда должны быть загружены
     */
    protected $with = ['user', 'likes'];

    /**
     * Атрибуты, добавляемые в JSON-представление
     */
    protected $appends = [
        'likes_count',
        'dislikes_count',
        'user_reaction'
    ];

    /**
     * Получить пользователя, создавшего комментарий
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Получить родительский комментарий
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Получить пост, к которому относится комментарий
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Получить дочерние комментарии (ответы)
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')->latest();
    }

    /**
     * Получить лайки комментария
     */
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Количество лайков
     */
    protected function likesCount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->likes()->where('is_like', true)->count()
        );
    }

    /**
     * Количество дизлайков
     */
    protected function dislikesCount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->likes()->where('is_like', false)->count()
        );
    }

    /**
     * Проверить, поставил ли пользователь лайк
     */
    public function isLikedByUser(?int $userId = null): bool
    {
        return $this->hasUserReaction(true, $userId);
    }

    /**
     * Проверить, поставил ли пользователь дизлайк
     */
    public function isDislikedByUser(?int $userId = null): bool
    {
        return $this->hasUserReaction(false, $userId);
    }

    /**
     * Получить текущую реакцию пользователя
     */
    protected function userReaction(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!Auth::check()) {
                    return null;
                }
                
                $like = $this->likes()
                    ->where('user_id', Auth::id())
                    ->first();
                    
                if (!$like) {
                    return null;
                }
                
                return $like->is_like ? 'like' : 'dislike';
            }
        );
    }

    /**
     * Проверить реакцию пользователя
     */
    private function hasUserReaction(bool $isLike, ?int $userId = null): bool
    {
        $userId = $userId ?: Auth::id();
        return $this->likes()
            ->where('user_id', $userId)
            ->where('is_like', $isLike)
            ->exists();
    }
}