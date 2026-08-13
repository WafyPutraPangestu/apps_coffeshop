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
                    style="font-family:var(--font-mono); font-size:10px; text-transform:uppercase; letter-spacing:0.14em; color:#fff;">Informasi
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
                            style="position:absolute; left:12px; top:50%; transform:translateY(-50%); font-family:var(--font-mono); font-size:12px; color:#ffff; pointer-events:none;">Rp</span>
                        <input wire:model="price" type="number" min="0"
                            class="form-input {{ $errors->has('price') ? 'error' : '' }}" placeholder="0"
                            style="padding-left:38px;" />
                    </div>
                    @error('price')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Stok --}}
                <div class="form-group">
                    <label class="form-label">Stok Awal <span class="required">*</span></label>
                    <input wire:model="stock" type="number" min="0"
                        class="form-input {{ $errors->has('stock') ? 'error' : '' }}" placeholder="cth: 50" />
                    @error('stock')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                    <span class="form-hint" style="margin-top:6px; display:block;">Isi 0 jika barang sedang kosong.</span>
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

        {{-- Add-ons Section --}}
        <div class="card-hard animate-fade-up" style="margin-top:20px;">

            {{-- Header Add-ons --}}
            <div
                style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; padding-bottom:12px; border-bottom:1px solid var(--color-ink-700);">
                <div style="display:flex; align-items:center; gap:8px;">
                    <div style="width:6px; height:6px; background:var(--color-lime-500); border-radius:50%;"></div>
                    <span
                        style="font-family:var(--font-mono); font-size:10px; text-transform:uppercase; letter-spacing:0.14em; color:#fff;">Add-ons</span>
                    @if (count($selectedAddOns) > 0)
                        <span
                            style="background:var(--color-lime-500); color:#0a0a0a; font-size:9px; font-family:var(--font-mono); padding:1px 7px; border-radius:100px;">{{ count($selectedAddOns) }} dipilih</span>
                    @endif
                </div>
                {{-- Tombol tambah add-on baru --}}
                <button type="button" wire:click="$toggle('showNewAddOnForm')"
                    style="display:flex; align-items:center; gap:5px; padding:4px 10px; border-radius:var(--radius-sm); border:1px solid {{ $showNewAddOnForm ? 'var(--color-error)' : 'var(--color-ink-600)' }}; background:transparent; cursor:pointer; color:{{ $showNewAddOnForm ? 'var(--color-error)' : 'var(--color-ink-400)' }}; font-family:var(--font-mono); font-size:10px; letter-spacing:0.08em; transition:all 0.15s;">
                    @if ($showNewAddOnForm)
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        Batal
                    @else
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Add-On Baru
                    @endif
                </button>
            </div>

            {{-- Form Quick-Create Add-On --}}
            @if ($showNewAddOnForm)
                <div
                    style="background:var(--color-ink-800); border:1px solid var(--color-lime-500); border-radius:var(--radius-md); padding:14px; margin-bottom:14px;">
                    <div
                        style="font-family:var(--font-mono); font-size:10px; color:var(--color-lime-400); letter-spacing:0.1em; margin-bottom:12px; text-transform:uppercase;">
                        ✦ Buat Add-On Baru</div>
                    <div style="display:flex; flex-direction:column; gap:10px;">
                        <div>
                            <input wire:model="newAddOnName" type="text" placeholder="Nama add-on (cth: Extra Shot)"
                                class="form-input {{ $errors->has('newAddOnName') ? 'error' : '' }}"
                                style="width:100%;" />
                            @error('newAddOnName')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div style="position:relative;">
                            <span
                                style="position:absolute; left:12px; top:50%; transform:translateY(-50%); font-family:var(--font-mono); font-size:12px; color:#fff; pointer-events:none;">Rp</span>
                            <input wire:model="newAddOnPrice" type="number" min="0" placeholder="0"
                                class="form-input {{ $errors->has('newAddOnPrice') ? 'error' : '' }}"
                                style="padding-left:38px; width:100%;" />
                            @error('newAddOnPrice')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="button" wire:click="saveNewAddOn" wire:loading.attr="disabled"
                            class="btn btn-primary btn-block">
                            <span wire:loading.remove wire:target="saveNewAddOn"
                                style="display:inline-flex; align-items:center; gap:6px;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                                Simpan & Pilih Add-On
                            </span>
                            <span wire:loading wire:target="saveNewAddOn" style="display:none;">Menyimpan...</span>
                        </button>
                    </div>
                </div>
            @endif

            {{-- Daftar Add-Ons yang ada --}}
            @if ($addOns->count() > 0)
                <div style="display:flex; flex-direction:column; gap:8px;">
                    @foreach ($addOns as $addOn)
                        <button type="button" wire:click="toggleAddOn({{ $addOn->id }})"
                            style="display:flex;align-items:center;justify-content:space-between;padding:10px 12px;border-radius:var(--radius-sm);border:1.5px solid {{ isset($selectedAddOns[$addOn->id]) ? 'var(--color-lime-500)' : 'var(--color-ink-600)' }};background:{{ isset($selectedAddOns[$addOn->id]) ? 'rgba(190,242,0,0.06)' : 'var(--color-ink-900)' }};cursor:pointer;transition:all 0.15s;width:100%">
                            <div style="display:flex;align-items:center;gap:8px">
                                <div
                                    style="width:16px;height:16px;border-radius:3px;border:1.5px solid {{ isset($selectedAddOns[$addOn->id]) ? 'var(--color-lime-500)' : 'var(--color-ink-500)' }};background:{{ isset($selectedAddOns[$addOn->id]) ? 'var(--color-lime-500)' : 'transparent' }};display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                    @if (isset($selectedAddOns[$addOn->id]))
                                        <svg style="width:10px;height:10px;color:var(--color-ink-950)" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    @endif
                                </div>
                                <span
                                    style="font-size:13px;color:{{ isset($selectedAddOns[$addOn->id]) ? 'var(--color-lime-400)' : 'var(--color-ink-200)' }};font-weight:500">
                                    {{ $addOn->name }}
                                </span>
                            </div>
                            <span class="font-mono"
                                style="font-size:12px;color:{{ isset($selectedAddOns[$addOn->id]) ? 'var(--color-lime-400)' : 'var(--color-ink-400)' }}">
                                +Rp {{ number_format($addOn->price, 0, ',', '.') }}
                            </span>
                        </button>
                    @endforeach
                </div>
                <span class="form-hint" style="margin-top:10px; display:block;">
                    Pilih add-on yang bisa dipesan pelanggan untuk menu ini.
                </span>
            @else
                @if (!$showNewAddOnForm)
                    <p class="font-mono"
                        style="font-size:12px; color:var(--color-ink-500); text-align:center; padding:16px 0;">
                        Belum ada add-on. Klik <strong style="color:var(--color-lime-400);">+ Add-On Baru</strong> untuk membuat.
                    </p>
                @endif
            @endif
        </div>

        {{-- Kolom Kanan: Gambar + Status --}}
        <div style="display:flex; flex-direction:column; gap:16px;">

            {{-- Upload Gambar --}}
            <div class="card-hard animate-fade-up stagger-1">
                <div
                    style="display:flex; align-items:center; gap:8px; margin-bottom:16px; padding-bottom:12px; border-bottom:1px solid var(--color-ink-700);">
                    <div style="width:6px; height:6px; background:var(--color-orange-500); border-radius:50%;"></div>
                    <span
                        style="font-family:var(--font-mono); font-size:10px; text-transform:uppercase; letter-spacing:0.14em; color:#fff;">Foto
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
                        style="font-family:var(--font-mono); font-size:10px; text-transform:uppercase; letter-spacing:0.14em; color:#ffff;">Status</span>
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
