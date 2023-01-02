<div class="pt-20 min-h-screen">
    <div class="mx-auto gap-y-5 flex flex-col w-full px-2 md:w-[80%] lg:w-2/3 justify-center mb-5">
        <h1 class="font-extrabold text-5xl">My Favorites ({{ sizeof($favorites) }})</h1>
        @if (session('success'))
            <div class="bg-teal-100 rounded-md p-4">
                <span class="text-teal-500 text-sm">{{ session('success') }}</span>
            </div>
        @endif
        @forelse ($favorites as $favorite)
            @if ($favorite['kind'] == 'POST')
                <div class="flex flex-col-reverse md:flex-row lg:flex-row gap-y-2 items-end md:items-start lg:items-start">
                    <livewire:cards.card :post="$favorite['post']">
                    <div x-data="api" class="flex flex-row md:flex-col lg:flex-col gap-x-2 gap-y-16 mt-6">
                        <button x-on:click="toggleFavoritePost(@js($favorite['post']['id']))" class="rotate-0 md:rotate-90 lg:rotate-90 rounded-lg font-bold px-4 py-2 bg-slate-200 hover:bg-slate-300 transition-all">Remove</button>
                    </div>
                </div>
            @endif
        @empty
            <h2 class="text-center italic text-4xl font-bold">No Favorite yet :(</h2>
        @endforelse
    </div>
</div>

@push('scripts')
    <script>
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        document.addEventListener('livewire:load', function () {
            Alpine.data('api', () => ({
                async toggleFavoritePost(postId) {
                    const res = await fetch(`http://localhost:8000/api/favorites/post/${postId}`, {
                        headers: {
                            'X-CSRF-TOKEN': token,
                        },
                        method: 'post',
                    });
                    const { status, data, message } = await res.json();
                    window.location.href = '';
                },
            }));
        });
        async function toggleLikePost(postId) {
            try {
                const res = await fetch(`http://localhost:8000/api/likes/post/${postId}`, {
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
        async function toggleFavoritePost(postId) {
            try {
                const res = await fetch(`http://localhost:8000/api/favorites/post/${postId}`, {
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
