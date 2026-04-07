<x-layouts.app :title="$tag->name">
    <section class="relative py-24">
        <x-gutter-grid />
        <div class="relative z-10 max-w-6xl mx-auto px-6">
            {{-- Page header --}}
            <div class="max-w-2xl mb-16 animate-fade-up">
                <a href="{{ url()->previous() }}"
                   class="inline-flex items-center gap-2 text-sm font-display font-medium text-base-400 hover:text-base-50 transition-colors mb-8">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/></svg>
                    Back
                </a>

                <p class="font-display text-sm font-semibold tracking-widest uppercase text-indigo-accent">
                    Tag
                </p>
                <h1 class="mt-4 font-display font-extrabold text-4xl md:text-5xl tracking-tight text-base-50">
                    {{ $tag->name }}
                </h1>
                <p class="mt-4 text-base-300 font-body text-lg leading-relaxed">
                    Everything tagged with <span class="text-base-50 font-medium">{{ $tag->name }}</span> &mdash;
                    {{ $projects->count() }} {{ Str::plural('project', $projects->count()) }},
                    {{ $posts->count() }} {{ Str::plural('post', $posts->count()) }}.
                </p>
            </div>

            {{-- Projects section --}}
            @if($projects->isNotEmpty())
                <div class="mb-16 animate-fade-up" style="animation-delay: 0.1s;">
                    <div class="flex items-center gap-4 mb-8">
                        <h2 class="font-display font-bold text-xl text-base-50">Things</h2>
                        <div class="flex-1 h-px bg-base-700/50"></div>
                        <span class="font-display text-sm text-teal-accent">{{ $projects->count() }}</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 stagger-children">
                        @foreach($projects as $project)
                            <div class="reveal">
                                <x-project-card :project="$project" />
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Posts section --}}
            @if($posts->isNotEmpty())
                <div class="animate-fade-up" style="animation-delay: 0.2s;">
                    <div class="flex items-center gap-4 mb-8">
                        <h2 class="font-display font-bold text-xl text-base-50">Stuff</h2>
                        <div class="flex-1 h-px bg-base-700/50"></div>
                        <span class="font-display text-sm text-indigo-accent">{{ $posts->count() }}</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 stagger-children">
                        @foreach($posts as $post)
                            <div class="reveal">
                                <x-post-card :post="$post" />
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Empty state --}}
            @if($projects->isEmpty() && $posts->isEmpty())
                <div class="text-center py-24">
                    <p class="text-base-400 font-display text-lg">Nothing tagged with {{ $tag->name }} yet.</p>
                </div>
            @endif
        </div>
    </section>
</x-layouts.app>
