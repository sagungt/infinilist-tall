<div class="flex flex-row gap-x-5 py-10">
    @if (is_null($profile_url))
        <div class="w-10 h-10 rounded-full bg-slate-900"></div>
    @else
        <img src="{{ $profile_url }}" alt="{{ $name }}" class="w-10 h-10 rounded-full" />
    @endif
    <div class="flex flex-col justify-center w-full flex-1">
        <form action="{{ route('comment.store', ['kind' => $kind, 'parent_id' => $parent_id]) }}" method="post">
            @csrf
            
            <input type="hidden" name="parent_comment_id" value="{{ $parent_comment_id }}">
            <textarea name="content" id="message" rows="4" class="block p-2.5 w-full max-w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Write your comments here...">{{ $comment }}</textarea>

            <button type="submit" class="my-4 py-2 px-4 rounded-lg bg-slate-700 text-white">Post 🚀</button>
        </form>
    </div>
</div>
