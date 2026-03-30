<x-layouts.app :title="$project->name">
    <article class="py-24">
        <div class="max-w-6xl mx-auto px-6">
            {{-- Back link --}}
            <a href="{{ route('projects.index') }}"
               class="inline-flex items-center gap-2 text-sm font-display font-medium text-base-400 hover:text-base-50 transition-colors mb-8 animate-fade-in">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/></svg>
                All projects
            </a>

            {{-- Header --}}
            <div class="max-w-3xl animate-fade-up" style="animation-delay: 0.1s;">
                <h1 class="font-display font-extrabold text-4xl md:text-5xl tracking-tight text-base-50">
                    {{ $project->name }}
                </h1>

                @if($project->short_description)
                    <p class="mt-4 text-lg text-base-300 font-body leading-relaxed">
                        {{ $project->short_description }}
                    </p>
                @endif

                {{-- Meta row --}}
                <div class="mt-6 flex flex-wrap items-center gap-4">
                    @if($project->url)
                        <a href="{{ $project->url }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-amber-accent/10 text-amber-accent border border-amber-accent/20 text-sm font-display font-semibold hover:bg-amber-accent/20 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            Visit Project
                        </a>
                    @endif
                    @if($project->repository_url)
                        <a href="{{ $project->repository_url }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-base-300 border border-base-600/50 text-sm font-display font-semibold hover:text-base-50 hover:border-base-500 transition-all">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
                            Source Code
                        </a>
                    @endif
                </div>
            </div>

            {{-- Featured image --}}
            @if($project->featured_image)
                <div class="mt-12 rounded-xl overflow-hidden border border-base-600/50 animate-fade-up" style="animation-delay: 0.2s;">
                    <img src="{{ Storage::url($project->featured_image) }}"
                         alt="{{ $project->name }}"
                         class="w-full h-auto">
                </div>
            @endif

            {{-- Content --}}
            <div class="mt-12 max-w-3xl animate-fade-up" style="animation-delay: 0.3s;">
                <div class="prose-custom">
                    {!! str($project->description)->markdown(extensions: [new \Torchlight\Commonmark\V2\TorchlightExtension]) !!}
                </div>
            </div>

            {{-- Tech stack --}}
            @if($project->tech_stack)
                <div class="mt-12 max-w-3xl reveal">
                    <h3 class="font-display font-bold text-lg text-base-50 mb-4">Tech Stack</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($project->tech_stack as $tech)
                            <span class="inline-block px-3 py-1.5 text-sm font-display font-medium rounded-lg bg-base-800 text-teal-accent border border-base-600/50">
                                {{ $tech }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Tags --}}
            @if($project->tags->isNotEmpty())
                <div class="mt-8 max-w-3xl reveal">
                    <h3 class="font-display font-bold text-lg text-base-50 mb-4">Tags</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($project->tags as $tag)
                            <x-tag-badge :tag="$tag" route="projects.index" style="indigo" />
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Screenshots --}}
            @if($project->screenshots)
                <div class="mt-12 max-w-3xl reveal">
                    <h3 class="font-display font-bold text-lg text-base-50 mb-4">Screenshots</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($project->screenshots as $screenshot)
                            <div class="rounded-lg overflow-hidden border border-base-600/50">
                                <img src="{{ Storage::url($screenshot) }}"
                                     alt="{{ $project->name }} screenshot"
                                     class="w-full h-auto"
                                     loading="lazy">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </article>
</x-layouts.app>
