// Переключение отображения формы ответа
function toggleReplyForm(commentId) {
    const form = document.getElementById(`reply-form-${commentId}`);
    
    // Закрываем все открытые формы
    document.querySelectorAll('[id^="reply-form-"]').forEach(el => {
        if (el.id !== `reply-form-${commentId}`) {
            el.classList.add('hidden');
        }
    });
    
    // Переключаем текущую форму
    form.classList.toggle('hidden');
    
    // Фокусируемся на поле ввода, если форма открыта
    if (!form.classList.contains('hidden')) {
        form.querySelector('textarea').focus();
    }
}

// Обработка лайков/дизлайков с анимацией
function toggleLike(commentId, isLike) {
    const likeBtn = document.getElementById(`like-btn-${commentId}`);
    const dislikeBtn = document.getElementById(`dislike-btn-${commentId}`);
    
    // Добавляем эффект нажатия
    const btn = isLike ? likeBtn : dislikeBtn;
    btn.classList.add('scale-110');
    setTimeout(() => btn.classList.remove('scale-110'), 200);

    // Отправляем запрос
    fetch(`/comments/${commentId}/toggle-like`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ is_like: isLike })
    })
    .then(response => response.json())
    .then(data => {
        // Обновляем счетчики
        document.getElementById(`likes-count-${commentId}`).textContent = data.likes_count;
        document.getElementById(`dislikes-count-${commentId}`).textContent = data.dislikes_count;
        
        // Обновляем стили кнопок
        if (data.user_reaction === 'like') {
            likeBtn.classList.add('text-blue-500');
            likeBtn.classList.remove('text-gray-500');
            dislikeBtn.classList.remove('text-red-500');
            dislikeBtn.classList.add('text-gray-500');
        } else if (data.user_reaction === 'dislike') {
            likeBtn.classList.remove('text-blue-500');
            likeBtn.classList.add('text-gray-500');
            dislikeBtn.classList.add('text-red-500');
            dislikeBtn.classList.remove('text-gray-500');
        } else {
            likeBtn.classList.remove('text-blue-500');
            likeBtn.classList.add('text-gray-500');
            dislikeBtn.classList.remove('text-red-500');
            dislikeBtn.classList.add('text-gray-500');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Показываем уведомление об ошибке
        const notification = document.createElement('div');
        notification.className = 'fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg';
        notification.textContent = 'Произошла ошибка. Попробуйте еще раз.';
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    });
}