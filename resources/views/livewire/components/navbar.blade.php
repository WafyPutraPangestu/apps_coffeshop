<nav
    style="
    background: var(--color-ink-950);
    border-bottom: 1px solid var(--color-ink-700);
    height: 56px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 24px;
    position: sticky;
    top: 0;
    z-index: 30;
">

    {{-- Logo --}}
    <a wire:navigate href="/" class="group inline-block" style="text-decoration: none;">
        <span
            class="font-display text-[28px] md:text-[32px] tracking-[0.07em] leading-[1] text-white drop-shadow-[3px_3px_0_#0a0a0a]">
            WARSO
            <span
                class="text-lime-500 drop-shadow-[3px_3px_0_#7a9c00] 
                         group-hover:text-lime-400 group-hover:drop-shadow-[4px_4px_0_#a8d400] 
                         transition-all duration-200">
                COFFEE
            </span>
        </span>
    </a>

    {{-- Right Side: Theme Toggle + Login --}}
    <div class="flex items-center gap-4">

        {{-- Theme Toggle --}}
        <div style="padding: 8px;  ">
            @livewire('components.theme-toggle')
        </div>

        {{-- Link ke Login --}}
        <a wire:navigate href="/login" class="btn btn-primary btn-sm">
            Login
        </a>
    </div>
</nav>
