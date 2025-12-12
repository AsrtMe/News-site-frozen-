<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Like extends Model
{
    use HasFactory;

    /**
     * Атрибуты, которые можно массово присваивать
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'comment_id',
        'is_like'
    ];

    /**
     * Атрибуты, которые должны быть приведены к определенным типам
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_like' => 'boolean',
        'user_id' => 'integer',
        'comment_id' => 'integer'
    ];

    /**
     * Получить пользователя, поставившего лайк/дизлайк
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Получить комментарий, к которому относится лайк/дизлайк
     */
    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class);
    }
}