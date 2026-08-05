<div>
    {{-- Page Header --}}
    <div class="page-header"
        style="display:flex; align-items:flex-end; justify-content:space-between; gap:16px; flex-wrap:wrap;">
        <div>
            <h1 class="page-title">LAPO<span class="accent">RAN</span></h1>
            <p class="page-subtitle">Rekap & analisis penjualan Warso Coffee</p>
        </div>

        {{-- Download Excel --}}
        <button wire:click="downloadExcel" wire:loading.attr="disabled" wire:target="downloadExcel" class="btn btn-primary">
            <span wire:loading.remove wire:target="downloadExcel" style="display:flex;align-items:center;gap:6px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                    <polyline points="7 10 12 15 17 10" />
                    <line x1="12" y1="15" x2="12" y2="3" />
                </svg>
                Download Excel
            </span>
            <span wire:loading wire:target="downloadExcel" style="display:none;align-items:center;gap:8px;">
                <span class="spinner"></span> Menyiapkan...
            </span>
        </button>
    </div>

    {{-- Filter Bar --}}
    <div style="display:flex; gap:10px; margin-bottom:24px; flex-wrap:wrap; align-items:flex-end;">

        {{-- Range Tanggal --}}
        <div class="form-group" style="margin:0;">
            <label class="form-label">Dari Tanggal</label>
            <input wire:model.live="startDate" type="date" class="form-input" style="width:auto;" />
        </div>

        <div class="form-group" style="margin:0;">
            <label class="form-label">Sampai Tanggal</label>
            <input wire:model.live="endDate" type="date" class="form-input" style="width:auto;" />
        </div>

        {{-- Filter Status --}}
        <div class="form-group" style="margin:0;">
            <label class="form-label">Status Order</label>
            <select wire:model.live="filterStatus" class="form-input form-select" style="width:auto; min-width:140px;">
                <option value="">Semua</option>
                <option value="Pending">Pending</option>
                <option value="Processing">Processing</option>
                <option value="Completed">Completed</option>
            </select>
        </div>

        {{-- Filter Payment --}}
        <div class="form-group" style="margin:0;">
            <label class="form-label">Pembayaran</label>
            <select wire:model.live="filterPayment" class="form-input form-select" style="width:auto; min-width:130px;">
                <option value="">Semua</option>
                <option value="Paid">Paid</option>
                <option value="Unpaid">Unpaid</option>
                <option value="Failed">Failed</option>
                <option value="Expired">Expired</option>
            </select>
        </div>

        {{-- Reset --}}
        <button wire:click="resetFilter" class="btn btn-ghost btn-sm" style="align-self:flex-end;">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="1 4 1 10 7 10" />
                <path d="M3.51 15a9 9 0 1 0 .49-3.5" />
            </svg>
            Reset
        </button>

    </div>

    {{-- Stat Cards --}}
    <div class="grid-dashboard animate-fade-up" style="margin-bottom:24px;">

        <div class="stat-card">
            <div class="stat-value">{{ number_format($stats['total_order']) }}</div>
            <div class="stat-label">Total Pesanan</div>
        </div>

        <div class="stat-card accent-orange">
            <div class="stat-value" style="font-size:26px; color:var(--color-lime-400);">
                Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}
            </div>
            <div class="stat-label">Total Pendapatan</div>
            <div class="stat-change up">Hanya order Paid</div>
        </div>

        <div class="stat-card accent-cyan">
            <div class="stat-value">{{ number_format($stats['completed']) }}</div>
            <div class="stat-label">Order Selesai</div>
        </div>

        <div class="stat-card accent-pink">
            <div class="stat-value" style="font-size:26px;">
                Rp {{ number_format($stats['avg_order'], 0, ',', '.') }}
            </div>
            <div class="stat-label">Rata-rata/Order</div>
        </div>

        <div class="stat-card">
            <div class="stat-value">{{ number_format($stats['total_items']) }}</div>
            <div class="stat-label">Total Item Terjual</div>
        </div>

        <div class="stat-card">
            <div class="stat-value">{{ number_format($stats['pending']) }}</div>
            <div class="stat-label">Masih Pending</div>
        </div>

    </div>

    {{-- Chart: Pendapatan Harian --}}
    @if ($dailyRevenue->count() > 0)
        <div class="card" style="margin-bottom:24px; padding:20px;">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
                <div>
                    <div style="font-family:var(--font-display); font-size:18px; letter-spacing:0.04em; color:#fff;">
                        PENDAPATAN HARIAN
                    </div>
                    <div
                        style="font-family:var(--font-mono); font-size:10px; color:var(--color-ink-500); text-transform:uppercase; letter-spacing:0.1em; margin-top:2px;">
                        Hanya order Paid
                    </div>
                </div>
                <span class="badge badge-lime">{{ $dailyRevenue->count() }} hari</span>
            </div>

            {{-- Bar chart manual --}}
            @php
                $maxRevenue = $dailyRevenue->max('revenue') ?: 1;
            @endphp
            {{-- Wrapper untuk scroll horizontal --}}
            <div style="overflow-x:auto; width:100%; padding-bottom:12px;">
                <div
                    style="display:flex; align-items:flex-end; gap:4px; height:140px; padding-bottom:32px; padding-left:8px; position:relative; min-width:100%; width:max-content;">
                    {{-- Garis horizontal --}}
                    <div style="position:absolute; top:0; left:0; right:0; border-top:1px dashed var(--color-ink-700);">
                    </div>
                    <div style="position:absolute; top:50%; left:0; right:0; border-top:1px dashed var(--color-ink-800);">
                    </div>

                @foreach ($dailyRevenue as $day)
                    @php $pct = ($day->revenue / $maxRevenue) * 100; @endphp
                    <div
                        style="flex:1; min-width:24px; display:flex; flex-direction:column; align-items:center; gap:4px; height:100%; justify-content:flex-end; position:relative;">
                        {{-- Tooltip on hover --}}
                        <div style="
                            position:absolute;
                            top:4px;
                            left:4px;
                            background:var(--color-ink-700);
                            border:1px solid var(--color-ink-500);
                            border-radius:var(--radius-sm);
                            padding:3px 6px;
                            font-family:var(--font-mono);
                            font-size:9px;
                            color:var(--color-lime-400);
                            white-space:nowrap;
                            opacity:0;
                            transition:opacity 0.15s;
                            pointer-events:none;
                            z-index:20;
                        "
                            class="bar-tooltip">
                            Rp {{ number_format($day->revenue, 0, ',', '.') }}<br />
                            {{ $day->orders }} order
                        </div>
                        {{-- Bar --}}
                        <div style="width:100%; height:{{ max(2, (int) $pct) }}%; background:var(--color-lime-500); border-radius:2px 2px 0 0; transition:height 0.4s ease; cursor:pointer;"
                            onmouseover="this.previousElementSibling.style.opacity='1'; this.style.background='var(--color-lime-400)';"
                            onmouseout="this.previousElementSibling.style.opacity='0'; this.style.background='var(--color-lime-500)';">
                        </div>
                        {{-- Label tanggal --}}
                        <div
                            style="position:absolute; bottom:-32px; font-family:var(--font-mono); font-size:8px; color:var(--color-ink-500); white-space:nowrap; transform:rotate(-45deg); transform-origin:top left; left:50%;">
                            {{ \Carbon\Carbon::parse($day->date)->format('d/m') }}
                        </div>
                    </div>
                @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- Tabel Transaksi --}}
    <div
        style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px; flex-wrap:wrap; gap:8px;">
        <div style="font-family:var(--font-display); font-size:18px; letter-spacing:0.04em; color:#fff;">DETAIL
            TRANSAKSI</div>
        <div class="badge badge-lime" style="font-size:11px; padding:5px 12px;">{{ $orders->total() }} pesanan</div>
    </div>

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
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr class="animate-fade-up" style="animation-delay:{{ $loop->index * 0.02 }}s;">

                        <td>
                            <span
                                style="font-family:var(--font-mono); font-size:12px; font-weight:600; color:var(--color-lime-400); letter-spacing:0.06em;">
                                {{ $order->order_code }}
                            </span>
                        </td>

                        <td>
                            <span class="table-indicator" style="font-size:10px; padding:3px 8px;">
                                {{ $order->table->table_number }}
                            </span>
                        </td>

                        <td>
                            <div style="display:flex; flex-direction:column; gap:2px;">
                                @foreach ($order->items->take(2) as $item)
                                    <span style="font-size:12px; color:var(--color-ink-200);">{{ $item->quantity }}×
                                        {{ $item->menu->name }}</span>
                                @endforeach
                                @if ($order->items->count() > 2)
                                    <span
                                        style="font-family:var(--font-mono); font-size:10px; color:var(--color-ink-500);">+{{ $order->items->count() - 2 }}
                                        item lain</span>
                                @endif
                            </div>
                        </td>

                        <td style="text-align:right;">
                            <span class="price price-sm">Rp
                                {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </td>

                        <td style="text-align:center;">
                            @if ($order->payment_status === 'Paid')
                                <span class="badge badge-paid"><span class="badge-dot"
                                        style="background:var(--color-success);"></span>Paid</span>
                            @elseif($order->payment_status === 'Unpaid')
                                <span class="badge badge-unpaid"><span class="badge-dot"
                                        style="background:var(--color-error);"></span>Unpaid</span>
                            @else
                                <span class="badge badge-unavailable">{{ $order->payment_status }}</span>
                            @endif
                        </td>

                        <td style="text-align:center;">
                            @if ($order->order_status === 'Pending')
                                <span class="badge badge-pending"><span class="badge-dot"></span>Pending</span>
                            @elseif($order->order_status === 'Processing')
                                <span class="badge badge-processing"><span class="badge-dot"></span>Processing</span>
                            @else
                                <span class="badge badge-completed"><span class="badge-dot"></span>Completed</span>
                            @endif
                        </td>

                        <td style="text-align:center;">
                            <span
                                style="font-family:var(--font-mono); font-size:10px; color:var(--color-ink-400); text-transform:uppercase; letter-spacing:0.06em;">
                                {{ $order->payment_method ?? '—' }}
                            </span>
                        </td>

                        <td>
                            <div style="font-family:var(--font-mono); font-size:11px; color:var(--color-ink-400);">
                                {{ $order->created_at->format('d M Y') }}</div>
                            <div style="font-family:var(--font-mono); font-size:10px; color:var(--color-ink-600);">
                                {{ $order->created_at->format('H:i') }}</div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center; padding:48px 16px;">
                            <div style="display:flex; flex-direction:column; align-items:center; gap:12px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40"
                                    viewBox="0 0 24 24" fill="none" stroke="var(--color-ink-600)"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="20" x2="18" y2="10" />
                                    <line x1="12" y1="20" x2="12" y2="4" />
                                    <line x1="6" y1="20" x2="6" y2="14" />
                                    <line x1="2" y1="20" x2="22" y2="20" />
                                </svg>
                                <div
                                    style="font-family:var(--font-display); font-size:20px; color:var(--color-ink-500); letter-spacing:0.04em;">
                                    Tidak ada data</div>
                                <p style="font-family:var(--font-mono); font-size:11px; color:var(--color-ink-600);">
                                    Coba ubah range tanggal atau filter</p>
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
