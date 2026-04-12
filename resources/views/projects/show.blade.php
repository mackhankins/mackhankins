<x-layouts.app
    :title="$project->name"
    :meta-description="$project->short_description"
    :meta-image="$project->featured_image ? url(Storage::url($project->featured_image)) : null"
    :meta-url="route('projects.show', $project)"
>
    <article class="relative py-24">
        <x-gutter-grid />
        <div class="relative z-10 max-w-6xl mx-auto px-6">
            {{-- Back link --}}
            <a href="{{ route('projects.index') }}"
               class="inline-flex items-center gap-2 text-sm font-display font-medium text-base-400 hover:text-base-50 transition-colors mb-8 animate-fade-in">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/></svg>
                All things
            </a>

            {{-- Header --}}
            <div class="max-w-3xl animate-fade-up" style="animation-delay: 0.1s;">
                <h1 class="font-display font-extrabold text-4xl md:text-5xl tracking-tight text-base-50"
                    style="view-transition-name: project-title-{{ $project->id }};">
                    {{ $project->name }}
                </h1>

                @if($project->short_description)
                    <p class="mt-4 text-lg text-base-300 font-body leading-relaxed">
                        {{ $project->short_description }}
                    </p>
                @endif

                {{-- Meta row --}}
                <div class="mt-6 flex flex-wrap items-center gap-6">
                    @if($project->url)
                        <a href="{{ $project->url }}" target="_blank" rel="noopener noreferrer"
                           class="group inline-flex items-center gap-2 px-5 py-2.5 bg-amber-accent text-base-950 text-sm font-display font-bold tracking-wide hover:bg-base-50 transition-colors">
                            Visit Project
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H8M17 7v9"/></svg>
                        </a>
                    @endif
                    @if($project->repository_url)
                        <a href="{{ $project->repository_url }}" target="_blank" rel="noopener noreferrer"
                           class="group inline-flex items-center gap-2 text-sm font-display font-semibold text-base-300 hover:text-base-50 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
                            <span class="border-b border-base-600/50 group-hover:border-base-300 transition-colors pb-0.5">Source Code</span>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Featured image --}}
            @if($project->featured_image)
                <div class="mt-12 rounded-xl overflow-hidden border border-base-600/50 animate-fade-up" style="animation-delay: 0.2s;">
                    <img src="{{ Storage::url($project->featured_image) }}"
                         alt="{{ $project->name }}"
                         class="w-full h-auto"
                         style="view-transition-name: project-image-{{ $project->id }};">
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
                    <div class="flex items-baseline gap-3 mb-3">
                        <span class="font-mono text-[11px] uppercase tracking-[0.2em] text-base-500">Built with</span>
                        <span class="h-px flex-1 bg-base-700/60"></span>
                    </div>
                    <p class="font-mono text-sm text-base-200 leading-relaxed">
                        @foreach($project->tech_stack as $tech)
                            <span class="inline-block mr-1">{{ $tech }}</span>@if(!$loop->last)<span class="text-base-600 mr-1">·</span>@endif
                        @endforeach
                    </p>
                </div>
            @endif

            {{-- Tags --}}
            @if($project->tags->isNotEmpty())
                <div class="mt-8 max-w-3xl reveal">
                    <h3 class="font-display font-bold text-lg text-base-50 mb-4">Tags</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($project->tags as $tag)
                            <x-tag-badge :tag="$tag" />
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Screenshots --}}
            @if($project->screenshots)
                @php($screenshots = collect($project->screenshots)->values())
                <div
                    class="mt-12 max-w-3xl reveal"
                    x-data="{
                        open: false,
                        index: 0,
                        images: @js($screenshots->map(fn($s) => Storage::url($s))->all()),
                        show(i) { this.index = i; this.open = true; },
                        close() { this.open = false; },
                        prev() { this.index = (this.index - 1 + this.images.length) % this.images.length; },
                        next() { this.index = (this.index + 1) % this.images.length; },
                    }"
                    @keydown.escape.window="open && close()"
                    @keydown.arrow-left.window="open && prev()"
                    @keydown.arrow-right.window="open && next()"
                >
                    <h3 class="font-display font-bold text-lg text-base-50 mb-4">Screenshots</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                        @foreach($screenshots as $i => $screenshot)
                            <button
                                type="button"
                                @click="show({{ $i }})"
                                class="group relative aspect-square rounded-lg overflow-hidden border border-base-600/50 hover:border-amber-accent/60 transition-colors"
                            >
                                <img src="{{ Storage::url($screenshot) }}"
                                     alt="{{ $project->name }} screenshot {{ $i + 1 }}"
                                     class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                     loading="lazy">
                            </button>
                        @endforeach
                    </div>

                    {{-- Lightbox (teleported to body to escape .reveal transform containing block) --}}
                    <template x-teleport="body">
                    <div
                        x-cloak
                        x-show="open"
                        x-transition.opacity
                        @click.self="close()"
                        class="fixed inset-0 z-50 bg-base-950/95 backdrop-blur-sm flex items-center justify-center p-4 sm:p-8"
                    >
                        <button
                            type="button"
                            @click="close()"
                            aria-label="Close"
                            class="absolute top-4 right-4 w-10 h-10 flex items-center justify-center rounded-full text-base-300 hover:text-base-50 hover:bg-base-800/80 transition-colors"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>

                        <template x-if="images.length > 1">
                            <button
                                type="button"
                                @click.stop="prev()"
                                aria-label="Previous"
                                class="absolute left-2 sm:left-6 w-10 h-10 flex items-center justify-center rounded-full text-base-300 hover:text-base-50 hover:bg-base-800/80 transition-colors"
                            >
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                        </template>

                        <template x-if="images.length > 1">
                            <button
                                type="button"
                                @click.stop="next()"
                                aria-label="Next"
                                class="absolute right-2 sm:right-6 w-10 h-10 flex items-center justify-center rounded-full text-base-300 hover:text-base-50 hover:bg-base-800/80 transition-colors"
                            >
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        </template>

                        <img
                            :src="images[index]"
                            :alt="`{{ $project->name }} screenshot ${index + 1}`"
                            @click.stop
                            class="max-w-full max-h-full object-contain rounded-lg shadow-2xl"
                        >

                        <div
                            x-show="images.length > 1"
                            class="absolute bottom-4 left-1/2 -translate-x-1/2 text-sm font-display text-base-300"
                        >
                            <span x-text="index + 1"></span> / <span x-text="images.length"></span>
                        </div>
                    </div>
                    </template>
                </div>
            @endif
        </div>
    </article>
</x-layouts.app>
