{{-- Форма создания нового комментария --}}
<div class="bg-navy-800 rounded-lg shadow-lg p-6 mb-8 border border-navy-700">
    <h2 class="text-xl font-semibold text-white mb-4">Написать комментарий</h2>

    @if(session('error'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-red-700 text-white border border-red-500 animate-pulse">
            <span class="font-semibold">Ошибка:</span> {{ session('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="mb-4 px-4 py-3 rounded-lg bg-red-700 text-white border border-red-500 animate-pulse">
            <span class="font-semibold">Ошибка:</span>
            <ul class="list-disc ml-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('comments.store') }}" 
          method="POST" 
          class="space-y-4">
        @csrf
        <input type="hidden" name="post_id" value="{{ $post->id }}">

        <div>
            <label for="content" class="sr-only">Комментарий</label>
            <textarea id="content"
                      name="content" 
                      rows="3" 
                      class="w-full px-4 py-2 bg-navy-950 border border-navy-700 rounded-lg text-gray-100 placeholder-gray-500 focus:outline-none focus:border-violet-500 transition-colors"
                      placeholder="Ваш комментарий..."
                      maxlength="1000"
                      required></textarea>
        </div>
        
        <div>
            <label for="gif_url" class="sr-only">GIF URL</label>
            <input id="gif_url"
                   type="url" 
                   name="gif_url" 
                   class="w-full px-4 py-2 bg-navy-950 border border-navy-700 rounded-lg text-gray-100 placeholder-gray-500 focus:outline-none focus:border-violet-500 transition-colors"
                   placeholder="URL GIF-изображения (необязательно)">
        </div>
        
        <button type="submit" 
                class="px-6 py-2 bg-violet-600 text-white rounded-lg hover:bg-violet-700 focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 focus:ring-offset-navy-800 transition-all">
            Отправить
        </button>
    </form>
</div>