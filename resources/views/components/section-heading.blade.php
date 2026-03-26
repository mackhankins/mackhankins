@props(['title', 'subtitle' => null, 'href' => null])

<div class="flex items-end justify-between gap-4 mb-8">
    <div>
        <h2 class="font-display font-bold text-2xl md:text-3xl tracking-tight text-base-50">
            {{ $title }}
        </h2>
        @if($subtitle)
            <p class="mt-2 text-base-300 font-body text-base">
                {{ $subtitle }}
            </p>
        @endif
    </div>

    @if($href)
        <a href="{{ $href }}" class="shrink-0 text-sm font-display font-medium text-amber-accent hover:text-teal-accent transition-colors group">
            View all
            <span class="inline-block transition-transform group-hover:translate-x-0.5">&rarr;</span>
        </a>
    @endif
</div>
