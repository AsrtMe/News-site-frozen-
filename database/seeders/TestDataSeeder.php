<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Comment;
use App\Models\Like;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создаем тестового пользователя
        $user = \App\Models\User::factory()->create([
            'name' => 'Тестовый пользователь',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Создаем несколько комментариев первого уровня
        \App\Models\Comment::factory()
            ->count(5)
            ->sequence(fn ($sequence) => ['user_id' => $user->id])
            ->create()
            ->each(function ($comment) use ($user) {
                // Для каждого комментария создаем 0-3 ответа
                $replies = \App\Models\Comment::factory()
                    ->count(rand(0, 3))
                    ->sequence(fn ($sequence) => [
                        'user_id' => $user->id,
                        'parent_id' => $comment->id
                    ])
                    ->create();

                // Добавляем случайное количество лайков и дизлайков
                $allComments = collect([$comment])->merge($replies);
                
                foreach ($allComments as $c) {
                    $likeCount = rand(0, 5);
                    $dislikeCount = rand(0, 3);
                    
                    \App\Models\Like::factory()
                        ->count($likeCount)
                        ->sequence(fn ($sequence) => [
                            'comment_id' => $c->id,
                            'is_like' => true
                        ])
                        ->create();

                    \App\Models\Like::factory()
                        ->count($dislikeCount)
                        ->sequence(fn ($sequence) => [
                            'comment_id' => $c->id,
                            'is_like' => false
                        ])
                        ->create();
                }
            });
    }
}
