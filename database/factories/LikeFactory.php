<?php

namespace Database\Factories;

use App\Models\Like;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

class LikeFactory extends Factory
{
    protected $model = Like::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'comment_id' => Comment::factory(),
            'is_like' => $this->faker->boolean(70), // 70% шанс лайка, 30% дизлайка
        ];
    }
}