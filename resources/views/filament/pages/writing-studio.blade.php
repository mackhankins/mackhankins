<x-filament-panels::page
    x-data="{
        sidebarOpen: false,
        init() {
            const rememberedState = window.localStorage.getItem('writing-studio-sidebar-open')

            if (rememberedState === null) {
                this.sidebarOpen = window.innerWidth >= 1280

                return
            }

            this.sidebarOpen = rememberedState === 'true'
        },
        setSidebar(open) {
            this.sidebarOpen = open
            window.localStorage.setItem('writing-studio-sidebar-open', open ? 'true' : 'false')
        },
        toggleSidebar() {
            this.setSidebar(! this.sidebarOpen)
        },
    }"
    x-on:writing-studio-reset-upload-input.window="$refs.composerUploadInput.value = ''"
>
    <div class="space-y-6">
        <section class="relative overflow-hidden rounded-[2rem] border border-gray-200 bg-white shadow-[0_24px_80px_-40px_rgba(15,23,42,0.35)] dark:border-white/8 dark:bg-[#0f1318]">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(251,191,36,0.14),_transparent_38%),radial-gradient(circle_at_top_right,_rgba(59,130,246,0.10),_transparent_34%),linear-gradient(180deg,_rgba(255,255,255,0.96),_rgba(248,250,252,0.92))] dark:bg-[radial-gradient(circle_at_top_left,_rgba(251,191,36,0.12),_transparent_34%),radial-gradient(circle_at_top_right,_rgba(56,189,248,0.10),_transparent_30%),linear-gradient(180deg,_rgba(17,24,39,0.88),_rgba(15,19,24,0.96))]"></div>

            <div class="relative px-5 py-5">
                <div class="flex flex-col gap-4">
                    <div class="max-w-3xl">
                        <div class="mb-3 flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center rounded-full border border-amber-200/80 bg-amber-50/90 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] text-amber-800 dark:border-amber-300/15 dark:bg-amber-300/10 dark:text-amber-100">
                                Editorial Workspace
                            </span>
                            <span class="inline-flex items-center rounded-full border border-gray-200/80 bg-white/80 px-3 py-1 text-[11px] font-medium text-gray-600 backdrop-blur dark:border-white/10 dark:bg-white/6 dark:text-gray-300">
                                Built for outlining, revision, and final drafting
                            </span>
                        </div>

                        <div>
                            <h1 class="font-display text-3xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-[2.35rem]">Writing Studio</h1>
                            <p class="mt-2 max-w-2xl text-sm leading-6 text-gray-600 dark:text-gray-300">
                                Chat with Codex, attach notes, source files, config, or images, and pull in existing posts with <span class="font-semibold text-gray-900 dark:text-white">@post</span> when you want continuity with earlier writing.
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-x-5 gap-y-2 text-xs text-gray-500 dark:text-gray-400">
                        <span class="font-medium text-gray-600 dark:text-gray-300">Treat the composer like a brief.</span>
                        <span class="inline-flex items-center gap-2">
                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500/70"></span>
                            {{ $this->conversations->count() }} conversations
                        </span>
                        <span class="inline-flex items-center gap-2">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500/70"></span>
                            {{ $this->activeAttachments->count() }} context files
                        </span>
                        <span class="inline-flex items-center gap-2">
                            <span class="h-1.5 w-1.5 rounded-full bg-sky-500/70"></span>
                            {{ $this->selectedPostReferences->count() }} referenced posts
                        </span>
                    </div>
                </div>
            </div>
        </section>

        <div
            class="grid gap-6"
            :class="sidebarOpen ? 'xl:grid-cols-[20rem_minmax(0,1fr)]' : 'xl:grid-cols-[minmax(0,1fr)]'"
        >
        <div
            x-cloak
            x-show="sidebarOpen"
            x-transition.opacity.duration.200ms
            class="fixed inset-0 z-30 bg-gray-950/40 backdrop-blur-[1px] xl:hidden"
            @click="sidebarOpen = false"
        ></div>

        <aside
            x-cloak
            x-show="sidebarOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="-translate-x-6 opacity-0"
            x-transition:enter-end="translate-x-0 opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="translate-x-0 opacity-100"
            x-transition:leave-end="-translate-x-6 opacity-0"
            class="z-40 rounded-3xl border border-gray-200 bg-white p-4 shadow-sm dark:border-white/8 dark:bg-[#12161c] xl:sticky xl:top-6 xl:h-[calc(100dvh-8rem)] xl:max-h-[calc(100dvh-8rem)] xl:overflow-y-auto"
            :class="sidebarOpen ? 'fixed inset-y-4 left-4 w-[min(22rem,calc(100vw-2rem))] overflow-y-auto xl:static xl:inset-auto xl:w-auto' : 'hidden'"
        >
            <div class="mb-4 flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-sm font-semibold text-gray-950 dark:text-white">Conversations</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Recent writing chats with Codex.</p>
                </div>
                <div class="flex items-center gap-2">
                    <button
                        type="button"
                        @click="setSidebar(false)"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-gray-200 text-xs font-semibold text-gray-600 transition hover:bg-gray-50 xl:hidden dark:border-white/10 dark:text-gray-300 dark:hover:bg-white/[0.04]"
                        aria-label="Close conversation history"
                    >
                        <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                            <path d="M6 6l8 8M14 6l-8 8" stroke-linecap="round" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="space-y-2">
                @forelse ($this->conversations as $conversation)
                    <div
                        @class([
                            'rounded-2xl border p-2 transition',
                            'border-primary-200 bg-primary-50/90 text-gray-900 shadow-xs dark:border-primary-400/20 dark:bg-primary-400/12 dark:text-white' => $activeConversationId === $conversation->id,
                            'border-gray-200 bg-gray-50/80 text-gray-800 hover:border-gray-300 hover:bg-white dark:border-white/8 dark:bg-white/[0.03] dark:text-gray-100 dark:hover:border-white/12 dark:hover:bg-white/[0.05]' => $activeConversationId !== $conversation->id,
                        ])
                    >
                        <div class="flex items-start gap-2">
                            <button
                                type="button"
                                wire:click="openConversation('{{ $conversation->id }}')"
                                @click="if (window.innerWidth < 1280) setSidebar(false)"
                                class="min-w-0 flex-1 rounded-xl px-2 py-1.5 text-left"
                            >
                                <div class="truncate text-sm font-medium">{{ $this->conversationLabel($conversation) }}</div>
                                <div class="mt-1 text-xs text-gray-500 dark:text-gray-300/80">{{ $conversation->updated_at?->diffForHumans() }}</div>
                            </button>

                            <div class="shrink-0">
                                <x-filament::dropdown placement="bottom-end">
                                    <x-slot name="trigger">
                                        <button
                                            type="button"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-black/10 text-gray-500 transition hover:bg-black/5 hover:text-gray-800 dark:border-white/10 dark:text-gray-300 dark:hover:bg-white/10 dark:hover:text-white"
                                            aria-label="Conversation actions"
                                        >
                                            <svg viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                                                <circle cx="4" cy="10" r="1.5" />
                                                <circle cx="10" cy="10" r="1.5" />
                                                <circle cx="16" cy="10" r="1.5" />
                                            </svg>
                                        </button>
                                    </x-slot>

                                    <x-filament::dropdown.list>
                                        <x-filament::dropdown.list.item
                                            icon="heroicon-o-pencil-square"
                                            wire:click="beginConversationRename('{{ $conversation->id }}')"
                                        >
                                            Rename
                                        </x-filament::dropdown.list.item>

                                        <x-filament::dropdown.list.item
                                            color="danger"
                                            icon="heroicon-o-trash"
                                            wire:click="deleteConversation('{{ $conversation->id }}')"
                                            wire:confirm="Delete this conversation and its uploaded documents?"
                                        >
                                            Delete
                                        </x-filament::dropdown.list.item>
                                    </x-filament::dropdown.list>
                                </x-filament::dropdown>
                            </div>
                        </div>

                        @if ($editingConversationId === $conversation->id)
                            <form wire:submit="saveConversationRename" class="mt-2 flex items-center gap-2 px-2 pb-1">
                                <input
                                    type="text"
                                    wire:model.defer="editingConversationTitle"
                                    maxlength="120"
                                    class="block min-w-0 flex-1 rounded-xl border-0 bg-white px-3 py-2 text-sm text-gray-900 ring-1 ring-gray-200 focus:ring-2 focus:ring-primary-500 dark:bg-black/20 dark:text-white dark:ring-white/10"
                                >
                                <button
                                    type="submit"
                                    class="inline-flex items-center rounded-full bg-gray-950 px-3 py-2 text-xs font-semibold text-white transition hover:bg-gray-800 dark:bg-white dark:text-gray-950 dark:hover:bg-gray-200"
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
            class="relative flex min-h-[70vh] flex-col overflow-hidden rounded-[2rem] border border-gray-200 bg-white shadow-[0_24px_80px_-40px_rgba(15,23,42,0.35)] dark:border-white/8 dark:bg-[#0f1318] xl:h-[calc(100dvh-8rem)] xl:max-h-[calc(100dvh-8rem)]"
        >
            @if (filled($activeConversationId) && $editingConversationId === $activeConversationId)
                <div class="border-b border-gray-200 bg-gray-50/80 px-5 py-4 dark:border-white/8 dark:bg-white/[0.03]">
                    <form wire:submit="saveConversationRename" class="flex flex-col gap-2 sm:flex-row">
                        <input
                            type="text"
                            wire:model.defer="editingConversationTitle"
                            maxlength="120"
                            class="block min-w-0 flex-1 rounded-2xl border-0 bg-gray-50/90 px-4 py-3 text-sm text-gray-900 ring-1 ring-gray-200 focus:bg-white focus:ring-2 focus:ring-primary-500 dark:bg-white/[0.04] dark:text-white dark:ring-white/10"
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
                </div>
            @endif

            <div x-ref="thread" @scroll="updateJumpState()" class="relative flex-1 overflow-y-auto px-5 py-5">
                @if ($this->activeAttachments->isNotEmpty())
                    <div class="mx-auto mb-5 max-w-4xl rounded-[1.75rem] border border-gray-200 bg-linear-to-br from-gray-50 to-white p-4 shadow-sm dark:border-white/8 dark:from-white/[0.05] dark:to-white/[0.02]">
                        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                            <div>
                                <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-gray-500 dark:text-gray-400">Conversation Context</div>
                                <div class="mt-1 text-sm font-semibold text-gray-950 dark:text-white">Attached files stay available to Codex throughout this conversation.</div>
                            </div>
                            <div class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-emerald-700 dark:border-emerald-300/15 dark:bg-emerald-300/10 dark:text-emerald-100">
                                {{ $this->activeAttachments->count() }} active {{ \Illuminate\Support\Str::plural('file', $this->activeAttachments->count()) }}
                            </div>
                        </div>

                        <div class="mt-4 flex flex-wrap gap-2.5">
                            @foreach ($this->activeAttachments as $attachment)
                                <span class="inline-flex items-center gap-3 rounded-2xl border border-gray-200 bg-white px-3 py-2 text-xs font-medium text-gray-700 shadow-xs dark:border-white/10 dark:bg-white/[0.04] dark:text-gray-200">
                                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-gray-950 text-[10px] font-semibold uppercase tracking-[0.18em] text-white dark:bg-white dark:text-gray-950">
                                        {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr(pathinfo($attachment->original_name, PATHINFO_EXTENSION) ?: 'file', 0, 3)) }}
                                    </span>
                                    <span class="min-w-0">
                                        <span class="block max-w-52 truncate text-sm font-semibold">{{ $attachment->original_name }}</span>
                                        <span class="block text-[11px] font-medium uppercase tracking-[0.16em] text-gray-400 dark:text-gray-500">Always in context</span>
                                    </span>
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="mx-auto max-w-4xl space-y-4 pr-1">
                    @forelse ($this->activeMessages as $message)
                        <article @class([
                            'overflow-hidden rounded-[1.75rem] transition',
                            'ml-auto w-fit max-w-[min(34rem,78%)] border border-primary-300/25 bg-linear-to-br from-primary-500/10 to-primary-400/4 shadow-[0_16px_40px_-34px_rgba(14,116,144,0.5)] dark:border-primary-400/15' => $message->role === 'user',
                            'mr-auto max-w-[min(48rem,92%)]' => $message->role !== 'user',
                        ])>
                            <div @class([
                                'flex items-center justify-between gap-3 px-4 py-3',
                                'border-primary-300/20 bg-primary-500/6 dark:border-primary-400/10 dark:bg-primary-400/8' => $message->role === 'user',
                                'bg-transparent' => $message->role !== 'user',
                            ])>
                                <div class="flex items-center gap-3">
                                    <span @class([
                                        'inline-flex h-8 w-8 items-center justify-center rounded-full text-[11px] font-semibold tracking-[0.18em] uppercase',
                                        'bg-primary-500/14 text-primary-700 dark:bg-primary-400/14 dark:text-primary-100' => $message->role === 'user',
                                        'border border-gray-200 bg-white text-gray-700 dark:border-white/10 dark:bg-white/[0.04] dark:text-gray-100' => $message->role !== 'user',
                                    ])>{{ $message->role === 'user' ? 'You' : 'AI' }}</span>
                                    <div>
                                        <div class="text-sm font-medium text-gray-950 dark:text-white">{{ $message->role === 'user' ? 'Your Prompt' : 'Codex Response' }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $message->created_at?->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </div>

                            @if ($message->role === 'assistant')
                                <div class="px-12 py-3 text-[15px] leading-7 text-gray-700 dark:text-gray-200 [&>*:first-child]:mt-0 [&>*:last-child]:mb-0 [&_p]:my-4 [&_ul]:my-4 [&_ul]:list-disc [&_ul]:space-y-2 [&_ul]:pl-6 [&_ol]:my-4 [&_ol]:list-decimal [&_ol]:space-y-2 [&_ol]:pl-6 [&_strong]:font-semibold [&_strong]:text-gray-950 dark:[&_strong]:text-white [&_h1]:mt-8 [&_h1]:mb-4 [&_h1]:font-display [&_h1]:text-3xl [&_h1]:font-bold [&_h1]:text-gray-950 dark:[&_h1]:text-white [&_h2]:mt-7 [&_h2]:mb-3 [&_h2]:font-display [&_h2]:text-2xl [&_h2]:font-bold [&_h2]:text-gray-950 dark:[&_h2]:text-white [&_h3]:mt-6 [&_h3]:mb-3 [&_h3]:font-display [&_h3]:text-xl [&_h3]:font-semibold [&_h3]:text-gray-950 dark:[&_h3]:text-white [&_blockquote]:my-5 [&_blockquote]:border-l-4 [&_blockquote]:border-amber-400/70 [&_blockquote]:bg-amber-50/70 [&_blockquote]:py-3 [&_blockquote]:pr-4 [&_blockquote]:pl-4 [&_blockquote]:italic dark:[&_blockquote]:border-amber-300/40 dark:[&_blockquote]:bg-amber-400/10 [&_code]:rounded [&_code]:bg-gray-100 [&_code]:px-1.5 [&_code]:py-0.5 [&_code]:font-mono dark:[&_code]:bg-white/8 [&_pre]:my-5 [&_pre]:overflow-x-auto [&_pre]:rounded-2xl [&_pre]:bg-gray-950 [&_pre]:p-4 [&_pre]:text-sm [&_pre]:text-gray-100 dark:[&_pre]:bg-black/40 [&_pre_code]:bg-transparent [&_pre_code]:p-0 [&_pre_code]:text-inherit [&_a]:font-medium [&_a]:text-primary-600 [&_a]:underline dark:[&_a]:text-primary-300 max-sm:px-1">
                                    {!! \Illuminate\Support\Str::markdown($message->content, ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}
                                </div>
                            @else
                                <div class="px-5 py-4 text-sm leading-6 whitespace-pre-wrap text-gray-800 dark:text-gray-100">
                                    {{ $message->content }}
                                </div>
                            @endif
                        </article>
                    @empty
                        <div class="rounded-[1.75rem] border border-dashed border-gray-300 bg-linear-to-br from-gray-50 to-white px-6 py-10 text-sm leading-6 text-gray-600 dark:border-white/10 dark:from-white/[0.04] dark:to-white/[0.02] dark:text-gray-300">
                            <div class="max-w-2xl">
                                <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-gray-400 dark:text-gray-500">Start Strong</div>
                                <div class="mt-2 text-lg font-semibold text-gray-950 dark:text-white">Give Codex a brief, not a blank stare.</div>
                                <p class="mt-2">
                                    Describe the post you want, attach notes or screenshots, or type <span class="font-semibold">@post</span> to pull in past writing for tone, structure, and continuity.
                                </p>
                                <div class="mt-5 flex flex-wrap gap-2">
                                    <button type="button" @click="applyStarter('Help me outline a new personal blog post about a lesson I learned recently. Give me three angles with a strong hook for each.')" class="inline-flex items-center rounded-full border border-gray-200 bg-white px-3 py-2 text-xs font-medium text-gray-700 transition hover:border-gray-300 hover:bg-gray-50 dark:border-white/10 dark:bg-white/[0.04] dark:text-gray-200 dark:hover:bg-white/[0.08]">
                                        Find the angle
                                    </button>
                                    <button type="button" @click="applyStarter('Build an outline for a thoughtful personal blog post. Start with a strong opening, then shape the middle into clear sections, and end with a memorable close.')" class="inline-flex items-center rounded-full border border-gray-200 bg-white px-3 py-2 text-xs font-medium text-gray-700 transition hover:border-gray-300 hover:bg-gray-50 dark:border-white/10 dark:bg-white/[0.04] dark:text-gray-200 dark:hover:bg-white/[0.08]">
                                        Build the outline
                                    </button>
                                    <button type="button" @click="applyStarter('I want to write on a topic that may overlap with past posts. Help me find a fresh angle and tell me which earlier ideas I should avoid repeating.')" class="inline-flex items-center rounded-full border border-gray-200 bg-white px-3 py-2 text-xs font-medium text-gray-700 transition hover:border-gray-300 hover:bg-gray-50 dark:border-white/10 dark:bg-white/[0.04] dark:text-gray-200 dark:hover:bg-white/[0.08]">
                                        Check overlap
                                    </button>
                                </div>
                            </div>
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
                    applyStarter(prompt) {
                        this.composer = prompt

                        this.$nextTick(() => {
                            this.$refs.composer?.focus()
                            this.syncMention()
                        })
                    },
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
                class="border-t border-gray-200 bg-linear-to-b from-white to-gray-50/80 p-4 dark:border-white/8 dark:from-[#12161c] dark:to-[#0f1318]"
            >
                <div class="mx-auto max-w-4xl">
                <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <div class="text-sm font-semibold text-gray-950 dark:text-white">Message Codex</div>
                        <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">Describe the piece, attach context, or type <span class="font-semibold">@post</span> to pull previous writing into the brief.</div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" @click="applyStarter('Help me write a polished first draft for a personal blog post. Start by asking one or two clarifying questions if the brief is thin.')" class="inline-flex items-center rounded-full border border-gray-200 bg-white px-3 py-2 text-[11px] font-medium uppercase tracking-[0.16em] text-gray-600 transition hover:border-gray-300 hover:bg-gray-50 dark:border-white/10 dark:bg-white/[0.04] dark:text-gray-300 dark:hover:bg-white/[0.08]">
                            Draft mode
                        </button>
                        <button type="button" @click="applyStarter('Review the idea below like an editor. Tighten the angle, sharpen the hook, and suggest a stronger structure.')" class="inline-flex items-center rounded-full border border-gray-200 bg-white px-3 py-2 text-[11px] font-medium uppercase tracking-[0.16em] text-gray-600 transition hover:border-gray-300 hover:bg-gray-50 dark:border-white/10 dark:bg-white/[0.04] dark:text-gray-300 dark:hover:bg-white/[0.08]">
                            Edit mode
                        </button>
                    </div>
                </div>

                @if ($this->selectedPostReferences->isNotEmpty())
                    <div class="mb-3 flex flex-wrap items-center gap-2 rounded-[1.25rem] border border-primary-200/70 bg-primary-50/70 px-3 py-2 dark:border-primary-400/20 dark:bg-primary-400/10">
                        <span class="text-[11px] font-semibold uppercase tracking-[0.18em] text-primary-700 dark:text-primary-200">Referenced posts</span>
                        @foreach ($this->selectedPostReferences as $post)
                            <button
                                type="button"
                                wire:click="removeSelectedPostReference({{ $post->id }})"
                                class="inline-flex items-center gap-2 rounded-full border border-primary-200 bg-white px-3 py-1.5 text-xs font-medium text-primary-700 transition hover:bg-primary-100 dark:border-primary-400/20 dark:bg-white/[0.04] dark:text-primary-200 dark:hover:bg-primary-400/15"
                            >
                                <span>{{ '@'.$post->title }}</span>
                                <span class="text-[10px] uppercase tracking-[0.2em] opacity-60">Remove</span>
                            </button>
                        @endforeach
                    </div>
                @endif

                <div class="rounded-[1.75rem] border border-gray-200 bg-white/95 p-3 shadow-[0_18px_50px_-30px_rgba(15,23,42,0.35)] dark:border-white/10 dark:bg-white/[0.04]">
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
                            rows="8"
                            placeholder="Write the post I should publish next... or type @post to reference existing posts in this message."
                            class="block w-full rounded-[1.35rem] border-0 bg-transparent px-4 py-4 text-[15px] leading-7 text-gray-900 placeholder:text-gray-400 focus:ring-0 dark:text-white dark:placeholder:text-gray-500"
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

                    <div class="mt-3 flex flex-col gap-3 border-t border-gray-200/80 pt-3 dark:border-white/10">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div class="min-w-0 text-xs text-gray-500 dark:text-gray-400">
                                <span>Cmd/Ctrl+Enter to send</span>
                                @if (($uploadCount = count($composerUploads)) > 0)
                                    <span class="ml-3 inline-flex items-center gap-1 rounded-full border border-amber-200/80 bg-amber-50/80 px-2.5 py-1 font-medium text-amber-800 dark:border-amber-300/15 dark:bg-amber-300/10 dark:text-amber-100">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500/80"></span>
                                        {{ $uploadCount }} pending {{ \Illuminate\Support\Str::plural('file', $uploadCount) }}
                                    </span>
                                @endif
                                @if ($this->selectedPostReferences->isNotEmpty())
                                    <span class="ml-2 hidden text-gray-400 dark:text-gray-500 sm:inline">
                                        {{ $this->selectedPostReferences->count() }} referenced {{ \Illuminate\Support\Str::plural('post', $this->selectedPostReferences->count()) }}
                                    </span>
                                @endif
                            </div>

                            <div class="inline-flex shrink-0 items-center gap-2">
                                <x-filament::dropdown placement="top-end">
                                    <x-slot name="trigger">
                                        <button
                                            type="button"
                                            class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-gray-200/90 bg-gray-50/90 text-sm font-semibold text-gray-700 shadow-[0_10px_30px_-22px_rgba(15,23,42,0.45)] transition hover:border-gray-300 hover:bg-white dark:border-white/10 dark:bg-white/[0.04] dark:text-gray-200 dark:hover:border-white/15 dark:hover:bg-white/[0.08]"
                                            aria-label="More actions"
                                            title="More actions"
                                        >
                                            <svg viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                                                <circle cx="4" cy="10" r="1.5" />
                                                <circle cx="10" cy="10" r="1.5" />
                                                <circle cx="16" cy="10" r="1.5" />
                                            </svg>
                                        </button>
                                    </x-slot>

                                    <x-filament::dropdown.list>
                                        <x-filament::dropdown.list.item tag="label" icon="heroicon-o-paper-clip">
                                            Attach file
                                            <input
                                                x-ref="composerUploadInput"
                                                wire:model="composerUpload"
                                                type="file"
                                                accept="{{ $this->attachmentAcceptAttribute() }}"
                                                class="hidden"
                                            >
                                        </x-filament::dropdown.list.item>

                                        <x-filament::dropdown.list.item
                                            icon="heroicon-o-bars-3"
                                            x-on:click="toggleSidebar()"
                                        >
                                            <span x-text="sidebarOpen ? 'Hide history' : 'Show history'"></span>
                                        </x-filament::dropdown.list.item>

                                        <x-filament::dropdown.list.item
                                            icon="heroicon-o-plus"
                                            wire:click="startFreshConversation"
                                        >
                                            New chat
                                        </x-filament::dropdown.list.item>

                                        @if (filled($activeConversationId))
                                            <x-filament::dropdown.list.item
                                                icon="heroicon-o-pencil-square"
                                                wire:click="beginConversationRename('{{ $activeConversationId }}')"
                                            >
                                                Rename conversation
                                            </x-filament::dropdown.list.item>

                                            <x-filament::dropdown.list.item
                                                color="danger"
                                                icon="heroicon-o-trash"
                                                wire:click="deleteConversation('{{ $activeConversationId }}')"
                                                wire:confirm="Delete this conversation and its uploaded documents?"
                                            >
                                                Delete conversation
                                            </x-filament::dropdown.list.item>
                                        @endif
                                    </x-filament::dropdown.list>
                                </x-filament::dropdown>

                                <button
                                    type="submit"
                                    class="inline-flex h-11 items-center justify-center gap-2 rounded-2xl bg-gray-950 px-5 text-sm font-semibold text-white shadow-[0_16px_40px_-26px_rgba(15,23,42,0.6)] transition hover:bg-gray-800 disabled:cursor-not-allowed disabled:opacity-60 dark:bg-white dark:text-gray-950 dark:hover:bg-gray-200"
                                    wire:loading.attr="disabled"
                                    wire:target="sendMessage,composerUploads"
                                >
                                    <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                                        <path d="M4 10h10M10 4l6 6-6 6" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <span wire:loading.remove wire:target="sendMessage">Send</span>
                                    <span wire:loading wire:target="sendMessage">Sending...</span>
                                </button>
                            </div>
                        </div>

                        @if ($composerUploads !== [])
                            <div class="flex flex-wrap gap-2">
                                @foreach ($composerUploads as $index => $upload)
                                    <span class="inline-flex items-center gap-3 rounded-[1.15rem] border border-gray-200/90 bg-gray-50/80 px-3 py-2 text-xs font-medium text-gray-700 dark:border-white/10 dark:bg-white/[0.03] dark:text-gray-200">
                                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-[0.9rem] bg-gray-950 text-[10px] font-semibold uppercase tracking-[0.18em] text-white dark:bg-white dark:text-gray-950">
                                            {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr(pathinfo($upload->getClientOriginalName(), PATHINFO_EXTENSION) ?: 'file', 0, 3)) }}
                                        </span>
                                        <span class="min-w-0">
                                            <span class="block max-w-56 truncate text-sm font-semibold">{{ $upload->getClientOriginalName() }}</span>
                                            <span class="block text-[11px] uppercase tracking-[0.16em] text-gray-400 dark:text-gray-500">{{ number_format($upload->getSize() / 1024, 0) }} KB pending</span>
                                        </span>
                                        <button
                                            type="button"
                                            wire:click="removeComposerUpload({{ $index }})"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-full text-gray-400 transition hover:bg-white hover:text-gray-700 dark:hover:bg-white/10 dark:hover:text-white"
                                        >
                                            <span class="sr-only">Remove attachment</span>
                                            &times;
                                        </button>
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        <div wire:loading wire:target="composerUpload" class="text-xs text-gray-500 dark:text-gray-400">Uploading file...</div>
                    </div>
                </div>
                </div>
                @error('composerMessage')
                    <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
                @enderror
                @error('composerUpload')
                    <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
                @enderror
                @error('composerUploads.*')
                    <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
                @enderror
            </form>
        </section>
        </div>
    </div>
</x-filament-panels::page>
