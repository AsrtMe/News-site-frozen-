<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class GenerateApiToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-api-token {--email=frontend@test.local} {--name="Frontend Developer"}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Генерирует тестовый API-токен для фронтенд-разработчика';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->option('email');
        $name = $this->option('name');

        // Создаём или получаем пользователя
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make('test123456'),
            ]
        );

        $this->info("✓ Пользователь: {$user->email}");
        $this->info("  Имя: {$user->name}");
        $this->info("  ID: {$user->id}\n");

        // Удалим старые токены
        $user->tokens()->delete();

        // Генерируем новый токен
        $token = $user->createToken('frontend-dev-token');

        $this->info("✓ Новый API-токен успешно создан!\n");
        $this->line(str_repeat('=', 70));
        $this->line('ТОКЕН ДЛЯ ФРОНТЕНДА:');
        $this->line(str_repeat('=', 70));
        $this->line($token->plainTextToken);
        $this->line(str_repeat('=', 70) . "\n");

        $this->info("📧 Email: {$user->email}");
        $this->info("🔑 Password: test123456");
        $this->info("🌐 Используйте токен в заголовке:");
        $this->line("   Authorization: Bearer {$token->plainTextToken}\n");

        $this->line("💡 Примеры использования API:");
        $this->line(str_repeat('-', 70));
        $this->line("1. Получить комментарии поста:");
        $this->line("   GET /api/posts/1/comments\n");

        $this->line("2. Создать комментарий:");
        $this->line("   POST /api/comments");
        $this->line("   { \"post_id\": 1, \"content\": \"Привет!\", \"parent_id\": null }\n");

        $this->line("3. Ответить на комментарий:");
        $this->line("   POST /api/comments");
        $this->line("   { \"post_id\": 1, \"content\": \"Ответ!\", \"parent_id\": 5 }\n");

        $this->line("4. Комментарий с гифкой:");
        $this->line("   POST /api/comments");
        $this->line("   { \"post_id\": 1, \"content\": \"Смешная!\", \"gif_url\": \"https://media.giphy.com/...\" }\n");

        $this->line("5. Поставить/снять лайк:");
        $this->line("   POST /api/comments/1/like");
        $this->line("   { \"is_like\": true }\n");

        $this->line("6. Поставить/снять дизлайк:");
        $this->line("   POST /api/comments/1/like");
        $this->line("   { \"is_like\": false }\n");

        $this->line("7. Редактировать свой комментарий:");
        $this->line("   PUT /api/comments/1");
        $this->line("   { \"content\": \"Новый текст\", \"gif_url\": \"https://...\" }\n");

        $this->line("8. Удалить свой комментарий:");
        $this->line("   DELETE /api/comments/1\n");
    }
}
