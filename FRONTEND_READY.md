# ✅ ГОТОВО К ПОДКЛЮЧЕНИЮ ФРОНТЕНДА

## 🚀 ПЕРЕДАЙТЕ ФРОНТЕНДУ ЭТО:

### Вариант 1: Через Radmin VPN (РЕКОМЕНДУЕТСЯ)
```
🌐 API URL:           http://26.237.194.233:8000/api
📍 Создать комментарий: POST http://26.237.194.233:8000/api/comments
📍 Лайк/дизлайк:      POST http://26.237.194.233:8000/api/comments/{id}/like

🔑 ТОКЕН: 1|ZEOMNcOvqIlbcc1NpHGVbphsPiy2JYcNPUykNtHU37e94dfa
```

### Вариант 2: По локальной сети (если на одной сети)
```
🌐 API URL:           http://192.168.0.14:8000/api
📍 Создать комментарий: POST http://192.168.0.14:8000/api/comments
📍 Лайк/дизлайк:      POST http://192.168.0.14:8000/api/comments/{id}/like

🔑 ТОКЕН: 1|ZEOMNcOvqIlbcc1NpHGVbphsPiy2JYcNPUykNtHU37e94dfa
```

---

## 📝 ФРОНТЕНДУ СКОПИРОВАТЬ И ВСТАВИТЬ КОД:

### Написать комментарий (БЕЗ токена, как гость):

```javascript
// Выберите IP в зависимости от способа подключения:
const API_URL = 'http://26.237.194.233:8000/api'; // Radmin VPN
// или
const API_URL = 'http://192.168.0.14:8000/api'; // Локальная сеть

fetch(API_URL + '/comments', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    post_id: 1,
    content: 'Привет от фронтенда!'
  })
})
.then(r => r.json())
.then(data => {
  if (data.success) {
    console.log('✓ Комментарий создан!', data.data);
  } else {
    console.error('✗ Ошибка:', data.message);
  }
})
.catch(err => console.error('✗ Ошибка сети:', err));
```

### Поставить лайк (ТРЕБУЕТСЯ токен):

```javascript
const API_URL = 'http://26.237.194.233:8000/api';
const TOKEN = '1|ZEOMNcOvqIlbcc1NpHGVbphsPiy2JYcNPUykNtHU37e94dfa';

fetch(API_URL + '/comments/1/like', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': 'Bearer ' + TOKEN
  },
  body: JSON.stringify({ is_like: true })
})
.then(r => r.json())
.then(data => {
  if (data.success) {
    console.log('✓ Лайк поставлен!', data.data);
  }
})
.catch(err => console.error('✗ Ошибка:', err));
```

### Получить все комментарии поста:

```javascript
const API_URL = 'http://26.237.194.233:8000/api';

fetch(API_URL + '/posts/1/comments')
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      console.log('Комментарии:', data.data);
      data.data.forEach(c => {
        console.log(`${c.user.name}: ${c.content}`);
      });
    }
  })
  .catch(err => console.error('✗ Ошибка:', err));
```

---

## ✅ ЧТО ФРОНТЕНДУ НУЖНО ПРОВЕРИТЬ:

1. **Написать комментарий** - запустить код выше
2. **Ожидать ответ:**
   ```json
   {
     "success": true,
     "message": "Комментарий создан",
     "data": {
       "id": 10,
       "post_id": 1,
       "content": "Привет от фронтенда!",
       "user_id": 4,
       "user": { "name": "Frontend Developer", "email": "frontend@test.local" },
       "created_at": "2025-12-04T..."
     }
   }
   ```
3. **Проверить на форуме** - комментарий должен быть виден на `http://26.237.194.233:8000/posts/1`

---

## 🔄 СИНХРОНИЗАЦИЯ В РЕАЛЬНОМ ВРЕМЕНИ

Когда фронтенд пишет комментарий:
1. Запрос → `http://26.237.194.233:8000/api/comments` (POST)
2. Бэкенд сохраняет в БД ✓
3. Вы видите его на `http://26.237.194.233:8000/posts/1` ✓
4. Фронтенд получает ответ с ID комментария ✓

**Всё работает в реальном времени!**

---

## 📡 IP АДРЕСА ДЛЯ РАЗНЫХ СЕТЕЙ:

| Способ подключения | IP адрес | Когда использовать |
|---|---|---|
| **Radmin VPN** | `26.237.194.233:8000` | Фронтенд подключен через VPN |
| **Локальная сеть** | `192.168.0.14:8000` | Фронтенд в одной локальной сети |
| **localhost** | `localhost:8000` | Фронтенд на одном компьютере |

---

## 🎯 КРАТКАЯ ИНСТРУКЦИЯ ДЛЯ ФРОНТЕНДА:

```
1. Выберите IP адрес (Radmin VPN или локальная сеть)
2. Скопируйте код выше
3. Замените IP в коде на выбранный
4. Запустите в консоли браузера (F12 → Console)
5. Смотрите результат
6. Проверьте на форуме, что комментарий там
```

---

## 🐛 ЕСЛИ ЧТО-ТО НЕ РАБОТАЕТ:

### Ошибка: "Cannot reach server"
```
❌ net::ERR_FAILED
```
**Решение:** 
- Проверьте, что используете правильный IP
- Проверьте, что сервер запущен (должна быть виден ответ на ping)
- Проверьте VPN подключение

### Ошибка: "CORS error"
```
❌ Access to XMLHttpRequest at '...' has been blocked by CORS policy
```
**Решение:** Это временная ошибка при разработке. Проверьте консоль браузера за деталями.

### Ошибка: "401 Unauthorized"
```json
{"success": false, "message": "Требуется авторизация"}
```
**Решение:** Это нормально для лайков. Убедитесь, что используете правильный токен в `Authorization` заголовке.

---

## 💾 ГОТОВЫЕ ССЫЛКИ ДЛЯ ПРОВЕРКИ:

**Просмотреть все комментарии:**
- Radmin VPN: `http://26.237.194.233:8000/posts/1`
- Локальная сеть: `http://192.168.0.14:8000/posts/1`

**API документация:**
- Radmin VPN: `http://26.237.194.233:8000/api/posts/1/comments`
- Локальная сеть: `http://192.168.0.14:8000/api/posts/1/comments`

---

## 🎉 ГОТОВО!

Сервер запущен и ждёт фронтенда! 🚀

Фронтенд может:
- ✅ Писать комментарии (как гость)
- ✅ Писать с гифками
- ✅ Отвечать на комментарии
- ✅ Ставить лайки (с токеном)
- ✅ Ставить дизлайки (с токеном)

**Передайте ему код выше и IP адреса — и он может начинать!**
