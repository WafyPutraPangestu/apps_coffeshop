<div>
    {{-- Page Header --}}
    <div class="page-header"
        style="display:flex; align-items:flex-end; justify-content:space-between; gap:16px; flex-wrap:wrap;">
        <div>
            <h1 class="page-title">DA<span class="accent">TA</span> MENU</h1>
            <p class="page-subtitle">Kelola seluruh menu Warso Coffee</p>
        </div>
        <a wire:navigate href="{{ route('menu.create') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19" />
                <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            Tambah Menu
        </a>
    </div>

    {{-- Filter Bar --}}
    <div style="display:flex; gap:10px; margin-bottom:20px; flex-wrap:wrap; align-items:center;">

        {{-- Search --}}
        <div style="position:relative; flex:1; min-width:180px; max-width:300px;">
            <svg style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--color-ink-500); pointer-events:none;"
                xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8" />
                <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari menu..." class="form-input"
                style="padding-left:36px;" />
        </div>

        {{-- Filter Kategori --}}
        <select wire:model.live="filterCategory" class="form-input form-select" style="width:auto; min-width:140px;">
            <option value="">Semua Kategori</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>

        {{-- Filter Status --}}
        <select wire:model.live="filterStatus" class="form-input form-select" style="width:auto; min-width:130px;">
            <option value="">Semua Status</option>
            <option value="1">Tersedia</option>
            <option value="0">Habis</option>
        </select>

        {{-- Total --}}
        <div class="badge badge-lime" style="font-size:11px; padding:5px 12px; margin-left:auto;">
            {{ $menus->total() }} menu
        </div>
    </div>

    {{-- Table --}}
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

                        {{-- No --}}
                        <td style="color:var(--color-ink-500); font-family:var(--font-mono); font-size:11px;">
                            {{ $menus->firstItem() + $loop->index }}
                        </td>

                        {{-- Nama + Gambar --}}
                        <td>
                            <div style="display:flex; align-items:center; gap:12px;">
                                {{-- Thumbnail --}}
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

                        {{-- Kategori --}}
                        <td>
                            <span class="badge badge-orange">{{ $menu->category->name }}</span>
                        </td>

                        {{-- Harga --}}
                        <td style="text-align:right;">
                            <span class="price price-sm">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                        </td>

                        {{-- Status badge --}}
                        <td style="text-align:center;">
                            @if ($menu->is_available)
                                <span class="badge badge-available">
                                    <span class="badge-dot  " style="background:var(--color-success);"></span>
                                    Tersedia
                                </span>
                            @else
                                <span class="badge badge-unavailable">
                                    <span class="badge-dot" style="background:var(--color-ink-500);"></span>
                                    Habis
                                </span>
                            @endif
                        </td>

                        {{-- Toggle --}}
                        <td style="text-align:center;">
                            <label class="toggle toggle-wrapper  " style="margin:0 auto;">
                                <input type="checkbox" {{ $menu->is_available ? 'checked' : '' }}
                                    wire:click="toggleAvailable({{ $menu->id }})" />
                                <div class="toggle-track "></div>
                                <div class="toggle-thumb"></div>
                            </label>
                        </td>

                        {{-- Aksi --}}
                        <td>
                            <div style="display:flex; align-items:center; justify-content:flex-end; gap:6px;">
                                <a wire:navigate href="{{ route('menu.edit', $menu->id) }}"
                                    class="btn btn-ghost btn-icon" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                    </svg>
                                </a>
                                <button wire:click="confirmDelete({{ $menu->id }})"
                                    class="btn btn-danger btn-icon" title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="3 6 5 6 21 6" />
                                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                                        <path d="M10 11v6" />
                                        <path d="M14 11v6" />
                                        <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
                                    </svg>
                                </button>
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
                                    <a wire:navigate href="{{ route('menu.create') }}" class="btn btn-primary btn-sm"
                                        style="margin-top:4px;">
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

    {{-- Pagination --}}
    @if ($menus->hasPages())
        <div style="margin-top:16px; display:flex; justify-content:flex-end;">
            {{ $menus->links() }}
        </div>
    @endif
</div>
