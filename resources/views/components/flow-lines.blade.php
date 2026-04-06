@props([
    'variant' => 'hero',
])

@if($variant === 'hero')
<div class="absolute inset-0 pointer-events-none overflow-hidden flow-lines">
    <svg class="absolute inset-0 w-full h-full" viewBox="0 0 1200 800" preserveAspectRatio="xMidYMid slice" fill="none" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <linearGradient id="flow-amber" x1="0%" y1="0%" x2="100%" y2="0%">
                <stop offset="0%" stop-color="var(--color-amber-accent)" stop-opacity="0"/>
                <stop offset="30%" stop-color="var(--color-amber-accent)" stop-opacity="0.15"/>
                <stop offset="70%" stop-color="var(--color-teal-accent)" stop-opacity="0.12"/>
                <stop offset="100%" stop-color="var(--color-teal-accent)" stop-opacity="0"/>
            </linearGradient>
            <linearGradient id="flow-teal" x1="0%" y1="0%" x2="100%" y2="0%">
                <stop offset="0%" stop-color="var(--color-teal-accent)" stop-opacity="0"/>
                <stop offset="25%" stop-color="var(--color-teal-accent)" stop-opacity="0.1"/>
                <stop offset="75%" stop-color="var(--color-indigo-accent)" stop-opacity="0.08"/>
                <stop offset="100%" stop-color="var(--color-indigo-accent)" stop-opacity="0"/>
            </linearGradient>
            <linearGradient id="flow-indigo" x1="100%" y1="0%" x2="0%" y2="0%">
                <stop offset="0%" stop-color="var(--color-indigo-accent)" stop-opacity="0"/>
                <stop offset="35%" stop-color="var(--color-indigo-accent)" stop-opacity="0.08"/>
                <stop offset="65%" stop-color="var(--color-amber-accent)" stop-opacity="0.06"/>
                <stop offset="100%" stop-color="var(--color-amber-accent)" stop-opacity="0"/>
            </linearGradient>
            <linearGradient id="flow-subtle" x1="0%" y1="0%" x2="100%" y2="0%">
                <stop offset="0%" stop-color="var(--color-base-400)" stop-opacity="0"/>
                <stop offset="50%" stop-color="var(--color-base-400)" stop-opacity="0.06"/>
                <stop offset="100%" stop-color="var(--color-base-400)" stop-opacity="0"/>
            </linearGradient>

            {{-- Traveling pulse gradients --}}
            <linearGradient id="pulse-amber" x1="0%" y1="0%" x2="100%" y2="0%">
                <stop offset="0%" stop-color="var(--color-amber-accent)" stop-opacity="0"/>
                <stop offset="45%" stop-color="var(--color-amber-accent)" stop-opacity="0"/>
                <stop offset="50%" stop-color="var(--color-amber-accent)" stop-opacity="0.6"/>
                <stop offset="55%" stop-color="var(--color-amber-accent)" stop-opacity="0"/>
                <stop offset="100%" stop-color="var(--color-amber-accent)" stop-opacity="0"/>
                <animateTransform attributeName="gradientTransform" type="translate" from="-1 0" to="1 0" dur="4s" repeatCount="indefinite"/>
            </linearGradient>
            <linearGradient id="pulse-teal" x1="0%" y1="0%" x2="100%" y2="0%">
                <stop offset="0%" stop-color="var(--color-teal-accent)" stop-opacity="0"/>
                <stop offset="45%" stop-color="var(--color-teal-accent)" stop-opacity="0"/>
                <stop offset="50%" stop-color="var(--color-teal-accent)" stop-opacity="0.5"/>
                <stop offset="55%" stop-color="var(--color-teal-accent)" stop-opacity="0"/>
                <stop offset="100%" stop-color="var(--color-teal-accent)" stop-opacity="0"/>
                <animateTransform attributeName="gradientTransform" type="translate" from="-1 0" to="1 0" dur="5s" repeatCount="indefinite"/>
            </linearGradient>
            <linearGradient id="pulse-indigo" x1="0%" y1="0%" x2="100%" y2="0%" gradientUnits="objectBoundingBox">
                <stop offset="0%" stop-color="var(--color-indigo-accent)" stop-opacity="0"/>
                <stop offset="45%" stop-color="var(--color-indigo-accent)" stop-opacity="0"/>
                <stop offset="50%" stop-color="var(--color-indigo-accent)" stop-opacity="0.4"/>
                <stop offset="55%" stop-color="var(--color-indigo-accent)" stop-opacity="0"/>
                <stop offset="100%" stop-color="var(--color-indigo-accent)" stop-opacity="0"/>
                <animateTransform attributeName="gradientTransform" type="translate" from="1 0" to="-1 0" dur="6s" repeatCount="indefinite"/>
            </linearGradient>

            {{-- Soft glow filter --}}
            <filter id="glow" x="-20%" y="-20%" width="140%" height="140%">
                <feGaussianBlur in="SourceGraphic" stdDeviation="3" result="blur"/>
                <feMerge>
                    <feMergeNode in="blur"/>
                    <feMergeNode in="SourceGraphic"/>
                </feMerge>
            </filter>
        </defs>

        {{-- Primary flowing curves - amber to teal --}}
        <path d="M -100 320 C 200 280, 400 180, 600 220 S 900 340, 1300 260" stroke="url(#flow-amber)" stroke-width="1" class="flow-line-1" />
        <path d="M -100 350 C 200 310, 420 200, 620 240 S 920 370, 1300 290" stroke="url(#flow-amber)" stroke-width="0.5" opacity="0.6" class="flow-line-2" />

        {{-- Pulse overlay on primary curve --}}
        <path d="M -100 320 C 200 280, 400 180, 600 220 S 900 340, 1300 260" stroke="url(#pulse-amber)" stroke-width="2" filter="url(#glow)" />

        {{-- Secondary curves - teal to indigo --}}
        <path d="M -50 480 C 250 420, 500 520, 750 460 S 1000 380, 1300 430" stroke="url(#flow-teal)" stroke-width="1" class="flow-line-3" />
        <path d="M -50 510 C 260 450, 510 540, 760 480 S 1010 400, 1300 455" stroke="url(#flow-teal)" stroke-width="0.5" opacity="0.5" class="flow-line-4" />

        {{-- Pulse overlay on secondary curve --}}
        <path d="M -50 480 C 250 420, 500 520, 750 460 S 1000 380, 1300 430" stroke="url(#pulse-teal)" stroke-width="2" filter="url(#glow)" />

        {{-- Tertiary - counter-flow from right --}}
        <path d="M 1300 180 C 1000 220, 800 140, 550 200 S 200 280, -100 220" stroke="url(#flow-indigo)" stroke-width="0.75" class="flow-line-5" />

        {{-- Pulse overlay on tertiary --}}
        <path d="M 1300 180 C 1000 220, 800 140, 550 200 S 200 280, -100 220" stroke="url(#pulse-indigo)" stroke-width="1.5" filter="url(#glow)" />

        {{-- Subtle background lines for depth --}}
        <path d="M -100 150 C 300 120, 500 200, 800 160 S 1100 100, 1300 140" stroke="url(#flow-subtle)" stroke-width="0.5" class="flow-line-6" />
        <path d="M -100 600 C 300 560, 600 640, 900 580 S 1100 520, 1300 570" stroke="url(#flow-subtle)" stroke-width="0.5" class="flow-line-7" />
        <path d="M -100 700 C 400 680, 600 720, 900 690 S 1100 660, 1300 680" stroke="url(#flow-subtle)" stroke-width="0.5" class="flow-line-8" />
    </svg>
</div>
@elseif($variant === 'card')
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

        <path d="M -100 350 C 200 280, 500 420, 750 340 S 1000 260, 1300 320" stroke="url(#flow-rose)" stroke-width="1" />
        <path d="M -100 380 C 220 310, 520 440, 770 360 S 1020 280, 1300 345" stroke="url(#flow-rose)" stroke-width="0.5" opacity="0.5" />
        <path d="M 1300 480 C 1000 440, 700 520, 450 460 S 200 400, -100 450" stroke="url(#flow-rose)" stroke-width="0.75" opacity="0.4" />

        <path d="M -100 200 C 300 180, 600 240, 900 190 S 1100 160, 1300 200" stroke="url(#flow-rose-subtle)" stroke-width="0.5" />
        <path d="M -100 600 C 400 580, 700 630, 1000 590 S 1150 570, 1300 590" stroke="url(#flow-rose-subtle)" stroke-width="0.5" />
    </svg>
</div>
@endif
