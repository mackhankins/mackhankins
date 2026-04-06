@props(['icon', 'label'])

<div class="flex items-center gap-2 text-sm text-base-300" title="{{ $label }}">
    <x-dynamic-component :component="$icon" class="w-4 h-4 shrink-0" />
    <span>{{ $label }}</span>
</div>
