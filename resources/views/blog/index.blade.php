<x-layouts.app :title="'Things'">
    <section class="py-24">
        <div class="max-w-6xl mx-auto px-6">
            {{-- Page header --}}
            <div class="max-w-2xl mb-16 animate-fade-up">
                <p class="font-display text-sm font-semibold tracking-widest uppercase text-indigo-accent">
                    Things
                </p>
                <h1 class="mt-4 font-display font-extrabold text-4xl md:text-5xl tracking-tight text-base-50">
                    Writing &amp; Thoughts
                </h1>
                <p class="mt-4 text-base-300 font-body text-lg leading-relaxed">
                    Notes on development, tools, and the occasional deep dive into
                    stuff that interests me.
                </p>
            </div>

            {{-- Active filter --}}
            @if($activeTag)
                <div class="mb-8 flex items-center gap-3 animate-fade-up">
                    <span class="text-sm text-base-400 font-display">Filtered by:</span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-sm font-display font-medium rounded-full bg-indigo-accent/10 text-indigo-accent border border-indigo-accent/20">
                        {{ $activeTag->name }}
                        <a href="{{ route('blog.index') }}" class="ml-1 hover:text-base-50 transition-colors" aria-label="Clear filter">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    </span>
                </div>
            @endif

            {{-- Posts grid --}}
            @if($posts->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 stagger-children">
                    @foreach($posts as $post)
                        <div class="reveal">
                            <x-post-card :post="$post" />
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($posts->hasPages())
                    <div class="mt-12 flex justify-center">
                        {{ $posts->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-24">
                    <p class="text-base-400 font-display text-lg">No posts published yet. Check back soon.</p>
                </div>
            @endif
        </div>
    </section>
</x-layouts.app>
