<x-layouts.app :title="'About'">
    <section class="py-24">
        <div class="max-w-6xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
                {{-- Left column --}}
                <div class="lg:col-span-7">
                    <div class="animate-fade-up">
                        <p class="font-display text-sm font-semibold tracking-widest uppercase text-rose-accent">
                            About
                        </p>
                        <h1 class="mt-4 font-display font-extrabold text-4xl md:text-5xl tracking-tight text-base-50">
                            Hi, I'm Mack
                        </h1>
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
                                <div class="w-8 h-8 rounded-lg bg-amber-accent/10 flex items-center justify-center mb-3">
                                    <svg class="w-4 h-4 text-amber-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                </div>
                                <h3 class="font-display font-bold text-base-50">Backend</h3>
                                <p class="mt-1 text-sm text-base-300">PHP, Laravel, Python, Node.js, REST APIs, GraphQL</p>
                            </div>

                            <div class="rounded-xl border border-base-600/50 bg-base-800/40 p-5">
                                <div class="w-8 h-8 rounded-lg bg-teal-accent/10 flex items-center justify-center mb-3">
                                    <svg class="w-4 h-4 text-teal-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </div>
                                <h3 class="font-display font-bold text-base-50">Frontend</h3>
                                <p class="mt-1 text-sm text-base-300">Vue.js, React, Livewire, Alpine.js, Tailwind CSS</p>
                            </div>

                            <div class="rounded-xl border border-base-600/50 bg-base-800/40 p-5">
                                <div class="w-8 h-8 rounded-lg bg-indigo-accent/10 flex items-center justify-center mb-3">
                                    <svg class="w-4 h-4 text-indigo-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/></svg>
                                </div>
                                <h3 class="font-display font-bold text-base-50">Data & Infrastructure</h3>
                                <p class="mt-1 text-sm text-base-300">MySQL, PostgreSQL, Redis, Docker, CI/CD</p>
                            </div>

                            <div class="rounded-xl border border-base-600/50 bg-base-800/40 p-5">
                                <div class="w-8 h-8 rounded-lg bg-rose-accent/10 flex items-center justify-center mb-3">
                                    <svg class="w-4 h-4 text-rose-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                </div>
                                <h3 class="font-display font-bold text-base-50">Tools & Interests</h3>
                                <p class="mt-1 text-sm text-base-300">Filament, internal tooling, automation, DX</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right column - Sidebar --}}
                <aside class="lg:col-span-5">
                    <div class="lg:sticky lg:top-24 space-y-8">
                        {{-- Connect --}}
                        <div class="rounded-xl border border-base-600/50 bg-base-800/40 p-6 reveal">
                            <h3 class="font-display font-bold text-lg text-base-50 mb-4">Connect</h3>
                            <div class="space-y-3">
                                <a href="https://github.com/mackhankins" target="_blank" rel="noopener noreferrer"
                                   class="flex items-center gap-3 text-base-300 hover:text-amber-accent transition-colors group">
                                    <div class="w-9 h-9 rounded-lg bg-base-700 flex items-center justify-center group-hover:bg-amber-accent/10 transition-colors">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
                                    </div>
                                    <div>
                                        <span class="text-sm font-display font-medium">GitHub</span>
                                        <p class="text-xs text-base-400">@mackhankins</p>
                                    </div>
                                </a>

                                <a href="https://x.com/mackhankins" target="_blank" rel="noopener noreferrer"
                                   class="flex items-center gap-3 text-base-300 hover:text-amber-accent transition-colors group">
                                    <div class="w-9 h-9 rounded-lg bg-base-700 flex items-center justify-center group-hover:bg-amber-accent/10 transition-colors">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
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
                                    <dd class="text-base-200">Laravel + Vue.js</dd>
                                </div>
                                <div class="h-px bg-base-700/50"></div>
                                <div class="flex justify-between">
                                    <dt class="text-base-400 font-display">Specialty</dt>
                                    <dd class="text-base-200">Internal Tools</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</x-layouts.app>
