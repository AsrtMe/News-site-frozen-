<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Post;

class MakeTestPost extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:make-test-post {--title=Тестовый пост} {--content=Это тестовый пост для API}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создаёт тестовый пост (использует guest-пользователя)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $title = $this->option('title');
        $content = $this->option('content');

        $user = User::guest();

        $post = Post::create([
            'user_id' => $user->id,
            'title' => $title,
            'content' => $content,
            'image_url' => null,
            'views_count' => 0,
        ]);

        $this->info("✓ Тестовый пост создан (id={$post->id})");
        $this->line("URL: /posts/{$post->id}");
    }
}
