<div class="hero-terminal hidden lg:block w-[420px] shrink-0 select-none pointer-events-none" aria-hidden="true">
    <div class="rounded-lg border border-base-600/30 bg-base-800/50 backdrop-blur-sm overflow-hidden shadow-lg shadow-black/10 dark:shadow-none">
        {{-- Title bar --}}
        <div class="flex items-center gap-2 px-4 py-2.5 border-b border-base-600/20">
            <span class="w-2.5 h-2.5 rounded-full bg-rose-accent/60"></span>
            <span class="w-2.5 h-2.5 rounded-full bg-amber-accent/60"></span>
            <span class="w-2.5 h-2.5 rounded-full bg-teal-accent/60"></span>
            <span class="ml-2 text-[11px] font-mono text-base-400 tracking-wide">~/projects</span>
        </div>

        {{-- Terminal body — whitespace-pre preserves indentation --}}
        <div class="px-4 py-4 font-mono text-[13px] leading-[1.7] whitespace-pre space-y-0">
<div class="terminal-line" data-delay="400"><span class="text-teal-accent">$</span> <span class="text-base-200">git rebase main</span></div>
<div class="terminal-line" data-delay="1200"><span class="text-rose-accent">CONFLICT</span> <span class="text-base-400">(content): Merge conflict</span></div>
<div class="terminal-line" data-delay="2000"><span class="text-base-400">error: could not apply 3f2a1b7</span></div>
<div class="terminal-line" data-delay="3000"><span class="text-teal-accent">$</span> <span class="text-base-200">git rebase --abort</span></div>
<div class="terminal-line" data-delay="3800"><span class="text-teal-accent">$</span> <span class="text-base-200">git merge main</span></div>
        </div>
    </div>
</div>
