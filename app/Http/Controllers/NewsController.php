<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class NewsController extends Controller
{
    /**
     * Возвращает список новостей из Firebase для фронтенда.
     */
    public function index()
    {
        $firebaseUrl = rtrim(config('services.firebase.url'), '/').'/';

        // Читаем данные: /news.json
        $response = Http::get($firebaseUrl.'news.json');

        if (! $response->ok()) {
            return response()->json([
                'message' => 'Failed to load news from Firebase',
            ], 500);
        }

        $data = $response->json() ?? [];

        // Firebase возвращает объект {id1: {...}, id2: {...}} – превращаем в массив
        $news = [];
        foreach ($data as $id => $item) {
            if (! is_array($item)) {
                continue;
            }

            $item['id'] = $id;
            $news[] = $item;
        }

        // Сортируем по дате публикации (новые сверху)
        usort($news, function ($a, $b) {
            return strcmp($b['published_at'] ?? '', $a['published_at'] ?? '');
        });

        return response()->json($news);
    }
}
