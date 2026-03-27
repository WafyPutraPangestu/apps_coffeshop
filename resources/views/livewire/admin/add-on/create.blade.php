<div>
    {{-- ── Header ── --}}
    <div class="page-header"
        style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:12px;">
        <div>
            <div class="page-subtitle">Add-Ons / New</div>
            <h1 class="page-title">CREATE <span class="accent">ADD-ON</span></h1>
        </div>
        <a href="{{ route('add-on.index') }}" class="btn btn-secondary">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="19" y1="12" x2="5" y2="12" />
                <polyline points="12 19 5 12 12 5" />
            </svg>
            Back
        </a>
    </div>

    {{-- ── Form Card ── --}}
    <div style="max-width:560px;">
        <div class="card-hard animate-fade-up">

            {{-- Decorative header strip --}}
            <div
                style="margin:-20px -20px 20px;padding:12px 20px;background:var(--color-ink-700);border-bottom:1px solid var(--color-ink-500);display:flex;align-items:center;gap:8px;">
                <div style="width:8px;height:8px;background:var(--color-lime-500);border-radius:2px;"></div>
                <span
                    style="font-family:var(--font-mono);font-size:10px;letter-spacing:0.14em;text-transform:uppercase;color:var(--color-ink-300);">
                    New Add-On Entry
                </span>
            </div>

            {{-- Name --}}
            <div class="form-group" style="margin-bottom:18px;">
                <label class="form-label">
                    Name <span class="required">*</span>
                </label>
                <input wire:model="name" type="text" class="form-input {{ $errors->has('name') ? 'error' : '' }}"
                    placeholder="e.g. Extra Shot, Oat Milk, Cheese…" autofocus>
                @error('name')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- Price --}}
            <div class="form-group" style="margin-bottom:18px;">
                <label class="form-label">
                    Price (Rp) <span class="required">*</span>
                </label>
                <div style="position:relative;">
                    <span
                        style="position:absolute;left:12px;top:50%;transform:translateY(-50%);font-family:var(--font-mono);font-size:12px;color:var(--color-ink-400);pointer-events:none;">Rp</span>
                    <input wire:model="price" type="number" min="0" step="500"
                        class="form-input {{ $errors->has('price') ? 'error' : '' }}" placeholder="5000"
                        style="padding-left:36px;">
                </div>
                @error('price')
                    <span class="form-error">{{ $message }}</span>
                @enderror
                <span class="form-hint">Price added on top of base menu price.</span>
            </div>

            {{-- Availability Toggle --}}
            <div class="form-group" style="margin-bottom:24px;">
                <label class="form-label">Availability</label>
                <label class="toggle-wrapper">
                    <div class="toggle">
                        <input type="checkbox" wire:model="is_available">
                        <div class="toggle-track"></div>
                        <div class="toggle-thumb"></div>
                    </div>
                    <span style="font-size:13px;color:var(--color-ink-200);">
                        {{ $is_available ? 'Available for order' : 'Marked as unavailable' }}
                    </span>
                </label>
            </div>

            <hr class="divider" style="margin:0 0 20px;">

            {{-- Actions --}}
            <div style="display:flex;gap:10px;">
                <a href="{{ route('add-on.index') }}" class="btn btn-secondary" style="flex:1;">Cancel</a>
                <button wire:click="save" class="btn btn-primary" style="flex:2;" wire:loading.attr="disabled"
                    wire:target="save">
                    <span wire:loading.remove wire:target="save">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                        Save Add-On
                    </span>
                    <span wire:loading wire:target="save">
                        <span class="spinner" style="width:14px;height:14px;border-width:2px;"></span>
                        Saving…
                    </span>
                </button>
            </div>

        </div>
    </div>
</div>
