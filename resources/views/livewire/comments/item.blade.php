<div class="flex flex-row w-full py-5 gap-x-5">
    @if (is_null($profile_url))
        <div class="w-10 h-10 rounded-full bg-slate-900"></div>
    @else
        <img src="{{ $profile_url }}" alt="{{ $name }}" class="w-10 h-10 rounded-full" />
    @endif
    <div x-data="data" class="flex flex-col gap-x-2 w-full flex-1">
        <div class="flex flex-col w-full">
            <div class="w-full p-5 bg-white rounded-lg border border-slate-200 flex flex-col">
                <span class="text-xs italic" x-text="(new Date(@js($updated_at))).toDateString() + (@js($is_edited) ? ' Edited' : '')"></span>
                <p>
                    {{ $comment }}
                </p>
            </div>
        </div>
        <div class="flex flex-row gap-x-2">
            <button>
                <span>💖 <span class="text-xs">{{ $like_count }}</span></span>
            </button>
            <button x-on:click="openReply()">
                <span>💬 <span class="text-xs">Reply</span></span>
            </button>
            <button>
                <span>📌 <span class="text-xs">{{ $is_pinned ? 'Pinned' : 'Pin' }}</span></span>
            </button>
            <button x-on:click="openEdit()">
                <span>🖊 <span class="text-xs">Edit</span></span>
            </button>
        </div>
        <div id="comment-{{ $comment_id }}">
            <template x-if="showReply">
                <livewire:comments.form
                    :kind="'post'"
                    :parent_id="$parent_id"
                    :name="$name"
                    :profile_url="$profile_url"
                    :parent_comment_id="$comment_id" />
            </template>
            <template x-if="showEdit">
                <livewire:comments.form
                    :comment="$comment"
                    :kind="'post'"
                    :parent_id="$parent_id"
                    :name="$name"
                    :profile_url="$profile_url"
                    :parent_comment_id="$comment_id" />
            </template>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:load', function() {
            Alpine.data('data', () => ({
                showReply: false,
                showEdit: false,
                openReply() {
                    this.showReply = !this.showReply;
                    this.showEdit = false;
                },
                openEdit() {
                    this.showEdit = !this.showEdit;
                    this.showReply = false;
                },
            }));
        });
    </script>
@endpush