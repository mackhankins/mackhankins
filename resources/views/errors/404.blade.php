<x-layouts.app :title="'404'">
    <section class="relative min-h-[80vh] flex items-center overflow-hidden">
        {{-- Flowing contour lines --}}
        <x-flow-lines variant="404" />
        <x-gutter-grid />

        <div class="relative z-10 max-w-6xl mx-auto px-6 py-24 text-center">
            <p class="font-display text-sm font-semibold tracking-widest uppercase text-rose-accent animate-fade-up" style="animation-delay: 0.1s;">
                404
            </p>

            <h1 class="mt-6 font-display font-extrabold text-5xl sm:text-6xl md:text-7xl tracking-tight text-base-50 animate-fade-up" style="animation-delay: 0.2s;">
                Nothing here
            </h1>

            <p class="mt-6 text-lg text-base-300 font-body leading-relaxed max-w-md mx-auto animate-fade-up" style="animation-delay: 0.3s;">
                This isn't stuff or things. Whatever you're looking for, it's not here.
            </p>

            <div class="mt-10 flex flex-wrap justify-center gap-4 animate-fade-up" style="animation-delay: 0.4s;">
                <a href="{{ route('home') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-amber-accent/10 text-amber-accent border border-amber-accent/20 font-display font-semibold text-sm hover:bg-amber-accent/20 hover:border-amber-accent/40 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/></svg>
                    Go home
                </a>
                <a href="{{ route('projects.index') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 rounded-lg text-base-300 border border-base-600/50 font-display font-semibold text-sm hover:text-base-50 hover:border-base-500 transition-all">
                    View Stuff
                </a>
                <a href="{{ route('blog.index') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 rounded-lg text-base-300 border border-base-600/50 font-display font-semibold text-sm hover:text-base-50 hover:border-base-500 transition-all">
                    Read Things
                </a>
            </div>
        </div>

    </section>
</x-layouts.app>
