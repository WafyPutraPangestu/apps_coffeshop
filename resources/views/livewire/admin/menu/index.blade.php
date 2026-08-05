<div>
    {{-- ══════════════════════════════════════════════════════ --}}
    {{-- PAGE HEADER --}}
    {{-- ══════════════════════════════════════════════════════ --}}
    <div class="page-header"
        style="display:flex; align-items:flex-end; justify-content:space-between; gap:16px; flex-wrap:wrap; margin-bottom:0;">
        <div>
            <h1 class="page-title">MANA<span class="accent">JEMEN</span> MENU</h1>
            <p class="page-subtitle">Kelola menu & add-ons Warso Coffee</p>
        </div>

        {{-- CTA Button berubah sesuai tab aktif --}}
        @if ($activeTab === 'menu')
            <a wire:navigate href="{{ route('menu.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                Tambah Menu
            </a>
        @else
            <a wire:navigate href="{{ route('add-on.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                Tambah Add-On
            </a>
        @endif
    </div>

    {{-- ══════════════════════════════════════════════════════ --}}
    {{-- TAB SWITCHER --}}
    {{-- ══════════════════════════════════════════════════════ --}}
    <div style="display:flex; gap:4px; margin-top:24px; margin-bottom:20px; border-bottom:1px solid var(--color-ink-700); padding-bottom:0;">
        {{-- Tab: Menu --}}
        <button wire:click="switchTab('menu')"
            style="
                font-family:var(--font-display);
                font-size:12px;
                letter-spacing:0.12em;
                padding:10px 20px;
                border:none;
                background:transparent;
                cursor:pointer;
                border-bottom:2px solid {{ $activeTab === 'menu' ? 'var(--color-lime-400)' : 'transparent' }};
                color:{{ $activeTab === 'menu' ? 'var(--color-lime-400)' : 'var(--color-ink-500)' }};
                transition:all 0.2s;
                margin-bottom:-1px;
                display:flex;
                align-items:center;
                gap:8px;
            ">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 8h1a4 4 0 0 1 0 8h-1" />
                <path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4Z" />
                <line x1="6" y1="2" x2="6" y2="4" />
                <line x1="10" y1="2" x2="10" y2="4" />
                <line x1="14" y1="2" x2="14" y2="4" />
            </svg>
            MENU
            <span style="
                background:{{ $activeTab === 'menu' ? 'var(--color-lime-400)' : 'var(--color-ink-700)' }};
                color:{{ $activeTab === 'menu' ? '#0a0a0a' : 'var(--color-ink-400)' }};
                font-size:10px;
                font-family:var(--font-mono);
                padding:1px 7px;
                border-radius:100px;
            ">{{ $menus->total() }}</span>
        </button>

        {{-- Tab: Add-Ons --}}
        <button wire:click="switchTab('addon')"
            style="
                font-family:var(--font-display);
                font-size:12px;
                letter-spacing:0.12em;
                padding:10px 20px;
                border:none;
                background:transparent;
                cursor:pointer;
                border-bottom:2px solid {{ $activeTab === 'addon' ? 'var(--color-lime-400)' : 'transparent' }};
                color:{{ $activeTab === 'addon' ? 'var(--color-lime-400)' : 'var(--color-ink-500)' }};
                transition:all 0.2s;
                margin-bottom:-1px;
                display:flex;
                align-items:center;
                gap:8px;
            ">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10" />
                <line x1="12" y1="8" x2="12" y2="16" />
                <line x1="8" y1="12" x2="16" y2="12" />
            </svg>
            ADD-ONS
            <span style="
                background:{{ $activeTab === 'addon' ? 'var(--color-lime-400)' : 'var(--color-ink-700)' }};
                color:{{ $activeTab === 'addon' ? '#0a0a0a' : 'var(--color-ink-400)' }};
                font-size:10px;
                font-family:var(--font-mono);
                padding:1px 7px;
                border-radius:100px;
            ">{{ $addOns->total() }}</span>
        </button>
    </div>

    {{-- ══════════════════════════════════════════════════════ --}}
    {{-- TAB CONTENT: MENU --}}
    {{-- ══════════════════════════════════════════════════════ --}}
    @if ($activeTab === 'menu')
        {{-- Filter Bar --}}
        <div style="display:flex; gap:10px; margin-bottom:20px; flex-wrap:wrap; align-items:center;">
            <button wire:click="toggleArchiveView"
                class="btn {{ $showArchived ? 'btn-warning' : 'btn-secondary' }}"
                style="white-space:nowrap;">
                @if ($showArchived)
                    ← Kembali ke Menu Aktif
                @else
                    Lihat Menu Dihapus (Arsip)
                @endif
            </button>

            <div style="position:relative; flex:1; min-width:180px; max-width:300px;">
                <svg style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--color-ink-500); pointer-events:none;"
                    xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari menu..."
                    class="form-input" style="padding-left:36px;" />
            </div>

            <select wire:model.live="filterCategory" class="form-input form-select"
                style="width:auto; min-width:140px;">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>

            <select wire:model.live="filterStatus" class="form-input form-select"
                style="width:auto; min-width:130px;">
                <option value="">Semua Status</option>
                <option value="1">Tersedia</option>
                <option value="0">Habis</option>
            </select>
        </div>

        {{-- Table Menu --}}
        <div class="table-wrapper animate-fade-up">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:48px;">#</th>
                        <th>Menu</th>
                        <th>Kategori</th>
                        <th style="text-align:right;">Harga</th>
                        <th style="text-align:center;">Status</th>
                        <th style="text-align:center; width:80px;">Tersedia</th>
                        <th style="text-align:right; width:90px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($menus as $menu)
                        <tr class="animate-fade-up" style="animation-delay:{{ $loop->index * 0.03 }}s;">
                            <td style="color:var(--color-ink-500); font-family:var(--font-mono); font-size:11px;">
                                {{ $menus->firstItem() + $loop->index }}
                            </td>
                            <td>
                                <div style="display:flex; align-items:center; gap:12px;">
                                    @if ($menu->image)
                                        <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}"
                                            style="width:40px; height:40px; object-fit:cover; border-radius:var(--radius-md); border:1px solid var(--color-ink-600); flex-shrink:0;" />
                                    @else
                                        <div
                                            style="width:40px; height:40px; border-radius:var(--radius-md); background:var(--color-ink-700); border:1px solid var(--color-ink-600); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="var(--color-ink-500)"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M17 8h1a4 4 0 0 1 0 8h-1" />
                                                <path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4Z" />
                                                <line x1="6" y1="2" x2="6" y2="4" />
                                                <line x1="10" y1="2" x2="10" y2="4" />
                                                <line x1="14" y1="2" x2="14" y2="4" />
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <div style="font-weight:600; color:var(--color-ink-100);">{{ $menu->name }}</div>
                                        @if ($menu->description)
                                            <div
                                                style="font-size:11px; color:var(--color-ink-500); margin-top:2px; max-width:220px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                                {{ $menu->description }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-orange">{{ $menu->category->name }}</span>
                            </td>
                            <td style="text-align:right;">
                                <span class="price price-sm">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                            </td>
                            <td style="text-align:center;">
                                @if ($menu->is_available)
                                    <span class="badge badge-available">
                                        <span class="badge-dot" style="background:var(--color-success);"></span>
                                        Tersedia
                                    </span>
                                @else
                                    <span class="badge badge-unavailable">
                                        <span class="badge-dot" style="background:var(--color-ink-500);"></span>
                                        Habis
                                    </span>
                                @endif
                            </td>
                            <td style="text-align:center;">
                                <label class="toggle toggle-wrapper" style="margin:0 auto;">
                                    <input type="checkbox" {{ $menu->is_available ? 'checked' : '' }}
                                        wire:click="toggleAvailable({{ $menu->id }})" />
                                    <div class="toggle-track"></div>
                                    <div class="toggle-thumb"></div>
                                </label>
                            </td>
                            <td>
                                <div style="display:flex; align-items:center; justify-content:flex-end; gap:6px;">
                                    @if ($showArchived)
                                        <button wire:click="restoreMenu({{ $menu->id }})"
                                            class="btn btn-success btn-sm">
                                            Aktifkan Lagi
                                        </button>
                                    @else
                                        <a wire:navigate href="{{ route('menu.edit', $menu->id) }}"
                                            class="btn btn-ghost btn-icon" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                            </svg>
                                        </a>
                                        <button wire:click="confirmDelete({{ $menu->id }})"
                                            class="btn btn-danger btn-icon" title="Hapus">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6" />
                                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                                                <path d="M10 11v6" />
                                                <path d="M14 11v6" />
                                                <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center; padding:48px 16px;">
                                <div style="display:flex; flex-direction:column; align-items:center; gap:12px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40"
                                        viewBox="0 0 24 24" fill="none" stroke="var(--color-ink-600)"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M17 8h1a4 4 0 0 1 0 8h-1" />
                                        <path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4Z" />
                                        <line x1="6" y1="2" x2="6" y2="4" />
                                        <line x1="10" y1="2" x2="10" y2="4" />
                                        <line x1="14" y1="2" x2="14" y2="4" />
                                    </svg>
                                    <div
                                        style="font-family:var(--font-display); font-size:20px; color:var(--color-ink-500); letter-spacing:0.04em;">
                                        {{ $search ? 'Tidak ditemukan' : 'Belum ada menu' }}
                                    </div>
                                    <p style="font-family:var(--font-mono); font-size:11px; color:var(--color-ink-600);">
                                        {{ $search ? 'Coba kata kunci atau filter lain' : 'Mulai dengan menambahkan menu pertama' }}
                                    </p>
                                    @if (!$search)
                                        <a wire:navigate href="{{ route('menu.create') }}"
                                            class="btn btn-primary btn-sm" style="margin-top:4px;">
                                            + Tambah Sekarang
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($menus->hasPages())
            <div style="margin-top:16px; display:flex; justify-content:flex-end;">
                {{ $menus->links() }}
            </div>
        @endif
    @endif

    {{-- ══════════════════════════════════════════════════════ --}}
    {{-- TAB CONTENT: ADD-ONS --}}
    {{-- ══════════════════════════════════════════════════════ --}}
    @if ($activeTab === 'addon')
        {{-- Stats Bar --}}
        <div style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:20px;">
            <div class="stat-card" style="padding:14px 20px; flex:1; min-width:140px;">
                <div class="stat-value" style="font-size:28px;">{{ $addOns->total() }}</div>
                <div class="stat-label">Total Add-Ons</div>
            </div>
            <div class="stat-card accent-orange" style="padding:14px 20px; flex:1; min-width:140px;">
                <div class="stat-value" style="font-size:28px;">{{ $addOns->where('is_available', true)->count() }}</div>
                <div class="stat-label">Tersedia</div>
            </div>
            <div class="stat-card"
                style="padding:14px 20px; flex:1; min-width:140px; --accent-color:var(--color-error);">
                <div class="stat-value" style="font-size:28px; color:var(--color-error);">
                    {{ $addOns->where('is_available', false)->count() }}
                </div>
                <div class="stat-label">Tidak Tersedia</div>
                <div style="position:absolute; top:0; left:0; right:0; height:2px; background:var(--color-error);"></div>
            </div>
        </div>

        {{-- Filter & Search --}}
        <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap; margin-bottom:16px;">
            <div style="flex:1; min-width:220px; position:relative;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--color-ink-500); pointer-events:none;">
                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
                <input wire:model.live.debounce.300ms="addonSearch" type="text" placeholder="Cari add-on..."
                    class="form-input" style="padding-left:36px;">
            </div>
            <select wire:model.live="addonFilterStatus" class="form-input form-select"
                style="width:auto; min-width:140px;">
                <option value="">Semua Status</option>
                <option value="1">Tersedia</option>
                <option value="0">Tidak Tersedia</option>
            </select>
        </div>

        {{-- Table Add-Ons --}}
        <div class="table-wrapper animate-fade-up">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:48px;">#</th>
                        <th>Nama Add-On</th>
                        <th style="text-align:right;">Harga</th>
                        <th style="text-align:center;">Status</th>
                        <th style="text-align:right; width:140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($addOns as $index => $addOn)
                        <tr wire:key="addon-{{ $addOn->id }}" class="animate-fade-up"
                            style="animation-delay:{{ $index * 0.03 }}s;">
                            <td>
                                <span
                                    style="font-family:var(--font-mono); font-size:11px; color:var(--color-ink-500);">
                                    {{ ($addOns->currentPage() - 1) * $addOns->perPage() + $index + 1 }}
                                </span>
                            </td>
                            <td>
                                <span style="font-weight:600; color:var(--color-ink-50);">{{ $addOn->name }}</span>
                            </td>
                            <td style="text-align:right;">
                                <span class="price price-sm">
                                    Rp {{ number_format($addOn->price, 0, ',', '.') }}
                                </span>
                            </td>
                            <td style="text-align:center;">
                                @if ($addOn->is_available)
                                    <span class="badge badge-available">
                                        <span class="badge-dot" style="background:var(--color-success);"></span>
                                        Tersedia
                                    </span>
                                @else
                                    <span class="badge badge-unavailable">
                                        <span class="badge-dot" style="background:var(--color-ink-500);"></span>
                                        Tidak Tersedia
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex; gap:6px; justify-content:flex-end; align-items:center;">
                                    <button wire:click="toggleAddonAvailability({{ $addOn->id }})"
                                        class="btn btn-ghost btn-icon"
                                        title="{{ $addOn->is_available ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            @if ($addOn->is_available)
                                                <circle cx="12" cy="12" r="10" />
                                                <line x1="4.93" y1="4.93" x2="19.07" y2="19.07" />
                                            @else
                                                <circle cx="12" cy="12" r="10" />
                                                <polyline points="12 6 12 12 16 14" />
                                            @endif
                                        </svg>
                                    </button>
                                    <a wire:navigate href="{{ route('add-on.edit', $addOn->id) }}"
                                        class="btn btn-ghost btn-icon" title="Edit">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                        </svg>
                                    </a>
                                    <button wire:click="confirmAddonDelete({{ $addOn->id }})"
                                        class="btn btn-danger btn-icon" title="Hapus">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <polyline points="3 6 5 6 21 6" />
                                            <path d="M19 6l-1 14H6L5 6" />
                                            <path d="M10 11v6M14 11v6" />
                                            <path d="M9 6V4h6v2" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding:48px 16px;">
                                <div style="display:flex; flex-direction:column; align-items:center; gap:12px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40"
                                        viewBox="0 0 24 24" fill="none" stroke="var(--color-ink-600)"
                                        stroke-width="1.5">
                                        <circle cx="12" cy="12" r="10" />
                                        <line x1="12" y1="8" x2="12" y2="16" />
                                        <line x1="8" y1="12" x2="16" y2="12" />
                                    </svg>
                                    <div
                                        style="font-family:var(--font-display); font-size:20px; color:var(--color-ink-500); letter-spacing:0.04em;">
                                        {{ $addonSearch ? 'Tidak ditemukan' : 'Belum ada add-on' }}
                                    </div>
                                    <p style="font-family:var(--font-mono); font-size:11px; color:var(--color-ink-600);">
                                        {{ $addonSearch ? 'Coba kata kunci lain' : 'Mulai dengan menambahkan add-on pertama' }}
                                    </p>
                                    @if (!$addonSearch)
                                        <a wire:navigate href="{{ route('add-on.create') }}"
                                            class="btn btn-primary btn-sm" style="margin-top:4px;">
                                            + Tambah Add-On
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($addOns->hasPages())
            <div style="margin-top:16px; display:flex; justify-content:flex-end;">
                {{ $addOns->links() }}
            </div>
        @endif

        {{-- Delete Confirmation Modal --}}
        @if ($addonDeleteId)
            <div class="modal-backdrop" wire:click.self="$set('addonDeleteId', null)">
                <div class="modal">
                    <div
                        style="display:flex; align-items:flex-start; justify-content:space-between; gap:12px; margin-bottom:20px;">
                        <div class="modal-title" style="color:var(--color-error); font-size:24px;">HAPUS<br>ADD-ON?
                        </div>
                        <button class="modal-close" wire:click="$set('addonDeleteId', null)">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <line x1="18" y1="6" x2="6" y2="18" />
                                <line x1="6" y1="6" x2="18" y2="18" />
                            </svg>
                        </button>
                    </div>
                    <p style="color:var(--color-ink-300); font-size:13px; margin-bottom:24px; line-height:1.6;">
                        Tindakan ini <strong style="color:var(--color-error);">tidak bisa dibatalkan</strong>. Add-on
                        akan dihapus secara permanen dari sistem.
                    </p>
                    <div style="display:flex; gap:10px;">
                        <button wire:click="$set('addonDeleteId', null)" class="btn btn-secondary"
                            style="flex:1;">Batal</button>
                        <button wire:click="deleteAddon" class="btn btn-danger" style="flex:1;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <polyline points="3 6 5 6 21 6" />
                                <path d="M19 6l-1 14H6L5 6" />
                            </svg>
                            Ya, Hapus
                        </button>
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>
