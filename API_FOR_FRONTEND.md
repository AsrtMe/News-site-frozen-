# 📡 ИНСТРУКЦИЯ ДЛЯ ФРОНТЕНД-РАЗРАБОТЧИКА
## Подключение через Radmin VPN и использование API

---

## 🔧 ОБЩАЯ ИНФОРМАЦИЯ

### Что реализовано в API
✓ Создание комментариев (с гифками)  
✓ Ответы на комментарии (вложенность)  
✓ Лайки и дизлайки  
✓ Редактирование своих комментариев  
✓ Удаление своих комментариев  
✓ Получение списка комментариев с пагинацией  

### Статус аутентификации
- **Создание комментариев**: ✓ доступно без регистрации (как гость)
- **Лайки/дизлайки**: требуется токен авторизации
- **Редактирование/удаление**: только для своих комментариев (через токен)

---

## 🌐 ПОДКЛЮЧЕНИЕ ЧЕРЕЗ RADMIN VPN

### 1. Убедитесь, что VPN активен
Вы должны быть подключены к Radmin VPN и видеть локальную сеть хоста (на котором запущен Laravel).

### 2. Определите IP-адрес хоста
На компьютере, где запущен Laravel, получите локальный IP:

**Windows (PowerShell):**
```powershell
ipconfig
# Ищите в выводе строку типа: IPv4 Address  . . . . . . . . . . . : 192.168.x.x
```

**Linux/Mac (Terminal):**
```bash
ifconfig
# или
hostname -I
```

Это будет что-то типа: `192.168.1.100` или `10.0.0.50`

### 3. Убедитесь, что Laravel запущен
На хосте откройте PowerShell/Terminal и запустите dev-сервер:

```powershell
cd C:\Users\98kek\forum
php artisan serve --host=0.0.0.0 --port=8000
```

Это сделает сервер доступным по сети (не только localhost).

### 4. Используйте IP в клиентском коде

В вашем JavaScript замените `BASE_URL`:

```javascript
// ❌ ЭТО НЕ БУДЕТ РАБОТАТЬ через VPN:
const BASE_URL = 'http://localhost:8000';

// ✓ ИСПОЛЬЗУЙТЕ ЛОКАЛЬНЫЙ IP:
const BASE_URL = 'http://192.168.1.100:8000'; // замените IP на реальный
```

---

## 🔐 АУТЕНТИФИКАЦИЯ

### Способ 1: Гостевые комментарии (БЕЗ ТОКЕНА)
Фронтенд может писать комментарии БЕЗ регистрации — они будут сохраняться за пользователем `guest@forum.local`.

```javascript
// Просто отправьте POST без Authorization заголовка
fetch('http://192.168.1.100:8000/api/comments', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    post_id: 1,
    content: 'Комментарий от гостя'
  })
});
```

### Способ 2: С авторизацией (С ТОКЕНОМ)
Для лайков, дизлайков и редактирования требуется токен.

**Токен для тестирования:**
```
1|ZEOMNcOvqIlbcc1NpHGVbphsPiy2JYcNPUykNtHU37e94dfa
```

**Email:** `frontend@test.local`  
**Password:** `test123456`

Используйте заголовок `Authorization` с токеном:

```javascript
const API_TOKEN = '1|ZEOMNcOvqIlbcc1NpHGVbphsPiy2JYcNPUykNtHU37e94dfa';

fetch('http://192.168.1.100:8000/api/comments/1/like', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': `Bearer ${API_TOKEN}`
  },
  body: JSON.stringify({ is_like: true })
});
```

---

## 📚 API ENDPOINTS

Все endpoints используют JSON для запросов и ответов.

### 1. Получить комментарии поста (без авторизации)
```
GET /api/posts/{post_id}/comments
```

**Ответ (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "post_id": 1,
      "user_id": 4,
      "user": {
        "id": 4,
        "name": "Frontend Developer",
        "email": "frontend@test.local"
      },
      "content": "Отличный пост!",
      "gif_url": null,
      "likes_count": 2,
      "dislikes_count": 1,
      "replies": [],
      "created_at": "2025-12-04T10:15:00Z",
      "updated_at": "2025-12-04T10:15:00Z"
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

### 2. Создать комментарий (без авторизации ИЛИ с токеном)
```
POST /api/comments
```

**Тело запроса:**
```json
{
  "post_id": 1,
  "content": "Мой комментарий",
  "parent_id": null,
  "gif_url": "https://media.giphy.com/media/..."
}
```

**Поля:**
- `post_id` (обязательно): ID поста
- `content` (обязательно): текст комментария (макс 1000 символов)
- `parent_id` (опционально): ID родительского комментария (для ответов)
- `gif_url` (опционально): URL гифки (макс 1024 символов)

**Ответ (201):**
```json
{
  "success": true,
  "message": "Комментарий создан",
  "data": {
    "id": 10,
    "post_id": 1,
    "user_id": 4,
    "user": {
      "id": 4,
      "name": "Frontend Developer",
      "email": "frontend@test.local"
    },
    "content": "Мой комментарий",
    "gif_url": null,
    "created_at": "2025-12-04T10:20:00Z"
  }
}
```

### 3. Ответить на комментарий
```
POST /api/comments
```

Используйте тот же endpoint, но установите `parent_id`:

```json
{
  "post_id": 1,
  "content": "Это ответ на комментарий 5",
  "parent_id": 5,
  "gif_url": null
}
```

### 4. Поставить лайк / дизлайк (требуется токен)
```
POST /api/comments/{comment_id}/like
```

**Тело запроса:**
```json
{
  "is_like": true
}
```

- `is_like: true` = лайк
- `is_like: false` = дизлайк
- Повторная отправка той же реакции = удаление реакции (toggle)

**Ответ (200):**
```json
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

### 5. Редактировать комментарий (требуется токен, только для своих)
```
PUT /api/comments/{comment_id}
```

**Тело запроса:**
```json
{
  "content": "Обновленный текст",
  "gif_url": "https://media.giphy.com/media/..."
}
```

**Ответ (200):**
```json
{
  "success": true,
  "message": "Комментарий обновлен",
  "data": {
    "id": 1,
    "content": "Обновленный текст",
    "gif_url": "https://media.giphy.com/media/...",
    "updated_at": "2025-12-04T10:25:00Z"
  }
}
```

### 6. Удалить комментарий (требуется токен, только для своих)
```
DELETE /api/comments/{comment_id}
```

**Ответ (200):**
```json
{
  "success": true,
  "message": "Комментарий удален"
}
```

---

## ❌ ОБРАБОТКА ОШИБОК

### 400 / 422 - Ошибка валидации
```json
{
  "success": false,
  "message": "Ошибка валидации",
  "errors": {
    "content": ["The content field is required"]
  }
}
```

### 401 - Требуется авторизация
```json
{
  "success": false,
  "message": "Требуется авторизация"
}
```

### 403 - Нет прав
```json
{
  "success": false,
  "message": "У вас нет прав на редактирование этого комментария"
}
```

### 404 - Не найдено
```json
{
  "success": false,
  "message": "Комментарий не найден"
}
```

### 500 - Ошибка сервера
```json
{
  "success": false,
  "message": "Ошибка при создании комментария",
  "error": "... детали ошибки в development режиме ..."
}
```

---

## 💻 ПРИМЕРЫ КОДА

### JavaScript (Fetch API)

```javascript
const BASE_URL = 'http://192.168.1.100:8000';
const API_TOKEN = '1|ZEOMNcOvqIlbcc1NpHGVbphsPiy2JYcNPUykNtHU37e94dfa';

// 1. Получить комментарии
async function getComments(postId) {
  const res = await fetch(`${BASE_URL}/api/posts/${postId}/comments`);
  const data = await res.json();
  console.log(data.data);
}

// 2. Написать комментарий (гость)
async function writeAsGuest(postId, text) {
  const res = await fetch(`${BASE_URL}/api/comments`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ post_id: postId, content: text })
  });
  const data = await res.json();
  console.log(data.data);
}

// 3. Поставить лайк (требуется токен)
async function likeComment(commentId) {
  const res = await fetch(`${BASE_URL}/api/comments/${commentId}/like`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${API_TOKEN}`
    },
    body: JSON.stringify({ is_like: true })
  });
  const data = await res.json();
  console.log(data.data);
}

// 4. Ответить на комментарий (гость)
async function replyAsGuest(postId, parentId, text) {
  const res = await fetch(`${BASE_URL}/api/comments`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      post_id: postId,
      content: text,
      parent_id: parentId
    })
  });
  const data = await res.json();
  console.log(data.data);
}

// 5. Комментарий с гифкой
async function commentWithGif(postId, text, gifUrl) {
  const res = await fetch(`${BASE_URL}/api/comments`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      post_id: postId,
      content: text,
      gif_url: gifUrl
    })
  });
  const data = await res.json();
  console.log(data.data);
}

// Использование:
getComments(1);
writeAsGuest(1, 'Отличный пост!');
likeComment(1);
replyAsGuest(1, 1, 'Я согласен!');
commentWithGif(1, 'Смешная гифка!', 'https://media.giphy.com/media/...');
```

### cURL (для тестирования с командной строки)

```bash
# 1. Получить комментарии
curl -X GET http://192.168.1.100:8000/api/posts/1/comments

# 2. Создать комментарий (гость)
curl -X POST http://192.168.1.100:8000/api/comments \
  -H "Content-Type: application/json" \
  -d '{
    "post_id": 1,
    "content": "Привет от curl!",
    "parent_id": null
  }'

# 3. Поставить лайк (требуется токен)
curl -X POST http://192.168.1.100:8000/api/comments/1/like \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer 1|ZEOMNcOvqIlbcc1NpHGVbphsPiy2JYcNPUykNtHU37e94dfa" \
  -d '{"is_like": true}'

# 4. Комментарий с гифкой
curl -X POST http://192.168.1.100:8000/api/comments \
  -H "Content-Type: application/json" \
  -d '{
    "post_id": 1,
    "content": "Гифка!",
    "gif_url": "https://media.giphy.com/media/...",
    "parent_id": null
  }'
```

---

## 🚀 БЫСТРЫЙ СТАРТ

1. **Подключитесь через Radmin VPN**
2. **Узнайте локальный IP хоста** (например, 192.168.1.100)
3. **На хосте запустите Laravel:**
   ```powershell
   php artisan serve --host=0.0.0.0 --port=8000
   ```
4. **В клиентском коде установите BASE_URL:**
   ```javascript
   const BASE_URL = 'http://192.168.1.100:8000';
   ```
5. **Используйте примеры выше для запросов**

---

## 📝 ПОЛЕЗНАЯ ИНФОРМАЦИЯ

### Максимальная длина контента
- `content`: 1000 символов
- `gif_url`: 1024 символа

### Пагинация по умолчанию
- 15 комментариев на странице

### Timezone
Все даты в ответах в UTC (ISO 8601 формат)

### Поддерживаемые методы
- `GET` - получение данных
- `POST` - создание или действие (лайк)
- `PUT` - редактирование
- `DELETE` - удаление

---

## ⚠️ ВАЖНЫЕ ЗАМЕЧАНИЯ

1. **Гостевые комментарии** создаются от пользователя `guest@forum.local` (не требует авторизации)
2. **Для лайков/дизлайков** нужен токен
3. **Для редактирования/удаления** нужен токен И комментарий должен быть ваш
4. **CORS** должны быть разрешены на бэкенде (обычно они разрешены для всех Origins в разработке)
5. **Если что-то не работает** — проверьте консоль браузера (DevTools → Network и Console)

---

## 🆘 ЧАСТО ЗАДАВАЕМЫЕ ВОПРОСЫ

**Q: Как мне генерировать новые токены?**  
A: На хосте запустите:
```powershell
php artisan app:generate-api-token --email=your-email@example.com --name="Your Name"
```

**Q: Можно ли писать комментарии без токена?**  
A: Да! Без токена комментарии сохраняются от гостевого пользователя.

**Q: Что если я забыл токен?**  
A: Запустите команду `php artisan app:generate-api-token` еще раз, она перегенерирует токен.

**Q: Как включить CORS для моего фронтенда?**  
A: Отредактируйте `config/cors.php` на хосте. По умолчанию CORS должны работать.

**Q: Как проверить, что API работает?**  
A: Откройте браузер и перейдите на `http://192.168.1.100:8000/api/posts/1/comments` — должен вернуться JSON.

---

**Готово к использованию! 🎉**
