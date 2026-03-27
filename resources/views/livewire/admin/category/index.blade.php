<div>
    {{-- Page Header --}}
    <div class="page-header"
        style="display:flex; align-items:flex-end; justify-content:space-between; gap:16px; flex-wrap:wrap;">
        <div>
            <h1 class="page-title">KATE<span class="accent">GORI</span></h1>
            <p class="page-subtitle">Kelola kategori menu Warso Coffee</p>
        </div>
        <a wire:navigate href="{{ route('categories.create') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19" />
                <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            Tambah Kategori
        </a>
    </div>

    {{-- Search + Stats --}}
    <div style="display:flex; align-items:center; gap:12px; margin-bottom:20px; flex-wrap:wrap;">
        {{-- Search --}}
        <div style="position:relative; flex:1; min-width:200px; max-width:360px;">
            <svg style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--color-ink-500); pointer-events:none;"
                xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8" />
                <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari kategori..."
                class="form-input" style="padding-left:36px;" />
        </div>

        {{-- Total badge --}}
        <div class="badge badge-lime" style="font-size:11px; padding:5px 12px;">
            {{ $categories->total() }} kategori
        </div>
    </div>

    {{-- Table --}}
    <div class="table-wrapper animate-fade-up">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:48px;">#</th>
                    <th>Nama Kategori</th>
                    <th style="text-align:center;">Jumlah Menu</th>
                    <th style="text-align:center;">Dibuat</th>
                    <th style="width:100px; text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr class="animate-fade-up" style="animation-delay: {{ $loop->index * 0.04 }}s;">
                        <td style="color:var(--color-ink-500); font-family:var(--font-mono); font-size:11px;">
                            {{ $categories->firstItem() + $loop->index }}
                        </td>
                        <td>
                            <div style="display:flex; align-items:center; gap:10px;">
                                {{-- Icon kategori --}}
                                <div
                                    style="width:32px; height:32px; border-radius:var(--radius-sm); background:rgba(200,255,0,0.06); border:1px solid rgba(200,255,0,0.2); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                        viewBox="0 0 24 24" fill="none" stroke="var(--color-lime-500)"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 6h16M4 10h16M4 14h10M4 18h6" />
                                    </svg>
                                </div>
                                <span style="font-weight:600; color:var(--color-ink-100);">{{ $category->name }}</span>
                            </div>
                        </td>
                        <td style="text-align:center;">
                            @if ($category->menus_count > 0)
                                <span class="badge badge-lime">{{ $category->menus_count }} menu</span>
                            @else
                                <span class="badge badge-unavailable">Kosong</span>
                            @endif
                        </td>
                        <td
                            style="text-align:center; font-family:var(--font-mono); font-size:11px; color:var(--color-ink-400);">
                            {{ $category->created_at->format('d M Y') }}
                        </td>
                        <td>
                            <div style="display:flex; align-items:center; justify-content:flex-end; gap:6px;">
                                <a wire:navigate href="{{ route('categories.edit', $category->id) }}"
                                    class="btn btn-ghost btn-icon" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                    </svg>
                                </a>
                                <button wire:click="confirmDelete({{ $category->id }})" class="btn btn-danger btn-icon"
                                    title="Hapus">
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
                        <td colspan="5" style="text-align:center; padding:48px 16px;">
                            <div style="display:flex; flex-direction:column; align-items:center; gap:12px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40"
                                    viewBox="0 0 24 24" fill="none" stroke="var(--color-ink-600)"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 6h16M4 10h16M4 14h10M4 18h6" />
                                </svg>
                                <div
                                    style="font-family:var(--font-display); font-size:20px; color:var(--color-ink-500); letter-spacing:0.04em;">
                                    {{ $search ? 'Tidak ditemukan' : 'Belum ada kategori' }}
                                </div>
                                <p style="font-family:var(--font-mono); font-size:11px; color:var(--color-ink-600);">
                                    {{ $search ? 'Coba kata kunci lain' : 'Mulai dengan menambahkan kategori pertama' }}
                                </p>
                                @if (!$search)
                                    <a wire:navigate href="{{ route('categories.create') }}"
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

    {{-- Pagination --}}
    @if ($categories->hasPages())
        <div style="margin-top:16px; display:flex; justify-content:flex-end;">
            {{ $categories->links() }}
        </div>
    @endif
</div>
