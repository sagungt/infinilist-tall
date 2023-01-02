<div x-data="allPosts" x-init="getPosts()">
    <div x-show="!loading" class="flex flex-col gap-y-5">
        @forelse ($posts as $post)
            <livewire:cards.card :post="$post">
        @empty
            <h2 class="text-4xl font-bold text-center my-10">No Post Yet :(</h2>
        @endforelse
    </div>
    <div x-show="loading" class="text-xl font-light italic text-center">
        loading...
    </div>
</div>

@push('scripts')
    <script>
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        document.addEventListener('livewire:load', function () {
            Alpine.data('allPosts', () => ({
                loading: true,
                posts: @entangle('posts'),
                async getPosts() {
                    const res = await axios.get('/api/posts');
                    const { data: posts } = res.data;
                    this.posts = posts;
                    this.loading = false;
                }
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
