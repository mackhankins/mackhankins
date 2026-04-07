@props(['project'])

<div class="card-glow group relative rounded-xl border border-base-600/50 bg-base-800/60 overflow-hidden">
    {{-- Clickable overlay for the card (behind tags) --}}
    <a href="{{ route('projects.show', $project) }}" class="absolute inset-0 z-0 rounded-xl" aria-label="{{ $project->name }}"></a>

    <div class="relative z-10 pointer-events-none">
        @if($project->featured_image)
            <div class="aspect-video overflow-hidden bg-base-700">
                <img src="{{ Storage::url($project->featured_image) }}"
                     alt="{{ $project->name }}"
                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                     loading="lazy">
            </div>
        @else
            <div class="aspect-video bg-gradient-to-br from-base-700 to-base-800 flex items-center justify-center">
                <svg class="w-10 h-10 text-base-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
        @endif

        <div class="p-5">
            <h3 class="font-display font-bold text-lg text-base-50 group-hover:text-amber-accent transition-colors">
                {{ $project->name }}
            </h3>

            @if($project->short_description)
                <p class="mt-2 text-sm text-base-300 leading-relaxed line-clamp-2">
                    {{ $project->short_description }}
                </p>
            @endif

            @if($project->tags->isNotEmpty())
                <div class="mt-4 flex flex-wrap gap-1.5 pointer-events-auto relative z-20">
                    @foreach($project->tags->take(4) as $tag)
                        <x-tag-badge :tag="$tag" style="teal" />
                    @endforeach
                    @if($project->tags->count() > 4)
                        <span class="inline-block px-2 py-0.5 text-xs font-display font-medium rounded-full bg-base-700/80 text-base-400 border border-base-600/50">
                            +{{ $project->tags->count() - 4 }}
                        </span>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
