{{-- resources/views/livewire/components/theme-toggle.blade.php --}}
<div x-data="{
    theme: localStorage.getItem('theme') || 'dark',
    transitioning: false,
    init() {
        if (this.theme === 'light') {
            document.documentElement.classList.add('light');
        }
    },
    toggle() {
        if (this.transitioning) return;
        this.transitioning = true;

        const isToLight = this.theme === 'dark';

        // Trigger animasi track
        this.$refs.track.classList.add(isToLight ? 'tt-to-light' : 'tt-to-dark');

        setTimeout(() => {
            this.theme = isToLight ? 'light' : 'dark';
            localStorage.setItem('theme', this.theme);
            document.documentElement.classList.toggle('light', this.theme === 'light');

            this.$refs.track.classList.remove('tt-to-light', 'tt-to-dark');
            this.transitioning = false;
        }, 350);
    }
}" class="inline-flex items-center">

    <div x-ref="track" @click="toggle()" @keydown.space.prevent="toggle()" @keydown.enter.prevent="toggle()"
        :class="theme === 'light' ? 'tt-light' : 'tt-dark'" role="switch" tabindex="0" title="Toggle theme"
        class="relative w-[96px] h-11 rounded-[22px] cursor-pointer overflow-hidden border border-ink-600 transition-all duration-700 outline-none">

        <!-- Stars (Dark Mode) -->
        <svg class="absolute inset-0 w-full h-full pointer-events-none transition-opacity duration-500"
            :class="theme === 'dark' ? 'opacity-100' : 'opacity-0'" viewBox="0 0 96 44"
            xmlns="http://www.w3.org/2000/svg">
            <circle cx="15" cy="9" r="1" fill="#fff" opacity="0.9" />
            <circle cx="28" cy="5" r="0.7" fill="#fff" opacity="0.7" />
            <circle cx="42" cy="12" r="1.1" fill="#fff" opacity="0.85" />
            <circle cx="55" cy="7" r="0.6" fill="#fff" opacity="0.6" />
            <circle cx="68" cy="11" r="0.9" fill="#fff" opacity="0.75" />
            <circle cx="80" cy="5" r="0.7" fill="#fff" opacity="0.8" />
            <circle cx="22" cy="18" r="0.6" fill="#fff" opacity="0.5" />
            <circle cx="75" cy="18" r="0.8" fill="#fff" opacity="0.6" />
        </svg>

        <!-- Clouds (Light Mode) -->
        <div class="absolute top-[10px] left-[54px] w-[22px] h-[8px] bg-white/80 rounded-full transition-all duration-700"
            :class="theme === 'light' ? 'opacity-85 translate-x-0' : 'opacity-0 translate-x-1.5'">
        </div>
        <div class="absolute top-[17px] left-[62px] w-[14px] h-[6px] bg-white/65 rounded-full transition-all duration-700"
            :class="theme === 'light' ? 'opacity-70 translate-x-0' : 'opacity-0 translate-x-1.5'">
        </div>

        <!-- Horizon Glow -->
        <div class="absolute bottom-0 left-0 right-0 h-[18px] rounded-b-[22px] bg-gradient-to-t from-[#ffe08888] to-transparent pointer-events-none transition-opacity duration-500"
            :class="theme === 'light' ? 'opacity-100' : 'opacity-0'">
        </div>

        <!-- Ground -->
        <div class="absolute bottom-0 left-0 right-0 h-[6px] rounded-b-[22px] transition-colors duration-700"
            :class="theme === 'light' ? 'bg-[#5eb855]' : 'bg-[#0a1520]'">
        </div>

        <!-- Celestial Body (Moon / Sun) -->
        <div class="absolute top-[6px] left-[4px] w-7 h-7 rounded-full transition-all duration-700 ease-out shadow-xl z-10"
            :class="theme === 'light'
                ?
                'translate-x-[56px] bg-[#ffda2a] shadow-[0_0_0_4px_rgba(255,237,160,0.45),0_0_0_8px_rgba(255,237,160,0.2)]' :
                'translate-x-0 bg-[#d8e8f8] shadow-inner'">

            <!-- Moon Craters (Dark) -->
            <div x-show="theme === 'dark'" class="absolute inset-0">
                <div class="absolute w-[5px] h-[5px] bg-[#8aabcc] rounded-full opacity-65 top-[5px] left-[6px]"></div>
                <div class="absolute w-[4px] h-[4px] bg-[#8aabcc] rounded-full opacity-60 top-[14px] left-[10px]"></div>
                <div class="absolute w-[3px] h-[3px] bg-[#8aabcc] rounded-full opacity-55 top-[8px] left-[16px]"></div>
            </div>

            <!-- Sun Rays (Light) -->
            <div x-show="theme === 'light'" class="absolute inset-0">
                <div class="absolute w-[3px] h-[7px] bg-[#ffd21e] opacity-70 rounded top-[-21px] left-[-1.5px] origin-bottom"
                    style="transform: rotate(0deg);"></div>
                <div class="absolute w-[3px] h-[7px] bg-[#ffd21e] opacity-70 rounded top-[-15px] left-[12px] origin-bottom"
                    style="transform: rotate(45deg);"></div>
                <div class="absolute w-[3px] h-[7px] bg-[#ffd21e] opacity-70 rounded top-[-1.5px] left-[16px] origin-bottom"
                    style="transform: rotate(90deg);"></div>
                <div class="absolute w-[3px] h-[7px] bg-[#ffd21e] opacity-70 rounded top-[12px] left-[12px] origin-bottom"
                    style="transform: rotate(135deg);"></div>
                <div class="absolute w-[3px] h-[7px] bg-[#ffd21e] opacity-70 rounded top-[16px] left-[-1.5px] origin-bottom"
                    style="transform: rotate(180deg);"></div>
                <div class="absolute w-[3px] h-[7px] bg-[#ffd21e] opacity-70 rounded top-[12px] left-[-15px] origin-bottom"
                    style="transform: rotate(225deg);"></div>
                <div class="absolute w-[3px] h-[7px] bg-[#ffd21e] opacity-70 rounded top-[-1.5px] left-[-19px] origin-bottom"
                    style="transform: rotate(270deg);"></div>
                <div class="absolute w-[3px] h-[7px] bg-[#ffd21e] opacity-70 rounded top-[-15px] left-[-15px] origin-bottom"
                    style="transform: rotate(315deg);"></div>
            </div>
        </div>
    </div>
</div>
