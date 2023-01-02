<div class="flex flex-col min-h-screen py-12 bg-gray-50">
    <div class="container mx-auto mt-8 w-full md:w-[80%] lg:w-2/3">
        <a href="{{ route('profile.series.list') }}" class="text-sm px-2 py-1 border border-slate-500 rounded-full font-bold">Back</a>
        <h1 class="text-4xl font-extrabold mt-5 mb-10">Edit Series</h1>

        @if (session('success'))
            <div class="bg-teal-100 rounded-md p-4">
                <span class="text-teal-500 text-sm">{{ session('success') }}</span>
            </div>
        @endif

        <div class="flex flex-col">
            <form action="{{ route('series.update', ['kind' => 'post', 'slug' => $slug]) }}" method="post" class="w-full" x-data="postFields">
                @csrf

                <div class="mb-6">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Name</label>
                    <input type="text" id="name" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 bg-white" placeholder="Series name" name="name" value="{{ old('name', $series['name']) }}">

                    @error('name')
                        <span class="text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Description</label>
                    <textarea id="description" rows="4" class="block p-2.5 w-full max-w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 no-tailwindcss-base" placeholder="Series description here..." name="description">{{ old('description', $series['description']) }}</textarea>

                    @error('description')
                        <span class="text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div x-data="postFields" x-init="posts = await getPosts()">
                    @foreach ($series['posts'] as $index => $post)
                        <div class="mb-6" x-data="{ el: $el, index: @js($index + 1) }">
                            <label for="post-@js($index + 1)" class="block mb-2 text-sm font-medium text-gray-900">Post {{ $index + 1 }}</label>
                            <div class="flex flex-row gap-x-2">
                                <select id="post-@js($index + 1)" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" name="posts[]">
                                    <option selected>Choose a post</option>
                                    <template x-for="post in posts">
                                        <option x-bind:value="post.id" x-bind:selected="@js($post['id']) === post.id" x-text="post.title"></option>
                                    </template>
                                </select>
                                <button {{--x-bind:disabled="index !== counter" x-bind:class="index !== counter ? 'opacity-60' : ''" --}} x-on:click="remove(el)" type="button" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-auto px-5 py-2.5 text-center"> - </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div id="posts" x-data="postFields" x-init="posts = await getPosts()">

                </div>

                <div class="mb-6 flex flex-col ">
                    <button x-on:click="add()" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center w-full md:w-fit lg:w-fit">+ Add</button>
                    @error('posts')
                        <span class="text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Publish</button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        var myEditor;
        document.addEventListener('DOMContentLoaded', function() {
            ClassicEditor
                .create(document.querySelector('#description'))
                .then((editor) => {
                    myEditor = editor;
                })
                .catch(error => {
                    console.error(error);
                });
        });
        function randomUrl() {
            const url = rand(5);
            document.querySelector('#url').value = url;
        }
        function rand(length) {
            let result = '';
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_';
            const charactersLength = characters.length;
            for (let i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            return result;
        }
        async function getPosts() {
            const res = await fetch('http://localhost:8000/api/posts/my');
            const { data: posts } = await res.json();
            return posts;
        }
        document.addEventListener('alpine:init', () => {
            Alpine.store('posts', () => ({
                posts: [],
                async getPosts() {
                    const res = await fetch('http://localhost:8000/api/posts/my');
                    const { data: posts } = await res.json();
                    return posts;
                }
            }));
            Alpine.data('postFields', () => ({
                counter: @js(sizeof($series['posts'])),
                posts: [],
                async getPosts() {
                    const res = await fetch('http://localhost:8000/api/posts/my');
                    const { data: posts } = await res.json();
                    return posts;
                },
                async add() {
                    if (this.posts.length === 0) {
                        this.posts = await getPosts();
                    }
                    if (this.counter >= this.posts.length) return;
                    this.counter += 1;
                    const postsElement = document.querySelector('#posts');
                    const options = this.posts.map((post) => {
                        return `<option value="${post.id}">${post.title}</option>`;
                    })
                    const element = `
                    <div class="mb-6" x-data="{ el: $el, index: ${this.counter} }">
                        <label for="post-${this.counter}" class="block mb-2 text-sm font-medium text-gray-900">Post ${this.counter}</label>
                        <div class="flex flex-row gap-x-2">
                            <select id="post-${this.counter}" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" name="posts[]">
                                <option selected>Choose a post</option>
                                ${options}
                            </select>
                            <button x-on:click="remove(el)" type="button" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-auto px-5 py-2.5 text-center"> - </button>
                        </div>
                    </div>
                    `;
                    const temp = document.createElement('div');
                    postsElement.innerHTML += element;
                },
                remove(el) {
                    this.counter -= 1;
                    el.remove();
                },
            }));
        });
    </script>
@endpush