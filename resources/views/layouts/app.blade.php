<!DOCTYPE html>
{{-- app.blade.php --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name') }} — Warso Coffee</title>
    <script>
        if (localStorage.getItem('theme') === 'light' || (!('theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: light)').matches)) {
            document.documentElement.classList.add('light');
        } else {
            document.documentElement.classList.remove('light');
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

</head>

<body>

    @auth
        @livewire('components.sidebar')
        <div class="page-wrapper">
            {{-- padding-top 56px di mobile untuk topbar, 0 di desktop --}}
            <main class="page-content" id="main-content" style="padding-top: 24px;">
                {{ $slot }}
            </main>
        </div>
        <script>
            (function() {
                function syncContentPadding() {
                    var el = document.getElementById('main-content');
                    if (!el) return;
                    el.style.paddingTop = window.innerWidth < 1024 ? '80px' : '24px';
                }
                syncContentPadding();
                window.addEventListener('resize', syncContentPadding);
                document.addEventListener('livewire:navigated', syncContentPadding);
            })
            ();
        </script>
    @endauth

    @guest
        @livewire('components.navbar')
        <main>
            {{ $slot }}
        </main>
    @endguest

    {{-- Notify: toast + modal konfirmasi + fullscreen loader --}}
    @livewire('components.notify')

    @livewireScripts
</body>

</html>
