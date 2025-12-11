<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Атрибуты, которые можно массово присваивать.
     */
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'image_url',
        'views_count'
    ];

    /**
     * Типы атрибутов
     *
     * @var array
     */
    protected $casts = [
        'views_count' => 'integer',
    ];

    /**
     * Получить пользователя, создавшего пост
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Получить комментарии к посту
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }

    /**
     * Увеличить счетчик просмотров
     */
    public function incrementViewsCount(): void
    {
        $this->increment('views_count');
    }
}
