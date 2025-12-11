{{-- Приглашение к авторизации --}}
<div class="bg-navy-800 rounded-lg shadow-lg p-6 mb-8 border border-navy-700">
    <p class="text-gray-300">
        Чтобы оставить комментарий, пожалуйста, 
        <a href="{{ route('login') }}" 
           class="text-violet-400 hover:text-violet-300 transition-colors">
            войдите
        </a> 
        или 
        <a href="{{ route('register') }}" 
           class="text-violet-400 hover:text-violet-300 transition-colors">
            зарегистрируйтесь
        </a>.
    </p>
</div>