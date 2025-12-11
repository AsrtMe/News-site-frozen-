# 🔌 ИНТЕГРАЦИЯ API В ГОТОВЫЙ ФРОНТЕНД

## 📋 ЧТО НУЖНО ФРОНТЕНДУ СДЕЛАТЬ

У фронтенда уже есть готовый интерфейс. Ему нужно только заменить API endpoints на ваши.

---

## 🔗 ЗАМЕНЫ В КОДЕ ФРОНТЕНДА

### 1️⃣ **Заменить BASE URL:**

**Было:**
```javascript
const API_URL = 'http://localhost:3000/api'; // его локальный сервер
```

**Стало:**
```javascript
const API_URL = 'http://26.237.194.233:8000/api'; // ваш сервер (Radmin VPN)
// или
const API_URL = 'http://192.168.0.14:8000/api'; // ваш сервер (локальная сеть)
```

---

### 2️⃣ **ENDPOINTS (все API маршруты):**

| Операция | Метод | Endpoint |
|---|---|---|
| Получить комментарии | GET | `/posts/{postId}/comments` |
| Создать комментарий | POST | `/comments` |
| Лайк/дизлайк | POST | `/comments/{id}/like` |
| Редактировать | PUT | `/comments/{id}` |
| Удалить | DELETE | `/comments/{id}` |

---

### 3️⃣ **ФОРМАТ ЗАПРОСА - Создание комментария:**

```javascript
// POST /api/comments
{
  "post_id": 1,           // ID поста (обязательно)
  "content": "текст",     // Текст комментария (обязательно)
  "parent_id": null,      // ID родительского комментария для ответа (опционально)
  "gif_url": "https://..." // URL гифки (опционально, макс 1024 символа)
}
```

---

### 4️⃣ **ФОРМАТ ОТВЕТА - Успешное создание:**

```json
{
  "success": true,
  "message": "Комментарий создан",
  "data": {
    "id": 10,
    "post_id": 1,
    "user_id": 4,
    "content": "текст",
    "gif_url": null,
    "user": {
      "id": 4,
      "name": "Frontend Developer",
      "email": "frontend@test.local"
    },
    "likes_count": 0,
    "dislikes_count": 0,
    "created_at": "2025-12-04T...",
    "updated_at": "2025-12-04T..."
  }
}
```

---

### 5️⃣ **ПОЛУЧИТЬ КОММЕНТАРИИ:**

```javascript
// GET /api/posts/1/comments

// Ответ:
{
  "success": true,
  "data": [
    {
      "id": 1,
      "post_id": 1,
      "content": "Комментарий",
      "user": { "name": "Guest", "email": "guest@forum.local" },
      "likes_count": 2,
      "dislikes_count": 0,
      "replies": [ /* вложенные комментарии */ ],
      "created_at": "..."
    }
  ],
  "pagination": {
    "total": 5,
    "per_page": 15,
    "current_page": 1,
    "last_page": 1
  }
}
```

---

### 6️⃣ **ЛАЙК/ДИЗЛАЙК:**

```javascript
// POST /api/comments/1/like
// Заголовок: Authorization: Bearer {токен}

{
  "is_like": true  // true = лайк, false = дизлайк
}

// Ответ:
{
  "success": true,
  "message": "Лайк поставлен",
  "data": {
    "comment_id": 1,
    "likes_count": 3,
    "dislikes_count": 1,
    "user_reaction": "like"
  }
}
```

---

## 🔑 ТОКЕН ДЛЯ АВТОРИЗАЦИИ

Для лайков/дизлайков/редактирования нужен токен в заголовке:

```javascript
const TOKEN = '1|ZEOMNcOvqIlbcc1NpHGVbphsPiy2JYcNPUykNtHU37e94dfa';

fetch(API_URL + '/comments/1/like', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': 'Bearer ' + TOKEN  // ← важно!
  },
  body: JSON.stringify({ is_like: true })
});
```

---

## 📝 ПРИМЕР ПОЛНОЙ ИНТЕГРАЦИИ (React)

```javascript
import { useState, useEffect } from 'react';

const API_URL = 'http://26.237.194.233:8000/api';
const TOKEN = '1|ZEOMNcOvqIlbcc1NpHGVbphsPiy2JYcNPUykNtHU37e94dfa';

function Comments({ postId }) {
  const [comments, setComments] = useState([]);
  const [newComment, setNewComment] = useState('');

  // Загрузить комментарии
  useEffect(() => {
    loadComments();
  }, [postId]);

  async function loadComments() {
    try {
      const res = await fetch(`${API_URL}/posts/${postId}/comments`);
      const data = await res.json();
      if (data.success) {
        setComments(data.data || []);
      }
    } catch (err) {
      console.error('Ошибка загрузки:', err);
    }
  }

  // Создать комментарий
  async function addComment() {
    if (!newComment.trim()) return;

    try {
      const res = await fetch(`${API_URL}/comments`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          post_id: postId,
          content: newComment,
          parent_id: null
        })
      });

      const data = await res.json();
      if (data.success) {
        setNewComment('');
        loadComments(); // Обновить
      } else {
        alert('Ошибка: ' + data.message);
      }
    } catch (err) {
      console.error('Ошибка:', err);
    }
  }

  // Лайк
  async function likeComment(commentId) {
    try {
      const res = await fetch(`${API_URL}/comments/${commentId}/like`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${TOKEN}`
        },
        body: JSON.stringify({ is_like: true })
      });

      const data = await res.json();
      if (data.success) {
        loadComments(); // Обновить счётчики
      }
    } catch (err) {
      console.error('Ошибка лайка:', err);
    }
  }

  return (
    <div className="comments">
      <h3>Комментарии</h3>

      <div className="comment-form">
        <textarea
          value={newComment}
          onChange={e => setNewComment(e.target.value)}
          placeholder="Ваш комментарий..."
        />
        <button onClick={addComment}>Отправить</button>
      </div>

      <div className="comments-list">
        {comments.map(comment => (
          <div key={comment.id} className="comment">
            <strong>{comment.user.name}</strong>
            <p>{comment.content}</p>
            {comment.gif_url && (
              <img src={comment.gif_url} alt="gif" style={{ maxWidth: '300px' }} />
            )}
            <div className="likes">
              <span>👍 {comment.likes_count}</span>
              <span>👎 {comment.dislikes_count}</span>
              <button onClick={() => likeComment(comment.id)}>👍 Like</button>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}

export default Comments;
```

---

## 🔍 ОСНОВНЫЕ МОМЕНТЫ

### ✅ Без авторизации (работает):
- Получить комментарии (GET)
- Создать комментарий (POST)
- Ответить на комментарий (POST с parent_id)
- Комментарии с гифками

### 🔐 С авторизацией (нужен токен):
- Лайк/дизлайк
- Редактирование (только своих)
- Удаление (только своих)

### 📍 Заголовок авторизации:
```javascript
'Authorization': 'Bearer 1|ZEOMNcOvqIlbcc1NpHGVbphsPiy2JYcNPUykNtHU37e94dfa'
```

---

## 🌐 IP АДРЕСА

**Выбрать один:**
- Radmin VPN: `26.237.194.233:8000`
- Локальная сеть: `192.168.0.14:8000`
- localhost (если на одном компе): `localhost:8000`

---

## ✅ ГОТОВО!

Фронтенду нужно:
1. Заменить BASE_URL на ваш IP
2. Заменить TOKEN на предоставленный
3. Использовать endpoints из таблицы выше
4. Всё остальное — как в его готовом коде

**Комментарии будут синхронизироваться в реальном времени!** 🎉
