@props(['post'])

<div class="card-glow group relative rounded-xl border border-base-600/50 bg-base-800/60 p-6">
    {{-- Clickable overlay for the card (behind tags) --}}
    <a href="{{ route('blog.show', $post) }}" class="absolute inset-0 z-0 rounded-xl" aria-label="{{ $post->title }}"></a>

    <div class="relative z-10 pointer-events-none">
        <div class="flex items-center gap-3 text-xs text-base-400 font-display">
            <time datetime="{{ $post->published_at->toDateString() }}">
                {{ $post->published_at->format('M d, Y') }}
            </time>
            <span class="w-1 h-1 rounded-full bg-base-500"></span>
            <span>{{ $post->reading_time }} min read</span>
        </div>

        <h3 class="mt-3 font-display font-bold text-lg text-base-50 group-hover:text-amber-accent transition-colors leading-snug">
            {{ $post->title }}
        </h3>

        @if($post->excerpt)
            <p class="mt-3 text-sm text-base-300 leading-relaxed line-clamp-3">
                {{ $post->excerpt }}
            </p>
        @endif

        @if($post->tags->isNotEmpty())
            <div class="mt-4 flex flex-wrap gap-1.5 relative z-20 pointer-events-auto">
                @foreach($post->tags->take(3) as $tag)
                    <x-tag-badge :tag="$tag" />
                @endforeach
            </div>
        @endif
    </div>
</div>
