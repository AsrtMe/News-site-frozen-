{{-- Форма ответа на комментарий --}}
<form action="{{ route('comments.store') }}" 
      method="POST" 
      class="space-y-4">
    @csrf
        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
        <input type="hidden" name="post_id" value="{{ $comment->post_id }}">
    
    <div>
        <label for="reply-content-{{ $comment->id }}" class="sr-only">Ответ</label>
        <textarea id="reply-content-{{ $comment->id }}"
                  name="content" 
                  rows="2" 
                  class="w-full px-4 py-2 bg-navy-900 border border-navy-700 rounded-xl text-navy-100 placeholder-navy-300 focus:outline-none focus:border-violet-500 transition-all"
                  placeholder="Ваш ответ..."
                  maxlength="1000"
                  required></textarea>
    </div>
    
    <div>
        <label for="reply-gif-{{ $comment->id }}" class="sr-only">GIF URL</label>
        <input id="reply-gif-{{ $comment->id }}"
               type="url" 
               name="gif_url" 
               class="w-full px-4 py-2 bg-navy-900 border border-navy-700 rounded-xl text-navy-100 placeholder-navy-300 focus:outline-none focus:border-violet-500 transition-all"
               placeholder="URL GIF-изображения (необязательно)">
    </div>
    
    <button type="submit" 
            class="px-4 py-2 bg-violet-600 text-white rounded-lg hover:bg-violet-700 focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 focus:ring-offset-navy-800 transition-all">
        Ответить
    </button>
</form>