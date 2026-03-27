<div>

    {{-- =====================================================
         TOAST STACK
         ===================================================== --}}
    <div
        style="position:fixed; bottom:20px; right:20px; z-index:100; display:flex; flex-direction:column; gap:8px; pointer-events:none;">
        @foreach ($toasts as $toast)
            <div id="toast-{{ $toast['id'] }}" class="toast toast-{{ $toast['type'] }}"
                style="pointer-events:auto; display:flex; align-items:flex-start; gap:10px;">
                {{-- Icon --}}
                <div style="flex-shrink:0; margin-top:1px;">
                    @if ($toast['type'] === 'success')
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                            fill="none" stroke="var(--color-success)" stroke-width="2.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                    @elseif($toast['type'] === 'error')
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                            fill="none" stroke="var(--color-error)" stroke-width="2.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                    @elseif($toast['type'] === 'warning')
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                            fill="none" stroke="var(--color-warning)" stroke-width="2.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path
                                d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                            <line x1="12" y1="9" x2="12" y2="13" />
                            <line x1="12" y1="17" x2="12.01" y2="17" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                            fill="none" stroke="var(--color-info)" stroke-width="2.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                    @endif

                    {{-- =====================================================
         FULLSCREEN LOADER
         Trigger via JS:
           window.showLoader()   — tampilkan
           window.hideLoader()   — sembunyikan
         ===================================================== --}}
                    <div id="ws-loader"
                        style="
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: var(--color-ink-950);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 32px;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.25s ease;
    ">
                        {{-- Logo animasi --}}
                        <div id="ws-loader-logo"
                            style="
            font-family: var(--font-display);
            font-size: 52px;
            letter-spacing: 0.06em;
            color: #ffffff;
            line-height: 1;
            transform: translateY(8px);
            opacity: 0;
            transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
        ">
                            WARSO<span style="color: var(--color-lime-500);">.</span>
                        </div>

                        {{-- Bar animasi --}}
                        <div
                            style="width: 160px; height: 2px; background: var(--color-ink-700); border-radius: 2px; overflow: hidden; position: relative;">
                            <div id="ws-loader-bar"
                                style="
                position: absolute;
                top: 0; left: -60%;
                width: 60%;
                height: 100%;
                background: linear-gradient(90deg, transparent, var(--color-lime-500), transparent);
                animation: none;
            ">
                            </div>
                        </div>

                        {{-- Label --}}
                        <div id="ws-loader-label"
                            style="
            font-family: var(--font-mono);
            font-size: 10px;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--color-ink-500);
            transform: translateY(4px);
            opacity: 0;
            transition: all 0.3s ease 0.15s;
        ">
                            Memproses...</div>
                    </div>

                    <style>
                        @keyframes ws-scan {
                            0% {
                                left: -60%;
                            }

                            100% {
                                left: 110%;
                            }
                        }
                    </style>

                    <script>
                        (function() {
                            var loaderEl = null;
                            var logoEl = null;
                            var barEl = null;
                            var labelEl = null;
                            var hideTimer = null;

                            function getEls() {
                                loaderEl = document.getElementById('ws-loader');
                                logoEl = document.getElementById('ws-loader-logo');
                                barEl = document.getElementById('ws-loader-bar');
                                labelEl = document.getElementById('ws-loader-label');
                            }

                            window.showLoader = function(label) {
                                getEls();
                                if (!loaderEl) return;
                                clearTimeout(hideTimer);

                                if (label) labelEl.textContent = label;

                                // Tampilkan overlay
                                loaderEl.style.opacity = '1';
                                loaderEl.style.pointerEvents = 'all';

                                // Animasi logo & label masuk
                                requestAnimationFrame(function() {
                                    logoEl.style.opacity = '1';
                                    logoEl.style.transform = 'translateY(0)';
                                    labelEl.style.opacity = '1';
                                    labelEl.style.transform = 'translateY(0)';
                                });

                                // Mulai scan bar
                                barEl.style.animation = 'ws-scan 1.1s linear infinite';
                            };

                            window.hideLoader = function() {
                                getEls();
                                if (!loaderEl) return;

                                // Fade out overlay
                                loaderEl.style.opacity = '0';
                                loaderEl.style.pointerEvents = 'none';

                                // Reset logo & label
                                logoEl.style.opacity = '0';
                                logoEl.style.transform = 'translateY(8px)';
                                labelEl.style.opacity = '0';
                                labelEl.style.transform = 'translateY(4px)';

                                // Stop animasi bar setelah fade selesai
                                hideTimer = setTimeout(function() {
                                    barEl.style.animation = 'none';
                                    barEl.style.left = '-60%';
                                }, 280);
                            };

                            // Safety net — sembunyikan loader setelah navigasi selesai
                            document.addEventListener('livewire:navigated', function() {
                                window.hideLoader();
                            });
                        })
                        ();
                    </script>

                </div>

                {{-- Message --}}
                <span style="flex:1; color:var(--color-ink-100); font-size:12px; line-height:1.5;">
                    {{ $toast['message'] }}
                </span>

                {{-- Close --}}
                <button wire:click="dismissToast({{ $toast['id'] }})"
                    style="background:transparent; border:none; color:var(--color-ink-500); cursor:pointer; padding:0; line-height:1; flex-shrink:0;"
                    onmouseover="this.style.color='var(--color-ink-200)'"
                    onmouseout="this.style.color='var(--color-ink-500)'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                        stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                </button>
            </div>
        @endforeach
    </div>

    {{-- =====================================================
         CONFIRM MODAL
         ===================================================== --}}
    @if ($confirmOpen)
        <div class="modal-backdrop" wire:click.self="closeConfirm">
            <div class="modal animate-fade-up" style="max-width:360px;">

                {{-- Icon warning --}}
                <div
                    style="width:44px; height:44px; border-radius:var(--radius-md); background:rgba(255,92,0,0.1); border:1.5px solid rgba(255,92,0,0.3); display:flex; align-items:center; justify-content:center; margin-bottom:16px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="var(--color-orange-400)" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path
                            d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                        <line x1="12" y1="9" x2="12" y2="13" />
                        <line x1="12" y1="17" x2="12.01" y2="17" />
                    </svg>
                </div>

                {{-- Title --}}
                <h2
                    style="font-family:var(--font-display); font-size:22px; letter-spacing:0.04em; color:#ffffff; line-height:1; margin:0 0 8px;">
                    KONFIRMASI
                </h2>

                {{-- Message --}}
                <p style="font-size:14px; color:var(--color-ink-300); margin:0 0 24px; line-height:1.6;">
                    {{ $confirmMessage }}
                </p>

                {{-- Actions --}}
                <div style="display:flex; gap:10px; justify-content:flex-end;">
                    <button wire:click="closeConfirm" class="btn btn-secondary">
                        Batal
                    </button>
                    <button wire:click="confirmYes" class="btn btn-cta">
                        Ya, Lanjutkan
                    </button>
                </div>

            </div>
        </div>
    @endif

    {{-- Auto-dismiss toast via JS --}}
    <script>
        document.addEventListener('toast-added', function(e) {
            var id = e.detail.id;
            var duration = e.detail.duration || 4000;

            setTimeout(function() {
                var el = document.getElementById('toast-' + id);
                if (el) {
                    el.style.transition = 'opacity 0.3s, transform 0.3s';
                    el.style.opacity = '0';
                    el.style.transform = 'translateY(8px)';
                }
                // Dismiss dari Livewire setelah animasi selesai
                setTimeout(function() {
                    @this.dismissToast(id);
                }, 320);
            }, duration);
        });
    </script>

</div>
