<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchNews extends Command
{
    /**
     * Имя консольной команды.
     */
    protected $signature = 'news:fetch';

    /**
     * Описание команды.
     */
    protected $description = 'Fetch news from NewsAPI and store them in Firebase Realtime Database';

    /**
     * Логика команды.
     */
    public function handle(): void
    {
        // Показываем, какой URL Firebase видит Laravel
        $firebaseUrl = rtrim(config('services.firebase.url'), '/').'/';
        $this->info('Firebase URL: '.$firebaseUrl);

        // 1. Забираем статьи из NewsAPI
        $response = Http::get('https://newsapi.org/v2/everything', [
            'q' => 'technology',
            'language' => 'ru',
            'pageSize' => 20,
            'apiKey' => env('NEWS_API_KEY'),
        ]);

        if (! $response->ok()) {
            $this->error('NewsAPI error: '.$response->status());

            return;
        }

        $articles = $response->json('articles') ?? [];

        if (empty($articles)) {
            $this->info('No articles received from NewsAPI');

            return;
        }

        // 3. (Опционально) очищаем предыдущие новости
        Http::delete($firebaseUrl.'news.json');

        // 4. Сохраняем каждую новость как отдельную запись
        foreach ($articles as $article) {
            $data = [
                'title' => $article['title'] ?? null,
                'description' => $article['description'] ?? null,
                'url' => $article['url'] ?? null,
                'image_url' => $article['urlToImage'] ?? null,
                'published_at' => $article['publishedAt'] ?? null,
                'source' => $article['source']['name'] ?? null,
            ];

            // push в Realtime Database: /news/{auto_id}
            Http::post($firebaseUrl.'news.json', $data);
        }

        $this->info('News stored in Firebase successfully');
    }
}
