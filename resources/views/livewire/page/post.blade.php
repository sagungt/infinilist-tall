<div
    id="post-root"
    class="flex flex-col min-h-screen py-12 bg-gray-50"
    @fetch-comment="await getComment(parentId)"
    @fetch-like="await getLike(parentId)"
    @fetch-favorite="await getFavorite(parentId)"
    x-data="showPost"
    x-init="await getPost()" >
    <template x-if="!loading">
        <div>
            <div class="w-full">
                <img :src="post.cover.path" class="w-full h-[600px] object-cover" :alt="post.title">
            </div>
        
            <div class="flex justify-center flex-col md:flex-row xl:flex-row container my-10 mx-auto w-full">
                <div class="w-full md:w-[10%] xl:w-[10%] block justify-between pt-10">
                    <div class="mb-4 flex justify-around flex-row md:flex-col xl:flex-col gap-y-5 items-center static md:sticky lg:sticky top-20">
                        <button x-on:click="toggleLikePost(parentId)" type="submit" class="flex flex-col items-center hover:bg-slate-300 px-4 rounded-lg py-2" @auth x-bind:class="likes.some((l) => l.user_id === @js(auth()->user()->id)) ? 'bg-slate-200' : 'bg-slate-50'" @endauth>
                            <span class="text-4xl">💖</span>
                            <span class="text-md" x-text="likes.length"></span>
                        </button>
                        <button class="flex flex-col items-center hover:bg-slate-300 px-4 rounded-lg py-2">
                            <span class="text-4xl">👀</span>
                            <span class="text-md" x-text="post.view_count"></span>
                        </button>
                        <a href="#comments" class="flex flex-col items-center hover:bg-slate-300 px-4 rounded-lg py-2">
                            <span class="text-4xl">💬</span>
                            <span class="text-md" x-text="comments.length"></span>
                        </a>
                        <button x-on:click="toggleFavoritePost(parentId)" class="flex flex-col items-center hover:bg-slate-300 px-4 rounded-lg py-2" @auth x-bind:class="favorites.some((f) => f.user_id === @js(auth()->user()->id)) ? 'bg-slate-200' : 'bg-slate-50'" @endauth>
                            <span class="text-4xl">📃</span>
                            <span class="text-md" x-text="favorites.length"></span>
                        </button>
                    </div>
                </div>
                <div class="w-full mx-auto px-8 bg-white rounded-lg border border-slate-200">
                    <div class="flex flex-row gap-x-2 py-10">
                        <template x-if="post.owner.profile_url === null">
                            <div class="w-10 h-10 rounded-full bg-slate-900 uppercase text-white flex items-center justify-center" x-text="post.owner.name[0]"></div>
                        </template>
                        <template x-if="post.owner.profile_url !== null">
                            <img x-bind:src="post.owner.profile_url.path" x-bind:alt="post.owner.name" class="w-10 h-10 rounded-full" />
                        </template>
                        <div class="flex flex-col justify-center">
                            <div class="text-sm font-bold">
                                <a href="#" x-text="post.owner.name"></a>
                            </div>
                            <span class="text-xs text-slate-500" x-text="(new Date(post.updated_at)).toDateString()"></span>
                        </div>
                    </div>
                    <h1 class="text-5xl font-extrabold my-4" x-text="post.title"></h1>
                    <template x-if="Number(post.chapter_id) > 0">
                        <a x-bind:href="'http://localhost:8000/series/'+post.series.slug" class="text-white w-fit font-bold text-xl flex flex-row items-center gap-x-2">
                            🔗 <h2 class="rounded-lg bg-slate-900 px-2 py-1 text-sm" x-text="`#${post.chapter_order} ${post.series.name}`"></h2>
                        </a>
                    </template>
                    <div class="space-x-2 text-sm py-10">
                        <template x-for="tag in post.tags">
                            <span x-text="tag.name"></span>
                        </template>
                    </div>
                    <div class="no-tailwindcss-base" x-html="post.content">
                        
                    </div>
                    <div class="flex flex-col mt-10" id="comments">
                        <h2 class="text-3xl font-bold">
                            Comments
                        </h2>
                        @if (session('success'))
                            <div class="bg-teal-100 rounded-md p-4">
                                <span class="text-teal-500 text-sm">{{ session('success') }}</span>
                            </div>
                        @endif
                        <div x-show="messageSuccess">
                            <div id="success-container" @show-success="messageSuccess = true" class="bg-teal-100 rounded-md p-4 flex justify-between items-center">
                                <span class="text-teal-500 text-sm">Comment posted</span>
                                <button class="p-1 text-teal-900 rotate-45" x-on:click="messageSuccess = false">+</button>
                            </div>
                        </div>
                        <div x-show="messageError">
                            <div id="error-container" @show-error="messageError = true" class="bg-red-100 rounded-md p-4 flex justify-between items-center">
                                <span class="text-red-500 text-sm">Comment posted</span>
                                <button class="p-1 text-red-900 rotate-45" x-on:click="messageError = false">+</button>
                            </div>
                        </div>

                        @auth
                            <div class="flex flex-row gap-x-5 py-10">
                                <template x-if="post.owner.profile_url === null">
                                    <div class="w-10 h-10 rounded-full bg-slate-900"></div>
                                </template>
                                <template x-if="post.owner.profile_url !== null">
                                    <img x-bind:src="post.owner.profile_url.path" x-bind:alt="post.owner.name" class="w-10 h-10 rounded-full" />
                                </template>
                                <div class="flex flex-col justify-center w-full flex-1" x-data="{ el: null, id: 0 }">
                                    <textarea x-init="el = $el" name="content" id="message-0" rows="4" class="block p-2.5 w-full max-w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Write your comments here..."></textarea>
                        
                                    <button x-on:click="postComment(parentId, id, el)" data-id="0" type="button" class="w-full md:w-fit lg:w-fit post-comment my-4 py-2 px-4 rounded-lg bg-slate-700 text-white">Post 🚀</button>
                                </div>
                            </div>
                        @endauth
                        <div class="flex flex-col w-full">
                            <template x-for="comment in comments">
                                <div class="flex flex-row w-auto py-5 gap-x-5" {{-- x-bind:class="Number(comment.parent_comment_id) > 0 ? 'ml-14' : ''" --}} x-data="{ showReply: false }">
                                    <template x-if="comment.owner.profile_url === null">
                                        <div class="w-10 h-10 rounded-full bg-slate-900"></div>
                                    </template>
                                    <template x-if="comment.owner.profile_url !== null">
                                        <img x-bind:src="comment.owner.profile_url.path" x-bind:alt="comment.owner.name" class="w-10 h-10 rounded-full" />
                                    </template>
                                    <div class="flex flex-col gap-2 w-full flex-1">
                                        <div class="flex flex-col w-full">
                                            <div class="w-full p-5 bg-white rounded-lg border border-slate-200 flex flex-col">
                                                <div class="flex flex-row justify-between">
                                                    <span class="text-xs italic" x-text="moment(comment.updated_at).fromNow() + (comment.is_edited ? ' Edited' : '') + (comment.is_pinned ? ' 📌 Pinned' : '')"></span>
                                                    <template x-if="Number(comment.parent_comment_id) > 0">
                                                        <span class="text-xs px-2 py-1 border border-slate-200 rounded-full" x-text="'Reply => ' + comments.find((c) => c.id === comment.parent_comment_id).content.substring(0, 10) + '...'"></span>
                                                    </template>
                                                </div>
                                                <p x-text="comment.content"></p>
                                            </div>
                                        </div>
                                        <div class="flex flex-row gap-x-2">
                                            <button x-on:click="toggleLikeComment(comment.id)">
                                                <span
                                                    class="px-2 py-1 rounded-lg text-xs"
                                                    @auth
                                                        x-bind:class="comment.likes.some((like) => like.user_id ===@js(auth()->user()->id)) ? 'bg-slate-200' : 'bg-white'"
                                                    @endauth>💖 
                                                    <span x-text="comment.likes.length"></span>
                                                </span>
                                            </button>
                                            <button x-on:click="showReply = !showReply">
                                                <span>💬 <span class="text-xs">Reply</span></span>
                                            </button>
                                            @auth
                                                <template x-if="post.owner.id === @js(auth()->user()->id)">
                                                    <button x-on:click="togglePinComment(comment.id)">
                                                        <span>📌 <span class="text-xs" x-text="comment.is_pinned ? 'Pinned' : 'Pin'"></span></span>
                                                    </button>
                                                </template>
                                            @endauth
                                        </div>
                                        <div>
                                            <template x-if="showReply">
                                                <div class="flex flex-col justify-center w-full flex-1" x-data="{ el: null, id: comment.id }">
                                                    <textarea x-init="el = $el" name="content" id="message-0" rows="4" class="block p-2.5 w-full max-w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Write your reply here..."></textarea>
                                        
                                                    <button x-on:click="postComment(parentId, id, el)" data-id="0" type="button" class="post-comment my-4 py-2 px-4 rounded-lg bg-slate-700 text-white w-full md:w-fit lg:w-fit">Post 🚀</button>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <template x-if="comments.length === 0">
                                <h3 class="text-xl italic text-center">No comment yet :(</h3>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
    <template x-if="notFound">
        <div class="flex w-full h-screen justify-center items-center">
            <h1 class="text-5xl font-bold text-center">404 - Post not found</h1>
        </div>
    </template>
</div>

@push('scripts')
    <script>
        const slug = '{{ $slug }}';
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const api = {
            messageSuccess: false,
            messageError: false,
            messageContent: '',
            notFound: false,
            async getPost() {
                const res = await fetch(`http://localhost:8000/api/posts/${slug}`);
                const { data: post, message } = await res.json();
                if (message === 'Post not found') {
                    this.notFound = true;
                    return;
                }
                this.post = post;
                this.parentId = post.id;
                await this.getComment(post.id);
                await this.getLike(post.id);
                await this.getFavorite(post.id);
                this.loading = false;
            },
            async getComment(id) {
                const res = await fetch(`http://localhost:8000/api/comments/post/${id}`);
                const { data: comments } = await res.json();
                console.log(comments);
                this.comments = comments;
            },
            async getLike(id) {
                const res = await fetch(`http://localhost:8000/api/likes/post/${id}`);
                const { data: likes } = await res.json();
                console.log(likes);
                this.likes = likes;
            },
            async getFavorite(id) {
                const res = await fetch(`http://localhost:8000/api/favorites/post/${id}`);
                const { data: favorites } = await res.json();
                console.log(favorites);
                this.favorites = favorites;
            },
        }
        document.addEventListener('livewire:load', function () {
            Alpine.data('showPost', () => ({
                loading: true,
                commentId: 0,
                parentId: 0,
                commentContent: '',
                post: [],
                comments: [],
                likes: [],
                favorites: [],
                ...api,
            }));
            Alpine.data('showComment', () => ({

            }));
        });
        async function postComment(parentId, commentId, content) {
            if (content.value === '') return;
            const fd = new FormData();
            if (Number(commentId) > 0) fd.append('parent_comment_id', commentId);
            fd.append('content', content.value);
            const res = await fetch(`http://localhost:8000/api/comments/post/${parentId}`, {
                headers: {
                    'X-CSRF-TOKEN': token,
                },
                method: 'post',
                body: fd,
            });
            const { status, data } = await res.json();
            content.value = '';
            if (status) {
                api.messageContent = 'Comment posted';
                document.querySelector('#post-root').dispatchEvent(new CustomEvent('fetch-comment', { detail: {} }));
                document.querySelector('#success-container').dispatchEvent(new CustomEvent('show-success', { detail: {} }));
            } else {
                api.messageContent = 'Failed to post comment';
                document.querySelector('#error-container').dispatchEvent(new CustomEvent('show-error', { detail: {} }));
            }
        }
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
                if (status) {
                    api.messageContent = 'Post liked';
                    document.querySelector('#post-root').dispatchEvent(new CustomEvent('fetch-comment', { detail: {} }));
                    document.querySelector('#success-container').dispatchEvent(new CustomEvent('show-success', { detail: {} }));
                } else {
                    api.messageContent = 'Failed to like comment';
                    document.querySelector('#error-container').dispatchEvent(new CustomEvent('show-error', { detail: {} }));
                }
            } catch (e) {
                console.error(e);
            }
        }
        async function toggleLikeComment(commentId) {
            const res = await fetch(`http://localhost:8000/api/likes/comment/${commentId}`, {
                headers: {
                    'X-CSRF-TOKEN': token,
                },
                method: 'post',
            });
            const { status, data, message } = await res.json();
            if (message === 'unauthorized') return window.location.href = 'http://localhost:8000/auth/login';
            if (status) {
                api.messageContent = 'Comment liked';
                document.querySelector('#post-root').dispatchEvent(new CustomEvent('fetch-comment', { detail: {} }));
                document.querySelector('#success-container').dispatchEvent(new CustomEvent('show-success', { detail: {} }));
            } else {
                api.messageContent = 'Failed to like comment';
                document.querySelector('#error-container').dispatchEvent(new CustomEvent('show-error', { detail: {} }));
            }
        }
        async function togglePinComment(commentId) {
            const res = await fetch(`http://localhost:8000/api/comments/${commentId}/pin`, {
                headers: {
                    'X-CSRF-TOKEN': token,
                },
                method: 'post',
            });
            const { status, data } = await res.json();
            if (status) {
                api.messageContent = 'Comment pinned';
                document.querySelector('#post-root').dispatchEvent(new CustomEvent('fetch-comment', { detail: {} }));
                document.querySelector('#success-container').dispatchEvent(new CustomEvent('show-success', { detail: {} }));
            } else {
                api.messageContent = 'Failed to like comment';
                document.querySelector('#error-container').dispatchEvent(new CustomEvent('show-error', { detail: {} }));
            }
        }
        async function toggleFavoritePost(postId) {
            const res = await fetch(`http://localhost:8000/api/favorites/post/${postId}`, {
                headers: {
                    'X-CSRF-TOKEN': token,
                },
                method: 'post',
            });
            const { status, data, message } = await res.json();
            if (message === 'unauthorized') window.location.href = 'http://localhost:8000/auth/login';
            if (status) {
                api.messageContent = 'Comment liked';
                document.querySelector('#post-root').dispatchEvent(new CustomEvent('fetch-favorite', { detail: {} }));
                document.querySelector('#success-container').dispatchEvent(new CustomEvent('show-success', { detail: {} }));
            } else {
                api.messageContent = 'Failed to like comment';
                document.querySelector('#error-container').dispatchEvent(new CustomEvent('show-error', { detail: {} }));
            }
        }
    </script>
@endpush