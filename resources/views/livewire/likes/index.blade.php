<div class="pt-20 min-h-screen">
    <div class="mx-auto gap-y-5 flex flex-col w-full px-2 md:w-[80%] lg:w-2/3 justify-center mb-10">
        <h1 class="font-extrabold text-5xl">My Likes</h1>
        @forelse ($likes as $like)
            <div class="flex flex-col-reverse md:flex-row lg:flex-row gap-y-2 items-end md:items-start lg:items-start">
                <div class="w-full border border-slate-500 bg-white rounded-lg">
                    @if ($like['kind'] == 'POST')
                        <div class="p-4 flex flex-row justify-between">
                            <p class="text-sm font-semibold">You liked a post => <a class="font-extrabold text-lg" href="{{ route('post.show', ['slug' => $like['child']['slug']]) }}">{{ $like['child']['title'] }}</a></p>
                            <span class="text-xs italic text-slate-700">{{ $like->created_at->format('Y-m-d') }}</span>
                        </div>
                    @endif
                    @if ($like['kind'] == 'SERIES')
                        <div class="p-4 flex flex-row justify-between">
                            <p class="text-sm font-semibold">You liked a series => <a class="font-extrabold text-lg" href="{{ route('post.show', ['slug' => $like['child']['slug']]) }}">{{ $like['child']['name'] }}</a></p>
                            <span class="text-xs italic text-slate-700">{{ $like->created_at->format('Y-m-d') }}</span>
                        </div>
                    @endif
                    @if ($like['kind'] == 'COMMENT')
                        <div class="p-4 flex flex-row justify-between">
                            <p class="text-sm font-semibold">You liked a comment => {{ $like['child']['content'] }}</p>
                            <span class="text-xs italic text-slate-700">{{ $like->created_at->format('Y-m-d') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <h2 class="text-center italic text-4xl font-bold">No Post yet :(</h2>
        @endforelse
    </div>
</div>