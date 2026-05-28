<div>
    {{-- Page Header --}}
    <div class="page-header"
        style="display:flex; align-items:flex-end; justify-content:space-between; gap:16px; flex-wrap:wrap;">
        <div>
            <h1 class="page-title">RIWA<span class="accent">YAT</span></h1>
            <p class="page-subtitle">Seluruh riwayat transaksi Warso Coffee</p>
        </div>
        {{-- Filter Tanggal --}}
        <div style="display:flex; align-items:center; gap:8px;">
            <input wire:model.live="filterDate" type="date" class="form-input" style="width:auto;" />
            @if ($filterDate)
                <button wire:click="$set('filterDate', '')" class="btn btn-ghost btn-sm" title="Reset tanggal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                        stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                </button>
            @endif
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid-dashboard animate-fade-up" style="margin-bottom:24px;">

        <div class="stat-card">
            <div class="stat-value">{{ number_format($stats['total']) }}</div>
            <div class="stat-label">Total Pesanan</div>
            @if ($filterDate)
                <div class="stat-change" style="color:var(--color-ink-500);">
                    {{ \Carbon\Carbon::parse($filterDate)->format('d M Y') }}</div>
            @endif
        </div>

        <div class="stat-card accent-orange">
            <div class="stat-value" style="color:var(--color-lime-400);">
                Rp {{ number_format($stats['revenue'], 0, ',', '.') }}
            </div>
            <div class="stat-label">Total Pendapatan</div>
            <div class="stat-change up">Hanya order Paid</div>
        </div>

        <div class="stat-card accent-cyan">
            <div class="stat-value">{{ number_format($stats['completed']) }}</div>
            <div class="stat-label">Selesai</div>
        </div>

        <div class="stat-card accent-pink">
            <div class="stat-value">{{ number_format($stats['pending']) }}</div>
            <div class="stat-label">Pending</div>
        </div>

    </div>

    {{-- Filter Bar --}}
    <div style="display:flex; gap:10px; margin-bottom:20px; flex-wrap:wrap; align-items:center;">

        {{-- Search kode order --}}
        <div style="position:relative; flex:1; min-width:180px; max-width:280px;">
            <svg style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--color-ink-500); pointer-events:none;"
                xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8" />
                <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari kode order..."
                class="form-input" style="padding-left:36px;" />
        </div>

        {{-- Filter Order Status --}}
        <select wire:model.live="filterStatus" class="form-input form-select" style="width:auto; min-width:150px;">
            <option value="">Semua Status Order</option>
            <option value="Pending">Pending</option>
            <option value="Processing">Processing</option>
            <option value="Completed">Completed</option>
        </select>

        {{-- Filter Payment --}}
        <select wire:model.live="filterPayment" class="form-input form-select" style="width:auto; min-width:150px;">
            <option value="">Semua Pembayaran</option>
            <option value="Paid">Paid</option>
            <option value="Unpaid">Unpaid</option>
            <option value="Failed">Failed</option>
            <option value="Expired">Expired</option>
        </select>

        {{-- Total --}}
        <div class="badge badge-lime" style="font-size:11px; padding:5px 12px; margin-left:auto;">
            {{ $orders->total() }} pesanan
        </div>
    </div>

    {{-- Table --}}
    <div class="table-wrapper animate-fade-up">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Kode Order</th>
                    <th>Meja</th>
                    <th>Item</th>
                    <th style="text-align:right;">Total</th>
                    <th style="text-align:center;">Pembayaran</th>
                    <th style="text-align:center;">Status</th>
                    <th style="text-align:center;">Metode</th>
                    <th>Waktu</th>
                    <th style="text-align:right; width:60px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr class="animate-fade-up" style="animation-delay:{{ $loop->index * 0.02 }}s;">

                        {{-- Kode Order --}}
                        <td>
                            <span
                                style="font-family:var(--font-mono); font-size:12px; font-weight:600; color:var(--color-lime-400); letter-spacing:0.06em;">
                                {{ $order->order_code }}
                            </span>
                        </td>

                        {{-- Meja --}}
                        <td>
                            <span class="table-indicator" style="font-size:10px; padding:3px 8px;">
                                {{ $order->table->table_number }}
                            </span>
                        </td>

                        {{-- Item --}}
                        <td>
                            <div style="display:flex; flex-direction:column; gap:2px;">
                                @foreach ($order->items->take(2) as $item)
                                    <span style="font-size:12px; color:var(--color-ink-200);">
                                        {{ $item->quantity }}× {{ $item->menu->name }}
                                    </span>
                                @endforeach
                                @if ($order->items->count() > 2)
                                    <span
                                        style="font-family:var(--font-mono); font-size:10px; color:var(--color-ink-500);">
                                        +{{ $order->items->count() - 2 }} item lain
                                    </span>
                                @endif
                            </div>
                        </td>

                        {{-- Total --}}
                        <td style="text-align:right;">
                            <span class="price price-sm">Rp
                                {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </td>

                        {{-- Payment Status --}}
                        <td style="text-align:center;">
                            @if ($order->payment_status === 'Paid')
                                <span class="badge badge-paid"><span class="badge-dot"
                                        style="background:var(--color-success);"></span>Paid</span>
                            @elseif($order->payment_status === 'Unpaid')
                                <span class="badge badge-unpaid"><span class="badge-dot"
                                        style="background:var(--color-error);"></span>Unpaid</span>
                            @elseif($order->payment_status === 'Failed')
                                <span class="badge"
                                    style="background:rgba(255,45,120,0.08); color:var(--color-error); border:1px solid rgba(255,45,120,0.3);">Failed</span>
                            @else
                                <span class="badge badge-unavailable">Expired</span>
                            @endif
                        </td>

                        {{-- Order Status --}}
                        <td style="text-align:center;">
                            @if ($order->order_status === 'Pending')
                                <span class="badge badge-pending"><span class="badge-dot"></span>Pending</span>
                            @elseif($order->order_status === 'Processing')
                                <span class="badge badge-processing"><span class="badge-dot"></span>Processing</span>
                            @else
                                <span class="badge badge-completed"><span class="badge-dot"></span>Completed</span>
                            @endif
                        </td>

                        {{-- Metode --}}
                        <td style="text-align:center;">
                            @if ($order->payment_method)
                                <span
                                    style="font-family:var(--font-mono); font-size:10px; color:var(--color-ink-400); text-transform:uppercase; letter-spacing:0.06em;">
                                    {{ $order->payment_method }}
                                </span>
                            @else
                                <span style="color:var(--color-ink-600); font-size:12px;">—</span>
                            @endif
                        </td>

                        {{-- Waktu --}}
                        <td>
                            <div style="font-family:var(--font-mono); font-size:11px; color:var(--color-ink-400);">
                                {{ $order->created_at->format('d M Y') }}
                            </div>
                            <div style="font-family:var(--font-mono); font-size:10px; color:var(--color-ink-600);">
                                {{ $order->created_at->format('H:i') }}
                            </div>
                        </td>

                        {{-- Aksi --}}
                        <td>
                            <div style="display:flex; justify-content:flex-end;">
                                <button wire:click="confirmDelete({{ $order->id }})"
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
                        <td colspan="9" style="text-align:center; padding:48px 16px;">
                            <div style="display:flex; flex-direction:column; align-items:center; gap:12px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40"
                                    viewBox="0 0 24 24" fill="none" stroke="var(--color-ink-600)"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10" />
                                    <polyline points="12 6 12 12 16 14" />
                                </svg>
                                <div
                                    style="font-family:var(--font-display); font-size:20px; color:var(--color-ink-500); letter-spacing:0.04em;">
                                    Belum ada riwayat
                                </div>
                                <p style="font-family:var(--font-mono); font-size:11px; color:var(--color-ink-600);">
                                    {{ $search || $filterStatus || $filterPayment || $filterDate ? 'Coba ubah filter pencarian' : 'Pesanan yang selesai akan muncul di sini' }}
                                </p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if ($orders->hasPages())
        <div style="margin-top:16px; display:flex; justify-content:flex-end;">
            {{ $orders->links() }}
        </div>
    @endif
</div>
