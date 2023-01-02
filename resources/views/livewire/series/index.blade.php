<div class="pt-20 min-h-screen">
    <div class="mx-auto gap-y-5 flex flex-col w-full px-2 md:w-[80%] lg:w-2/3 justify-center mb-10">
        @forelse ($series as $s)
            <div class="flex flex-col-reverse md:flex-row lg:flex-row gap-y-2 items-end md:items-start lg:items-start">
                <div class="w-full border border-slate-500 bg-white rounded-lg">
                    <div class="flex flex-col p-4">
                        <livewire:cards.owner :owner="$s['owner']" :created_at="$s['created_at']">
                        <div class="flex flex-col p-4 gap-y-3">
                            <a href="{{ route('series.show', ['slug' => $s['slug']]) }}">
                                <h2 class="text-2xl text-slate-900 font-bold">
                                    {{ $s['name'] }}
                                </h2>
                            </a>
                        </div>
                        <div class="p-4">
                            <p>
                                {{ $s['description'] }}
                            </p>
                        </div>
                        <div class="flex flex-row justify-between" x-data="{ id: @js($s['id']) }">
                            <div class="space-x-2 flex">
                                <button {{-- x-on:click="toggleLikeSeries(id)" --}} class="cursor-default flex items-center px-1 py-0.5 hover:bg-slate-300 rounded-lg @auth {{ collect($s['likes'])->contains(fn ($like) => $like['user_id'] === auth()->user()->id) ? 'bg-slate-200' : 'bg-white' }} @endauth">
                                    <span>💖 <span class="text-xs">{{ count($s['likes']) }}</span></span>
                                </button>
                                <a href="{{ route('post.show', ['slug' => $s['slug']]) . '#comments' }}" class="cursor-default flex items-center px-1 py-0.5 hover:bg-slate-300 rounded-lg @auth {{ collect($s['comments'])->contains(fn ($like) => $like['user_id'] === auth()->user()->id) ? 'bg-slate-200' : 'bg-white' }} @endauth">
                                    <span>💬 <span class="text-xs">{{ count($s['comments']) }}</span></span>
                                </a>
                            </div>
                            <div>
                                <button {{-- x-on:click="toggleFavoriteSeries(id)" --}} class="cursor-default flex items-center px-2 py-0.5 hover:bg-slate-300 rounded-lg @auth {{ collect($s['favorites'])->contains(fn ($like) => $like['user_id'] === auth()->user()->id) ? 'bg-slate-200' : 'bg-white' }} @endauth">
                                    <span>📃 <span class="text-xs">{{ count($s['favorites']) }}</span></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <h2 class="text-center italic text-4xl font-bold">No Post yet :(</h2>
        @endforelse
    </div>
</div>
