<div class="pt-20 min-h-screen">
    <div class="mx-auto gap-y-5 flex flex-col w-full px-2 md:w-[80%] lg:w-2/3 justify-center mb-10">
        <h1 class="font-extrabold text-5xl">My Comments</h1>
        @forelse ($comments as $comment)
            <div class="flex flex-col-reverse md:flex-row lg:flex-row gap-y-2 items-end md:items-start lg:items-start">
                <div class="w-full border border-slate-500 bg-white rounded-lg">
                    @if (intval($comment['parent_comment_id']) > 0)
                        <div class="p-4 flex flex-row justify-between">
                            <p class="text-sm font-semibold">You replied a comment => {{ $comment['reply']['content'] }} => at <a class="font-extrabold text-lg" href="{{ route('post.show', ['slug' => $comment['child']['slug']]) }}">{{ $comment['child']['title'] }}</a></p>
                            <span class="text-xs italic text-slate-700">{{ $comment->created_at->format('Y-m-d') }}</span>
                        </div>
                    @else
                        @if ($comment['kind'] == 'POST')
                            <div class="p-4 flex flex-row justify-between">
                                <p class="text-sm font-semibold">You commented a post => <a class="font-extrabold text-lg" href="{{ route('post.show', ['slug' => $comment['child']['slug']]) }}">{{ $comment['child']['title'] }}</a></p>
                                <span class="text-xs italic text-slate-700">{{ $comment->created_at->format('Y-m-d') }}</span>
                            </div>
                        @endif
                        @if ($comment['kind'] == 'SERIES')
                            <div class="p-4 flex flex-row justify-between">
                                <p class="text-sm font-semibold">You commented a series => <a class="font-extrabold text-lg" href="{{ route('post.show', ['slug' => $comment['child']['slug']]) }}">{{ $comment['child']['name'] }}</a></p>
                                <span class="text-xs italic text-slate-700">{{ $comment->created_at->format('Y-m-d') }}</span>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        @empty
            <h2 class="text-center italic text-4xl font-bold">No Post yet :(</h2>
        @endforelse
    </div>
</div>