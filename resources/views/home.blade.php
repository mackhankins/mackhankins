<x-layouts.app>
    {{-- Hero Section --}}
    <section class="relative min-h-[65vh] flex items-center overflow-hidden">
        {{-- Flowing contour lines --}}
        <x-flow-lines variant="hero" />

        <div class="relative z-10 max-w-6xl mx-auto px-6 py-24">
            <div class="max-w-3xl">
                <p class="font-display text-sm font-semibold tracking-widest uppercase text-amber-accent animate-fade-up" style="animation-delay: 0.1s;">
                    Developer &amp; Creator
                </p>

                <h1 class="mt-6 font-display font-extrabold text-5xl sm:text-6xl md:text-6xl tracking-tight leading-[1.05] text-base-50 animate-fade-up" style="animation-delay: 0.2s;">
                    <span class="bg-gradient-to-r from-amber-accent via-teal-accent to-indigo-accent bg-clip-text text-transparent uppercase">Stuff &amp; Things</span>
                </h1>

                <p class="mt-6 text-lg md:text-xl text-base-300 leading-relaxed font-body max-w-xl animate-fade-up" style="animation-delay: 0.3s;">
                    I build <span class="hero-rotate-word inline-block text-amber-accent font-semibold">tools</span>
                    that solve real problems.
                    <br class="hidden sm:block">
                    Most of my work lives behind closed doors — this is where I share what I can.
                </p>

                <div class="mt-10 flex flex-wrap gap-4 animate-fade-up" style="animation-delay: 0.4s;">
                    <a href="{{ route('blog.index') }}"
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-amber-accent/10 text-amber-accent border border-amber-accent/20 font-display font-semibold text-sm hover:bg-amber-accent/20 hover:border-amber-accent/40 transition-all">
                        Read Stuff
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <a href="{{ route('projects.index') }}"
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-lg text-base-300 border border-base-600/50 font-display font-semibold text-sm hover:text-base-50 hover:border-base-500 transition-all">
                        View Things
                    </a>
                </div>
            </div>
        </div>

    </section>

    {{-- Post-hero content with gutter grid --}}
    <div class="relative">
        <x-gutter-grid />

        {{-- Latest Stuff (Blog Posts) --}}
        @if($latestPosts->isNotEmpty())
            <section class="relative z-10 py-24 reveal">
                <div class="max-w-6xl mx-auto px-6">
                    <x-section-heading
                        title="Latest Stuff"
                        subtitle="Thoughts on development, tools, and building things."
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

                        </div>
                    </div>
                </div>
            </section>
        @endif

        {{-- Featured Things (Projects) --}}
        @if($featuredProjects->isNotEmpty())
            <div class="relative z-10 max-w-6xl mx-auto px-6">
                <div class="h-px bg-gradient-to-r from-transparent via-base-600/60 to-transparent"></div>
            </div>
            <section class="relative z-10 py-24 reveal">
                <div class="max-w-6xl mx-auto px-6">
                    <x-section-heading
                        title="Featured Things"
                        subtitle="A selection of things I've built."
                    />

                    @php
                        $featured = $featuredProjects->take(2);
                        $remaining = $featuredProjects->skip(2)->values();
                        $leftCol = $remaining->take(3);
                        $rightCol = $remaining->skip(3)->take(3);
                    @endphp

                    {{-- Two featured projects side by side --}}
                    <div class="relative">
                        {{-- Single continuous center line --}}
                        <div class="absolute left-1/2 -translate-x-1/2 top-0 bottom-0 w-px hidden md:block bg-gradient-to-b from-teal-accent/25 via-amber-accent/20 to-transparent"></div>

                        <div class="grid grid-cols-1 md:grid-cols-2">
                            {{-- Featured (top 2, bigger) --}}
                            @foreach($featured as $project)
                                <div class="reveal">
                                    <div class="h-px bg-gradient-to-r {{ $loop->first ? 'from-transparent via-base-600/40 to-base-600/60 md:to-base-600/40' : 'from-base-600/60 md:from-base-600/40 via-base-600/40 to-transparent' }}"></div>
                                    <a href="{{ route('projects.show', $project) }}"
                                       class="{{ $loop->first ? 'md:pr-10' : 'md:pl-10' }} group block py-6">
                                        <h3 class="font-display font-extrabold text-xl md:text-2xl text-base-50 group-hover:text-teal-accent transition-colors tracking-tight">
                                            {{ $project->name }}
                                        </h3>
                                        @if($project->short_description)
                                            <p class="mt-2 text-sm text-base-300 font-body leading-relaxed line-clamp-2">
                                                {{ $project->short_description }}
                                            </p>
                                        @endif
                                        @if($project->tech_stack)
                                            <div class="mt-3 flex flex-wrap gap-1.5">
                                                @foreach(array_slice($project->tech_stack, 0, 4) as $tech)
                                                    <span class="text-xs font-display font-medium text-base-400">{{ $tech }}@if(!$loop->last) <span class="text-base-600 mx-0.5">/</span> @endif</span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </a>
                                </div>
                            @endforeach

                            {{-- Remaining (smaller rows) --}}
                            @foreach($remaining as $project)
                                <div class="reveal">
                                    <div class="h-px bg-gradient-to-r {{ $loop->index % 2 === 0 ? 'from-transparent via-base-600/40 to-base-600/60 md:to-base-600/40' : 'from-base-600/60 md:from-base-600/40 via-base-600/40 to-transparent' }}"></div>
                                    <a href="{{ route('projects.show', $project) }}"
                                       class="{{ $loop->index % 2 === 0 ? 'md:pr-10' : 'md:pl-10' }} group block py-5">
                                        <h4 class="font-display font-bold text-base md:text-lg text-base-50 group-hover:text-amber-accent transition-colors tracking-tight">
                                            {{ $project->name }}
                                        </h4>
                                        @if($project->short_description)
                                            <p class="mt-1 text-sm text-base-400 font-body leading-relaxed line-clamp-1">
                                                {{ $project->short_description }}
                                            </p>
                                        @endif
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        @endif

        {{-- About CTA --}}
        <div class="relative z-10 max-w-6xl mx-auto px-6">
            <div class="h-px bg-gradient-to-r from-transparent via-base-600/60 to-transparent"></div>
        </div>
        <section class="relative z-10 py-24 reveal">
            <div class="max-w-6xl mx-auto px-6">
                <div class="relative rounded-2xl border border-base-600/50 bg-base-800/40 p-8 md:p-12 overflow-hidden">
                    <x-flow-lines variant="card" />
                    <div class="relative z-10 flex flex-col md:flex-row items-center gap-10 md:gap-16">
                        {{-- Text --}}
                        <div class="flex-1 min-w-0">
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
                        {{-- Avatar --}}
                        <div class="relative shrink-0">
                            <div class="absolute -inset-1 rounded-full bg-gradient-to-br from-amber-accent via-rose-accent to-teal-accent opacity-30 dark:opacity-20 blur-sm"></div>
                            <img src="{{ asset('images/avatar.jpg') }}"
                                 alt="Mack Hankins"
                                 class="relative w-36 h-36 md:w-44 md:h-44 rounded-full object-cover ring-2 ring-base-700"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-layouts.app>
