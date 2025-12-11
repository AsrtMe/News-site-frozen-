# ✅ ЧЕК-ЛИСТ ДЛЯ ФРОНТЕНД-РАЗРАБОТЧИКА

## Шаг 1: Подготовка окружения

- [ ] Установлен Radmin VPN
- [ ] Подключены к VPN
- [ ] Видна локальная сеть (192.168.x.x)
- [ ] Получили IP-адрес хоста (например: 192.168.1.100)

## Шаг 2: Запуск сервера на хосте

На хосте (где Laravel) выполнить:

```powershell
cd C:\Users\98kek\forum
php artisan serve --host=0.0.0.0 --port=8000
```

- [ ] Сервер запущен и выводит: `Server running on [http://0.0.0.0:8000]`
- [ ] В браузере доступен `http://192.168.1.100:8000` (замените IP)

## Шаг 3: Получить файлы для фронтенда

- [ ] Получены файлы:
  - ✅ `API_FOR_FRONTEND.md` (документация)
  - ✅ `API_FRONTEND_EXAMPLES.js` (примеры кода)
  - ✅ `FRONTEND_PACKAGE.md` (этот пакет)
  - ✅ `.env.example` (конфигурация)

## Шаг 4: Настроить подключение

- [ ] Скопировали пример конфигурации в `.env.local` или переменные окружения
- [ ] Заменили `192.168.1.100` на реальный IP хоста
- [ ] Заменили `API_TOKEN` на: `1|ZEOMNcOvqIlbcc1NpHGVbphsPiy2JYcNPUykNtHU37e94dfa`

## Шаг 5: Интегрировать API в фронтенд

Выберите один из вариантов:

### Вариант A: Использовать готовый HTML
- [ ] Скопировали HTML-пример из этого файла
- [ ] Сохранили как `test.html`
- [ ] Открыли в браузере
- [ ] Заменили IP в коде
- [ ] Успешно написали тестовый комментарий

### Вариант B: Использовать JavaScript функции
- [ ] Скопировали функции из `API_FRONTEND_EXAMPLES.js`
- [ ] Адаптировали под вашу фреймворк (React, Vue, Angular и т.д.)
- [ ] Заменили `BASE_URL` на реальный IP
- [ ] Заменили `API_TOKEN` на предоставленный токен

### Вариант C: cURL тестирование
Если используете Node.js или другой язык, тестируйте через curl:

```bash
# Получить комментарии поста 1
curl -X GET http://192.168.1.100:8000/api/posts/1/comments

# Создать комментарий (гость, без токена)
curl -X POST http://192.168.1.100:8000/api/comments \
  -H "Content-Type: application/json" \
  -d '{"post_id":1,"content":"Тест из curl"}'

# Поставить лайк (требуется токен)
curl -X POST http://192.168.1.100:8000/api/comments/1/like \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer 1|ZEOMNcOvqIlbcc1NpHGVbphsPiy2JYcNPUykNtHU37e94dfa" \
  -d '{"is_like":true}'
```

## Шаг 6: Тестирование синхронизации

- [ ] Написали комментарий с фронтенда
- [ ] Комментарий появился на хосте (http://192.168.1.100:8000/posts/1)
- [ ] Ставите лайк с фронтенда
- [ ] Лайк считается на бэкенде

## Шаг 7: Отладка (если что-то не работает)

### Ошибка: "CORS error"
```
❌ Access to XMLHttpRequest at 'http://...' from origin 'http://localhost:3000' has been blocked
```
**Решение:** CORS разрешены в разработке. Проверьте, что IP правильный и Laravel запущен.

### Ошибка: "Cannot reach server"
```
❌ net::ERR_FAILED
```
**Решение:**
1. Проверьте VPN подключение
2. Проверьте IP адрес (ipconfig на хосте)
3. Убедитесь, что Laravel запущен: `php artisan serve --host=0.0.0.0 --port=8000`

### Ошибка: "Unauthorized" (401)
```json
{"success": false, "message": "Требуется авторизация"}
```
**Решение:** Для лайков нужен правильный токен в заголовке `Authorization`.

### Ошибка: "Not Found" (404)
```json
{"success": false, "message": "Комментарий не найден"}
```
**Решение:** Используйте правильный ID комментария или поста.

## Шаг 8: Готовые примеры по фреймворкам

### React пример
```javascript
import { useState, useEffect } from 'react';

const BASE_URL = 'http://192.168.1.100:8000';
const API_TOKEN = '1|ZEOMNcOvqIlbcc1NpHGVbphsPiy2JYcNPUykNtHU37e94dfa';

function Comments({ postId }) {
  const [comments, setComments] = useState([]);
  const [text, setText] = useState('');

  useEffect(() => {
    loadComments();
  }, [postId]);

  async function loadComments() {
    const res = await fetch(`${BASE_URL}/api/posts/${postId}/comments`);
    const data = await res.json();
    setComments(data.data || []);
  }

  async function addComment() {
    if (!text) return;
    const res = await fetch(`${BASE_URL}/api/comments`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ post_id: postId, content: text })
    });
    if (res.ok) {
      setText('');
      loadComments();
    }
  }

  return (
    <div>
      <h3>Комментарии</h3>
      <textarea value={text} onChange={e => setText(e.target.value)} placeholder="Комментарий..." />
      <button onClick={addComment}>Отправить</button>
      {comments.map(c => (
        <div key={c.id} style={{ border: '1px solid #ddd', padding: '10px', margin: '10px 0' }}>
          <strong>{c.user.name}</strong>
          <p>{c.content}</p>
        </div>
      ))}
    </div>
  );
}

export default Comments;
```

### Vue 3 пример
```vue
<template>
  <div>
    <h3>Комментарии</h3>
    <textarea v-model="text" placeholder="Комментарий..."></textarea>
    <button @click="addComment">Отправить</button>
    <div v-for="comment in comments" :key="comment.id" class="comment">
      <strong>{{ comment.user.name }}</strong>
      <p>{{ comment.content }}</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const BASE_URL = 'http://192.168.1.100:8000';
const text = ref('');
const comments = ref([]);

async function loadComments() {
  const res = await fetch(`${BASE_URL}/api/posts/1/comments`);
  const data = await res.json();
  comments.value = data.data || [];
}

async function addComment() {
  if (!text.value) return;
  await fetch(`${BASE_URL}/api/comments`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ post_id: 1, content: text.value })
  });
  text.value = '';
  loadComments();
}

onMounted(loadComments);
</script>

<style scoped>
.comment { border: 1px solid #ddd; padding: 10px; margin: 10px 0; }
</style>
```

### Node.js (Express) пример
```javascript
const express = require('express');
const fetch = require('node-fetch');
const app = express();

const BASE_URL = 'http://192.168.1.100:8000';
const API_TOKEN = '1|ZEOMNcOvqIlbcc1NpHGVbphsPiy2JYcNPUykNtHU37e94dfa';

app.use(express.json());

// Получить комментарии
app.get('/comments/:postId', async (req, res) => {
  const response = await fetch(`${BASE_URL}/api/posts/${req.params.postId}/comments`);
  const data = await response.json();
  res.json(data);
});

// Создать комментарий
app.post('/comments', async (req, res) => {
  const { postId, content } = req.body;
  const response = await fetch(`${BASE_URL}/api/comments`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ post_id: postId, content })
  });
  const data = await response.json();
  res.json(data);
});

// Лайк
app.post('/like/:commentId', async (req, res) => {
  const response = await fetch(`${BASE_URL}/api/comments/${req.params.commentId}/like`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${API_TOKEN}`
    },
    body: JSON.stringify({ is_like: true })
  });
  const data = await response.json();
  res.json(data);
});

app.listen(3000, () => console.log('Frontend server на http://localhost:3000'));
```

## Шаг 9: Готово!

- [ ] Фронтенд подключён к API
- [ ] Комментарии синхронизируются в реальном времени
- [ ] Лайки работают
- [ ] Гифки отображаются
- [ ] Ответы на комментарии работают

---

## 📞 КОНТАКТЫ ДЛЯ СПРАВОК

**Основной токен:**
```
1|ZEOMNcOvqIlbcc1NpHGVbphsPiy2JYcNPUykNtHU37e94dfa
```

**Email фронтенда:**
```
frontend@test.local
```

**Пароль:**
```
test123456
```

**API документация:**
- `API_FOR_FRONTEND.md` — полная документация со всеми endpoints
- `API_FRONTEND_EXAMPLES.js` — готовые функции JavaScript
- `.env.example` — пример конфигурации

---

**Всё готово! 🚀 Начинайте разработку!**
