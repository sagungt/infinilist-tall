<div class="w-full border border-slate-500 bg-white rounded-lg">
    <a href="{{ route('post.show', ['slug' => $post['slug']]) }}">
        <img class="rounded-t-lg w-full h-80 object-cover" src="{{ $post['cover']['path'] }}" alt="{{ $post['title'] }}" />
    </a>
    <div class="flex flex-col p-4">
        <livewire:cards.owner :owner="$post['owner']" :created_at="$post['created_at']">
        <div class="flex flex-col p-4 gap-y-3">
            <a href="{{ route('post.show', ['slug' => $post['slug']]) }}">
                <h2 class="text-2xl text-slate-900 font-bold">
                    {{ $post['title'] }}
                </h2>
            </a>
            @if (intval($post['chapter_id']) != 0)
                <a href="{{ route('series.show', ['slug' => $post['series']['slug']]) }}" class="text-white w-fit font-bold text-xl flex flex-row items-center gap-x-2">
                    🔗 <h3 class="rounded-lg bg-slate-900 px-2 py-1 text-sm"><span>#{{ $post['chapter_order'] }}</span> {{ $post['series']['name'] }}</h3>
                </a>
            @endif
            <div class="space-x-2 text-xs">
                @foreach ($post['tags'] as $tag)
                    <span>{{ $tag['name'] }}</span>
                @endforeach
            </div>
        </div>
        <div class="flex flex-row justify-between">
            <div class="space-x-2 flex">
                <button x-on:click="toggleLikePost(@js($post['id']))" class="flex items-center px-1 py-0.5 hover:bg-slate-300 rounded-lg @auth {{ collect($post['likes'])->contains(fn ($like) => $like['user_id'] === auth()->user()->id) ? 'bg-slate-200' : 'bg-white' }} @endauth">
                    <span>💖 <span class="text-xs">{{ count($post['likes']) }}</span></span>
                </button>
                <button class="flex items-center px-1 py-0.5 hover:bg-slate-300 rounded-lg">
                    <span>👀 <span class="text-xs">{{ $post['view_count'] }}</span></span>
                </button>
                <a href="{{ route('post.show', ['slug' => $post['slug']]) . '#comments' }}" class="flex items-center px-1 py-0.5 hover:bg-slate-300 rounded-lg @auth {{ collect($post['comments'])->contains(fn ($like) => $like['user_id'] === auth()->user()->id) ? 'bg-slate-200' : 'bg-white' }} @endauth">
                    <span>💬 <span class="text-xs">{{ count($post['comments']) }}</span></span>
                </a>
            </div>
            <div>
                <button x-on:click="toggleFavoritePost(@js($post['id']))" class="flex items-center px-2 py-0.5 hover:bg-slate-300 rounded-lg @auth {{ collect($post['favorites'])->contains(fn ($like) => $like['user_id'] === auth()->user()->id) ? 'bg-slate-200' : 'bg-white' }} @endauth">
                    <span>📃 <span class="text-xs">{{ count($post['favorites']) }}</span></span>
                </button>
            </div>
        </div>
    </div>
</div>
