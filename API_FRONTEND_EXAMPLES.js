// ═══════════════════════════════════════════════════════════════════
// ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ API ДЛЯ ФРОНТЕНДА
// ═══════════════════════════════════════════════════════════════════

// ⚠️ ВАЖНО: Замените BASE_URL на адрес вашего сервера
// Через Radmin VPN это может быть что-то типа: http://192.168.x.x:8000 или http://локальный-ip:8000

const BASE_URL = 'http://your-server-ip:8000'; // Пример: http://192.168.1.100:8000
const API_TOKEN = '1|ZEOMNcOvqIlbcc1NpHGVbphsPiy2JYcNPUykNtHU37e94dfa'; // Замените на реальный токен

// ═══════════════════════════════════════════════════════════════════
// 1. ПОЛУЧИТЬ КОММЕНТАРИИ ПОСТА (Публичный, без авторизации)
// ═══════════════════════════════════════════════════════════════════
async function getPostComments(postId) {
  try {
    const response = await fetch(`${BASE_URL}/api/posts/${postId}/comments`);
    const data = await response.json();
    
    if (data.success) {
      console.log('Комментарии:', data.data);
      return data.data;
    } else {
      console.error('Ошибка:', data.message);
    }
  } catch (error) {
    console.error('Ошибка сети:', error);
  }
}

// ═══════════════════════════════════════════════════════════════════
// 2. СОЗДАТЬ КОММЕНТАРИЙ (Требует авторизация через токен)
// ═══════════════════════════════════════════════════════════════════
async function createComment(postId, content, gifUrl = null, parentId = null) {
  try {
    const payload = {
      post_id: postId,
      content: content,
      parent_id: parentId,
      gif_url: gifUrl
    };

    const response = await fetch(`${BASE_URL}/api/comments`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${API_TOKEN}`
      },
      body: JSON.stringify(payload)
    });

    const data = await response.json();
    
    if (data.success) {
      console.log('✓ Комментарий создан:', data.data);
      return data.data;
    } else {
      console.error('✗ Ошибка:', data.message);
    }
  } catch (error) {
    console.error('✗ Ошибка сети:', error);
  }
}

// ═══════════════════════════════════════════════════════════════════
// 3. ОТВЕТИТЬ НА КОММЕНТАРИЙ (то же самое, но с parent_id)
// ═══════════════════════════════════════════════════════════════════
async function replyToComment(postId, parentCommentId, content, gifUrl = null) {
  return createComment(postId, content, gifUrl, parentCommentId);
}

// ═══════════════════════════════════════════════════════════════════
// 4. ПОСТАВИТЬ ЛАЙК НА КОММЕНТАРИЙ
// ═══════════════════════════════════════════════════════════════════
async function likeComment(commentId) {
  try {
    const response = await fetch(`${BASE_URL}/api/comments/${commentId}/like`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${API_TOKEN}`
      },
      body: JSON.stringify({ is_like: true })
    });

    const data = await response.json();
    
    if (data.success) {
      console.log('✓ Лайк поставлен:', data.message);
      console.log('Лайки:', data.data.likes_count, 'Дизлайки:', data.data.dislikes_count);
      return data.data;
    } else {
      console.error('✗ Ошибка:', data.message);
    }
  } catch (error) {
    console.error('✗ Ошибка сети:', error);
  }
}

// ═══════════════════════════════════════════════════════════════════
// 5. ПОСТАВИТЬ ДИЗЛАЙК НА КОММЕНТАРИЙ
// ═══════════════════════════════════════════════════════════════════
async function dislikeComment(commentId) {
  try {
    const response = await fetch(`${BASE_URL}/api/comments/${commentId}/like`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${API_TOKEN}`
      },
      body: JSON.stringify({ is_like: false })
    });

    const data = await response.json();
    
    if (data.success) {
      console.log('✓ Дизлайк поставлен:', data.message);
      console.log('Лайки:', data.data.likes_count, 'Дизлайки:', data.data.dislikes_count);
      return data.data;
    } else {
      console.error('✗ Ошибка:', data.message);
    }
  } catch (error) {
    console.error('✗ Ошибка сети:', error);
  }
}

// ═══════════════════════════════════════════════════════════════════
// 6. РЕДАКТИРОВАТЬ КОММЕНТАРИЙ
// ═══════════════════════════════════════════════════════════════════
async function updateComment(commentId, newContent, gifUrl = null) {
  try {
    const payload = {
      content: newContent,
      gif_url: gifUrl
    };

    const response = await fetch(`${BASE_URL}/api/comments/${commentId}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${API_TOKEN}`
      },
      body: JSON.stringify(payload)
    });

    const data = await response.json();
    
    if (data.success) {
      console.log('✓ Комментарий обновлен:', data.data);
      return data.data;
    } else {
      console.error('✗ Ошибка:', data.message);
    }
  } catch (error) {
    console.error('✗ Ошибка сети:', error);
  }
}

// ═══════════════════════════════════════════════════════════════════
// 7. УДАЛИТЬ КОММЕНТАРИЙ
// ═══════════════════════════════════════════════════════════════════
async function deleteComment(commentId) {
  try {
    const response = await fetch(`${BASE_URL}/api/comments/${commentId}`, {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${API_TOKEN}`
      }
    });

    const data = await response.json();
    
    if (data.success) {
      console.log('✓ Комментарий удален');
      return true;
    } else {
      console.error('✗ Ошибка:', data.message);
    }
  } catch (error) {
    console.error('✗ Ошибка сети:', error);
  }
}

// ═══════════════════════════════════════════════════════════════════
// ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ
// ═══════════════════════════════════════════════════════════════════

// Пример 1: Получить все комментарии поста 1
getPostComments(1);

// Пример 2: Написать новый комментарий
createComment(1, 'Отличный пост!');

// Пример 3: Написать комментарий с гифкой
createComment(
  1, 
  'Смешная гифка!', 
  'https://media.giphy.com/media/giphy.gif'
);

// Пример 4: Ответить на комментарий с ID 5
replyToComment(1, 5, 'Я согласен!');

// Пример 5: Поставить лайк на комментарий 1
likeComment(1);

// Пример 6: Поставить дизлайк на комментарий 1
dislikeComment(1);

// Пример 7: Редактировать комментарий 1
updateComment(1, 'Обновленный текст комментария');

// Пример 8: Удалить комментарий 1
deleteComment(1);
