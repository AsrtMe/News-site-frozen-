<?php
/**
 * Скрипт для генерации тестового API-токена для фронтенд-разработчика
 * 
 * Запуск: php generate-api-token.php
 */

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap/app.php';

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Создаём или получаем тестовго пользователя для фронтенда
$frontendUser = User::firstOrCreate(
    ['email' => 'frontend@test.local'],
    [
        'name' => 'Frontend Developer',
        'password' => Hash::make('test123456'),
    ]
);

echo "✓ Пользователь: {$frontendUser->email}\n";
echo "  Имя: {$frontendUser->name}\n";
echo "  ID: {$frontendUser->id}\n\n";

// Удалим старые токены, если есть
$frontendUser->tokens()->delete();

// Генерируем новый токен
$token = $frontendUser->createToken('frontend-dev-token');

echo "✓ Новый API-токен успешно создан!\n\n";
echo "════════════════════════════════════════════════════════════════\n";
echo "ТОКЕН ДЛЯ ФРОНТЕНДА:\n";
echo "════════════════════════════════════════════════════════════════\n";
echo $token->plainTextToken . "\n";
echo "════════════════════════════════════════════════════════════════\n\n";

echo "📧 Email: {$frontendUser->email}\n";
echo "🔑 Password: test123456\n";
echo "🌐 Используйте этот токен в заголовке Authorization:\n";
echo "   Authorization: Bearer {$token->plainTextToken}\n\n";

echo "💡 Примеры использования:\n";
echo "────────────────────────────────────────────────────────────────\n";
echo "1. Создать комментарий:\n";
echo "   POST /api/comments\n";
echo "   { \"post_id\": 1, \"content\": \"Привет!\", \"parent_id\": null }\n\n";

echo "2. Поставить лайк:\n";
echo "   POST /api/comments/1/like\n";
echo "   { \"is_like\": true }\n\n";

echo "3. Поставить дизлайк:\n";
echo "   POST /api/comments/1/like\n";
echo "   { \"is_like\": false }\n\n";

echo "4. Отправить комментарий с гифкой:\n";
echo "   POST /api/comments\n";
echo "   { \"post_id\": 1, \"content\": \"Смешная гифка!\", \"gif_url\": \"https://media.giphy.com/...\" }\n\n";
