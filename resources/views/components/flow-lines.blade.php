@props([
    'variant' => 'hero',
])

@if($variant === 'card')
<div class="absolute inset-0 pointer-events-none overflow-hidden flow-lines">
    <svg class="absolute inset-0 w-full h-full" viewBox="0 0 800 300" preserveAspectRatio="xMidYMid slice" fill="none" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <linearGradient id="card-flow" x1="0%" y1="0%" x2="100%" y2="0%">
                <stop offset="0%" stop-color="var(--color-teal-accent)" stop-opacity="0"/>
                <stop offset="40%" stop-color="var(--color-teal-accent)" stop-opacity="0.1"/>
                <stop offset="100%" stop-color="var(--color-teal-accent)" stop-opacity="0"/>
            </linearGradient>
        </defs>
        <path d="M 0 80 C 200 60, 400 120, 600 70 S 750 50, 800 90" stroke="url(#card-flow)" stroke-width="0.75" />
        <path d="M 0 160 C 250 140, 450 190, 650 150 S 770 130, 800 170" stroke="url(#card-flow)" stroke-width="0.5" opacity="0.5" />
    </svg>
</div>
@elseif($variant === '404')
<div class="absolute inset-0 pointer-events-none overflow-hidden flow-lines">
    <svg class="absolute inset-0 w-full h-full" viewBox="0 0 1200 800" preserveAspectRatio="xMidYMid slice" fill="none" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <linearGradient id="flow-rose" x1="0%" y1="0%" x2="100%" y2="0%">
                <stop offset="0%" stop-color="var(--color-rose-accent)" stop-opacity="0"/>
                <stop offset="40%" stop-color="var(--color-rose-accent)" stop-opacity="0.12"/>
                <stop offset="80%" stop-color="var(--color-amber-accent)" stop-opacity="0.08"/>
                <stop offset="100%" stop-color="var(--color-amber-accent)" stop-opacity="0"/>
            </linearGradient>
            <linearGradient id="flow-rose-subtle" x1="0%" y1="0%" x2="100%" y2="0%">
                <stop offset="0%" stop-color="var(--color-base-400)" stop-opacity="0"/>
                <stop offset="50%" stop-color="var(--color-base-400)" stop-opacity="0.05"/>
                <stop offset="100%" stop-color="var(--color-base-400)" stop-opacity="0"/>
            </linearGradient>
        </defs>

        <path d="M -100 350 C 200 280, 500 420, 750 340 S 1000 260, 1300 320" stroke="url(#flow-rose)" stroke-width="1" class="flow-line-1" />
        <path d="M -100 380 C 220 310, 520 440, 770 360 S 1020 280, 1300 345" stroke="url(#flow-rose)" stroke-width="0.5" opacity="0.5" class="flow-line-2" />
        <path d="M 1300 480 C 1000 440, 700 520, 450 460 S 200 400, -100 450" stroke="url(#flow-rose)" stroke-width="0.75" opacity="0.4" class="flow-line-3" />

        <path d="M -100 200 C 300 180, 600 240, 900 190 S 1100 160, 1300 200" stroke="url(#flow-rose-subtle)" stroke-width="0.5" class="flow-line-6" />
        <path d="M -100 600 C 400 580, 700 630, 1000 590 S 1150 570, 1300 590" stroke="url(#flow-rose-subtle)" stroke-width="0.5" class="flow-line-7" />
    </svg>
</div>
@endif
