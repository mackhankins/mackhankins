<x-filament-panels::page
    :full-height="true"
    @class(['ws-root'])
    x-data="{
        sidebarOpen: false,
        contextOpen: false,
        init() {
            const remembered = window.localStorage.getItem('writing-studio-sidebar-open')
            this.sidebarOpen = remembered === null ? window.innerWidth >= 1280 : remembered === 'true'

            const rememberedContext = window.localStorage.getItem('writing-studio-context-open')
            this.contextOpen = rememberedContext === null ? window.innerWidth >= 1440 : rememberedContext === 'true'
        },
        setSidebar(open) {
            this.sidebarOpen = open
            window.localStorage.setItem('writing-studio-sidebar-open', open ? 'true' : 'false')
        },
        toggleSidebar() { this.setSidebar(! this.sidebarOpen) },
        setContext(open) {
            this.contextOpen = open
            window.localStorage.setItem('writing-studio-context-open', open ? 'true' : 'false')
        },
        toggleContext() { this.setContext(! this.contextOpen) },
    }"
    x-on:writing-studio-reset-upload-input.window="$refs.composerUploadInput.value = ''"
>
    {{-- Hairline frame that occupies the page: title row + split --}}
    <div
        class="relative -mx-4 -my-8 flex h-[calc(100dvh-4rem)] flex-col border-y md:-mx-6 lg:-mx-8"
        style="border-color: var(--ws-rule);"
    >
        {{-- Title row --}}
        <header
            class="relative z-10 flex items-center gap-4 px-5 py-3"
            style="border-bottom: 1px solid var(--ws-rule);"
        >
            <button
                type="button"
                @click="toggleSidebar()"
                class="inline-flex h-8 w-8 items-center justify-center text-[color:var(--color-base-300)] transition hover:text-[color:var(--color-base-50)]"
                :aria-label="sidebarOpen ? 'Hide conversations' : 'Show conversations'"
            >
                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" class="h-4 w-4">
                    <path d="M3 5h14M3 10h14M3 15h14" stroke-linecap="round" />
                </svg>
            </button>

            <div class="min-w-0 flex-1">
                <div class="ws-eyebrow">Writing Studio</div>
                <div class="mt-0.5 truncate font-display text-[15px] font-medium text-[color:var(--color-base-50)]">
                    @if ($activeConversation = $this->conversations->firstWhere('id', $activeConversationId))
                        {{ $this->conversationLabel($activeConversation) }}
                    @else
                        Unwritten draft
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button type="button" wire:click="startFreshConversation" class="ws-btn-ghost">
                    <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7" class="h-3.5 w-3.5">
                        <path d="M10 4v12M4 10h12" stroke-linecap="round" />
                    </svg>
                    New
                </button>

                <button
                    type="button"
                    @click="toggleContext()"
                    class="ws-btn-ghost hidden lg:inline-flex"
                    :data-active="contextOpen"
                >
                    <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" class="h-3.5 w-3.5">
                        <path d="M5 4h7l3 3v9H5V4z" stroke-linejoin="round" />
                        <path d="M12 4v3h3" />
                    </svg>
                    Context
                </button>
            </div>
        </header>

        {{-- Split: rail / main / sidecar --}}
        <div class="relative flex flex-1 min-h-0">
            {{-- Mobile scrim --}}
            <div
                x-cloak
                x-show="sidebarOpen"
                x-transition.opacity.duration.200ms
                class="fixed inset-0 z-30 bg-black/45 backdrop-blur-[1px] xl:hidden"
                @click="sidebarOpen = false"
            ></div>

            {{-- Conversations rail --}}
            <aside
                x-cloak
                x-show="sidebarOpen"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-x-3"
                x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-x-0"
                x-transition:leave-end="opacity-0 -translate-x-3"
                class="flex flex-col ws-rail bg-[color:var(--ws-surface)]
                       max-xl:fixed max-xl:inset-y-0 max-xl:left-0 max-xl:z-40 max-xl:w-[min(20rem,calc(100vw-3rem))] max-xl:overflow-y-auto max-xl:shadow-2xl
                       xl:relative xl:w-72 xl:flex-shrink-0"
            >
                <div class="flex items-center justify-between px-5 pt-4 pb-2">
                    <div class="ws-eyebrow">Conversations</div>
                    <span class="font-mono text-[11px] text-[color:var(--color-base-400)]">
                        {{ str_pad((string) $this->conversations->count(), 2, '0', STR_PAD_LEFT) }}
                    </span>
                </div>

                <div class="flex-1 overflow-y-auto pb-3">
                    @forelse ($this->conversations as $conversation)
                        <div class="ws-conv group" data-active="{{ $activeConversationId === $conversation->id ? 'true' : 'false' }}">
                            <button
                                type="button"
                                wire:click="openConversation('{{ $conversation->id }}')"
                                @click="if (window.innerWidth < 1280) setSidebar(false)"
                                class="min-w-0 text-left"
                            >
                                <div class="truncate font-display text-[13.5px] font-medium text-[color:var(--color-base-50)]">
                                    {{ $this->conversationLabel($conversation) }}
                                </div>
                                <div class="mt-1 font-mono text-[10.5px] uppercase tracking-[0.16em] text-[color:var(--color-base-400)]">
                                    {{ $conversation->updated_at?->diffForHumans(null, true) }} old
                                </div>
                            </button>

                            <x-filament::dropdown placement="bottom-end">
                                <x-slot name="trigger">
                                    <button
                                        type="button"
                                        class="invisible mt-1 inline-flex h-6 w-6 items-center justify-center text-[color:var(--color-base-400)] transition hover:text-[color:var(--color-base-50)] group-hover:visible"
                                        aria-label="Conversation actions"
                                    >
                                        <svg viewBox="0 0 20 20" fill="currentColor" class="h-3.5 w-3.5">
                                            <circle cx="4" cy="10" r="1.4" />
                                            <circle cx="10" cy="10" r="1.4" />
                                            <circle cx="16" cy="10" r="1.4" />
                                        </svg>
                                    </button>
                                </x-slot>

                                <x-filament::dropdown.list>
                                    <x-filament::dropdown.list.item
                                        icon="heroicon-o-pencil-square"
                                        wire:click="beginConversationRename('{{ $conversation->id }}')"
                                    >Rename</x-filament::dropdown.list.item>
                                    <x-filament::dropdown.list.item
                                        color="danger"
                                        icon="heroicon-o-trash"
                                        wire:click="deleteConversation('{{ $conversation->id }}')"
                                        wire:confirm="Delete this conversation and its uploaded documents?"
                                    >Delete</x-filament::dropdown.list.item>
                                </x-filament::dropdown.list>
                            </x-filament::dropdown>

                            @if ($editingConversationId === $conversation->id)
                                <form wire:submit="saveConversationRename" class="col-span-2 mt-2 flex items-center gap-2">
                                    <input
                                        type="text"
                                        wire:model.defer="editingConversationTitle"
                                        maxlength="120"
                                        class="min-w-0 flex-1 border-0 bg-transparent font-display text-[13px] text-[color:var(--color-base-50)] focus:outline-none focus:ring-0"
                                        style="border-bottom: 1px solid var(--color-amber-accent); border-radius: 0; padding: 0.25rem 0;"
                                        autofocus
                                    >
                                    <button type="submit" class="font-display text-[10px] uppercase tracking-[0.2em] text-[color:var(--color-amber-accent)] hover:text-[color:var(--color-base-50)]">Save</button>
                                    <button type="button" wire:click="cancelConversationRename" class="font-display text-[10px] uppercase tracking-[0.2em] text-[color:var(--color-base-400)] hover:text-[color:var(--color-base-50)]">Esc</button>
                                </form>
                                @error('editingConversationTitle')
                                    <p class="col-span-2 mt-1 font-display text-[11px] text-[color:var(--color-rose-accent)]">{{ $message }}</p>
                                @enderror
                            @endif
                        </div>
                    @empty
                        <div class="mx-5 mt-4 border border-dashed p-5 font-display text-[13px] leading-6 text-[color:var(--color-base-300)]"
                             style="border-color: var(--ws-rule);">
                            No drafts yet. Start by describing the piece you want to write.
                        </div>
                    @endforelse
                </div>
            </aside>

            {{-- Main column: thread + composer --}}
            <section
                x-data="{
                    showJumpToLatest: false,
                    interceptCleanup: null,
                    mermaidRenderAttempts: 0,
                    scrollThread(behavior = 'smooth') {
                        this.$nextTick(() => {
                            requestAnimationFrame(() => requestAnimationFrame(() => {
                                this.$refs.threadEnd?.scrollIntoView({ block: 'end', behavior })
                                this.updateJumpState()
                            }))
                        })
                    },
                    updateJumpState() {
                        const thread = this.$refs.thread
                        if (! thread) return
                        this.showJumpToLatest = (thread.scrollHeight - thread.scrollTop - thread.clientHeight) > 240
                    },
                    scheduleMermaidRender() {
                        this.$nextTick(() => this.renderMermaidBlocks())
                    },
                    async renderMermaidBlocks() {
                        const previews = Array.from(this.$el.querySelectorAll('[data-writing-studio-mermaid]'))

                        if (! previews.length) return

                        if (! window.mermaid) {
                            if (this.mermaidRenderAttempts >= 20) return

                            this.mermaidRenderAttempts += 1
                            window.setTimeout(() => this.renderMermaidBlocks(), 150)

                            return
                        }

                        this.mermaidRenderAttempts = 0

                        window.mermaid.initialize({
                            startOnLoad: false,
                            securityLevel: 'strict',
                            theme: document.documentElement.classList.contains('dark') ? 'dark' : 'neutral',
                        })

                        for (const [index, preview] of previews.entries()) {
                            const source = preview.parentElement?.querySelector('[data-mermaid-source]')?.textContent?.trim()
                            const sourceHash = preview.dataset.mermaidHash

                            if (! source || ! sourceHash || preview.dataset.renderedHash === sourceHash) continue

                            try {
                                const renderTargetId = `writing-studio-mermaid-${sourceHash}-${index}`
                                const { svg, bindFunctions } = await window.mermaid.render(renderTargetId, source)

                                preview.innerHTML = svg
                                preview.dataset.renderedHash = sourceHash
                                bindFunctions?.(preview)
                            } catch (error) {
                                preview.innerHTML = ''
                                preview.dataset.renderedHash = ''

                                const errorMessage = document.createElement('div')
                                errorMessage.className = 'rounded-xl border border-dashed border-danger-200 bg-danger-50/70 px-4 py-6 text-sm text-danger-700 dark:border-danger-400/20 dark:bg-danger-400/10 dark:text-danger-200'
                                errorMessage.textContent = 'Mermaid could not render this diagram. Check the source block below.'

                                preview.appendChild(errorMessage)
                            }
                        }
                    },
                    init() {
                        this.interceptCleanup = this.$wire.intercept(({ action, onSuccess }) => {
                            if (! ['openConversation', 'sendMessage', 'startFreshConversation'].includes(action.name)) return
                            onSuccess(() => {
                                this.scrollThread(action.name === 'sendMessage' ? 'smooth' : 'auto')
                                this.scheduleMermaidRender()
                            })
                        })
                        this.scrollThread('auto')
                        this.scheduleMermaidRender()
                    },
                    destroy() { this.interceptCleanup?.() },
                }"
                x-load-js="[@js(\Filament\Support\Facades\FilamentAsset::getScriptSrc('mermaid'))]"
                x-on:writing-studio-scroll-bottom.window="$nextTick(() => { scrollThread(); updateJumpState() })"
                x-on:writing-studio-focus-composer.window="$nextTick(() => $refs.composer?.focus())"
                x-on:writing-studio-render-mermaid.window="scheduleMermaidRender()"
                class="relative flex min-w-0 flex-1 flex-col"
            >
                @if (filled($activeConversationId) && $editingConversationId === $activeConversationId)
                    <div class="px-6 py-3" style="border-bottom: 1px solid var(--ws-rule);">
                        <form wire:submit="saveConversationRename" class="flex items-center gap-3">
                            <span class="ws-eyebrow shrink-0">Rename</span>
                            <input
                                type="text"
                                wire:model.defer="editingConversationTitle"
                                maxlength="120"
                                class="min-w-0 flex-1 border-0 bg-transparent font-display text-[15px] text-[color:var(--color-base-50)] focus:outline-none focus:ring-0"
                                style="border-bottom: 1px solid var(--color-amber-accent); border-radius: 0;"
                                autofocus
                            >
                            <button type="submit" class="ws-btn-ghost">Save</button>
                            <button type="button" wire:click="cancelConversationRename" class="ws-btn-ghost">Cancel</button>
                        </form>
                    </div>
                @endif

                <div x-ref="thread" @scroll="updateJumpState()" class="relative min-h-0 flex-1 overflow-y-auto">
                    <div class="mx-auto max-w-[46rem] px-6 py-10 sm:px-10">
                        @forelse ($this->activeMessages as $message)
                            @if ($message->role === 'user')
                                <article class="mb-10">
                                    <div class="ws-eyebrow mb-2">Your brief · {{ $message->created_at?->diffForHumans() }}</div>
                                    <div class="ws-message-user">{{ $message->content }}</div>
                                </article>
                            @else
                                <article class="mb-12">
                                    <div class="ws-eyebrow mb-3 flex items-center gap-2">
                                        <span class="h-px w-6 bg-[color:var(--color-amber-accent)]"></span>
                                        Codex · {{ $message->created_at?->diffForHumans() }}
                                    </div>
                                    <div class="ws-message-assistant">
                                        {!! $this->renderMessageContent($message->content) !!}
                                    </div>
                                </article>
                            @endif
                        @empty
                            {{-- Empty state: the page itself is the prompt --}}
                            <div class="relative">
                                <div class="ws-gutter-grid pointer-events-none absolute -inset-x-10 -top-6 bottom-0" aria-hidden="true"></div>

                                <div class="relative">
                                    <div class="ws-eyebrow">A blank page</div>
                                    <h1 class="mt-4 font-display text-[2.4rem] font-extrabold leading-[1.05] tracking-tight text-[color:var(--color-base-50)] sm:text-[2.85rem]">
                                        What are we<br>writing today?
                                    </h1>
                                    <p class="mt-5 max-w-lg font-body text-[17px] leading-7 text-[color:var(--color-base-200)]">
                                        Describe the piece. Attach notes, code, or images for Codex to read.
                                        Type <span class="font-display font-semibold text-[color:var(--color-amber-accent)]">@post</span>
                                        to fold in an earlier draft and stay consistent with yourself.
                                    </p>

                                    <div class="mt-10 space-y-5">
                                        <button
                                            type="button"
                                            @click="applyStarter('Help me outline a new personal blog post about a lesson I learned recently. Give me three angles with a strong hook for each.')"
                                            class="group block w-full max-w-xl text-left"
                                        >
                                            <div class="flex items-baseline gap-3">
                                                <span class="font-mono text-[11px] text-[color:var(--color-base-400)]">01</span>
                                                <span class="font-display text-[17px] font-semibold text-[color:var(--color-base-50)] underline decoration-[color:var(--ws-rule)] decoration-1 underline-offset-[6px] group-hover:decoration-[color:var(--color-amber-accent)] group-hover:decoration-2">
                                                    Find the angle
                                                </span>
                                            </div>
                                            <p class="ml-8 mt-1 font-body text-[14.5px] text-[color:var(--color-base-300)]">
                                                Three ways into a lesson worth sharing, each with a hook.
                                            </p>
                                        </button>

                                        <button
                                            type="button"
                                            @click="applyStarter('Build an outline for a thoughtful personal blog post. Start with a strong opening, then shape the middle into clear sections, and end with a memorable close.')"
                                            class="group block w-full max-w-xl text-left"
                                        >
                                            <div class="flex items-baseline gap-3">
                                                <span class="font-mono text-[11px] text-[color:var(--color-base-400)]">02</span>
                                                <span class="font-display text-[17px] font-semibold text-[color:var(--color-base-50)] underline decoration-[color:var(--ws-rule)] decoration-1 underline-offset-[6px] group-hover:decoration-[color:var(--color-amber-accent)] group-hover:decoration-2">
                                                    Shape the outline
                                                </span>
                                            </div>
                                            <p class="ml-8 mt-1 font-body text-[14.5px] text-[color:var(--color-base-300)]">
                                                Opening, body sections, and a closing line that lingers.
                                            </p>
                                        </button>

                                        <button
                                            type="button"
                                            @click="applyStarter('I want to write on a topic that may overlap with past posts. Help me find a fresh angle and tell me which earlier ideas I should avoid repeating.')"
                                            class="group block w-full max-w-xl text-left"
                                        >
                                            <div class="flex items-baseline gap-3">
                                                <span class="font-mono text-[11px] text-[color:var(--color-base-400)]">03</span>
                                                <span class="font-display text-[17px] font-semibold text-[color:var(--color-base-50)] underline decoration-[color:var(--ws-rule)] decoration-1 underline-offset-[6px] group-hover:decoration-[color:var(--color-amber-accent)] group-hover:decoration-2">
                                                    Check overlap
                                                </span>
                                            </div>
                                            <p class="ml-8 mt-1 font-body text-[14.5px] text-[color:var(--color-base-300)]">
                                                Find a fresh angle without repeating past ground.
                                            </p>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforelse

                        <div x-ref="threadEnd" class="h-px w-full"></div>
                    </div>

                    <button
                        type="button"
                        x-cloak
                        x-show="showJumpToLatest"
                        x-transition.opacity.duration.150ms
                        @click="scrollThread()"
                        class="ws-btn-ghost sticky right-6 bottom-4 ml-auto"
                        style="background: var(--color-base-900);"
                    >
                        ↓ Latest
                    </button>
                </div>

                {{-- Composer: the desk --}}
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
                            this.$nextTick(() => { this.$refs.composer?.focus(); this.syncMention() })
                        },
                        syncMention() {
                            const text = this.composer ?? ''
                            const textarea = this.$refs.composer
                            const caret = textarea?.selectionStart ?? text.length
                            const beforeCaret = text.slice(0, caret)
                            const match = beforeCaret.match(/(?:^|\s)@post(?:\s+([^\n]*))?$/)
                            if (! match) { this.closeMention(); return }
                            this.mentionStart = beforeCaret.lastIndexOf('@post')
                            this.mentionQuery = (match[1] ?? '').trimStart()
                            this.mentionOpen = true
                            this.mentionIndex = 0
                            $wire.set('postReferenceSearch', this.mentionQuery)
                        },
                        closeMention() {
                            this.mentionOpen = false; this.mentionStart = null
                            this.mentionQuery = ''; this.mentionIndex = 0
                            $wire.set('postReferenceSearch', '')
                        },
                        moveMentionSelection(step) {
                            const options = this.mentionOptions()
                            if (! options.length) return
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
                            if (this.mentionStart === null) return
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
                    @writing-studio-apply-starter.window="applyStarter($event.detail.prompt)"
                    class="ws-composer relative"
                >
                    <div class="mx-auto max-w-[46rem] px-6 py-5 sm:px-10">
                        {{-- Chips: referenced posts + pending uploads --}}
                        @if ($this->selectedPostReferences->isNotEmpty() || $composerUploads !== [])
                            <div class="mb-4 flex flex-wrap gap-2">
                                @foreach ($this->selectedPostReferences as $post)
                                    <button
                                        type="button"
                                        wire:click="removeSelectedPostReference({{ $post->id }})"
                                        class="ws-chip ws-chip--ref group"
                                    >
                                        <span>{{ '@'.\Illuminate\Support\Str::limit($post->title, 40) }}</span>
                                        <span class="text-[color:var(--color-base-400)] group-hover:text-[color:var(--color-rose-accent)]">×</span>
                                    </button>
                                @endforeach
                                @foreach ($composerUploads as $index => $upload)
                                    <span class="ws-chip ws-chip--file">
                                        <span>{{ \Illuminate\Support\Str::limit($upload->getClientOriginalName(), 32) }}</span>
                                        <span class="font-mono text-[10px] text-[color:var(--color-base-400)]">{{ number_format($upload->getSize() / 1024, 0) }}K</span>
                                        <button
                                            type="button"
                                            wire:click="removeComposerUpload({{ $index }})"
                                            class="text-[color:var(--color-base-400)] hover:text-[color:var(--color-rose-accent)]"
                                            aria-label="Remove attachment"
                                        >×</button>
                                    </span>
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
                                rows="5"
                                placeholder="Begin here. A single sentence is enough — Codex will ask before filling in the rest."
                                class="ws-composer-input"
                            ></textarea>

                            <div
                                x-cloak
                                x-show="mentionOpen"
                                x-transition.opacity.duration.120ms
                                class="ws-mention-panel absolute inset-x-0 bottom-full z-20 mb-3 max-h-72 overflow-y-auto"
                            >
                                <div class="flex items-center justify-between px-4 py-3" style="border-bottom: 1px solid var(--ws-rule);">
                                    <div class="ws-eyebrow">Reference a post</div>
                                    <div class="font-mono text-[11px] text-[color:var(--color-base-400)]">
                                        {{ '@post' }}<span x-show="mentionQuery">:<span x-text="mentionQuery"></span></span>
                                    </div>
                                </div>
                                <div x-ref="mentionResults">
                                    @forelse ($this->availablePostReferences as $post)
                                        <button
                                            type="button"
                                            data-post-option
                                            @mousedown.prevent="choosePost({{ $post->id }})"
                                            @mouseenter="mentionIndex = {{ $loop->index }}"
                                            :data-active="mentionIndex === {{ $loop->index }}"
                                            class="ws-mention-option"
                                        >
                                            <span class="font-mono text-[11px] text-[color:var(--color-base-400)]">{{ str_pad((string) ($loop->iteration), 2, '0', STR_PAD_LEFT) }}</span>
                                            <span class="min-w-0">
                                                <span class="block truncate font-display text-[14px] font-medium text-[color:var(--color-base-50)]">{{ $post->title }}</span>
                                                <span class="mt-0.5 block truncate font-body text-[13px] text-[color:var(--color-base-300)]">
                                                    {{ \Illuminate\Support\Str::limit($post->excerpt ?: 'No excerpt.', 96) }}
                                                </span>
                                            </span>
                                        </button>
                                    @empty
                                        <div class="px-4 py-6 font-body text-[13px] italic text-[color:var(--color-base-400)]">
                                            No published posts match that query.
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 flex flex-wrap items-center justify-between gap-3 pt-3" style="border-top: 1px solid var(--ws-rule);">
                            <div class="flex items-center gap-4">
                                <label class="inline-flex items-center gap-2 font-display text-[11px] uppercase tracking-[0.18em] text-[color:var(--color-base-400)] hover:text-[color:var(--color-base-50)] cursor-pointer transition">
                                    <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" class="h-4 w-4">
                                        <path d="M12 6l-5.5 5.5a2.5 2.5 0 103.5 3.5L15 10a4 4 0 10-6-6L5 8" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    Attach
                                    <input
                                        x-ref="composerUploadInput"
                                        wire:model="composerUpload"
                                        type="file"
                                        accept="{{ $this->attachmentAcceptAttribute() }}"
                                        class="hidden"
                                    >
                                </label>

                                <span class="font-mono text-[11px] text-[color:var(--color-base-400)]">
                                    <span class="ws-kbd">⌘</span> <span class="ws-kbd">↵</span> to send
                                </span>

                                <span wire:loading wire:target="composerUpload" class="font-display text-[11px] uppercase tracking-[0.18em] text-[color:var(--color-amber-accent)]">
                                    Uploading...
                                </span>
                            </div>

                            <button
                                type="submit"
                                class="ws-btn-send"
                                wire:loading.attr="disabled"
                                wire:target="sendMessage,composerUploads"
                            >
                                <span wire:loading.remove wire:target="sendMessage">Send</span>
                                <span wire:loading wire:target="sendMessage">Sending</span>
                                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" class="h-3.5 w-3.5">
                                    <path d="M4 10h11M10 5l5 5-5 5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>

                        @error('composerMessage')
                            <p class="mt-2 font-display text-[12px] text-[color:var(--color-rose-accent)]">{{ $message }}</p>
                        @enderror
                        @error('composerUpload')
                            <p class="mt-2 font-display text-[12px] text-[color:var(--color-rose-accent)]">{{ $message }}</p>
                        @enderror
                        @error('composerUploads.*')
                            <p class="mt-2 font-display text-[12px] text-[color:var(--color-rose-accent)]">{{ $message }}</p>
                        @enderror
                    </div>
                </form>
            </section>

            {{-- Context sidecar: attachments + references --}}
            <aside
                x-cloak
                x-show="contextOpen && ({{ $this->activeAttachments->count() }} > 0 || {{ $this->selectedPostReferences->count() }} > 0)"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-x-3"
                x-transition:enter-end="opacity-100 translate-x-0"
                class="hidden w-72 flex-shrink-0 overflow-y-auto px-5 py-5 lg:block"
                style="border-left: 1px solid var(--ws-rule);"
            >
                @if ($this->activeAttachments->isNotEmpty())
                    <div class="mb-7">
                        <div class="mb-3 flex items-center justify-between">
                            <div class="ws-eyebrow">Context files</div>
                            <span class="font-mono text-[11px] text-[color:var(--color-amber-accent)]">{{ str_pad((string) $this->activeAttachments->count(), 2, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <ul class="space-y-3">
                            @foreach ($this->activeAttachments as $attachment)
                                <li class="flex items-baseline gap-3">
                                    <span class="shrink-0 font-mono text-[10px] uppercase tracking-[0.2em] text-[color:var(--color-amber-accent)]">
                                        {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr(pathinfo($attachment->original_name, PATHINFO_EXTENSION) ?: '•', 0, 4)) }}
                                    </span>
                                    <span class="min-w-0 flex-1">
                                        <span class="block truncate font-display text-[13px] text-[color:var(--color-base-50)]">{{ $attachment->original_name }}</span>
                                        <span class="block font-mono text-[10px] uppercase tracking-[0.16em] text-[color:var(--color-base-400)]">Always in context</span>
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if ($this->selectedPostReferences->isNotEmpty())
                    <div>
                        <div class="mb-3 flex items-center justify-between">
                            <div class="ws-eyebrow">Referenced posts</div>
                            <span class="font-mono text-[11px] text-[color:var(--color-teal-accent)]">{{ str_pad((string) $this->selectedPostReferences->count(), 2, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <ul class="space-y-3">
                            @foreach ($this->selectedPostReferences as $post)
                                <li class="flex items-baseline gap-3">
                                    <span class="shrink-0 font-mono text-[10px] uppercase tracking-[0.2em] text-[color:var(--color-teal-accent)]">@</span>
                                    <span class="min-w-0 flex-1 font-display text-[13px] text-[color:var(--color-base-50)]">{{ $post->title }}</span>
                                    <button
                                        type="button"
                                        wire:click="removeSelectedPostReference({{ $post->id }})"
                                        class="font-mono text-[14px] text-[color:var(--color-base-400)] transition hover:text-[color:var(--color-rose-accent)]"
                                        aria-label="Remove reference"
                                    >×</button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </aside>
        </div>
    </div>
</x-filament-panels::page>
