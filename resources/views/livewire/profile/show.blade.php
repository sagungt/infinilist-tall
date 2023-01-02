<div class="container mx-auto min-h-screen flex flex-col justify-center pt-20 gap-5" x-data="user" x-init="getProfileInfo()">

    <div class="w-full md:w-[80%] lg:w-1/2 bg-white border border-gray-200 rounded-lg shadow-md mx-auto">
        <div class="flex justify-end px-4 pt-4">
            <a href="{{ route('profile.edit') }}" class="inline-block text-gray-500 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg text-sm px-4 py-2">
                <span class="text-sm">Edit</span>
            </a>
        </div>
        <div class="flex flex-col items-center py-5">
            <template x-if="user.profile_url === null">
                <div class="w-24 h-24 text-3xl text-white bg-slate-900 rounded-full flex items-center justify-center uppercase" x-text="user.name[0]"></div>
            </template>
            <template x-if="user.profile_url !== null">
                <img class="w-24 h-24 mb-3 rounded-full shadow-lg" x-bind:src="user.profile_url.path" x-bind:alt="user.name"/>
            </template>
            <h1 class="mb-1 text-2xl my-4 font-extrabold text-gray-900" x-text="user.name"></h1>
            <span class="text-sm text-gray-500" x-text="user.username"></span>
            <span class="text-md text-gray-700" x-text="user.email"></span>
            <div class="flex no-tailwindcss-base p-10 no-tailwindcss-base">
                <p x-html="user.bio"></p>
            </div>
        </div>
    </div>
    <div class="w-full justify-center flex flex-row gap-x-5">
        <a href="{{ route('profile.post.list') }}" class="p-2 bg-slate-900 text-white rounded-lg font-bold">
            <span>My Posts</span>
        </a>
        <a href="{{ route('profile.series.list') }}" class="p-2 bg-slate-900 text-white rounded-lg font-bold">
            <span>My Series</span>
        </a>
        <a href="{{ route('profile.favorite.list') }}" class="p-2 bg-slate-900 text-white rounded-lg font-bold">
            <span>My Favorites</span>
        </a>
        <a href="{{ route('profile.like.list') }}" type="button" class="p-2 bg-slate-900 text-white rounded-lg font-bold">
            <span>Likes</span>
        </a>
        <a href="{{ route('profile.comment.list') }}" type="button" class="p-2 bg-slate-900 text-white rounded-lg font-bold">
            <span>Comments</span>
        </a>
    </div>
</div>

@push('scripts')
    <script>
        const userId = '{{ auth()->user()->id }}';
        const api = {
            async getProfileInfo() {
                const res = await fetch(`http://localhost:8000/api/users`);
                const { data: user } = await res.json();
                this.user = user;
            },
        }
        document.addEventListener('livewire:load', function () {
            Alpine.data('user', () => ({
                user: [],
                ...api,
            }));
        });
    </script>
@endpush
