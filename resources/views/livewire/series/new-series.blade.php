<div class="flex flex-col min-h-screen py-12 bg-gray-50">
    <div class="container mx-auto w-full md:w-[80%] lg:w-2/3">

        <h1 class="text-4xl font-extrabold my-10">Create New Series</h1>

        @if (session('success'))
            <div class="bg-teal-100 rounded-md p-4">
                <span class="text-teal-500 text-sm">{{ session('success') }}</span>
            </div>
        @endif

        <div class="flex flex-col">
            <form action="{{ route('series.store', ['kind' => 'post']) }}" method="post" class="w-full" x-data="postFields">
                @csrf

                <div class="mb-6">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Name</label>
                    <input type="text" id="name" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 bg-white" placeholder="Series name" name="name" value="{{ old('name') }}">

                    @error('name')
                        <span class="text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Description</label>
                    <textarea id="description" rows="4" class="block p-2.5 w-full max-w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 no-tailwindcss-base" placeholder="Series description here..." name="description">{{ old('description') }}</textarea>

                    @error('description')
                        <span class="text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div id="posts" x-data="postFields" x-init="posts = await getPosts()">

                </div>

                <div class="mb-6">
                    @error('posts')
                        <span class="text-xs text-red-600">{{ $message }}</span>
                    @enderror
                    <button x-on:click="add()" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">+ Add</button>
                </div>

                <div class="flex flex-col items-start mb-6" x-data="{ active: false, url: '' }">
                    <div class="flex">
                        <div class="flex items-center h-5">
                            <input id="shorten" type="checkbox" x-model="active" value="" class="w-4 h-4 bg-gray-50 rounded border border-gray-300 focus:ring-3 focus:ring-blue-300" x-on:click="active = !active" name="shorten">
                        </div>

                        <label for="shorten" class="ml-2 text-sm font-medium text-gray-900">Shorten</label>
                    </div>

                    <template x-if="active">
                        <div class="w-full mt-5" x-show="active" x-cloak>
                            <label for="url" class="block mb-2 text-sm font-medium text-gray-900">Url</label>
    
                            <div class="relative w-full">
    
                                <input x-model="url" type="text" id="url" class="block p-2.5 w-full z-20 text-sm text-gray-900 bg-white rounded-r-lg border-l-gray-100 border-l-2 border border-gray-300 focus:ring-blue-500 focus:border-blue-500 rounded-tl-lg rounded-bl-lg" placeholder="Custom URL" name="url">
    
                                <button type="button" class="absolute top-0 right-0 p-2.5 text-sm font-medium text-white bg-blue-700 rounded-r-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300" x-on:click="url = rand(5)">Random</button>
    
                            </div>
                            
                            <div class="mt-1 text-sm text-gray-500" id="preview_url" x-text="'{{ url('/') }}/s/' + url"></div>
    
                            @error('title')
                                <span class="text-xs text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </template>
                </div>

                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Publish</button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
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
                posts: @entangle('posts'),
                async getPosts() {
                    const res = await fetch('http://localhost:8000/api/posts/my');
                    const { data: posts } = await res.json();
                    return posts;
                }
            }));
            Alpine.data('postFields', () => ({
                counter: 0,
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
                            <button :disabled="index !== counter" :class="index !== counter ? 'opacity-60' : ''" x-on:click="remove(el)" type="button" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-auto px-5 py-2.5 text-center"> - </button>
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