<div>
    {{-- ── Header ── --}}
    <div class="page-header"
        style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:12px;">
        <div>
            <div class="page-subtitle">Catalog / Extras</div>
            <h1 class="page-title">ADD<span class="accent">-ONS</span></h1>
        </div>
        <a href="{{ route('add-on.create') }}" class="btn btn-primary">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="12" y1="5" x2="12" y2="19" />
                <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            New Add-On
        </a>
    </div>

    {{-- ── Flash Message ── --}}
    @if (session()->has('success'))
        <div class="toast toast-success animate-fade-up" style="position:relative;margin-bottom:16px;max-width:100%;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2.5" style="color:var(--color-success);flex-shrink:0;margin-top:1px;">
                <polyline points="20 6 9 17 4 12" />
            </svg>
            <span style="color:var(--color-ink-100);">{{ session('success') }}</span>
        </div>
    @endif

    {{-- ── Stats Bar ── --}}
    <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:20px;">
        <div class="stat-card" style="padding:14px 20px;flex:1;min-width:140px;">
            <div class="stat-value" style="font-size:28px;">{{ $addOns->total() }}</div>
            <div class="stat-label">Total Add-Ons</div>
        </div>
        <div class="stat-card accent-orange" style="padding:14px 20px;flex:1;min-width:140px;">
            <div class="stat-value" style="font-size:28px;">{{ $addOns->where('is_available', true)->count() }}</div>
            <div class="stat-label">Available</div>
        </div>
        <div class="stat-card" style="padding:14px 20px;flex:1;min-width:140px;--accent-color:var(--color-error);">
            <div class="stat-value" style="font-size:28px;color:var(--color-error);">
                {{ $addOns->where('is_available', false)->count() }}</div>
            <div class="stat-label">Unavailable</div>
            <div style="position:absolute;top:0;left:0;right:0;height:2px;background:var(--color-error);"></div>
        </div>
    </div>

    {{-- ── Filter & Search ── --}}
    <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;margin-bottom:16px;">
        <div style="flex:1;min-width:220px;position:relative;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--color-ink-400);pointer-events:none;">
                <circle cx="11" cy="11" r="8" />
                <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search add-ons…"
                class="form-input" style="padding-left:36px;">
        </div>
        <select wire:model.live="filterStatus" class="form-input form-select" style="width:auto;min-width:140px;">
            <option value="">All Status</option>
            <option value="1">Available</option>
            <option value="0">Unavailable</option>
        </select>
    </div>

    {{-- ── Table ── --}}
    <div class="table-wrapper animate-fade-up">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:48px;">#</th>
                    <th>Add-On Name</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($addOns as $index => $addOn)
                    <tr wire:key="addon-{{ $addOn->id }}" class="animate-fade-up"
                        style="animation-delay:{{ $index * 0.03 }}s;">
                        <td>
                            <span style="font-family:var(--font-mono);font-size:11px;color:var(--color-ink-500);">
                                {{ ($addOns->currentPage() - 1) * $addOns->perPage() + $index + 1 }}
                            </span>
                        </td>
                        <td>
                            <span style="font-weight:600;color:var(--color-ink-50);">{{ $addOn->name }}</span>
                        </td>
                        <td>
                            <span class="price price-sm">
                                Rp {{ number_format($addOn->price, 0, ',', '.') }}
                            </span>
                        </td>
                        <td>
                            @if ($addOn->is_available)
                                <span class="badge badge-available">
                                    <span class="badge-dot" style="background:var(--color-success);"></span>
                                    Available
                                </span>
                            @else
                                <span class="badge badge-unavailable">
                                    <span class="badge-dot" style="background:var(--color-ink-500);"></span>
                                    Unavailable
                                </span>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex;gap:6px;justify-content:flex-end;align-items:center;">
                                {{-- Toggle --}}
                                <button wire:click="toggleAvailability({{ $addOn->id }})"
                                    class="btn btn-xs btn-mono"
                                    title="{{ $addOn->is_available ? 'Disable' : 'Enable' }}"
                                    wire:loading.attr="disabled" wire:target="toggleAvailability({{ $addOn->id }})">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        @if ($addOn->is_available)
                                            <circle cx="12" cy="12" r="10" />
                                            <line x1="4.93" y1="4.93" x2="19.07" y2="19.07" />
                                        @else
                                            <circle cx="12" cy="12" r="10" />
                                            <polyline points="12 6 12 12 16 14" />
                                        @endif
                                    </svg>
                                    {{ $addOn->is_available ? 'Disable' : 'Enable' }}
                                </button>

                                {{-- Edit --}}
                                <a href="{{ route('add-on.edit', $addOn->id) }}" class="btn btn-xs btn-secondary">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                    </svg>
                                    Edit
                                </a>

                                {{-- Delete --}}
                                <button wire:click="confirmDelete({{ $addOn->id }})"
                                    class="btn btn-xs btn-danger">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6" />
                                        <path d="M19 6l-1 14H6L5 6" />
                                        <path d="M10 11v6M14 11v6" />
                                        <path d="M9 6V4h6v2" />
                                    </svg>
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center;padding:40px 16px;">
                            <div
                                style="color:var(--color-ink-500);font-family:var(--font-mono);font-size:12px;letter-spacing:0.08em;">
                                — NO ADD-ONS FOUND —
                            </div>
                            <a href="{{ route('add-on.create') }}" class="btn btn-primary btn-sm"
                                style="margin-top:16px;display:inline-flex;">
                                Add First Add-On
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ── Pagination ── --}}
    @if ($addOns->hasPages())
        <div style="margin-top:16px;">
            {{ $addOns->links() }}
        </div>
    @endif

    {{-- ── Delete Confirm Modal ── --}}
    @if ($deleteId)
        <div class="modal-backdrop" wire:click.self="$set('deleteId', null)">
            <div class="modal">
                <div
                    style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:20px;">
                    <div class="modal-title" style="color:var(--color-error);font-size:24px;">DELETE<br>ADD-ON?</div>
                    <button class="modal-close" wire:click="$set('deleteId', null)">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18" />
                            <line x1="6" y1="6" x2="18" y2="18" />
                        </svg>
                    </button>
                </div>
                <p style="color:var(--color-ink-300);font-size:13px;margin-bottom:24px;line-height:1.6;">
                    This action <strong style="color:var(--color-error);">cannot be undone</strong>. The add-on will be
                    permanently removed from the system.
                </p>
                <div style="display:flex;gap:10px;">
                    <button wire:click="$set('deleteId', null)" class="btn btn-secondary"
                        style="flex:1;">Cancel</button>
                    <button wire:click="delete" class="btn btn-danger" style="flex:1;">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <polyline points="3 6 5 6 21 6" />
                            <path d="M19 6l-1 14H6L5 6" />
                        </svg>
                        Yes, Delete
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
