<div class="flex flex-row gap-x-2">
    @if ($profile_url)
        <img class="w-10 h-10 rounded-full" src="{{ $profile_url }}" alt="{{ $name }}">
    @else
        <div class="w-10 h-10 rounded-full bg-slate-900"></div>
    @endif
    <div class="flex flex-col justify-center">
        <div class="text-sm font-bold">
            <a href="{{ route('user.profile', ['username' => $username]) }}">{{ $name }}</a>
        </div>
        <span class="text-xs text-slate-500" x-text="(new Date(@js($created_at))).toDateString()"></span>
    </div>
</div>