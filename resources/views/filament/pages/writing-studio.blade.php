<x-filament-panels::page>
    <div class="grid gap-6 xl:grid-cols-[20rem_minmax(0,1fr)]">
        <aside class="rounded-3xl border border-gray-200 bg-white p-4 shadow-sm dark:border-white/8 dark:bg-white/[0.03] xl:sticky xl:top-6 xl:h-[calc(100dvh-8rem)] xl:max-h-[calc(100dvh-8rem)] xl:overflow-y-auto">
            <div class="mb-4 flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-sm font-semibold text-gray-950 dark:text-white">Conversations</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Recent writing chats with Codex.</p>
                </div>
                <button
                    type="button"
                    wire:click="startFreshConversation"
                    class="inline-flex items-center rounded-full border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-white/10 dark:text-gray-200 dark:hover:bg-white/[0.04]"
                >
                    New chat
                </button>
            </div>

            <div class="space-y-2">
                @forelse ($this->conversations as $conversation)
                    <div
                        @class([
                            'rounded-2xl border p-2 transition',
                            'border-gray-950 bg-gray-950 text-white dark:border-white dark:bg-white dark:text-gray-950' => $activeConversationId === $conversation->id,
                            'border-gray-200 bg-gray-50 text-gray-800 hover:bg-gray-100 dark:border-white/10 dark:bg-white/[0.03] dark:text-gray-100 dark:hover:bg-white/[0.05]' => $activeConversationId !== $conversation->id,
                        ])
                    >
                        <div class="flex items-start gap-2">
                            <button
                                type="button"
                                wire:click="openConversation('{{ $conversation->id }}')"
                                class="min-w-0 flex-1 rounded-xl px-2 py-1.5 text-left"
                            >
                                <div class="truncate text-sm font-medium">{{ $this->conversationLabel($conversation) }}</div>
                                <div class="mt-1 text-xs opacity-70">{{ $conversation->updated_at?->diffForHumans() }}</div>
                            </button>

                            <div class="flex shrink-0 items-center gap-1">
                                <button
                                    type="button"
                                    wire:click="beginConversationRename('{{ $conversation->id }}')"
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-black/10 text-xs font-semibold transition hover:bg-black/5 dark:border-white/10 dark:hover:bg-white/10"
                                    aria-label="Rename conversation"
                                >
                                    Edit
                                </button>

                                <button
                                    type="button"
                                    wire:click="deleteConversation('{{ $conversation->id }}')"
                                    wire:confirm="Delete this conversation and its uploaded documents?"
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-black/10 text-xs font-semibold transition hover:bg-black/5 dark:border-white/10 dark:hover:bg-white/10"
                                    aria-label="Delete conversation"
                                >
                                    x
                                </button>
                            </div>
                        </div>

                        @if ($editingConversationId === $conversation->id)
                            <form wire:submit="saveConversationRename" class="mt-2 flex items-center gap-2 px-2 pb-1">
                                <input
                                    type="text"
                                    wire:model.defer="editingConversationTitle"
                                    maxlength="120"
                                    class="block min-w-0 flex-1 rounded-xl border-0 bg-white/90 px-3 py-2 text-sm text-gray-900 ring-1 ring-gray-200 focus:ring-2 focus:ring-primary-500 dark:bg-black/20 dark:text-white dark:ring-white/10"
                                >
                                <button
                                    type="submit"
                                    class="inline-flex items-center rounded-full bg-white px-3 py-2 text-xs font-semibold text-gray-950 transition hover:bg-gray-100 dark:bg-white dark:text-gray-950 dark:hover:bg-gray-200"
                                >
                                    Save
                                </button>
                                <button
                                    type="button"
                                    wire:click="cancelConversationRename"
                                    class="inline-flex items-center rounded-full border border-black/10 px-3 py-2 text-xs font-semibold transition hover:bg-black/5 dark:border-white/10 dark:hover:bg-white/10"
                                >
                                    Cancel
                                </button>
                            </form>
                            @error('editingConversationTitle')
                                <p class="px-2 pt-1 text-xs text-danger-600">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-gray-300 bg-gray-50 px-4 py-8 text-sm text-gray-600 dark:border-white/10 dark:bg-white/[0.02] dark:text-gray-300">
                        No conversations yet. Start a new chat and tell Codex what you want to write.
                    </div>
                @endforelse
            </div>
        </aside>

        <section
            x-data="{
                showJumpToLatest: false,
                interceptCleanup: null,
                scrollThread(behavior = 'smooth') {
                    this.$nextTick(() => {
                        requestAnimationFrame(() => {
                            requestAnimationFrame(() => {
                                this.$refs.threadEnd?.scrollIntoView({
                                    block: 'end',
                                    behavior,
                                })

                                this.updateJumpState()
                            })
                        })
                    })
                },
                updateJumpState() {
                    const thread = this.$refs.thread

                    if (! thread) {
                        return
                    }

                    this.showJumpToLatest = (thread.scrollHeight - thread.scrollTop - thread.clientHeight) > 240
                },
                init() {
                    this.interceptCleanup = this.$wire.intercept(({ action, onSuccess }) => {
                        if (! ['openConversation', 'sendMessage', 'startFreshConversation'].includes(action.name)) {
                            return
                        }

                        onSuccess(() => {
                            this.scrollThread(action.name === 'sendMessage' ? 'smooth' : 'auto')
                        })
                    })

                    this.scrollThread('auto')
                },
                destroy() {
                    this.interceptCleanup?.()
                },
            }"
            x-on:writing-studio-scroll-bottom.window="$nextTick(() => { scrollThread(); updateJumpState() })"
            x-on:writing-studio-focus-composer.window="$nextTick(() => $refs.composer?.focus())"
            class="flex min-h-[70vh] flex-col rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-white/8 dark:bg-white/[0.03] xl:h-[calc(100dvh-8rem)] xl:max-h-[calc(100dvh-8rem)] xl:overflow-hidden"
        >
            <div class="border-b border-gray-200 px-5 py-5 dark:border-white/8">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h1 class="font-display text-3xl font-bold tracking-tight text-gray-950 dark:text-white">Writing Studio</h1>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Chat with Codex, attach Markdown docs, and type <span class="font-semibold">@post</span> to reference existing posts in the current message.
                        </p>
                    </div>

                    <div class="flex shrink-0 items-center gap-2">
                        <button
                            type="button"
                            wire:click="startFreshConversation"
                            class="inline-flex items-center rounded-full bg-gray-950 px-3 py-2 text-xs font-semibold text-white transition hover:bg-gray-800 dark:bg-white dark:text-gray-950 dark:hover:bg-gray-200"
                        >
                            New chat
                        </button>

                        @if (filled($activeConversationId))
                            <button
                                type="button"
                                wire:click="beginConversationRename('{{ $activeConversationId }}')"
                                class="inline-flex items-center rounded-full border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-white/10 dark:text-gray-200 dark:hover:bg-white/[0.04]"
                            >
                                Rename
                            </button>

                            <button
                                type="button"
                                wire:click="deleteConversation('{{ $activeConversationId }}')"
                                wire:confirm="Delete this conversation and its uploaded documents?"
                                class="inline-flex items-center rounded-full border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-white/10 dark:text-gray-200 dark:hover:bg-white/[0.04]"
                            >
                                Delete chat
                            </button>
                        @endif
                    </div>
                </div>

                @if (filled($activeConversationId) && $editingConversationId === $activeConversationId)
                    <form wire:submit="saveConversationRename" class="mt-4 flex flex-col gap-2 sm:flex-row">
                        <input
                            type="text"
                            wire:model.defer="editingConversationTitle"
                            maxlength="120"
                            class="block min-w-0 flex-1 rounded-2xl border-0 bg-gray-50 px-4 py-3 text-sm text-gray-900 ring-1 ring-gray-200 focus:bg-white focus:ring-2 focus:ring-primary-500 dark:bg-white/[0.04] dark:text-white dark:ring-white/10"
                        >
                        <div class="flex items-center gap-2">
                            <button
                                type="submit"
                                class="inline-flex items-center rounded-full bg-gray-950 px-4 py-2 text-xs font-semibold text-white transition hover:bg-gray-800 dark:bg-white dark:text-gray-950 dark:hover:bg-gray-200"
                            >
                                Save title
                            </button>
                            <button
                                type="button"
                                wire:click="cancelConversationRename"
                                class="inline-flex items-center rounded-full border border-gray-200 px-4 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-white/10 dark:text-gray-200 dark:hover:bg-white/[0.04]"
                            >
                                Cancel
                            </button>
                        </div>
                    </form>
                @endif
            </div>

            <div x-ref="thread" @scroll="updateJumpState()" class="relative flex-1 overflow-y-auto px-5 py-5">
                @if ($this->activeAttachments->isNotEmpty())
                    <div class="mb-5 rounded-2xl border border-gray-200 bg-gray-50 p-4 dark:border-white/8 dark:bg-white/[0.03]">
                        <div class="mb-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-gray-500 dark:text-gray-400">Conversation Documents</div>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($this->activeAttachments as $attachment)
                                <span class="inline-flex items-center rounded-full border border-gray-200 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 dark:border-white/10 dark:bg-white/[0.04] dark:text-gray-200">
                                    {{ $attachment->original_name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="space-y-4 pr-1">
                    @forelse ($this->activeMessages as $message)
                        <article @class([
                            'overflow-hidden rounded-3xl border shadow-sm',
                            'ml-auto w-fit max-w-[min(36rem,78%)] border-primary-300/30 bg-linear-to-br from-primary-500/12 to-primary-400/4 dark:border-primary-400/15' => $message->role === 'user',
                            'mr-auto max-w-[min(54rem,92%)] border-gray-200 bg-white dark:border-white/8 dark:bg-white/[0.03]' => $message->role !== 'user',
                        ])>
                            <div @class([
                                'flex items-center justify-between gap-3 border-b px-4 py-3',
                                'border-primary-300/20 bg-primary-500/6 dark:border-primary-400/10 dark:bg-primary-400/8' => $message->role === 'user',
                                'border-gray-200/80 bg-gray-50/80 dark:border-white/6 dark:bg-white/[0.02]' => $message->role !== 'user',
                            ])>
                                <div class="flex items-center gap-3">
                                    <span @class([
                                        'inline-flex h-8 w-8 items-center justify-center rounded-full text-[11px] font-semibold tracking-[0.18em] uppercase',
                                        'bg-primary-500/14 text-primary-700 dark:bg-primary-400/14 dark:text-primary-100' => $message->role === 'user',
                                        'bg-gray-900 text-white dark:bg-white dark:text-gray-950' => $message->role !== 'user',
                                    ])>{{ $message->role === 'user' ? 'You' : 'AI' }}</span>
                                    <div>
                                        <div class="text-sm font-medium text-gray-950 dark:text-white">{{ $message->role === 'user' ? 'Your Prompt' : 'Codex Response' }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $message->created_at?->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </div>

                            @if ($message->role === 'assistant')
                                <div class="px-5 py-5 text-[15px] leading-7 text-gray-700 dark:text-gray-200 [&>*:first-child]:mt-0 [&>*:last-child]:mb-0 [&_p]:my-4 [&_ul]:my-4 [&_ul]:list-disc [&_ul]:space-y-2 [&_ul]:pl-6 [&_ol]:my-4 [&_ol]:list-decimal [&_ol]:space-y-2 [&_ol]:pl-6 [&_strong]:font-semibold [&_strong]:text-gray-950 dark:[&_strong]:text-white [&_h1]:mt-8 [&_h1]:mb-4 [&_h1]:font-display [&_h1]:text-3xl [&_h1]:font-bold [&_h1]:text-gray-950 dark:[&_h1]:text-white [&_h2]:mt-7 [&_h2]:mb-3 [&_h2]:font-display [&_h2]:text-2xl [&_h2]:font-bold [&_h2]:text-gray-950 dark:[&_h2]:text-white [&_h3]:mt-6 [&_h3]:mb-3 [&_h3]:font-display [&_h3]:text-xl [&_h3]:font-semibold [&_h3]:text-gray-950 dark:[&_h3]:text-white [&_blockquote]:my-5 [&_blockquote]:border-l-4 [&_blockquote]:border-amber-400/70 [&_blockquote]:bg-amber-50/70 [&_blockquote]:py-3 [&_blockquote]:pr-4 [&_blockquote]:pl-4 [&_blockquote]:italic dark:[&_blockquote]:border-amber-300/40 dark:[&_blockquote]:bg-amber-400/10 [&_code]:rounded [&_code]:bg-gray-100 [&_code]:px-1.5 [&_code]:py-0.5 [&_code]:font-mono dark:[&_code]:bg-white/8 [&_pre]:my-5 [&_pre]:overflow-x-auto [&_pre]:rounded-2xl [&_pre]:bg-gray-950 [&_pre]:p-4 [&_pre]:text-sm [&_pre]:text-gray-100 dark:[&_pre]:bg-black/40 [&_pre_code]:bg-transparent [&_pre_code]:p-0 [&_pre_code]:text-inherit [&_a]:font-medium [&_a]:text-primary-600 [&_a]:underline dark:[&_a]:text-primary-300">
                                    {!! \Illuminate\Support\Str::markdown($message->content, ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}
                                </div>
                            @else
                                <div class="px-5 py-4 text-sm leading-6 whitespace-pre-wrap text-gray-800 dark:text-gray-100">
                                    {{ $message->content }}
                                </div>
                            @endif
                        </article>
                    @empty
                        <div class="rounded-3xl border border-dashed border-gray-300 bg-gray-50 px-6 py-10 text-sm leading-6 text-gray-600 dark:border-white/10 dark:bg-white/[0.02] dark:text-gray-300">
                            Start by describing the blog post you want, attach a Markdown document, or type <span class="font-semibold">@post</span> to bring existing posts into the current message.
                        </div>
                    @endforelse
                </div>

                <button
                    type="button"
                    x-cloak
                    x-show="showJumpToLatest"
                    x-transition.opacity.duration.150ms
                    @click="scrollThread()"
                    class="sticky right-0 bottom-4 ml-auto mt-4 inline-flex items-center rounded-full border border-gray-200 bg-white/95 px-4 py-2 text-xs font-semibold text-gray-700 shadow-lg ring-1 ring-black/5 backdrop-blur transition hover:bg-gray-50 dark:border-white/10 dark:bg-gray-950/95 dark:text-gray-200 dark:ring-white/10 dark:hover:bg-white/10"
                >
                    Jump to latest
                </button>

                <div x-ref="threadEnd" class="h-px w-full"></div>
            </div>

            <form
                wire:submit="sendMessage"
                x-data="{
                    composer: @entangle('composerMessage').defer,
                    mentionOpen: false,
                    mentionQuery: '',
                    mentionStart: null,
                    mentionIndex: 0,
                    syncMention() {
                        const text = this.composer ?? ''
                        const textarea = this.$refs.composer
                        const caret = textarea?.selectionStart ?? text.length
                        const beforeCaret = text.slice(0, caret)
                        const match = beforeCaret.match(/(?:^|\s)@post(?:\s+([^\n]*))?$/)

                        if (! match) {
                            this.closeMention()
                            return
                        }

                        this.mentionStart = beforeCaret.lastIndexOf('@post')
                        this.mentionQuery = (match[1] ?? '').trimStart()
                        this.mentionOpen = true
                        this.mentionIndex = 0
                        $wire.set('postReferenceSearch', this.mentionQuery)
                    },
                    closeMention() {
                        this.mentionOpen = false
                        this.mentionStart = null
                        this.mentionQuery = ''
                        this.mentionIndex = 0
                        $wire.set('postReferenceSearch', '')
                    },
                    moveMentionSelection(step) {
                        const options = this.mentionOptions()

                        if (! options.length) {
                            return
                        }

                        this.mentionIndex = (this.mentionIndex + step + options.length) % options.length
                        options[this.mentionIndex]?.scrollIntoView({ block: 'nearest' })
                    },
                    mentionOptions() {
                        return Array.from(this.$refs.mentionResults?.querySelectorAll('[data-post-option]') ?? [])
                    },
                    chooseActiveMention() {
                        const option = this.mentionOptions()[this.mentionIndex]

                        option?.dispatchEvent(new MouseEvent('mousedown', { bubbles: true }))
                    },
                    choosePost(postId) {
                        if (this.mentionStart === null) {
                            return
                        }

                        const text = this.composer ?? ''
                        const textarea = this.$refs.composer
                        const caret = textarea?.selectionStart ?? text.length
                        const beforeMention = text.slice(0, this.mentionStart).replace(/[ \t]+$/, '')
                        const afterMention = text.slice(caret).replace(/^[ \t]+/, '')
                        const prefix = beforeMention === '' ? '' : `${beforeMention} `
                        const suffix = afterMention === '' ? '' : ` ${afterMention}`

                        this.composer = `${prefix}${suffix}`.replace(/\s+\n/g, '\n').trimStart()
                        $wire.call('selectPostReference', postId)
                        this.closeMention()

                        this.$nextTick(() => {
                            textarea?.focus()

                            const nextCaret = prefix.length
                            textarea?.setSelectionRange(nextCaret, nextCaret)
                        })
                    },
                }"
                class="border-t border-gray-200 bg-white p-4 dark:border-white/8 dark:bg-white/[0.03]"
            >
                <div class="mb-3">
                    <div class="text-sm font-semibold text-gray-950 dark:text-white">Message Codex</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Ask for ideas, compare against old posts, or tell it to create a draft. Use Shift+Enter for a new line and Cmd/Ctrl+Enter to send.</div>
                </div>

                @if ($this->selectedPostReferences->isNotEmpty())
                    <div class="mb-3 flex flex-wrap gap-2">
                        @foreach ($this->selectedPostReferences as $post)
                            <button
                                type="button"
                                wire:click="removeSelectedPostReference({{ $post->id }})"
                                class="inline-flex items-center gap-2 rounded-full border border-primary-200 bg-primary-50 px-3 py-1.5 text-xs font-medium text-primary-700 transition hover:bg-primary-100 dark:border-primary-400/20 dark:bg-primary-400/10 dark:text-primary-200 dark:hover:bg-primary-400/15"
                            >
                                <span>{{ '@'.$post->title }}</span>
                                <span class="text-[10px] uppercase tracking-[0.2em] opacity-70">Remove</span>
                            </button>
                        @endforeach
                    </div>
                @endif

                <div class="relative">
                    <textarea
                        x-ref="composer"
                        x-model="composer"
                        wire:model.defer="composerMessage"
                        @input="syncMention()"
                        @click="syncMention()"
                        @keyup="syncMention()"
                        @keydown.escape="closeMention()"
                        @keydown.arrow-down.prevent="if (mentionOpen) moveMentionSelection(1)"
                        @keydown.arrow-up.prevent="if (mentionOpen) moveMentionSelection(-1)"
                        @keydown.meta.enter.prevent="$el.form.requestSubmit()"
                        @keydown.ctrl.enter.prevent="$el.form.requestSubmit()"
                        @keydown.enter="if (mentionOpen && ! $event.shiftKey) { $event.preventDefault(); chooseActiveMention() }"
                        @keydown.tab.prevent="if (mentionOpen) chooseActiveMention()"
                        rows="6"
                        placeholder="Write the post I should publish next... or type @post to reference existing posts in this message."
                        class="block w-full rounded-2xl border-0 bg-gray-50 px-4 py-3 text-sm text-gray-900 ring-1 ring-gray-200 placeholder:text-gray-400 focus:bg-white focus:ring-2 focus:ring-primary-500 dark:bg-white/[0.04] dark:text-white dark:ring-white/10 dark:placeholder:text-gray-500 dark:focus:bg-white/[0.06]"
                    ></textarea>

                    <div
                        x-cloak
                        x-show="mentionOpen"
                        x-transition.opacity.duration.150ms
                        class="absolute inset-x-3 bottom-3 z-20 rounded-2xl border border-gray-200 bg-white/96 p-3 shadow-2xl ring-1 ring-black/5 backdrop-blur dark:border-white/10 dark:bg-gray-950/96 dark:ring-white/10"
                    >
                        <div class="mb-2 flex items-center justify-between gap-3">
                            <div>
                                <div class="text-sm font-semibold text-gray-950 dark:text-white">Reference a post</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    Keep typing after <span class="font-semibold">@post</span> and press <kbd class="rounded bg-gray-100 px-1.5 py-0.5 font-mono text-[10px] dark:bg-white/10">Enter</kbd> to pick the highlighted result.
                                </div>
                            </div>
                            <div class="rounded-full border border-gray-200 px-2 py-1 text-[10px] font-semibold uppercase tracking-[0.18em] text-gray-500 dark:border-white/10 dark:text-gray-400">
                                {{ '@post' }}{{ filled($postReferenceSearch) ? ': '.$postReferenceSearch : '' }}
                            </div>
                        </div>

                        <div x-ref="mentionResults" class="grid max-h-64 gap-2 overflow-y-auto pr-1">
                            @forelse ($this->availablePostReferences as $post)
                                <button
                                    type="button"
                                    data-post-option
                                    @mousedown.prevent="choosePost({{ $post->id }})"
                                    @mouseenter="mentionIndex = {{ $loop->index }}"
                                    :class="mentionIndex === {{ $loop->index }}
                                        ? 'border-primary-300 bg-primary-50/80 dark:border-primary-400/30 dark:bg-primary-400/15'
                                        : 'border-gray-200 bg-white dark:border-white/8 dark:bg-white/[0.03]'"
                                    class="flex w-full items-start gap-3 rounded-2xl border px-3 py-3 text-left transition"
                                >
                                    <span class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-full bg-gray-900 text-[10px] font-semibold uppercase tracking-[0.18em] text-white dark:bg-white dark:text-gray-950">P</span>
                                    <span class="min-w-0">
                                        <span class="block text-sm font-medium text-gray-950 dark:text-white">{{ $post->title }}</span>
                                        <span class="mt-1 block text-xs text-gray-500 dark:text-gray-400">{{ \Illuminate\Support\Str::limit($post->excerpt ?: 'No excerpt provided.', 140) }}</span>
                                    </span>
                                </button>
                            @empty
                                <div class="rounded-2xl border border-dashed border-gray-200 bg-gray-50 px-4 py-6 text-sm text-gray-500 dark:border-white/10 dark:bg-white/[0.03] dark:text-gray-400">
                                    No published posts match that query.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                @error('composerMessage')
                    <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
                @enderror

                <div class="mt-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div class="space-y-2">
                        <label class="inline-flex cursor-pointer items-center gap-2 rounded-full border border-gray-200 bg-gray-50 px-3 py-2 text-xs font-medium text-gray-700 transition hover:bg-gray-100 dark:border-white/10 dark:bg-white/[0.04] dark:text-gray-200 dark:hover:bg-white/[0.06]">
                            <span>Attach Markdown</span>
                            <input wire:model="composerUploads" type="file" multiple accept=".md,.markdown,.txt,text/plain,text/markdown" class="hidden">
                        </label>
                        <div wire:loading wire:target="composerUploads" class="text-xs text-gray-500 dark:text-gray-400">Uploading files...</div>
                        @error('composerUploads.*')
                            <p class="text-sm text-danger-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            wire:click="startFreshConversation"
                            class="inline-flex items-center justify-center rounded-full border border-gray-200 px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-white/10 dark:text-gray-200 dark:hover:bg-white/[0.04]"
                        >
                            New chat
                        </button>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-full bg-gray-950 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-gray-800 disabled:cursor-not-allowed disabled:opacity-60 dark:bg-white dark:text-gray-950 dark:hover:bg-gray-200"
                            wire:loading.attr="disabled"
                            wire:target="sendMessage,composerUploads"
                        >
                            <span wire:loading.remove wire:target="sendMessage">Send</span>
                            <span wire:loading wire:target="sendMessage">Sending...</span>
                        </button>
                    </div>
                </div>
            </form>
        </section>
    </div>
</x-filament-panels::page>
