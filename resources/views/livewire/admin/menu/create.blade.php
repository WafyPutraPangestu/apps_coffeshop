<div>
    {{-- Page Header --}}
    <div class="page-header">
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:4px;">
            <a wire:navigate href="{{ route('menu.index') }}"
                style="color:var(--color-ink-500); display:flex; align-items:center; transition:color 0.15s;"
                onmouseover="this.style.color='var(--color-lime-500)'"
                onmouseout="this.style.color='var(--color-ink-500)'">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12" />
                    <polyline points="12 19 5 12 12 5" />
                </svg>
            </a>
            <h1 class="page-title">TAMBAH <span class="accent">MENU</span></h1>
        </div>
        <p class="page-subtitle">Tambahkan menu baru ke katalog</p>
    </div>

    <div style="display:grid; grid-template-columns:1fr 340px; gap:20px; align-items:start; max-width:900px;">

        {{-- Form Utama --}}
        <div class="card-hard animate-fade-up">

            <div
                style="display:flex; align-items:center; gap:8px; margin-bottom:24px; padding-bottom:16px; border-bottom:1px solid var(--color-ink-700);">
                <div style="width:6px; height:6px; background:var(--color-lime-500); border-radius:50%;"></div>
                <span
                    style="font-family:var(--font-mono); font-size:10px; text-transform:uppercase; letter-spacing:0.14em; color:var(--color-ink-400);">Informasi
                    Menu</span>
            </div>

            <div style="display:flex; flex-direction:column; gap:18px;">

                {{-- Nama --}}
                <div class="form-group">
                    <label class="form-label">Nama Menu <span class="required">*</span></label>
                    <input wire:model="name" type="text" class="form-input {{ $errors->has('name') ? 'error' : '' }}"
                        placeholder="cth: Kopi Susu Gula Aren..." autofocus />
                    @error('name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Kategori --}}
                <div class="form-group">
                    <label class="form-label">Kategori <span class="required">*</span></label>
                    <select wire:model="category_id"
                        class="form-input form-select {{ $errors->has('category_id') ? 'error' : '' }}">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Harga --}}
                <div class="form-group">
                    <label class="form-label">Harga <span class="required">*</span></label>
                    <div style="position:relative;">
                        <span
                            style="position:absolute; left:12px; top:50%; transform:translateY(-50%); font-family:var(--font-mono); font-size:12px; color:var(--color-ink-400); pointer-events:none;">Rp</span>
                        <input wire:model="price" type="number" min="0"
                            class="form-input {{ $errors->has('price') ? 'error' : '' }}" placeholder="0"
                            style="padding-left:38px;" />
                    </div>
                    @error('price')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea wire:model="description" class="form-input form-textarea {{ $errors->has('description') ? 'error' : '' }}"
                        placeholder="Deskripsi singkat menu..."></textarea>
                    @error('description')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

            </div>
        </div>

        {{-- Kolom Kanan: Gambar + Status --}}
        <div style="display:flex; flex-direction:column; gap:16px;">

            {{-- Upload Gambar --}}
            <div class="card-hard animate-fade-up stagger-1">
                <div
                    style="display:flex; align-items:center; gap:8px; margin-bottom:16px; padding-bottom:12px; border-bottom:1px solid var(--color-ink-700);">
                    <div style="width:6px; height:6px; background:var(--color-orange-500); border-radius:50%;"></div>
                    <span
                        style="font-family:var(--font-mono); font-size:10px; text-transform:uppercase; letter-spacing:0.14em; color:var(--color-ink-400);">Foto
                        Menu</span>
                </div>

                {{-- Preview --}}
                @if ($image)
                    <div
                        style="margin-bottom:12px; border-radius:var(--radius-md); overflow:hidden; border:1px solid var(--color-ink-600); aspect-ratio:4/3; background:var(--color-ink-900);">
                        <img src="{{ $image->temporaryUrl() }}" alt="Preview"
                            style="width:100%; height:100%; object-fit:cover;" />
                    </div>
                @else
                    <div
                        style="margin-bottom:12px; border-radius:var(--radius-md); border:1.5px dashed var(--color-ink-600); aspect-ratio:4/3; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:8px; background:var(--color-ink-900);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                            fill="none" stroke="var(--color-ink-600)" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2" />
                            <circle cx="8.5" cy="8.5" r="1.5" />
                            <polyline points="21 15 16 10 5 21" />
                        </svg>
                        <span style="font-family:var(--font-mono); font-size:10px; color:var(--color-ink-600);">Belum
                            ada foto</span>
                    </div>
                @endif

                <label style="cursor:pointer; display:block;">
                    <input wire:model="image" type="file" accept="image/*" style="display:none;" />
                    <div class="btn btn-secondary btn-block" style="text-align:center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:4px;">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                            <polyline points="17 8 12 3 7 8" />
                            <line x1="12" y1="3" x2="12" y2="15" />
                        </svg>
                        {{ $image ? 'Ganti Foto' : 'Upload Foto' }}
                    </div>
                </label>
                @error('image')
                    <span class="form-error" style="margin-top:6px; display:block;">{{ $message }}</span>
                @enderror
                <span class="form-hint" style="margin-top:6px; display:block;">Max 2MB. Format: JPG, PNG, WEBP</span>
            </div>

            {{-- Status --}}
            <div class="card-hard animate-fade-up stagger-2">
                <div
                    style="display:flex; align-items:center; gap:8px; margin-bottom:16px; padding-bottom:12px; border-bottom:1px solid var(--color-ink-700);">
                    <div style="width:6px; height:6px; background:var(--color-spray-cyan); border-radius:50%;"></div>
                    <span
                        style="font-family:var(--font-mono); font-size:10px; text-transform:uppercase; letter-spacing:0.14em; color:var(--color-ink-400);">Status</span>
                </div>
                <label class="toggle-wrapper">
                    <div class="toggle">
                        <input type="checkbox" wire:model="is_available" />
                        <div class="toggle-track"></div>
                        <div class="toggle-thumb"></div>
                    </div>
                    <div>
                        <div style="font-size:13px; font-weight:600; color:var(--color-ink-100);">
                            {{ $is_available ? 'Tersedia' : 'Habis / Nonaktif' }}
                        </div>
                        <div
                            style="font-family:var(--font-mono); font-size:10px; color:var(--color-ink-500); margin-top:2px;">
                            {{ $is_available ? 'Tampil di katalog pelanggan' : 'Disembunyikan dari pelanggan' }}
                        </div>
                    </div>
                </label>
            </div>

            {{-- Actions --}}
            <div style="display:flex; flex-direction:column; gap:8px;">
                <button wire:click="save" wire:loading.attr="disabled" class="btn btn-primary btn-block btn-lg">
                    <span wire:loading.remove wire:target="save"
                        style="display:inline-flex; align-items:center; gap:6px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                        Simpan Menu
                    </span>
                    <span wire:loading wire:target="save"
                        style="display:none; align-items:center; gap:8px; justify-content:center;">
                        <span class="spinner"></span> Menyimpan...
                    </span>
                </button>
                <a wire:navigate href="{{ route('menu.index') }}" class="btn btn-ghost btn-block"
                    style="text-align:center;">Batal</a>
            </div>

        </div>
    </div>

    {{-- Responsive: stack di mobile --}}
    <style>
        @media (max-width: 700px) {
            div[style*="grid-template-columns:1fr 340px"] {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
</div>
