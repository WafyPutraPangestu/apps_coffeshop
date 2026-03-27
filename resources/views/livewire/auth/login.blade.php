<div
    style="min-height:100vh; display:flex; align-items:center; justify-content:center; padding:16px; position:relative; overflow:hidden;">

    {{-- Background dekorasi --}}
    <div style="position:absolute; inset:0; pointer-events:none; overflow:hidden;">
        {{-- Grid lines --}}
        <svg style="position:absolute; inset:0; width:100%; height:100%; opacity:0.03;"
            xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M 40 0 L 0 0 0 40" fill="none" stroke="#c8ff00" stroke-width="0.5" />
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#grid)" />
        </svg>

        {{-- Glow blobs --}}
        <div
            style="position:absolute; top:-120px; right:-80px; width:min(400px, 60vw); height:min(400px, 60vw); background:radial-gradient(circle, rgba(200,255,0,0.06) 0%, transparent 70%); border-radius:50%;">
        </div>
        <div
            style="position:absolute; bottom:-100px; left:-60px; width:min(300px, 50vw); height:min(300px, 50vw); background:radial-gradient(circle, rgba(255,92,0,0.05) 0%, transparent 70%); border-radius:50%;">
        </div>

        {{-- Teks dekoratif besar di background --}}
        <div
            style="position:absolute; bottom:-20px; left:-10px; font-family:var(--font-display); font-size:clamp(60px, 18vw, 180px); color:rgba(255,255,255,0.015); letter-spacing:0.04em; user-select:none; white-space:nowrap; line-height:1;">
            WARSO COFFEE
        </div>
    </div>

    {{-- Card Login --}}
    <div class="animate-fade-up" style="width:100%; max-width:420px; position:relative; z-index:1;">

        {{-- Header --}}
        <div style="margin-bottom:clamp(20px, 5vw, 32px); text-align:center;">
            <div style="display:flex; align-items:baseline; justify-content:center; gap:10px; flex-wrap:wrap;">
                <span
                    style="font-family:var(--font-display); font-size:clamp(36px, 10vw, 58px); letter-spacing:0.06em; color:#ffffff; line-height:1;">
                    WARSO
                </span>
                <span
                    style="font-family:var(--font-display); font-size:clamp(36px, 10vw, 58px); letter-spacing:0.06em; color:var(--color-lime-500); line-height:1; text-shadow:3px 3px 0 var(--color-lime-700), 5px 5px 0 rgba(0,0,0,0.4);">
                    COFFEE
                </span>
            </div>
            <div
                style="margin-top:8px; font-family:var(--font-mono); font-size:clamp(9px, 2.2vw, 11px); letter-spacing:0.22em; text-transform:uppercase; color:var(--color-ink-400);">
                ADMIN ACCESS ONLY
            </div>
        </div>

        {{-- Form Card --}}
        <div
            style="background:var(--color-ink-800); border:1.5px solid var(--color-ink-600); border-radius:var(--radius-lg); padding:clamp(18px, 5vw, 28px); box-shadow:6px 6px 0px var(--color-ink-950);">

            {{-- Title --}}
            <div style="margin-bottom:clamp(16px, 4vw, 24px);">
                <h1
                    style="font-family:var(--font-display); font-size:clamp(22px, 6vw, 28px); letter-spacing:0.04em; color:#ffffff; line-height:1; margin:0;">
                    SIGN IN
                </h1>
                <p
                    style="font-family:var(--font-mono); font-size:clamp(10px, 2.5vw, 11px); color:var(--color-ink-400); margin-top:6px; letter-spacing:0.04em;">
                    Masuk ke dashboard Warso Coffee
                </p>
            </div>

            <div style="display:flex; flex-direction:column; gap:clamp(14px, 3.5vw, 18px);">

                {{-- Email --}}
                <div class="form-group">
                    <label class="form-label">
                        Email <span class="required">*</span>
                    </label>
                    <input wire:model="email" type="email"
                        class="form-input {{ $errors->has('email') ? 'error' : '' }}" placeholder="admin@warsocoffee.id"
                        autocomplete="email" autofocus />
                    @error('email')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="form-group">
                    <label class="form-label">
                        Password <span class="required">*</span>
                    </label>
                    <input wire:model="password" type="password"
                        class="form-input {{ $errors->has('password') ? 'error' : '' }}" placeholder="••••••••"
                        autocomplete="current-password" wire:keydown.enter="login" />
                    @error('password')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Remember me --}}
                <label class="toggle-wrapper" style="cursor:pointer;">
                    <div class="toggle">
                        <input type="checkbox" wire:model="remember" />
                        <div class="toggle-track"></div>
                        <div class="toggle-thumb"></div>
                    </div>
                    <span style="font-family:var(--font-mono); font-size:12px; color:var(--color-ink-300);">
                        Ingat saya
                    </span>
                </label>

                {{-- Submit --}}
                <button wire:click="login" wire:loading.attr="disabled" class="btn btn-primary btn-block btn-lg"
                    style="margin-top:4px;">
                    <span wire:loading.remove wire:target="login">Masuk</span>
                    <span wire:loading wire:target="login" style="display:none; align-items:center; gap:8px;">
                        <span class="spinner"></span>
                        Memverifikasi...
                    </span>
                </button>

            </div>
        </div>

        {{-- Footer --}}
        <div
            style="text-align:center; margin-top:16px; font-family:var(--font-mono); font-size:10px; color:var(--color-ink-500); letter-spacing:0.06em;">
            WARSO COFFEE — INTERNAL SYSTEM
        </div>

    </div>
</div>
