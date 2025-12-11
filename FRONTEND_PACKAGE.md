# 📦 ПАКЕТ ДЛЯ ФРОНТЕНД-РАЗРАБОТЧИКА
## Всё, что нужно для подключения и интеграции API

---

## 📋 СОДЕРЖИМОЕ ПАКЕТА

### 1. **Файлы документации:**
- ✅ `API_FOR_FRONTEND.md` — полная документация API с примерами
- ✅ `API_FRONTEND_EXAMPLES.js` — готовые функции JavaScript
- ✅ `.env.example` — пример конфигурации

### 2. **Учётные данные:**

**API Токен (готов к использованию):**
```
1|ZEOMNcOvqIlbcc1NpHGVbphsPiy2JYcNPUykNtHU37e94dfa
```

**Email для входа:**
```
frontend@test.local
```

**Пароль:**
```
test123456
```

### 3. **Информация о подключении:**

**На хосте запустить:**
```powershell
cd C:\Users\98kek\forum
php artisan serve --host=0.0.0.0 --port=8000
```

**Локальный IP хоста (узнать командой):**
```powershell
ipconfig
# Найти: IPv4 Address . . . . . . . . . . . : 192.168.x.x
```

**В коде фронтенда использовать:**
```javascript
const BASE_URL = 'http://192.168.x.x:8000'; // замените IP
const API_TOKEN = '1|ZEOMNcOvqIlbcc1NpHGVbphsPiy2JYcNPUykNtHU37e94dfa';
```

---

## 🚀 БЫСТРЫЙ СТАРТ ДЛЯ ФРОНТЕНДА

### Шаг 1: Подключитесь к Radmin VPN
Убедитесь, что VPN активен и видна локальная сеть.

### Шаг 2: Узнайте локальный IP хоста
На компьютере, где запущен Laravel, запустите:
```powershell
ipconfig
```
Найдите строку `IPv4 Address` в разделе Ethernet или Wi-Fi. Это будет примерно `192.168.x.x`.

### Шаг 3: Скопируйте код из `API_FRONTEND_EXAMPLES.js`
Все функции уже готовы — просто скопируйте нужные в ваш проект.

### Шаг 4: Замените BASE_URL на реальный IP
```javascript
// ❌ Не работает:
const BASE_URL = 'http://localhost:8000';

// ✅ Правильно:
const BASE_URL = 'http://192.168.1.100:8000'; // ваш IP
```

### Шаг 5: Тестируйте
```javascript
// Получить комментарии поста 1
getPostComments(1);

// Написать комментарий как гость
createComment(1, 'Привет от фронтенда!');

// Поставить лайк (требуется токен)
likeComment(1);
```

---

## 📡 API ENDPOINTS (КРАТКАЯ СПРАВКА)

### Без авторизации (доступно всем):
```
GET /api/posts/{post_id}/comments          # Получить комментарии
POST /api/comments                          # Создать комментарий (как гость)
```

### С авторизацией (требуется токен в заголовке):
```
POST /api/comments/{id}/like                # Лайк/дизлайк
PUT /api/comments/{id}                      # Редактировать (только свой)
DELETE /api/comments/{id}                   # Удалить (только свой)
```

### Заголовок авторизации:
```javascript
'Authorization': 'Bearer 1|ZEOMNcOvqIlbcc1NpHGVbphsPiy2JYcNPUykNtHU37e94dfa'
```

---

## 💻 ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ

### Пример 1: Получить комментарии поста
```javascript
const BASE_URL = 'http://192.168.1.100:8000';

fetch(`${BASE_URL}/api/posts/1/comments`)
  .then(res => res.json())
  .then(data => {
    console.log(data.data); // Массив комментариев
    // Вывести на страницу
    data.data.forEach(comment => {
      console.log(`${comment.user.name}: ${comment.content}`);
    });
  });
```

### Пример 2: Написать комментарий (как гость, без токена)
```javascript
const BASE_URL = 'http://192.168.1.100:8000';

fetch(`${BASE_URL}/api/comments`, {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    post_id: 1,
    content: 'Отличный пост!',
    parent_id: null,
    gif_url: null
  })
})
  .then(res => res.json())
  .then(data => {
    console.log('✓ Комментарий создан:', data.data);
  });
```

### Пример 3: Ответить на комментарий
```javascript
const BASE_URL = 'http://192.168.1.100:8000';

fetch(`${BASE_URL}/api/comments`, {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    post_id: 1,
    content: 'Это ответ на комментарий #5',
    parent_id: 5,  // ← ID комментария, на который отвечаем
    gif_url: null
  })
})
  .then(res => res.json())
  .then(data => {
    console.log('✓ Ответ создан:', data.data);
  });
```

### Пример 4: Комментарий с гифкой
```javascript
const BASE_URL = 'http://192.168.1.100:8000';

fetch(`${BASE_URL}/api/comments`, {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    post_id: 1,
    content: 'Смешная гифка!',
    gif_url: 'https://media.giphy.com/media/xTiTnDz9gU5s9GTsEo/giphy.gif',
    parent_id: null
  })
})
  .then(res => res.json())
  .then(data => {
    console.log('✓ Комментарий с гифкой создан');
  });
```

### Пример 5: Поставить лайк (требуется токен)
```javascript
const BASE_URL = 'http://192.168.1.100:8000';
const API_TOKEN = '1|ZEOMNcOvqIlbcc1NpHGVbphsPiy2JYcNPUykNtHU37e94dfa';

fetch(`${BASE_URL}/api/comments/1/like`, {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': `Bearer ${API_TOKEN}`
  },
  body: JSON.stringify({ is_like: true })
})
  .then(res => res.json())
  .then(data => {
    console.log('✓ Лайк поставлен');
    console.log('Лайки:', data.data.likes_count);
    console.log('Дизлайки:', data.data.dislikes_count);
  });
```

### Пример 6: Дизлайк
```javascript
// Всё то же самое, но:
body: JSON.stringify({ is_like: false })
```

---

## 🎨 HTML ПРИМЕР: Форма для комментариев

```html
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Комментарии</title>
  <style>
    body { font-family: Arial; max-width: 800px; margin: 0 auto; padding: 20px; }
    .comment-form { background: #f5f5f5; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
    input, textarea, button { width: 100%; padding: 10px; margin: 10px 0; font-size: 14px; }
    button { background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
    button:hover { background: #0056b3; }
    .comment { background: white; padding: 15px; margin: 10px 0; border-left: 3px solid #007bff; border-radius: 5px; }
    .comment-author { font-weight: bold; color: #333; }
    .comment-content { margin: 10px 0; }
    .comment-likes { font-size: 12px; color: #666; margin-top: 10px; }
    .like-btn { padding: 5px 10px; margin-right: 5px; font-size: 12px; }
  </style>
</head>
<body>
  <h1>Комментарии к посту</h1>

  <div class="comment-form">
    <h3>Добавить комментарий</h3>
    <input type="text" id="postId" placeholder="ID поста (например, 1)" value="1">
    <textarea id="commentText" placeholder="Ваш комментарий..." rows="4"></textarea>
    <input type="text" id="gifUrl" placeholder="URL гифки (опционально)">
    <button onclick="submitComment()">Отправить</button>
  </div>

  <h3>Комментарии:</h3>
  <div id="comments"></div>

  <script>
    const BASE_URL = 'http://192.168.1.100:8000'; // ← ЗАМЕНИТЕ IP
    const API_TOKEN = '1|ZEOMNcOvqIlbcc1NpHGVbphsPiy2JYcNPUykNtHU37e94dfa';

    async function loadComments() {
      const postId = document.getElementById('postId').value || 1;
      const res = await fetch(`${BASE_URL}/api/posts/${postId}/comments`);
      const data = await res.json();

      const commentsDiv = document.getElementById('comments');
      commentsDiv.innerHTML = '';

      if (data.data) {
        data.data.forEach(comment => {
          const html = `
            <div class="comment">
              <div class="comment-author">${comment.user.name}</div>
              <div class="comment-content">${comment.content}</div>
              ${comment.gif_url ? `<img src="${comment.gif_url}" style="max-width: 300px; border-radius: 5px; margin: 10px 0;">` : ''}
              <div class="comment-likes">
                👍 ${comment.likes_count} 👎 ${comment.dislikes_count}
                <button class="like-btn" onclick="likeCommentAPI(${comment.id})">👍 Like</button>
                <button class="like-btn" onclick="dislikeCommentAPI(${comment.id})">👎 Dislike</button>
              </div>
            </div>
          `;
          commentsDiv.innerHTML += html;
        });
      }
    }

    async function submitComment() {
      const postId = document.getElementById('postId').value;
      const content = document.getElementById('commentText').value;
      const gifUrl = document.getElementById('gifUrl').value;

      if (!content) {
        alert('Введите текст комментария');
        return;
      }

      const res = await fetch(`${BASE_URL}/api/comments`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          post_id: postId,
          content: content,
          gif_url: gifUrl || null,
          parent_id: null
        })
      });

      const data = await res.json();
      if (data.success) {
        alert('✓ Комментарий добавлен!');
        document.getElementById('commentText').value = '';
        document.getElementById('gifUrl').value = '';
        loadComments();
      } else {
        alert('✗ Ошибка: ' + data.message);
      }
    }

    async function likeCommentAPI(commentId) {
      const res = await fetch(`${BASE_URL}/api/comments/${commentId}/like`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${API_TOKEN}`
        },
        body: JSON.stringify({ is_like: true })
      });
      const data = await res.json();
      if (data.success) {
        loadComments();
      }
    }

    async function dislikeCommentAPI(commentId) {
      const res = await fetch(`${BASE_URL}/api/comments/${commentId}/like`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${API_TOKEN}`
        },
        body: JSON.stringify({ is_like: false })
      });
      const data = await res.json();
      if (data.success) {
        loadComments();
      }
    }

    // Загрузить комментарии при открытии страницы
    loadComments();
  </script>
</body>
</html>
```

**Как использовать этот HTML:**
1. Сохраните как `test.html`
2. Откройте в браузере
3. Замените `192.168.1.100` на реальный IP вашего хоста
4. Пишите комментарии и они будут видны у вас на форуме в реальном времени!

---

## ⚠️ ВАЖНЫЕ МОМЕНТЫ

### 1. **IP-адрес должен быть доступен через VPN**
- Убедитесь, что оба компьютера подключены к Radmin VPN
- Проверьте, что `php artisan serve` запущен с флагом `--host=0.0.0.0`

### 2. **Гостевые комментарии**
- Без токена комментарии сохраняются от `guest@forum.local`
- Вы видите их в своём форуме как обычные комментарии

### 3. **Лайки требуют авторизации**
- Для лайков/дизлайков обязателен токен в заголовке `Authorization`

### 4. **CORS ошибки**
Если видите ошибку в консоли браузера типа "CORS error":
- Это часто временно (при разработке разрешены)
- Проверьте, что API URL правильный
- Убедитесь, что Laravel запущен

### 5. **Синхронизация в реальном времени**
- После отправки комментария с фронтенда, он появится у вас на форуме
- Используйте `loadComments()` в цикле, чтобы обновлять комментарии автоматически

---

## 🔍 ОТЛАДКА

### Проверить, работает ли API:
```javascript
fetch('http://192.168.1.100:8000/api/posts/1/comments')
  .then(r => r.json())
  .then(console.log)
  .catch(console.error);
```

### Проверить, доступен ли сервер:
```bash
curl -I http://192.168.1.100:8000/
```

### Посмотреть логи на хосте:
```powershell
# В реальном времени:
tail -f C:\Users\98kek\forum\storage\logs\laravel.log

# Или на Windows:
Get-Content C:\Users\98kek\forum\storage\logs\laravel.log -Wait -Tail 50
```

---

## 📞 КОНТАКТЫ И ВОПРОСЫ

Если у фронтенда есть вопросы:
1. **Документация:** `API_FOR_FRONTEND.md`
2. **Примеры кода:** `API_FRONTEND_EXAMPLES.js`
3. **HTML пример:** выше в этом файле

**Основной токен для всех операций с авторизацией:**
```
1|ZEOMNcOvqIlbcc1NpHGVbphsPiy2JYcNPUykNtHU37e94dfa
```

---

## 🎉 ГОТОВО!
Теперь фронтенд может подключиться через VPN и писать комментарии, которые будут видны у вас на форуме в реальном времени!
