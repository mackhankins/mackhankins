<x-layouts.app>
    {{-- Hero Section --}}
    <section class="relative min-h-[85vh] flex items-center overflow-hidden">
        {{-- Ambient glows --}}
        <div class="ambient-glow w-[500px] h-[500px] -top-48 -right-24 bg-teal-accent/15" style="animation-delay: 0s;"></div>
        <div class="ambient-glow w-[400px] h-[400px] top-1/3 -left-32 bg-amber-accent/12" style="animation-delay: 2s;"></div>
        <div class="ambient-glow w-[300px] h-[300px] bottom-0 right-1/4 bg-indigo-accent/10" style="animation-delay: 1s;"></div>

        <div class="relative z-10 max-w-6xl mx-auto px-6 py-24">
            <div class="max-w-3xl">
                <p class="font-display text-sm font-semibold tracking-widest uppercase text-amber-accent animate-fade-up" style="animation-delay: 0.1s;">
                    Developer &amp; Creator
                </p>

                <h1 class="mt-6 font-display font-extrabold text-5xl sm:text-6xl md:text-6xl tracking-tight leading-[1.05] text-base-50 animate-fade-up" style="animation-delay: 0.2s;">
                    <span class="bg-gradient-to-r from-amber-accent via-teal-accent to-indigo-accent bg-clip-text text-transparent uppercase">Stuff &amp; Things</span>
                </h1>

                <p class="mt-6 text-lg md:text-xl text-base-300 leading-relaxed font-body max-w-xl animate-fade-up" style="animation-delay: 0.3s;">
                    I build tools, applications, and systems that solve real problems.
                    Most of my work lives behind closed doors — this is where I share what I can.
                </p>

                <div class="mt-10 flex flex-wrap gap-4 animate-fade-up" style="animation-delay: 0.4s;">
                    <a href="{{ route('projects.index') }}"
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-amber-accent/10 text-amber-accent border border-amber-accent/20 font-display font-semibold text-sm hover:bg-amber-accent/20 hover:border-amber-accent/40 transition-all">
                        View Stuff
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <a href="{{ route('blog.index') }}"
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-lg text-base-300 border border-base-600/50 font-display font-semibold text-sm hover:text-base-50 hover:border-base-500 transition-all">
                        Read Things
                    </a>
                </div>
            </div>
        </div>

        {{-- Decorative grid lines --}}
        <div class="absolute inset-0 pointer-events-none opacity-[0.03]"
             style="background-image: linear-gradient(var(--color-base-400) 1px, transparent 1px), linear-gradient(to right, var(--color-base-400) 1px, transparent 1px); background-size: 60px 60px;"></div>
    </section>

    {{-- Featured Projects --}}
    @if($featuredProjects->isNotEmpty())
        <section class="py-24 reveal">
            <div class="max-w-6xl mx-auto px-6">
                <x-section-heading
                    title="Featured Stuff"
                    subtitle="A selection of stuff I've built."
                    :href="route('projects.index')"
                />

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 stagger-children">
                    @foreach($featuredProjects as $project)
                        <div class="reveal">
                            <x-project-card :project="$project" />
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Latest Posts --}}
    @if($latestPosts->isNotEmpty())
        <section class="py-24 border-t border-base-700/30 reveal">
            <div class="max-w-6xl mx-auto px-6">
                <x-section-heading
                    title="Latest Things"
                    subtitle="Thoughts on development, tools, and building stuff."
                    :href="route('blog.index')"
                />

                <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                    {{-- Featured latest post --}}
                    @if($latestPosts->first())
                        @php $featured = $latestPosts->first(); @endphp
                        <a href="{{ route('blog.show', $featured) }}"
                           class="lg:col-span-3 card-glow group relative rounded-2xl border border-base-600/50 bg-base-800/40 p-8 md:p-10 flex flex-col justify-between min-h-[320px] overflow-hidden reveal">
                            {{-- Decorative accent line --}}
                            <div class="absolute top-0 left-8 right-8 h-px bg-gradient-to-r from-transparent via-amber-accent/40 to-transparent"></div>

                            <div>
                                <div class="flex items-center gap-3 text-xs text-base-400 font-display">
                                    <span class="uppercase tracking-widest text-amber-accent font-semibold">Latest</span>
                                    <span class="w-1 h-1 rounded-full bg-base-500"></span>
                                    <time datetime="{{ $featured->published_at->toDateString() }}">
                                        {{ $featured->published_at->format('M d, Y') }}
                                    </time>
                                    <span class="w-1 h-1 rounded-full bg-base-500"></span>
                                    <span>{{ $featured->reading_time }} min read</span>
                                </div>

                                <h3 class="mt-5 font-display font-extrabold text-2xl md:text-3xl text-base-50 group-hover:text-amber-accent transition-colors leading-snug tracking-tight">
                                    {{ $featured->title }}
                                </h3>

                                @if($featured->excerpt)
                                    <p class="mt-4 text-base-300 font-body leading-relaxed line-clamp-3 max-w-xl">
                                        {{ $featured->excerpt }}
                                    </p>
                                @endif
                            </div>

                            <div class="mt-6 flex items-center gap-2 text-sm font-display font-semibold text-amber-accent group-hover:text-teal-accent transition-colors">
                                Read article
                                <svg class="w-4 h-4 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </div>
                        </a>
                    @endif

                    {{-- Remaining posts as compact list --}}
                    <div class="lg:col-span-2 flex flex-col gap-4 stagger-children">
                        @foreach($latestPosts->skip(1) as $post)
                            <a href="{{ route('blog.show', $post) }}"
                               class="card-glow group rounded-xl border border-base-600/50 bg-base-800/40 p-5 reveal">
                                <div class="flex items-center gap-3 text-xs text-base-400 font-display">
                                    <time datetime="{{ $post->published_at->toDateString() }}">
                                        {{ $post->published_at->format('M d, Y') }}
                                    </time>
                                    <span class="w-1 h-1 rounded-full bg-base-500"></span>
                                    <span>{{ $post->reading_time }} min</span>
                                </div>

                                <h3 class="mt-2 font-display font-bold text-base text-base-50 group-hover:text-amber-accent transition-colors leading-snug">
                                    {{ $post->title }}
                                </h3>

                                @if($post->excerpt)
                                    <p class="mt-2 text-sm text-base-400 leading-relaxed line-clamp-2">
                                        {{ $post->excerpt }}
                                    </p>
                                @endif
                            </a>
                        @endforeach

                        {{-- View all link --}}
                        <a href="{{ route('blog.index') }}"
                           class="flex items-center justify-center gap-2 rounded-xl border border-dashed border-base-600/50 p-5 text-sm font-display font-medium text-base-400 hover:text-amber-accent hover:border-amber-accent/30 transition-colors">
                            View all things
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- About CTA --}}
    <section class="py-24 border-t border-base-700/30 reveal">
        <div class="max-w-6xl mx-auto px-6">
            <div class="relative rounded-2xl border border-base-600/50 bg-base-800/40 p-8 md:p-12 overflow-hidden">
                <div class="ambient-glow w-[300px] h-[300px] -top-20 -right-20 bg-teal-accent/10"></div>
                <div class="relative z-10 max-w-lg">
                    <h2 class="font-display font-bold text-2xl md:text-3xl tracking-tight text-base-50">
                        A bit about me
                    </h2>
                    <p class="mt-4 text-base-300 font-body leading-relaxed">
                        I'm a developer who cares about building things that work well and look good doing it.
                        Most of my projects are internal tools, but I'm always working on something interesting.
                    </p>
                    <a href="{{ route('about') }}"
                       class="inline-flex items-center gap-2 mt-6 text-sm font-display font-semibold text-amber-accent hover:text-teal-accent transition-colors group">
                        Learn more about me
                        <span class="inline-block transition-transform group-hover:translate-x-0.5">&rarr;</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
