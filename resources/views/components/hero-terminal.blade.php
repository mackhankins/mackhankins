<div class="hero-terminal hidden lg:block w-[420px] shrink-0 select-none pointer-events-none" aria-hidden="true">
    <div class="rounded-lg border border-base-600/30 bg-base-800/50 backdrop-blur-sm overflow-hidden shadow-lg shadow-black/10 dark:shadow-none">
        {{-- Title bar --}}
        <div class="flex items-center gap-2 px-4 py-2.5 border-b border-base-600/20">
            <span class="w-2.5 h-2.5 rounded-full bg-rose-accent/60"></span>
            <span class="w-2.5 h-2.5 rounded-full bg-amber-accent/60"></span>
            <span class="w-2.5 h-2.5 rounded-full bg-teal-accent/60"></span>
            <span class="ml-2 text-[11px] font-mono text-base-400 tracking-wide">~/projects</span>
        </div>

        {{-- Terminal body --}}
        <div class="px-4 py-4 font-mono text-[13px] leading-[1.7] space-y-0">
            <div class="terminal-line" data-delay="400">
                <span class="text-teal-accent">$</span>
                <span class="text-base-200"> php artisan make:magic</span>
            </div>
            <div class="terminal-line" data-delay="1200">
                <span class="text-base-400 italic">  Building something new...</span>
            </div>
            <div class="terminal-line" data-delay="2200"><span class="text-base-500">&nbsp;</span></div>
            <div class="terminal-line" data-delay="2600">
                <span class="text-amber-accent">class</span>
                <span class="text-base-100"> StuffAndThings</span>
            </div>
            <div class="terminal-line" data-delay="3000">
                <span class="text-base-400">{</span>
            </div>
            <div class="terminal-line" data-delay="3400">
                <span class="text-base-400">    </span><span class="text-indigo-accent">public function</span>
                <span class="text-teal-accent"> build</span><span class="text-base-400">()</span>
            </div>
            <div class="terminal-line" data-delay="3800">
                <span class="text-base-400">    {</span>
            </div>
            <div class="terminal-line" data-delay="4200">
                <span class="text-base-400">        </span><span class="text-amber-accent">return</span>
                <span class="text-teal-accent"> $this</span><span class="text-base-400">-></span><span class="text-base-200">solve</span><span class="text-base-400">(</span><span class="text-rose-accent">'real problems'</span><span class="text-base-400">);</span>
            </div>
            <div class="terminal-line" data-delay="4600">
                <span class="text-base-400">    }</span>
            </div>
            <div class="terminal-line" data-delay="5000">
                <span class="text-base-400">}</span>
            </div>
            <div class="terminal-line" data-delay="5600"><span class="text-base-500">&nbsp;</span></div>
            <div class="terminal-line" data-delay="6000">
                <span class="text-teal-accent">✓</span>
                <span class="text-base-300"> Ready to ship.</span>
                <span class="terminal-cursor"></span>
            </div>
        </div>
    </div>
</div>
