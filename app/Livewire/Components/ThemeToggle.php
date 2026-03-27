<?php

// app/Livewire/Components/ThemeToggle.php

namespace App\Livewire\Components;

use Livewire\Component;
use Illuminate\Support\Facades\Cookie;

class ThemeToggle extends Component
{
    public string $theme = 'dark';

    public function mount(): void
    {
        // ✅ Cookie lebih reliable karena dibaca sebelum Livewire boot
        $this->theme = request()->cookie('theme')
            ?? session('theme', 'dark');
    }



    // app/Livewire/Components/ThemeToggle.php

    public function toggle(): void
    {
        $this->theme = $this->theme === 'dark' ? 'light' : 'dark';

        session(['theme' => $this->theme]);
        Cookie::queue('theme', $this->theme, 60 * 24 * 365);

        $theme = $this->theme;

        // 🚀 Panggil window.applyTheme()
        $this->js("
            document.cookie = 'theme={$theme}; path=/; max-age=31536000; SameSite=Lax';
            if (typeof window.applyTheme === 'function') window.applyTheme();
        ");
    }

    public function render()
    {
        return view('livewire.components.theme-toggle');
    }
}
