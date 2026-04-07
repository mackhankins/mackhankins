<x-layouts.app
    :title="$post->title"
    :meta-description="$post->excerpt"
    :meta-image="route('blog.og-image', $post)"
    :meta-url="route('blog.show', $post)"
>
    {{-- Reading progress bar --}}
    <div id="reading-progress" class="fixed top-16 left-0 right-0 z-30 h-0.5 pointer-events-none">
        <div id="reading-progress-bar" class="h-full w-0 bg-gradient-to-r from-amber-accent to-teal-accent transition-none"></div>
    </div>

    <article id="article" class="relative py-24">
        <x-gutter-grid />
        <div class="max-w-4xl mx-auto px-6">
            @if(!empty($preview))
                <div class="mb-6 rounded-lg border border-amber-accent/30 bg-amber-accent/10 px-4 py-3 text-sm font-display text-amber-accent animate-fade-in">
                    Preview mode — this post is not published yet.
                </div>
            @endif

            {{-- Back link --}}
            <a href="{{ route('blog.index') }}"
               class="inline-flex items-center gap-2 text-sm font-display font-medium text-base-400 hover:text-base-50 transition-colors mb-8 animate-fade-in">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/></svg>
                All stuff
            </a>

            {{-- Header --}}
            <header class="animate-fade-up" style="animation-delay: 0.1s;">
                <div class="flex items-center gap-3 text-sm text-base-400 font-display">
                    <time datetime="{{ ($post->published_at ?? $post->created_at)->toDateString() }}">
                        {{ ($post->published_at ?? $post->created_at)->format('F j, Y') }}
                    </time>
                    <span class="w-1 h-1 rounded-full bg-base-500"></span>
                    <span>{{ $post->reading_time }} min read</span>
                </div>

                <h1 class="mt-4 font-display font-extrabold text-3xl md:text-4xl lg:text-5xl tracking-tight text-base-50 leading-tight">
                    {{ $post->title }}
                </h1>

                @if($post->excerpt)
                    <p class="mt-4 text-lg text-base-300 font-body leading-relaxed">
                        {{ $post->excerpt }}
                    </p>
                @endif

                @if($post->tags->isNotEmpty())
                    <div class="mt-6 flex flex-wrap gap-2">
                        @foreach($post->tags as $tag)
                            <x-tag-badge :tag="$tag" />
                        @endforeach
                    </div>
                @endif
            </header>

            {{-- Featured image --}}
            @if($post->featured_image)
                <div class="mt-10 rounded-xl overflow-hidden border border-base-600/50 animate-fade-up" style="animation-delay: 0.2s;">
                    <img src="{{ Storage::url($post->featured_image) }}"
                         alt="{{ $post->title }}"
                         class="w-full h-auto">
                </div>
            @endif

            {{-- Divider --}}
            <div class="mt-10 mb-10 flex items-center gap-4 animate-fade-up" style="animation-delay: 0.25s;">
                <div class="flex-1 h-px bg-base-700/50"></div>
                <span class="font-display text-xs text-amber-accent/60 select-none">+</span>
                <div class="flex-1 h-px bg-base-700/50"></div>
            </div>

            {{-- Content --}}
            <div class="prose-custom animate-fade-up" style="animation-delay: 0.3s;">
                {!! str($post->content)->markdown(extensions: [new \Torchlight\Commonmark\V2\TorchlightExtension]) !!}
            </div>

            {{-- Footer divider --}}
            <div class="mt-16 pt-8 border-t border-base-700/50">
                <a href="{{ route('blog.index') }}"
                   class="inline-flex items-center gap-2 text-sm font-display font-semibold text-amber-accent hover:text-teal-accent transition-colors group">
                    <svg class="w-4 h-4 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/></svg>
                    Back to all stuff
                </a>
            </div>
        </div>
    </article>
</x-layouts.app>
