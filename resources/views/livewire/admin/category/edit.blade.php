<div>
    {{-- Page Header --}}
    <div class="page-header">
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:4px;">
            <a wire:navigate href="{{ route('categories.index') }}"
                style="color:var(--color-ink-500); display:flex; align-items:center; transition:color 0.15s;"
                onmouseover="this.style.color='var(--color-lime-500)'"
                onmouseout="this.style.color='var(--color-ink-500)'">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12" />
                    <polyline points="12 19 5 12 12 5" />
                </svg>
            </a>
            <h1 class="page-title">EDIT <span class="accent">KATEGORI</span></h1>
        </div>
        <p class="page-subtitle">Perbarui nama kategori</p>
    </div>

    {{-- Form Card --}}
    <div style="max-width:480px;">
        <div class="card-hard animate-fade-up">

            {{-- Label atas --}}
            <div
                style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; padding-bottom:16px; border-bottom:1px solid var(--color-ink-700);">
                <div style="display:flex; align-items:center; gap:8px;">
                    <div style="width:6px; height:6px; background:var(--color-orange-500); border-radius:50%;"></div>
                    <span
                        style="font-family:var(--font-mono); font-size:10px; text-transform:uppercase; letter-spacing:0.14em; color:var(--color-ink-400);">
                        Edit Data
                    </span>
                </div>
                <span style="font-family:var(--font-mono); font-size:10px; color:var(--color-ink-600);">
                    ID #{{ $categoryId }}
                </span>
            </div>

            <div style="display:flex; flex-direction:column; gap:20px;">

                {{-- Nama --}}
                <div class="form-group">
                    <label class="form-label">
                        Nama Kategori <span class="required">*</span>
                    </label>
                    <input wire:model="name" type="text" class="form-input {{ $errors->has('name') ? 'error' : '' }}"
                        placeholder="cth: Coffee, Non-Coffee, Snack..." autofocus />
                    @error('name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Actions --}}
                <div style="display:flex; gap:10px; padding-top:4px;">
                    <a wire:navigate href="{{ route('categories.index') }}" class="btn btn-secondary"
                        style="flex:1; text-align:center;">
                        Batal
                    </a>
                    <button wire:click="save" wire:loading.attr="disabled" class="btn btn-primary" style="flex:2;">
                        <span wire:loading.remove wire:target="save">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:4px;">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                            Simpan Perubahan
                        </span>
                        <span wire:loading wire:target="save" style="display:none; align-items:center; gap:8px;">
                            <span class="spinner"></span>
                            Menyimpan...
                        </span>
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>
