<x-layouts.app :title="'About'">
    <section class="relative py-24">
        <x-gutter-grid />
        <div class="relative z-10 max-w-6xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
                {{-- Left column --}}
                <div class="lg:col-span-7">
                    {{-- Intro with inline avatar --}}
                    <div class="animate-fade-up">
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
                                <p class="font-display text-sm font-semibold tracking-widest uppercase text-rose-accent">
                                    About
                                </p>
                                <h1 class="mt-3 font-display font-extrabold text-4xl md:text-5xl tracking-tight text-base-50">
                                    Hi, I'm Mack
                                </h1>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 space-y-6 font-body text-base-200 text-lg leading-relaxed animate-fade-up" style="animation-delay: 0.15s;">
                        <p>
                            I'm a developer who loves building things that work. From internal dashboards to
                            complex data pipelines, I spend most of my time crafting tools that make people's
                            work easier and more efficient.
                        </p>
                        <p>
                            Most of my projects live behind company walls — proprietary tools, internal systems,
                            and infrastructure that can't be shared publicly. This site exists to showcase the
                            work I <em>can</em> share and to write about the things I learn along the way.
                        </p>
                        <p>
                            When I'm not coding, you'll find me exploring new technologies, contributing to
                            open source when I can, and always looking for the next interesting problem to solve.
                        </p>
                    </div>

                    {{-- Skills / Expertise --}}
                    <div class="mt-16 reveal">
                        <h2 class="font-display font-bold text-2xl tracking-tight text-base-50 mb-6">
                            What I work with
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="rounded-xl border border-base-600/50 bg-base-800/40 p-5">
                                <h3 class="font-display font-bold text-base-50 mb-3">Backend</h3>
                                <div class="flex flex-wrap gap-3">
                                    <x-about-skill-icon icon="si-php" label="PHP" />
                                    <x-about-skill-icon icon="si-laravel" label="Laravel" />
                                    <x-about-skill-icon icon="si-python" label="Python" />
                                    <x-about-skill-icon icon="si-gnubash" label="Bash" />
                                    <x-about-skill-icon icon="si-nodedotjs" label="Node.js" />
                                </div>
                            </div>

                            <div class="rounded-xl border border-base-600/50 bg-base-800/40 p-5">
                                <h3 class="font-display font-bold text-base-50 mb-3">Frontend</h3>
                                <div class="flex flex-wrap gap-3">
                                    <x-about-skill-icon icon="si-livewire" label="Livewire" />
                                    <x-about-skill-icon icon="si-alpinedotjs" label="Alpine.js" />
                                    <x-about-skill-icon icon="si-react" label="React" />
                                    <x-about-skill-icon icon="si-tailwindcss" label="Tailwind" />
                                </div>
                            </div>

                            <div class="rounded-xl border border-base-600/50 bg-base-800/40 p-5">
                                <h3 class="font-display font-bold text-base-50 mb-3">Data & Infrastructure</h3>
                                <div class="flex flex-wrap gap-3">
                                    <x-about-skill-icon icon="si-mysql" label="MySQL" />
                                    <x-about-skill-icon icon="si-postgresql" label="PostgreSQL" />
                                    <x-about-skill-icon icon="si-redis" label="Redis" />
                                    <x-about-skill-icon icon="si-docker" label="Docker" />
                                    <x-about-skill-icon icon="si-githubactions" label="CI/CD" />
                                </div>
                            </div>

                            <div class="rounded-xl border border-base-600/50 bg-base-800/40 p-5">
                                <h3 class="font-display font-bold text-base-50 mb-3">Tools & Interests</h3>
                                <div class="flex flex-wrap gap-3">
                                    <x-about-skill-icon icon="si-filament" label="Filament" />
                                    <x-about-skill-icon icon="si-github" label="GitHub" />
                                    <x-about-skill-icon icon="si-anthropic" label="Claude" />
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Work History --}}
                    @if($experiences->isNotEmpty())
                        <div class="mt-16 reveal">
                            <h2 class="font-display font-bold text-2xl tracking-tight text-base-50 mb-8">
                                Experience
                            </h2>
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
                                                    <h3 class="font-display font-bold text-base-50">{{ $experience->title }}</h3>
                                                    <span class="text-xs font-mono text-base-400 shrink-0">
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
                            <h2 class="font-display font-bold text-2xl tracking-tight text-base-50 mb-6">
                                Certifications
                            </h2>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($certifications as $certification)
                                    <div class="group rounded-xl border border-base-600/50 bg-base-800/40 p-4 {{ $certification->credential_url ? 'hover:border-amber-accent/30 transition-colors' : '' }}">
                                        @if($certification->credential_url)
                                            <a href="{{ $certification->credential_url }}" target="_blank" rel="noopener noreferrer" class="block">
                                        @endif
                                            <div class="flex items-start gap-3">
                                                @if($certification->icon)
                                                    <div class="w-8 h-8 rounded-lg bg-base-700 flex items-center justify-center shrink-0 group-hover:bg-amber-accent/10 transition-colors">
                                                        <x-dynamic-component :component="'si-' . $certification->icon" class="w-4 h-4 text-base-300 group-hover:text-amber-accent transition-colors" />
                                                    </div>
                                                @else
                                                    <div class="w-8 h-8 rounded-lg bg-base-700 flex items-center justify-center shrink-0 group-hover:bg-amber-accent/10 transition-colors">
                                                        <svg class="w-4 h-4 text-base-300 group-hover:text-amber-accent transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                                                    </div>
                                                @endif
                                                <div class="min-w-0">
                                                    <h3 class="font-display font-bold text-sm text-base-50 group-hover:text-amber-accent transition-colors">{{ $certification->name }}</h3>
                                                    <p class="text-xs text-base-400 mt-0.5">{{ $certification->issuer }}</p>
                                                    <p class="text-xs text-base-500 mt-0.5">{{ $certification->earned_at->format('M Y') }}</p>
                                                </div>
                                                @if($certification->credential_url)
                                                    <svg class="w-4 h-4 text-base-500 group-hover:text-amber-accent transition-colors shrink-0 ml-auto mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                @endif
                                            </div>
                                        @if($certification->credential_url)
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Right column - Sidebar --}}
                <aside class="lg:col-span-5">
                    <div class="lg:sticky lg:top-24 space-y-6">
                        {{-- Quick facts --}}
                        <div class="rounded-xl border border-base-600/50 bg-base-800/40 p-6 reveal">
                            <h3 class="font-display font-bold text-lg text-base-50 mb-4">Quick Facts</h3>
                            <dl class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <dt class="text-base-400 font-display">Focus</dt>
                                    <dd class="text-base-200">Full-stack Development</dd>
                                </div>
                                <div class="h-px bg-base-700/50"></div>
                                <div class="flex justify-between">
                                    <dt class="text-base-400 font-display">Primary Stack</dt>
                                    <dd class="text-base-200">Laravel + Livewire</dd>
                                </div>
                                <div class="h-px bg-base-700/50"></div>
                                <div class="flex justify-between">
                                    <dt class="text-base-400 font-display">Specialty</dt>
                                    <dd class="text-base-200">Internal Tools</dd>
                                </div>
                            </dl>
                        </div>

                        {{-- Connect --}}
                        <div class="rounded-xl border border-base-600/50 bg-base-800/40 p-6 reveal">
                            <h3 class="font-display font-bold text-lg text-base-50 mb-4">Connect</h3>
                            <div class="space-y-3">
                                <a href="https://github.com/mackhankins" target="_blank" rel="noopener noreferrer"
                                   class="flex items-center gap-3 text-base-300 hover:text-amber-accent transition-colors group">
                                    <div class="w-9 h-9 rounded-lg bg-base-700 flex items-center justify-center group-hover:bg-amber-accent/10 transition-colors">
                                        <x-si-github class="w-4 h-4" />
                                    </div>
                                    <div>
                                        <span class="text-sm font-display font-medium">GitHub</span>
                                        <p class="text-xs text-base-400">@mackhankins</p>
                                    </div>
                                </a>

                                <a href="https://x.com/mackhankins" target="_blank" rel="noopener noreferrer"
                                   class="flex items-center gap-3 text-base-300 hover:text-amber-accent transition-colors group">
                                    <div class="w-9 h-9 rounded-lg bg-base-700 flex items-center justify-center group-hover:bg-amber-accent/10 transition-colors">
                                        <x-si-x class="w-4 h-4" />
                                    </div>
                                    <div>
                                        <span class="text-sm font-display font-medium">X / Twitter</span>
                                        <p class="text-xs text-base-400">@mackhankins</p>
                                    </div>
                                </a>

                                <a href="https://www.linkedin.com/in/mack-hankins/" target="_blank" rel="noopener noreferrer"
                                   class="flex items-center gap-3 text-base-300 hover:text-amber-accent transition-colors group">
                                    <div class="w-9 h-9 rounded-lg bg-base-700 flex items-center justify-center group-hover:bg-amber-accent/10 transition-colors">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                    </div>
                                    <div>
                                        <span class="text-sm font-display font-medium">LinkedIn</span>
                                        <p class="text-xs text-base-400">Mack Hankins</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</x-layouts.app>
