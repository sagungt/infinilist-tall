<div class="flex flex-col min-h-screen py-12 bg-gray-50">
    <div class="container mx-auto w-full md:w-[80%] lg:w-1/2 mt-10">
        <a href="{{ route('shortener.list') }}" class="text-sm px-2 py-1 border border-slate-500 rounded-full font-bold">Back</a>

        <h1 class="text-4xl font-extrabold mb-10 mt-5">Edit Shortener</h1>

        @if (session('success'))
            <div class="bg-teal-100 rounded-md p-4">
                <span class="text-teal-500 text-sm">{{ session('success') }}</span>
            </div>
        @endif

        <div class="flex flex-col">
            <form action="{{ route('shortener.update', ['id' => $shortener['id']]) }}" method="post" class="w-full" x-data="data">
                @csrf

                <div class="flex flex-col items-start mb-6" x-data="{ url: @js($shortener['alias']) }">

                    <label for="url" class="block mb-2 text-sm font-medium text-gray-900">Url</label>

                    <div class="relative w-full">
                        <input name="url" x-model="url" type="text" id="url" class="block p-2.5 w-full z-20 text-sm text-gray-900 bg-white rounded-r-lg border-l-gray-100 border-l-2 border border-gray-300 focus:ring-blue-500 focus:border-blue-500 rounded-tl-lg rounded-bl-lg" placeholder="Custom URL">

                        <button type="button" class="absolute top-0 right-0 p-2.5 text-sm font-medium text-white bg-blue-700 rounded-r-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300" x-on:click="url = rand(5)">Random</button>
                    </div>

                    <div class="mt-1 text-sm text-gray-500" id="preview_url" x-text="'{{ url('/') }}/s/' + url"></div>

                    @error('url')
                        <span class="text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-6" x-data="{ selectedType: @js(strtolower($shortener['kind'])) }" x-init="getList(selectedType)" x-effect="getList(selectedType)">
                    <input type="hidden" x-bind:value="selectedType" name="kind">
                    <div class="flex">
                        <button id="states-button" data-dropdown-toggle="dropdown-states" class="uppercase flex-shrink-0 z-10 inline-flex items-center py-2.5 px-4 text-sm font-medium text-center text-gray-500 bg-gray-100 border border-gray-300 rounded-l-lg hover:bg-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100" type="button" x-text="selectedType">
                        </button>
                        <div id="dropdown-states" class="z-10 hidden bg-white divide-y divide-gray-100 rounded shadow w-44">
                            <ul class="py-1 text-sm text-gray-700" aria-labelledby="states-button">
                                <li>
                                    <button x-on:click="selectedType = 'post'" type="button" class="inline-flex w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <div class="inline-flex items-center">
                                            Post
                                        </div>
                                    </button>
                                </li>
                                <li>
                                    <button x-on:click="selectedType = 'series'" type="button" class="inline-flex w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <div class="inline-flex items-center">
                                            Series
                                        </div>
                                    </button>
                                </li>
                            </ul>
                        </div>
                        <label for="states" class="sr-only">Choose a target</label>
                        <select id="states" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-r-lg border-l-gray-100 dark:border-l-gray-700 border-l-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" name="parent_id">
                            <option selected>Choose a target</option>
                            <template x-for="item in data">
                                <option x-bind:selected="item.id === @js($shortener['parent_id'])" x-bind:value="item.id" x-text="selectedType === 'post' ? item.title : item.name"></option>
                            </template>
                        </select>
                    </div>

                    @error('error')
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
        function rand(length) {
            let result = '';
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_';
            const charactersLength = characters.length;
            for (let i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            return result;
        }
        document.addEventListener('livewire:load', function () {
            Alpine.data('data', () => ({
                data: [],
                async getList(type) {
                    const url = type === 'post'
                        ? 'http://localhost:8000/api/posts/my/all'
                        : 'http://localhost:8000/api/series/my';
                    const res = await fetch(url);
                    const { data, message } = await res.json();
                    if (message === 'unauthorized') return window.location.href = 'http://localhost:8000/auth/login';
                    this.data = data;
                },
            }));
        });
    </script>
@endpush