@props(['title', 'subtitle' => null, 'href' => null, 'number' => null])

<div class="flex items-end justify-between gap-4 mb-8">
    <div>
        @if($number)
            <div class="flex items-center gap-3 mb-3">
                <span class="font-mono text-xs tracking-widest text-amber-accent">{{ str_pad((string) $number, 2, '0', STR_PAD_LEFT) }}</span>
                <span class="h-px w-8 bg-base-600/60"></span>
                <span class="font-mono text-[11px] uppercase tracking-[0.2em] text-base-400">{{ $title }}</span>
            </div>
            @if($subtitle)
                <h2 class="font-display font-bold text-2xl md:text-3xl tracking-tight text-base-50">
                    {{ $subtitle }}
                </h2>
            @endif
        @else
            <h2 class="font-display font-bold text-2xl md:text-3xl tracking-tight text-base-50">
                {{ $title }}
            </h2>
            @if($subtitle)
                <p class="mt-2 text-base-300 font-body text-base">
                    {{ $subtitle }}
                </p>
            @endif
        @endif
    </div>

    @if($href)
        <a href="{{ $href }}" class="shrink-0 text-sm font-display font-medium text-amber-accent hover:text-teal-accent transition-colors group">
            View all
            <span class="inline-block transition-transform group-hover:translate-x-0.5">&rarr;</span>
        </a>
    @endif
</div>
