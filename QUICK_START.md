# 🚀 БЫСТРЫЙ СТАРТ (3 МИНУТЫ)

## Для фронтенд-разработчика

### 1️⃣ На вашем компьютере (в интернете через VPN)

Откройте браузер и перейдите на эту страницу (сохраните как `test.html` и откройте):

```html
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Тест API</title>
  <style>
    body { font-family: Arial; max-width: 600px; margin: 50px auto; }
    textarea { width: 100%; height: 100px; }
    button { padding: 10px 20px; margin: 10px 0; }
    .comment { border: 1px solid #ccc; padding: 10px; margin: 10px 0; }
  </style>
</head>
<body>
  <h1>Форум - Тестирование API</h1>
  
  <h2>Добавить комментарий:</h2>
  <textarea id="text" placeholder="Ваш комментарий..."></textarea>
  <button onclick="add()">Отправить комментарий</button>
  
  <h2>Комментарии:</h2>
  <button onclick="load()">Обновить</button>
  <div id="comments"></div>

  <script>
    // 🔴 ЗАМЕНИТЕ 192.168.1.100 НА РЕАЛЬНЫЙ IP ХОСТА!
    const BASE = 'http://192.168.1.100:8000';
    const TOKEN = '1|ZEOMNcOvqIlbcc1NpHGVbphsPiy2JYcNPUykNtHU37e94dfa';

    async function add() {
      const text = document.getElementById('text').value;
      if (!text) return alert('Введите комментарий');

      const res = await fetch(`${BASE}/api/comments`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ post_id: 1, content: text })
      });

      const data = await res.json();
      if (data.success) {
        alert('✓ Комментарий добавлен!');
        document.getElementById('text').value = '';
        load();
      } else {
        alert('✗ Ошибка: ' + data.message);
      }
    }

    async function load() {
      const res = await fetch(`${BASE}/api/posts/1/comments`);
      const data = await res.json();
      const html = data.data?.map(c => `
        <div class="comment">
          <strong>${c.user.name}</strong> (👍 ${c.likes_count} 👎 ${c.dislikes_count})
          <p>${c.content}</p>
          <button onclick="like(${c.id})">👍 Лайк</button>
          <button onclick="dislike(${c.id})">👎 Дизлайк</button>
        </div>
      `).join('') || 'Нет комментариев';
      document.getElementById('comments').innerHTML = html;
    }

    async function like(id) {
      const res = await fetch(`${BASE}/api/comments/${id}/like`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${TOKEN}`
        },
        body: JSON.stringify({ is_like: true })
      });
      const data = await res.json();
      if (data.success) load();
      else alert('Ошибка: ' + data.message);
    }

    async function dislike(id) {
      const res = await fetch(`${BASE}/api/comments/${id}/like`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${TOKEN}`
        },
        body: JSON.stringify({ is_like: false })
      });
      const data = await res.json();
      if (data.success) load();
      else alert('Ошибка: ' + data.message);
    }

    // Загрузить комментарии при открытии
    load();
  </script>
</body>
</html>
```

---

## ДЛЯ РАЗРАБОТЧИКА (НА ХОСТЕ)

### 1️⃣ Запустить сервер

```powershell
cd C:\Users\98kek\forum
php artisan serve --host=0.0.0.0 --port=8000
```

### 2️⃣ Узнать IP адрес

```powershell
ipconfig
# Ищите: IPv4 Address . . . . . . . : 192.168.x.x
```

### 3️⃣ Дать фронтенду IP и токен

```
🌐 IP адрес: 192.168.x.x (замените x на реальные цифры)
🔑 Токен: 1|ZEOMNcOvqIlbcc1NpHGVbphsPiy2JYcNPUykNtHU37e94dfa
```

### 4️⃣ Фронтенд заменит в HTML:
```javascript
const BASE = 'http://192.168.x.x:8000'; // ← сюда реальный IP
```

### 5️⃣ Фронтенд откроет HTML в браузере и пишет комментарии

---

## ✅ ЧТО РАБОТАЕТ

| Функция | Требует токен? | Работает? |
|---------|---|---|
| Написать комментарий | ❌ | ✅ |
| Написать с гифкой | ❌ | ✅ |
| Ответить на комментарий | ❌ | ✅ |
| Поставить лайк | ✅ | ✅ |
| Поставить дизлайк | ✅ | ✅ |

---

## 🐛 ПРОБЛЕМЫ И РЕШЕНИЯ

### Проблема: "Cannot reach server"
**Решение:** 
1. Убедитесь, что Laravel запущен
2. Проверьте правильность IP адреса
3. Проверьте, что оба компьютера в одной VPN сети

### Проблема: "CORS error"
**Решение:** Это нормально при разработке. Проверьте консоль браузера (F12 → Console) для деталей.

### Проблема: Лайк не работает
**Решение:** Убедитесь, что в коде правильный токен в заголовке `Authorization`.

---

## 📱 ГОТОВЫЙ ТОКЕН

```
1|ZEOMNcOvqIlbcc1NpHGVbphsPiy2JYcNPUykNtHU37e94dfa
```

Используйте этот токен для ВСЕХ запросов, требующих авторизации (лайки, удаления и т.д.).

---

**Готово! Можете начинать тестировать! 🎉**
