<div x-data="{ categories: dropdownCategories() }" class="flex flex-col min-h-screen py-12 bg-gray-50">
    <div class="container mx-auto w-full md:w-[80%] lg:w-2/3">

        <h1 class="text-4xl font-extrabold my-10">Create New Post</h1>

        @if (session('success'))
            <div class="bg-teal-100 rounded-md p-4">
                <span class="text-teal-500 text-sm">{{ session('success') }}</span>
            </div>
        @endif

        <div class="flex flex-col">

            <form action="{{ route('post.store') }}" method="post" class="w-full" enctype="multipart/form-data">
                @csrf

                <div class="mb-6">
                    <label for="title" class="block mb-2 text-sm font-medium text-gray-900">Title</label>
                    <input x-model="$store.title" type="text" id="title" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 bg-white" placeholder="Post title" name="title" value="{{ old('title') }}">

                    @error('title')
                        <span class="text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-6" x-data="categoryData" x-init="getCategories()">
                    <label for="categories" class="block mb-2 text-sm font-medium text-gray-900">Categories</label>

                    <select id="categories" class="w-full px-4 py-3 text-base text-gray-900 border border-gray-300 rounded-lg bg-white focus:ring-blue-500 focus:border-blue-500 hidden">
                        <option selected>Choose a categories</option>

                        <template x-for="category in categories">
                            <option :value="category.id" x-text="category.name"></option>
                        </template>

                    </select>

                    <div id="hidden-input-categories" class="hidden">

                    </div>

                    <div x-data="dropdownCategories" x-effect="loaded ? loadOptions() : loadOptions()" class="w-full flex flex-col items-center mx-auto">

                        <input name="values" type="hidden" x-bind:value="selectedValues()">
                        
                        <div class="inline-block relative w-full">
                            <div class="flex flex-col items-center relative">

                                <div x-on:click="open" class="w-full">
                                    <div class="my-2 p-1 flex border border-gray-300 bg-white rounded-lg">
                                        <div class="flex flex-auto flex-wrap">
                                            <template x-for="(option, index) in selected" :key="index">
                                                <div class="flex justify-center items-center m-1 font-medium py-1 px-1 bg-white rounde border">
                                                    <div class="text-xs font-normal leading-none max-w-full flex-initial" x-model="options[option]" x-text="options[option].text"></div>
                                                    <div class="flex flex-auto flex-row-reverse">
                                                        <div x-on:click.stop="$store.categories = remove(index, option)">
                                                            <svg class="fill-current h-4 w-4 " role="button" viewBox="0 0 20 20">
                                                            <path d="M14.348,14.849c-0.469,0.469-1.229,0.469-1.697,0L10,11.819l-2.651,3.029c-0.469,0.469-1.229,0.469-1.697,0
                                                                c-0.469-0.469-0.469-1.229,0-1.697l2.758-3.15L5.651,6.849c-0.469-0.469-0.469-1.228,0-1.697s1.228-0.469,1.697,0L10,8.183
                                                                l2.651-3.031c0.469-0.469,1.228-0.469,1.697,0s0.469,1.229,0,1.697l-2.758,3.152l2.758,3.15
                                                                C14.817,13.62,14.817,14.38,14.348,14.849z" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                            <div x-show="selected.length === 0" class="flex-1">
                                                <input placeholder="Select a categories" class="bg-transparent p-1 px-2 appearance-none outline-none h-full w-full text-gray-800 text-sm" x-bind:value="selectedValues()">
                                                @error('categories')
                                                    <span class="text-xs text-red-600">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="text-gray-300 w-8 py-1 pl-2 pr-1 border-l flex items-center border-gray-200">
                                            <button type="button" x-show="isOpen() === true" x-on:click="open" class="cursor-pointer w-6 h-6 text-gray-600 outline-none focus:outline-none">
                                            <svg version="1.1" class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                                <path d="M17.418,6.109c0.272-0.268,0.709-0.268,0.979,0s0.271,0.701,0,0.969l-7.908,7.83
                                                c-0.27,0.268-0.707,0.268-0.979,0l-7.908-7.83c-0.27-0.268-0.27-0.701,0-0.969c0.271-0.268,0.709-0.268,0.979,0L10,13.25 L17.418,6.109z" />
                                            </svg>
                                            </button>
                                            <button type="button" x-show="isOpen() === false" x-on:click="close" class="cursor-pointer w-6 h-6 text-gray-600 outline-none focus:outline-none">
                                            <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                                <path d="M2.582,13.891c-0.272,0.268-0.709,0.268-0.979,0s-0.271-0.701,0-0.969l7.908-7.83
                                                c0.27-0.268,0.707-0.268,0.979,0l7.908,7.83c0.27,0.268,0.27,0.701,0,0.969c-0.271,0.268-0.709,0.268-0.978,0L10,6.75L2.582,13.891z" />
                                            </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="w-full px-4">
                                    <div x-show.transition.origin.top="isOpen()" class="absolute shadow top-100 bg-white z-40 w-full left-0 rounded max-h-select" x-on:click.away="close">
                                        <div class="flex flex-col w-full overflow-y-auto h-64">
                                            <template x-for="(option, index) in options" :key="index" class="overflow-auto">
                                                <div class="cursor-pointer w-full border-gray-100 rounded-t border-b hover:bg-gray-100" x-on:click="$store.categories = select(index, $event)">
                                                    <div class="flex w-full items-center p-2 pl-2 border-transparent border-l-2 relative">
                                                        <div class="w-full items-center flex justify-between">
                                                            <div class="mx-2 leading-6" x-model="option" x-text="option.text"></div>
                                                            <div x-show="option.selected">
                                                                <svg class="svg-icon" viewBox="0 0 20 20">
                                                                    <path fill="none" d="M7.197,16.963H7.195c-0.204,0-0.399-0.083-0.544-0.227l-6.039-6.082c-0.3-0.302-0.297-0.788,0.003-1.087
                                                                    C0.919,9.266,1.404,9.269,1.702,9.57l5.495,5.536L18.221,4.083c0.301-0.301,0.787-0.301,1.087,0c0.301,0.3,0.301,0.787,0,1.087
                                                                    L7.741,16.738C7.596,16.882,7.401,16.963,7.197,16.963z"></path>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                </div>

                <div class="mb-6" x-data="tagData" x-init="getTags()">

                    <label for="title" class="block mb-2 text-sm font-medium text-gray-900">Tags</label>

                    <select id="tags" class="w-full px-4 py-3 text-base text-gray-900 border border-gray-300 rounded-lg bg-white focus:ring-blue-500 focus:border-blue-500 hidden">
                        <option selected>Choose a tags</option>

                        <template x-for="tag in tags">
                            <option :value="tag.id" x-text="tag.name"></option>
                        </template>

                    </select>

                    <div x-data="dropdownTags()" x-effect="loaded ? loadOptions() : loadOptions()" class="w-full flex flex-col items-center mx-auto">

                        <input name="values" type="hidden" x-bind:value="selectedValues()">

                        <div class="inline-block relative w-full">
                            <div class="flex flex-col items-center relative">
                                <div x-on:click="open" class="w-full">
                                    <div class="my-2 p-1 flex border border-gray-300 bg-white rounded-lg">
                                        <div class="flex flex-auto flex-wrap">
                                            <template x-for="(option, index) in selected" :key="index">
                                                <div class="flex justify-center items-center m-1 font-medium py-1 px-1 bg-white rounde border">
                                                    <div class="text-xs font-normal leading-none max-w-full flex-initial" x-model="options[option]" x-text="options[option].text"></div>
                                                    <div class="flex flex-auto flex-row-reverse">
                                                        <div x-on:click.stop="$store.tags = remove(index,option)">
                                                            <svg class="fill-current h-4 w-4 " role="button" viewBox="0 0 20 20">
                                                                <path d="M14.348,14.849c-0.469,0.469-1.229,0.469-1.697,0L10,11.819l-2.651,3.029c-0.469,0.469-1.229,0.469-1.697,0
                                                                    c-0.469-0.469-0.469-1.229,0-1.697l2.758-3.15L5.651,6.849c-0.469-0.469-0.469-1.228,0-1.697s1.228-0.469,1.697,0L10,8.183
                                                                    l2.651-3.031c0.469-0.469,1.228-0.469,1.697,0s0.469,1.229,0,1.697l-2.758,3.152l2.758,3.15
                                                                    C14.817,13.62,14.817,14.38,14.348,14.849z" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                            <div x-show="selected.length == 0" class="flex-1">
                                                <input placeholder="Select a tags" class="bg-transparent p-1 px-2 appearance-none outline-none h-full w-full text-gray-800 text-sm" x-bind:value="selectedValues()">
                                                @error('tags')
                                                    <span class="text-xs text-red-600">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="text-gray-300 w-8 py-1 pl-2 pr-1 border-l flex items-center border-gray-200">
                                            <button type="button" x-show="isOpen() === true" x-on:click="open" class="cursor-pointer w-6 h-6 text-gray-600 outline-none focus:outline-none">
                                                <svg version="1.1" class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                                    <path d="M17.418,6.109c0.272-0.268,0.709-0.268,0.979,0s0.271,0.701,0,0.969l-7.908,7.83
                                                    c-0.27,0.268-0.707,0.268-0.979,0l-7.908-7.83c-0.27-0.268-0.27-0.701,0-0.969c0.271-0.268,0.709-0.268,0.979,0L10,13.25 L17.418,6.109z" />
                                                </svg>
                                            </button>
                                            <button type="button" x-show="isOpen() === false" @click="close" class="cursor-pointer w-6 h-6 text-gray-600 outline-none focus:outline-none">
                                                <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                                    <path d="M2.582,13.891c-0.272,0.268-0.709,0.268-0.979,0s-0.271-0.701,0-0.969l7.908-7.83
                                                    c0.27-0.268,0.707-0.268,0.979,0l7.908,7.83c0.27,0.268,0.27,0.701,0,0.969c-0.271,0.268-0.709,0.268-0.978,0L10,6.75L2.582,13.891z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="w-full px-4">
                                    <div x-show.transition.origin.top="isOpen()" class="absolute shadow top-100 bg-white z-40 w-full left-0 rounded max-h-select" x-on:click.away="close">
                                        <div class="flex flex-col w-full overflow-y-auto h-64">
                                            <template x-for="(option, index) in options" :key="index" class="overflow-auto">
                                                <div class="cursor-pointer w-full border-gray-100 rounded-t border-b hover:bg-gray-100" @click="$store.tags = select(index,$event)">
                                                    <div class="flex w-full items-center p-2 pl-2 border-transparent border-l-2 relative">
                                                        <div class="w-full items-center flex justify-between">
                                                            <div class="mx-2 leading-6" x-model="option" x-text="option.text"></div>
                                                            <div x-show="option.selected">
                                                                <svg class="svg-icon" viewBox="0 0 20 20">
                                                                    <path fill="none" d="M7.197,16.963H7.195c-0.204,0-0.399-0.083-0.544-0.227l-6.039-6.082c-0.3-0.302-0.297-0.788,0.003-1.087
                                                                    C0.919,9.266,1.404,9.269,1.702,9.57l5.495,5.536L18.221,4.083c0.301-0.301,0.787-0.301,1.087,0c0.301,0.3,0.301,0.787,0,1.087
                                                                    L7.741,16.738C7.596,16.882,7.401,16.963,7.197,16.963z"></path>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-900" for="cover">Cover</label>
                    <input x-on:change="$store.cover = Object.values($event.target.files)" type="file" id="cover" name="cover" class="text-sm text-grey-500 file:mr-5 file:py-2 file:px-6 border border-gray-300 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-slate-700 hover:file:cursor-pointer hover:file:bg-slate-300 hover:file:text-slate-700 w-full bg-white p-1.5 rounded-lg " />

                    @error('cover')
                        <span class="text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="content" class="block mb-2 text-sm font-medium text-gray-900">Content</label>
                    <textarea id="content" rows="4" class="block p-2.5 w-full max-w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 no-tailwindcss-base" placeholder="Your post here..." name="content">{{ old('content') }}</textarea>

                    @error('content')
                        <span class="text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>
            
                <div class="flex items-start mb-6">
                    <div class="flex items-center h-5">
                        <input x-model="$store.is_published" x-on:click="$store.is_published = !$store.is_published" id="is_published" checked type="checkbox" class="w-4 h-4 bg-gray-50 rounded border border-gray-300 focus:ring-3 focus:ring-blue-300" name="is_published">
                    </div>

                    <label for="is_published" class="ml-2 text-sm font-medium text-gray-900">Publish</label>
                </div>

                <div class="flex flex-col items-start mb-6" x-data="{ active: false, url: '' }">

                    <div class="flex">
                        <div class="flex items-center h-5">
                            <input id="shorten" type="checkbox" x-model="active" class="w-4 h-4 bg-gray-50 rounded border border-gray-300 focus:ring-3 focus:ring-blue-300" x-on:click="active = !active" name="shorten">
                        </div>

                        <label for="shorten" class="ml-2 text-sm font-medium text-gray-900">Shorten</label>
                    </div>

                    <template x-if="active">
                        <div class="w-full mt-5">
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
                    </template>
                </div>
            
                <div x-data="seriesData" x-init="getSeries()" class="flex flex-col items-start mb-6">
                    <div class="flex">

                        <div class="flex items-center h-5">
                            <input id="is_series" type="checkbox" x-model="active" class="w-4 h-4 bg-gray-50 rounded border border-gray-300 focus:ring-3 focus:ring-blue-300" x-on:click="active = !active" name="is_series">
                        </div>

                        <label for="is_series" class="ml-2 text-sm font-medium text-gray-900">Series</label>
                    </div>

                    <template x-if="active">
                        <div class="w-full mt-5">

                            <label for="series" class="block mb-2 text-sm font-medium text-gray-900">Series</label>

                            <div class="flex flex-row gap-x-2">
                                <select id="series" name="series" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    <option selected>Choose a series</option>

                                    <template x-for="s in series">
                                        <option :value="s.id" x-text="s.name"></option>
                                    </template>
                                </select>

                                <a href="{{ route('series.add') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">New</a>
                            </div>

                            @error('series')
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

{{-- <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script> --}}
@push('scripts')
    <script>
        var myEditor;
        document.addEventListener('DOMContentLoaded', function() {
            ClassicEditor
                .create(document.querySelector('#content'), {
                ckfinder: {
                        uploadUrl: '{{route('post.ckeditor.upload').'?_token='.csrf_token()}}',
                    }
                })
                .then((editor) => {
                    myEditor = editor;
                })
                .catch(error => {
                    console.error(error);
                });
        });
        document.addEventListener('livewire:load', function() {
            Alpine.store('title', '');
            Alpine.store('cover', undefined);
            Alpine.store('is_published', true);
            Alpine.store('categories', []);
            Alpine.store('tags', []);
            Alpine.data('categoryData', () => ({
                loaded: false,
                categories: [],
                async getCategories() {
                    const res = await fetch('http://localhost:8000/api/categories');
                    const { data: categories } = await res.json();
                    this.categories = categories;
                    this.loaded = true;
                },
            }));
            Alpine.data('tagData', () => ({
                loaded: false,
                tags: [],
                async getTags() {
                    const res = await fetch('http://localhost:8000/api/tags');
                    const { data: tags } = await res.json();
                    this.tags = tags;
                    this.loaded = true;
                },
            }));
            Alpine.data('seriesData', () => ({
                loaded: false,
                active: false,
                series: [],
                async getSeries() {
                    const res = await fetch('http://localhost:8000/api/series/my');
                    const { data: series } = await res.json();
                    this.series = series;
                    this.loaded = true;
                }
            }))
        });
        function randomUrl() {
            const url = rand(5);
            document.querySelector('#url').value = url;
        }
        function dropdownCategories() {
            return {
            options: [],
            selected: [],
            show: false,
            open() { this.show = true },
            close() { this.show = false },
            isOpen() { return this.show === true },
            select(index, event) {
                if (!this.options[index].selected) {
                    this.options[index].selected = true;
                    this.options[index].element = event.target;
                    this.selected.push(index);
                    appendInputCategory(this.options[index].value);
                } else {
                    removeInputCategory(this.options[index].value);
                    this.selected.splice(this.selected.lastIndexOf(index), 1);
                    this.options[index].selected = false
                }
                return this.selected;
            },
            remove(index, option) {
                this.options[option].selected = false;
                this.selected.splice(index, 1);
                removeInputCategory(this.options[index].value);
                return this.selected;
            },
            loadOptions() {
                const options = document.getElementById('categories').options;
                for (let i = 1; i < options.length; i++) {
                    this.options.push({
                        value: options[i].value,
                        text: options[i].innerText,
                        selected: options[i].getAttribute('selected') != null ? options[i].getAttribute('selected') : false
                    });
                }
            },
            selectedValues(){
                    return this.selected.map((option) => {
                        return this.options[option].value;
                    });
                }
            }
        }
        function dropdownTags() {
            return {
            options: [],
            selected: [],
            show: false,
            open() { this.show = true },
            close() { this.show = false },
            isOpen() { return this.show === true },
            select(index, event) {
                if (!this.options[index].selected) {
                    this.options[index].selected = true;
                    this.options[index].element = event.target;
                    this.selected.push(index);
                    appendInputTag(this.options[index].value);
                } else {
                    this.selected.splice(this.selected.lastIndexOf(index), 1);
                    this.options[index].selected = false
                    removeInputTag(this.options[index].value);
                }
                return this.selected;
            },
            remove(index, option) {
                this.options[option].selected = false;
                this.selected.splice(index, 1);
                removeInputTag(this.options[index].value);
                return this.selected;
            },
            loadOptions() {
                const options = document.getElementById('tags').options;
                for (let i = 1; i < options.length; i++) {
                    this.options.push({
                        value: options[i].value,
                        text: options[i].innerText,
                        selected: options[i].getAttribute('selected') != null ? options[i].getAttribute('selected') : false
                    });
                }


            },
            selectedValues(){
                return this.selected.map((option)=>{
                        return this.options[option].value;
                    })
                }
            }
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
        function appendInputCategory(val) {
            document
                .querySelector('#hidden-input-categories')
                .innerHTML += `<input id="hidden-category-${val}" type="hidden" name="categories[]" value="${val}">`;
        }
        function removeInputCategory(val) {
            document
                .querySelector(`input#hidden-category-${val}`)
                .remove();
        }
        function appendInputTag(val) {
            document
                .querySelector('#hidden-input-categories')
                .innerHTML += `<input id="hidden-tag-${val}" type="hidden" name="tags[]" value="${val}">`;
        }
        function removeInputTag(val) {
            document
                .querySelector(`input#hidden-tag-${val}`)
                .remove();
        }
    </script>
@endpush
