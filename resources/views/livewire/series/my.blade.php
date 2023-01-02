<div class="pt-20 min-h-screen">
    <div class="mx-auto gap-y-5 flex flex-col w-full px-2 md:w-[80%] lg:w-2/3 justify-center mb-10">
        <h1 class="font-extrabold text-5xl">My Series</h1>
        @if (session('success'))
            <div class="bg-teal-100 rounded-md p-4">
                <span class="text-teal-500 text-sm">{{ session('success') }}</span>
            </div>
        @endif
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
                <div class="flex flex-row md:flex-col lg:flex-col gap-x-2 gap-y-16 mt-6">
                    <a href="{{ route('series.edit', ['slug' => $s['slug'], 'kind' => 'series']) }}" class="text-center rotate-0 md:rotate-90 lg:rotate-90 rounded-lg font-bold px-4 py-2 bg-slate-900 text-white hover:bg-slate-800 transition-all">Edit</a>
                    <form action="{{ route('series.destroy', ['kind' => 'post', 'slug' => $s['slug']]) }}" method="post">
                        @csrf
                        <button class="rotate-0 md:rotate-90 lg:rotate-90 rounded-lg font-bold px-4 py-2 bg-slate-900 text-white hover:bg-slate-800 transition-all">Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <h2 class="text-center italic text-4xl font-bold">No Post yet :(</h2>
        @endforelse
    </div>
</div>

@push('scripts')
    <script>
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        document.addEventListener('livewire:load', function () {
            Alpine.data('data', () => ({}));
        })
        async function toggleLikeSeries(seriesId) {
            console.log(seriesId);
            // try {
            //     const res = await fetch(`http://localhost:8000/api/likes/series/${seriesId}`, {
            //         headers: {
            //             'X-CSRF-TOKEN': token,
            //         },
            //         method: 'post',
            //     });
            //     const { status, data, message } = await res.json();
            //     if (message === 'unauthorized') return window.location.href = 'http://localhost:8000/auth/login';
            //     window.location.href = '';
            // } catch (e) {
            //     console.error(e);
            // }
        }
        async function toggleFavoriteSeries(seriesId) {
            try {
                const res = await fetch(`http://localhost:8000/api/favorites/series/${seriesId}`, {
                    headers: {
                        'X-CSRF-TOKEN': token,
                    },
                    method: 'post',
                });
                const { status, data, message } = await res.json();
                if (message === 'unauthorized') return window.location.href = 'http://localhost:8000/auth/login';
                window.location.href = '';
            } catch (e) {
                console.error(e);
            }
        }
    </script>
@endpush