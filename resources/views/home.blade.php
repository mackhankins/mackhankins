<x-layouts.app>
    <div class="relative">
    <x-gutter-grid />

    {{-- Hero Section --}}
    <section class="relative min-h-[65vh] flex items-center overflow-hidden">
        <div class="relative z-10 max-w-6xl mx-auto px-6 py-24 w-full">
            <div class="flex items-center gap-12">
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

                <div class="relative z-20 mt-10 flex flex-wrap gap-4 animate-fade-up" style="animation-delay: 0.4s;">
                    <a href="{{ route('blog.index') }}"
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-base-900/90 text-amber-accent border border-amber-accent/20 font-display font-semibold text-sm hover:bg-amber-accent/20 hover:border-amber-accent/40 transition-all backdrop-blur-sm">
                        Read Stuff
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <a href="{{ route('projects.index') }}"
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-base-900/90 text-base-300 border border-base-600/50 font-display font-semibold text-sm hover:text-base-50 hover:border-base-500 transition-all backdrop-blur-sm">
                        View Things
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>

            <x-hero-terminal />
            </div>
        </div>

    </section>

        {{-- Latest Stuff (Blog Posts) --}}
        @if($latestPosts->isNotEmpty())
            <section class="relative z-10 py-24 reveal">
                <div class="max-w-6xl mx-auto px-6">
                    <x-section-heading
                        number="1"
                        title="Latest Stuff"
                        subtitle="Thoughts on development, tools, and building things."
                    />

                    {{-- Top line spanning full width --}}
                    <div class="h-px bg-gradient-to-r from-transparent via-base-600/40 to-transparent"></div>

                    <div class="relative">
                        {{-- Vertical divider between columns --}}
                        <div class="absolute left-1/2 -translate-x-1/2 top-0 bottom-0 w-px hidden lg:block bg-gradient-to-b from-amber-accent/25 via-teal-accent/20 to-transparent"></div>

                        <div class="grid grid-cols-1 lg:grid-cols-2">
                            {{-- Featured latest post (big left) --}}
                            @if($latestPosts->first())
                                @php $featured = $latestPosts->first(); @endphp
                                <div class="lg:pr-10 reveal">
                                    <a href="{{ route('blog.show', $featured) }}"
                                       class="group block py-8">
                                        <div class="flex items-center gap-3 text-xs text-base-400 font-display mb-3">
                                            <span class="uppercase tracking-widest text-amber-accent font-semibold">Latest</span>
                                            <span class="w-1 h-1 rounded-full bg-base-500"></span>
                                            <time datetime="{{ $featured->published_at->toDateString() }}">
                                                {{ $featured->published_at->format('M d, Y') }}
                                            </time>
                                            <span class="w-1 h-1 rounded-full bg-base-500"></span>
                                            <span>{{ $featured->reading_time }} min read</span>
                                        </div>

                                        <h3 class="font-display font-extrabold text-2xl md:text-3xl text-base-50 group-hover:text-amber-accent transition-colors leading-snug tracking-tight">
                                            {{ $featured->title }}
                                        </h3>

                                        @if($featured->excerpt)
                                            <p class="mt-3 text-base-300 font-body leading-relaxed line-clamp-3 max-w-xl">
                                                {{ $featured->excerpt }}
                                            </p>
                                        @endif

                                        <div class="mt-4 flex items-center gap-2 text-sm font-display font-semibold text-amber-accent group-hover:text-teal-accent transition-colors">
                                            Read article
                                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                        </div>
                                    </a>
                                </div>
                            @endif

                            {{-- Remaining posts as compact line items --}}
                            <div class="lg:pl-10 flex flex-col stagger-children">
                                @foreach($latestPosts->skip(1) as $post)
                                    <div class="reveal">
                                        <div class="h-px bg-gradient-to-r from-base-600/60 lg:from-base-600/40 via-base-600/40 to-transparent {{ $loop->first ? 'lg:hidden' : '' }}"></div>
                                        <a href="{{ route('blog.show', $post) }}"
                                           class="group block py-5">
                                            <div class="flex items-center gap-3 text-xs text-base-400 font-display mb-1">
                                                <time datetime="{{ $post->published_at->toDateString() }}">
                                                    {{ $post->published_at->format('M d, Y') }}
                                                </time>
                                                <span class="w-1 h-1 rounded-full bg-base-500"></span>
                                                <span>{{ $post->reading_time }} min</span>
                                            </div>

                                            <h4 class="font-display font-bold text-base text-base-50 group-hover:text-amber-accent transition-colors leading-snug tracking-tight">
                                                {{ $post->title }}
                                            </h4>

                                            @if($post->excerpt)
                                                <p class="mt-1 text-sm text-base-400 leading-relaxed line-clamp-2">
                                                    {{ $post->excerpt }}
                                                </p>
                                            @endif
                                        </a>
                                    </div>
                                @endforeach
                            </div>
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
                        number="2"
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

        {{-- About --}}
        <div class="relative z-10 max-w-6xl mx-auto px-6">
            <div class="h-px bg-gradient-to-r from-transparent via-base-600/60 to-transparent"></div>
        </div>
        <section id="about" class="relative z-10 py-24 reveal">
            <div class="max-w-6xl mx-auto px-6">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
                    {{-- Left column --}}
                    <div class="lg:col-span-7">
                        {{-- Intro with inline avatar --}}
                        <div class="flex items-start gap-6 sm:gap-8">
                            <div class="shrink-0">
                                <div class="relative">
                                    <div class="absolute -inset-1 rounded-full bg-gradient-to-br from-amber-accent via-rose-accent to-teal-accent opacity-30 dark:opacity-20 blur-sm"></div>
                                    <img src="{{ asset('images/avatar.jpg') }}"
                                         alt="Mack Hankins"
                                         class="relative w-24 h-24 sm:w-28 sm:h-28 rounded-full object-cover ring-2 ring-base-700 dark:ring-base-600"
                                    />
                                </div>
                            </div>
                            <div>
                                <x-section-heading
                                    number="3"
                                    title="A bit about me"
                                    subtitle="Hi, I'm Mack — a developer who loves building things that work."
                                />
                            </div>
                        </div>

                        {{-- Skills / Expertise --}}
                        <div class="mt-12 reveal">
                            <h3 class="font-display font-bold text-2xl tracking-tight text-base-50 mb-6">
                                What I work with
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="rounded-xl border border-base-600/50 bg-base-800/40 p-5 transition-colors hover:border-base-500">
                                    <h4 class="font-display font-bold text-base-50 mb-3">Backend</h4>
                                    <div class="flex flex-wrap gap-3">
                                        <x-about-skill-icon icon="si-php" label="PHP" />
                                        <x-about-skill-icon icon="si-laravel" label="Laravel" />
                                        <x-about-skill-icon icon="si-python" label="Python" />
                                        <x-about-skill-icon icon="si-gnubash" label="Bash" />
                                        <x-about-skill-icon icon="si-nodedotjs" label="Node.js" />
                                    </div>
                                </div>

                                <div class="rounded-xl border border-base-600/50 bg-base-800/40 p-5 transition-colors hover:border-base-500">
                                    <h4 class="font-display font-bold text-base-50 mb-3">Frontend</h4>
                                    <div class="flex flex-wrap gap-3">
                                        <x-about-skill-icon icon="si-livewire" label="Livewire" />
                                        <x-about-skill-icon icon="si-alpinedotjs" label="Alpine.js" />
                                        <x-about-skill-icon icon="si-react" label="React" />
                                        <x-about-skill-icon icon="si-tailwindcss" label="Tailwind" />
                                    </div>
                                </div>

                                <div class="rounded-xl border border-base-600/50 bg-base-800/40 p-5 transition-colors hover:border-base-500">
                                    <h4 class="font-display font-bold text-base-50 mb-3">Data & Infrastructure</h4>
                                    <div class="flex flex-wrap gap-3">
                                        <x-about-skill-icon icon="si-mysql" label="MySQL" />
                                        <x-about-skill-icon icon="si-postgresql" label="PostgreSQL" />
                                        <x-about-skill-icon icon="si-redis" label="Redis" />
                                        <x-about-skill-icon icon="si-docker" label="Docker" />
                                        <x-about-skill-icon icon="si-githubactions" label="CI/CD" />
                                    </div>
                                </div>

                                <div class="rounded-xl border border-base-600/50 bg-base-800/40 p-5 transition-colors hover:border-base-500">
                                    <h4 class="font-display font-bold text-base-50 mb-3">Tools & Interests</h4>
                                    <div class="flex flex-wrap gap-3">
                                        <x-about-skill-icon icon="si-filament" label="Filament" />
                                        <x-about-skill-icon icon="si-git" label="Git" />
                                        <x-about-skill-icon icon="si-anthropic" label="Claude" />
                                        <x-about-skill-icon icon="si-openai" label="Codex" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Work History --}}
                        @if($experiences->isNotEmpty())
                            <div class="mt-16 reveal">
                                <h3 class="font-display font-bold text-2xl tracking-tight text-base-50 mb-8">
                                    Experience
                                </h3>
                                <div class="relative">
                                    {{-- Timeline line --}}
                                    <div class="absolute left-[7px] top-2 bottom-2 w-px bg-gradient-to-b from-amber-accent/60 via-teal-accent/40 to-base-700/20"></div>

                                    <div class="space-y-8">
                                        @foreach($experiences as $experience)
                                            <div class="relative pl-8">
                                                {{-- Timeline dot --}}
                                                <div class="absolute left-0 top-1.5 w-[15px] h-[15px] rounded-full border-2 {{ $experience->isCurrent() ? 'border-amber-accent bg-amber-accent/20' : 'border-base-500 bg-base-800' }}"></div>

                                                <div>
                                                    <div class="flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-1">
                                                        <h4 class="font-display font-bold text-base-50">{{ $experience->title }}</h4>
                                                        <span class="text-xs font-mono shrink-0 {{ $experience->isCurrent() ? 'text-amber-accent' : 'text-base-400' }}">
                                                            {{ $experience->start_date->format('M Y') }} — {{ $experience->end_date ? $experience->end_date->format('M Y') : 'Present' }}
                                                        </span>
                                                    </div>
                                                    @if($experience->company_url)
                                                        <a href="{{ $experience->company_url }}" target="_blank" rel="noopener noreferrer"
                                                           class="text-sm text-teal-accent hover:text-amber-accent transition-colors">
                                                            {{ $experience->company }}
                                                        </a>
                                                    @else
                                                        <p class="text-sm text-teal-accent">{{ $experience->company }}</p>
                                                    @endif
                                                    @if($experience->description)
                                                        <p class="mt-2 text-sm text-base-300 leading-relaxed">{{ $experience->description }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Certifications --}}
                        @if($certifications->isNotEmpty())
                            <div class="mt-16 reveal">
                                <h3 class="font-display font-bold text-2xl tracking-tight text-base-50 mb-6">
                                    Certifications
                                </h3>
                                <div class="rounded-xl border border-base-600/50 bg-base-800/40 divide-y divide-base-700/50">
                                    @foreach($certifications as $certification)
                                        @if($certification->credential_url)
                                            <a href="{{ $certification->credential_url }}" target="_blank" rel="noopener noreferrer"
                                               class="group flex items-start gap-3 px-4 py-3.5 transition-colors hover:bg-base-700/30 {{ $loop->first ? 'rounded-t-xl' : '' }} {{ $loop->last ? 'rounded-b-xl' : '' }}">
                                        @else
                                            <div class="flex items-start gap-3 px-4 py-3.5">
                                        @endif
                                            @if($certification->icon)
                                                <x-dynamic-component :component="'si-' . $certification->icon" class="w-4 h-4 text-base-400 group-hover:text-amber-accent transition-colors shrink-0 mt-0.5" />
                                            @else
                                                <svg class="w-4 h-4 text-base-400 group-hover:text-amber-accent transition-colors shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                                            @endif
                                            <div class="min-w-0 flex-1">
                                                <h4 class="font-display font-bold text-sm text-base-50 group-hover:text-amber-accent transition-colors">{{ $certification->name }}</h4>
                                                <p class="text-xs text-base-400 mt-0.5">{{ $certification->issuer }} <span class="text-base-500">&middot;</span> <span class="font-mono text-base-500">{{ $certification->earned_at->format('M Y') }}</span></p>
                                            </div>
                                            @if($certification->credential_url)
                                                <svg class="w-3.5 h-3.5 text-base-500 group-hover:text-amber-accent transition-colors shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            @endif
                                        @if($certification->credential_url)
                                            </a>
                                        @else
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Right column - Sidebar --}}
                    <aside class="lg:col-span-5">
                        <div class="lg:sticky lg:top-24 space-y-12">
                            {{-- Quick facts --}}
                            <div class="reveal">
                                <div class="flex items-baseline gap-3 mb-5">
                                    <span class="h-px flex-1 bg-base-700/60"></span>
                                    <h3 class="font-display text-[11px] uppercase tracking-[0.2em] text-base-400">Quick Facts</h3>
                                </div>
                                <dl class="font-display">
                                    <div class="flex items-baseline justify-between py-2.5">
                                        <dt class="text-sm text-base-400">Focus</dt>
                                        <dd class="text-sm text-base-100">Full-stack Development</dd>
                                    </div>
                                    <div class="flex items-baseline justify-between py-2.5">
                                        <dt class="text-sm text-base-400">Primary Stack</dt>
                                        <dd class="text-sm text-base-100">Laravel + Livewire</dd>
                                    </div>
                                    <div class="flex items-baseline justify-between py-2.5">
                                        <dt class="text-sm text-base-400">Specialty</dt>
                                        <dd class="text-sm text-base-100">Internal Tools</dd>
                                    </div>
                                </dl>
                            </div>

                            {{-- Connect --}}
                            <div class="reveal">
                                <div class="flex items-baseline gap-3 mb-5">
                                    <span class="h-px flex-1 bg-base-700/60"></span>
                                    <h3 class="font-display text-[11px] uppercase tracking-[0.2em] text-base-400">Connect</h3>
                                </div>
                                <ul class="font-display">
                                    <li>
                                        <a href="https://github.com/mackhankins" target="_blank" rel="noopener noreferrer"
                                           class="group flex items-center gap-4 py-3 text-base-200 hover:text-amber-accent transition-colors">
                                            <x-si-github class="w-4 h-4 text-base-400 group-hover:text-amber-accent transition-colors shrink-0" />
                                            <span class="text-sm font-medium">GitHub</span>
                                            <span class="text-xs text-base-500 font-mono ml-auto">@mackhankins</span>
                                            <svg class="w-3.5 h-3.5 text-base-600 group-hover:text-amber-accent group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://x.com/mackhankins" target="_blank" rel="noopener noreferrer"
                                           class="group flex items-center gap-4 py-3 text-base-200 hover:text-amber-accent transition-colors">
                                            <x-si-x class="w-4 h-4 text-base-400 group-hover:text-amber-accent transition-colors shrink-0" />
                                            <span class="text-sm font-medium">X / Twitter</span>
                                            <span class="text-xs text-base-500 font-mono ml-auto">@mackhankins</span>
                                            <svg class="w-3.5 h-3.5 text-base-600 group-hover:text-amber-accent group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.linkedin.com/in/mack-hankins/" target="_blank" rel="noopener noreferrer"
                                           class="group flex items-center gap-4 py-3 text-base-200 hover:text-amber-accent transition-colors">
                                            <svg class="w-4 h-4 text-base-400 group-hover:text-amber-accent transition-colors shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                            <span class="text-sm font-medium">LinkedIn</span>
                                            <span class="text-xs text-base-500 font-mono ml-auto">mack-hankins</span>
                                            <svg class="w-3.5 h-3.5 text-base-600 group-hover:text-amber-accent group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </section>
    </div>
</x-layouts.app>
