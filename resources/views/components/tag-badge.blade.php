@props(['tag', 'style' => 'indigo'])

@php
    $colors = match($style) {
        'teal' => 'bg-teal-accent/10 text-teal-accent border-teal-accent/20 hover:bg-teal-accent/20',
        'amber' => 'bg-amber-accent/10 text-amber-accent border-amber-accent/20 hover:bg-amber-accent/20',
        'rose' => 'bg-rose-accent/10 text-rose-accent border-rose-accent/20 hover:bg-rose-accent/20',
        default => 'bg-indigo-accent/10 text-indigo-accent border-indigo-accent/20 hover:bg-indigo-accent/20',
    };
@endphp

<a href="{{ route('tags.show', $tag) }}"
   class="inline-block px-2 py-0.5 text-xs font-display font-medium rounded-full border transition-colors {{ $colors }}"
   title="View all {{ $tag->name }} content">
    {{ $tag->name }}
</a>
