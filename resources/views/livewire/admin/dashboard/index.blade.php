<div x-data="{
    revenueChart: null,
    hourlyChart: null,
    categoryChart: null,
    paymentChart: null,
    initCharts() {
        this.$nextTick(() => {
            this.buildRevenueChart();
            this.buildHourlyChart();
            this.buildCategoryChart();
            this.buildPaymentChart();
        });
    },
    buildRevenueChart() {
        const ctx = document.getElementById('revenueChart');
        if (!ctx) return;
        if (this.revenueChart) this.revenueChart.destroy();
        const labels = @js($revenueChart['labels'] ?? []);
        const values = @js($revenueChart['values'] ?? []);
        this.revenueChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Revenue',
                    data: values,
                    backgroundColor: 'rgba(190,242,0,0.15)',
                    borderColor: '#bef200',
                    borderWidth: 2,
                    borderRadius: 3,
                    hoverBackgroundColor: 'rgba(190,242,0,0.3)',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#181818',
                        borderColor: '#2c2c2c',
                        borderWidth: 1,
                        titleColor: '#a3a3a3',
                        bodyColor: '#bef200',
                        callbacks: {
                            label: (ctx) => 'Rp ' + ctx.parsed.y.toLocaleString('id-ID')
                        }
                    }
                },
                scales: {
                    x: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#737373', font: { family: 'IBM Plex Mono', size: 10 } } },
                    y: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#737373', font: { family: 'IBM Plex Mono', size: 10 }, callback: (v) => 'Rp ' + (v / 1000).toFixed(0) + 'k' } }
                }
            }
        });
    },
    buildHourlyChart() {
        const ctx = document.getElementById('hourlyChart');
        if (!ctx) return;
        if (this.hourlyChart) this.hourlyChart.destroy();
        const labels = @js($hourlyChart['labels'] ?? []);
        const values = @js($hourlyChart['values'] ?? []);
        this.hourlyChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    label: 'Orders',
                    data: values,
                    borderColor: '#f05200',
                    backgroundColor: 'rgba(240,82,0,0.08)',
                    borderWidth: 2,
                    pointBackgroundColor: '#f05200',
                    pointRadius: 3,
                    tension: 0.4,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { backgroundColor: '#181818', borderColor: '#2c2c2c', borderWidth: 1, titleColor: '#a3a3a3', bodyColor: '#f05200' } },
                scales: {
                    x: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#737373', font: { family: 'IBM Plex Mono', size: 10 } } },
                    y: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#737373', font: { family: 'IBM Plex Mono', size: 10 }, stepSize: 1 }, beginAtZero: true }
                }
            }
        });
    },
    buildCategoryChart() {
        const ctx = document.getElementById('categoryChart');
        if (!ctx) return;
        if (this.categoryChart) this.categoryChart.destroy();
        const data = @js($categoryRevenue);
        const labels = data.map(d => d.name);
        const values = data.map(d => d.revenue);
        this.categoryChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels,
                datasets: [{
                    data: values,
                    backgroundColor: ['rgba(190,242,0,0.8)', 'rgba(240,82,0,0.8)', 'rgba(0,217,245,0.8)', 'rgba(255,32,112,0.8)', 'rgba(147,51,234,0.8)'],
                    borderColor: '#0f0f0f',
                    borderWidth: 3,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: { position: 'bottom', labels: { color: '#a3a3a3', font: { family: 'IBM Plex Mono', size: 10 }, padding: 12, boxWidth: 10 } },
                    tooltip: { backgroundColor: '#181818', borderColor: '#2c2c2c', borderWidth: 1, bodyColor: '#f2f2f2', callbacks: { label: (ctx) => ctx.label + ': Rp ' + ctx.parsed.toLocaleString('id-ID') } }
                }
            }
        });
    },
    buildPaymentChart() {
        const ctx = document.getElementById('paymentChart');
        if (!ctx) return;
        if (this.paymentChart) this.paymentChart.destroy();
        const data = @js($paymentMethodChart);
        const labels = data.map(d => d.method || 'Unknown');
        const values = data.map(d => d.revenue);
        this.paymentChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Revenue',
                    data: values,
                    backgroundColor: ['rgba(190,242,0,0.7)', 'rgba(240,82,0,0.7)', 'rgba(0,217,245,0.7)', 'rgba(255,32,112,0.7)'],
                    borderRadius: 3,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { backgroundColor: '#181818', borderColor: '#2c2c2c', borderWidth: 1, bodyColor: '#f2f2f2', callbacks: { label: (ctx) => 'Rp ' + ctx.parsed.x.toLocaleString('id-ID') } } },
                scales: {
                    x: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#737373', font: { family: 'IBM Plex Mono', size: 10 }, callback: (v) => 'Rp ' + (v / 1000).toFixed(0) + 'k' } },
                    y: { grid: { display: false }, ticks: { color: '#a3a3a3', font: { family: 'IBM Plex Mono', size: 10 } } }
                }
            }
        });
    }
}" x-init="initCharts()"
    wire:key="dashboard-{{ $period }}-{{ $selectedYear }}-{{ $selectedMonth }}" @stats-updated.window="initCharts()"
    class="min-h-screen">
    {{-- ════════════════════════════════════════ --}}
    {{-- PAGE HEADER --}}
    {{-- ════════════════════════════════════════ --}}
    <div class="page-content" style="padding-bottom: 40px;">

        {{-- Header Row --}}
        <div class="flex flex-col gap-4 mb-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="page-header mb-0">
                <div class="flex items-center gap-3 mb-1">
                    <span class="sticker sticker-lime text-xs">LIVE</span>
                    <span class="font-mono text-xs" style="color:var(--color-ink-400); letter-spacing:0.12em;">WARSO
                        COFFEE</span>
                </div>
                <h1 class="page-title">
                    DASH<span class="accent">BOARD</span>
                </h1>
                <p class="page-subtitle">Analytics & Revenue Overview — {{ now()->format('D, d M Y') }}</p>
            </div>

            {{-- Period Controls --}}
            <div class="flex flex-wrap items-center gap-2">
                {{-- Period buttons --}}
                <div class="flex gap-1"
                    style="background:var(--color-ink-800); border:1px solid var(--color-ink-600); border-radius:var(--radius-md); padding:3px;">
                    @foreach (['today' => 'TODAY', 'week' => 'WEEK', 'month' => 'MONTH', 'year' => 'YEAR'] as $key => $label)
                        <button wire:click="setPeriod('{{ $key }}')"
                            class="btn btn-xs font-mono {{ $period === $key ? 'btn-primary' : 'btn-ghost' }}"
                            style="{{ $period !== $key ? 'border:none;' : '' }}">{{ $label }}</button>
                    @endforeach
                </div>

                {{-- Year selector --}}
                @if (in_array($period, ['month', 'year']))
                    <select wire:model.live="selectedYear" class="form-input form-select"
                        style="width:auto; padding:6px 32px 6px 12px; font-family:var(--font-mono); font-size:12px;">
                        @foreach ($availableYears as $yr)
                            <option value="{{ $yr }}">{{ $yr }}</option>
                        @endforeach
                    </select>
                @endif

                {{-- Month selector --}}
                @if ($period === 'month')
                    <select wire:model.live="selectedMonth" class="form-input form-select"
                        style="width:auto; padding:6px 32px 6px 12px; font-family:var(--font-mono); font-size:12px;">
                        @foreach (range(1, 12) as $m)
                            <option value="{{ $m }}">{{ \Carbon\Carbon::create(null, $m)->format('F') }}
                            </option>
                        @endforeach
                    </select>
                @endif

                {{-- Refresh --}}
                <button wire:click="loadData" class="btn btn-icon btn-secondary" title="Refresh">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8" />
                        <path d="M21 3v5h-5" />
                        <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16" />
                        <path d="M8 16H3v5" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- ════════════════════════════════════════ --}}
        {{-- STAT CARDS ROW --}}
        {{-- ════════════════════════════════════════ --}}
        <div class="grid-dashboard mb-6" wire:loading.class="opacity-60">

            {{-- Revenue --}}
            <div class="stat-card animate-fade-up stagger-1">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <p class="stat-label">Total Revenue</p>
                        <p class="stat-value" style="font-size:28px; margin-top:6px;">
                            Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                        </p>
                    </div>
                    <div
                        style="background:rgba(190,242,0,0.08); border:1px solid rgba(190,242,0,0.2); border-radius:var(--radius-sm); padding:8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            fill="none" stroke="#bef200" stroke-width="2">
                            <line x1="12" y1="1" x2="12" y2="23" />
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                        </svg>
                    </div>
                </div>
                <p class="stat-change {{ $revenueGrowth >= 0 ? 'up' : 'down' }}">
                    {{ $revenueGrowth >= 0 ? '↑' : '↓' }} {{ abs($revenueGrowth) }}% vs period before
                </p>
            </div>

            {{-- Total Orders --}}
            <div class="stat-card accent-orange animate-fade-up stagger-2">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <p class="stat-label">Total Orders</p>
                        <p class="stat-value" style="font-size:36px; margin-top:6px;">{{ $totalOrders }}</p>
                    </div>
                    <div
                        style="background:rgba(240,82,0,0.08); border:1px solid rgba(240,82,0,0.2); border-radius:var(--radius-sm); padding:8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            fill="none" stroke="#f05200" stroke-width="2">
                            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                            <line x1="3" y1="6" x2="21" y2="6" />
                            <path d="M16 10a4 4 0 0 1-8 0" />
                        </svg>
                    </div>
                </div>
                <p class="stat-change {{ $orderGrowth >= 0 ? 'up' : 'down' }}">
                    {{ $orderGrowth >= 0 ? '↑' : '↓' }} {{ abs($orderGrowth) }}% vs period before
                </p>
            </div>

            {{-- Completed --}}
            <div class="stat-card accent-cyan animate-fade-up stagger-3">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <p class="stat-label">Completed</p>
                        <p class="stat-value" style="font-size:36px; margin-top:6px; color:var(--color-success);">
                            {{ $completedOrders }}</p>
                    </div>
                    <div
                        style="background:var(--color-success-bg); border:1px solid rgba(50,224,16,0.2); border-radius:var(--radius-sm); padding:8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            fill="none" stroke="var(--color-success)" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                    </div>
                </div>
                <div class="flex gap-2 flex-wrap">
                    <span class="badge badge-processing"><span class="badge-dot"></span>{{ $processingOrders }}
                        making</span>
                    <span class="badge badge-pending"><span class="badge-dot"></span>{{ $pendingOrders }}
                        pending</span>
                </div>
            </div>

            {{-- Menu & Tables --}}
            <div class="stat-card accent-pink animate-fade-up stagger-4">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <p class="stat-label">Active Menu</p>
                        <p class="stat-value" style="font-size:36px; margin-top:6px;">{{ $totalMenuItems }}</p>
                    </div>
                    <div
                        style="background:rgba(255,32,112,0.08); border:1px solid rgba(255,32,112,0.2); border-radius:var(--radius-sm); padding:8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            fill="none" stroke="var(--color-spray-pink)" stroke-width="2">
                            <path d="M3 11l19-9-9 19-2-8-8-2z" />
                        </svg>
                    </div>
                </div>
                <p class="font-mono" style="font-size:11px; color:var(--color-ink-400);">{{ $activeTables }} tables
                    registered</p>
            </div>

        </div>

        {{-- ════════════════════════════════════════ --}}
        {{-- MAIN CHARTS ROW --}}
        {{-- ════════════════════════════════════════ --}}
        <div class="grid gap-4 mb-4" style="grid-template-columns: 1fr 340px;">

            {{-- Revenue Bar Chart --}}
            <div class="card" style="padding:20px;">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="font-display"
                            style="font-size:18px; letter-spacing:0.04em; color:var(--color-text-primary);">REVENUE
                            TREND</h3>
                        <p class="font-mono"
                            style="font-size:10px; color:var(--color-ink-400); text-transform:uppercase; letter-spacing:0.1em; margin-top:2px;">
                            @if ($period === 'today')
                                Today breakdown
                            @elseif($period === 'week')
                                This week daily
                            @elseif($period === 'month')
                                {{ \Carbon\Carbon::create($selectedYear, $selectedMonth)->format('F Y') }} daily
                            @else
                                {{ $selectedYear }} monthly
                            @endif
                        </p>
                    </div>
                    <span class="badge badge-lime">
                        <span class="badge-dot" style="background:var(--color-lime-400);"></span>
                        Rp {{ number_format($totalRevenue / 1000, 0, ',', '.') }}K
                    </span>
                </div>
                <div style="height:220px; position:relative;">
                    <canvas id="revenueChart" wire:ignore
                        x-effect="$wire.on('stats-updated', () => buildRevenueChart())"></canvas>
                </div>
            </div>

            {{-- Category Donut --}}
            <div class="card" style="padding:20px;">
                <div class="mb-4">
                    <h3 class="font-display"
                        style="font-size:18px; letter-spacing:0.04em; color:var(--color-text-primary);">BY CATEGORY
                    </h3>
                    <p class="font-mono"
                        style="font-size:10px; color:var(--color-ink-400); text-transform:uppercase; letter-spacing:0.1em; margin-top:2px;">
                        Revenue split</p>
                </div>
                <div style="height:220px; position:relative;">
                    <canvas id="categoryChart" wire:ignore></canvas>
                </div>
                @if (empty($categoryRevenue))
                    <p class="font-mono text-center"
                        style="font-size:11px; color:var(--color-ink-500); margin-top:12px;">No data yet</p>
                @endif
            </div>

        </div>

        {{-- ════════════════════════════════════════ --}}
        {{-- SECOND ROW: HOURLY + PAYMENT --}}
        {{-- ════════════════════════════════════════ --}}
        <div class="grid gap-4 mb-4" style="grid-template-columns: 1fr 1fr;">

            {{-- Hourly Traffic --}}
            <div class="card" style="padding:20px;">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="font-display"
                            style="font-size:18px; letter-spacing:0.04em; color:var(--color-text-primary);">PEAK HOURS
                        </h3>
                        <p class="font-mono"
                            style="font-size:10px; color:var(--color-ink-400); text-transform:uppercase; letter-spacing:0.1em; margin-top:2px;">
                            Order volume by hour</p>
                    </div>
                    <span class="sticker sticker-orange" style="font-size:9px;">07:00 – 22:00</span>
                </div>
                <div style="height:180px; position:relative;">
                    <canvas id="hourlyChart" wire:ignore></canvas>
                </div>
            </div>

            {{-- Payment Methods --}}
            <div class="card" style="padding:20px;">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="font-display"
                            style="font-size:18px; letter-spacing:0.04em; color:var(--color-text-primary);">PAYMENT
                            METHODS</h3>
                        <p class="font-mono"
                            style="font-size:10px; color:var(--color-ink-400); text-transform:uppercase; letter-spacing:0.1em; margin-top:2px;">
                            Revenue by channel</p>
                    </div>
                </div>
                <div style="height:180px; position:relative;">
                    <canvas id="paymentChart" wire:ignore></canvas>
                </div>
                @if (empty($paymentMethodChart))
                    <p class="font-mono text-center"
                        style="font-size:11px; color:var(--color-ink-500); margin-top:12px;">No paid orders yet</p>
                @endif
            </div>

        </div>

        {{-- ════════════════════════════════════════ --}}
        {{-- THIRD ROW: TOP MENUS + RECENT ORDERS --}}
        {{-- ════════════════════════════════════════ --}}
        <div class="grid gap-4 mb-4" style="grid-template-columns: 360px 1fr;">

            {{-- Top Menus --}}
            <div class="card" style="padding:20px;">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-display"
                        style="font-size:18px; letter-spacing:0.04em; color:var(--color-text-primary);">TOP MENU</h3>
                    <span class="badge badge-orange">TOP 5</span>
                </div>

                @if (empty($topMenus))
                    <div style="text-align:center; padding:30px 0;">
                        <p class="font-mono" style="font-size:12px; color:var(--color-ink-500);">No sales data yet</p>
                    </div>
                @else
                    @php $maxSold = collect($topMenus)->max('sold') ?: 1; @endphp
                    <div style="display:flex; flex-direction:column; gap:14px;">
                        @foreach ($topMenus as $i => $menu)
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <div class="flex items-center gap-2">
                                        <span class="font-display"
                                            style="font-size:18px; color:var(--color-ink-600); min-width:20px;">{{ $i + 1 }}</span>
                                        <span
                                            style="font-size:13px; font-weight:500; color:var(--color-ink-100);">{{ $menu['name'] }}</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="font-mono"
                                            style="font-size:11px; color:var(--color-lime-400);">{{ $menu['sold'] }}x</span>
                                    </div>
                                </div>
                                <div
                                    style="height:4px; background:var(--color-ink-700); border-radius:2px; overflow:hidden;">
                                    <div
                                        style="height:100%; width:{{ round(($menu['sold'] / $maxSold) * 100) }}%; background:{{ $i === 0 ? '#bef200' : ($i === 1 ? '#f05200' : 'var(--color-ink-500)') }}; border-radius:2px; transition:width 0.6s ease;">
                                    </div>
                                </div>
                                <p class="font-mono"
                                    style="font-size:10px; color:var(--color-ink-400); margin-top:2px;">
                                    Rp {{ number_format($menu['revenue'], 0, ',', '.') }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Recent Orders --}}
            <div class="card" style="padding:20px;">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-display"
                        style="font-size:18px; letter-spacing:0.04em; color:var(--color-text-primary);">RECENT ORDERS
                    </h3>
                    <a href="{{ route('orders.index') ?? '#' }}" class="btn btn-xs btn-secondary">VIEW ALL</a>
                </div>

                @if (empty($recentOrders))
                    <div style="text-align:center; padding:40px 0;">
                        <p class="font-mono" style="font-size:12px; color:var(--color-ink-500);">No orders yet —
                            waiting for the first scan!</p>
                    </div>
                @else
                    <div class="table-wrapper" style="border-radius:var(--radius-md);">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Table</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Pay</th>
                                    <th>Status</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentOrders as $order)
                                    <tr>
                                        <td><span class="order-tag">#{{ $order['order_code'] }}</span></td>
                                        <td><span class="table-indicator"
                                                style="padding:3px 8px; font-size:10px;">T{{ $order['table'] }}</span>
                                        </td>
                                        <td><span class="font-mono"
                                                style="font-size:12px; color:var(--color-ink-300);">{{ $order['items_count'] }}
                                                item</span></td>
                                        <td><span class="price price-sm" style="font-size:13px;">Rp
                                                {{ number_format($order['total'], 0, ',', '.') }}</span></td>
                                        <td>
                                            <span
                                                class="badge {{ strtolower($order['payment_status']) === 'paid' ? 'badge-paid' : 'badge-unpaid' }}">
                                                {{ $order['payment_status'] }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $statusClass = match ($order['order_status']) {
                                                    'Completed' => 'badge-completed',
                                                    'Processing' => 'badge-processing',
                                                    default => 'badge-pending',
                                                };
                                            @endphp
                                            <span class="badge {{ $statusClass }}">
                                                <span class="badge-dot"></span>
                                                {{ $order['order_status'] }}
                                            </span>
                                        </td>
                                        <td><span class="font-mono"
                                                style="font-size:10px; color:var(--color-ink-400);">{{ $order['created_at'] }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>

        {{-- ════════════════════════════════════════ --}}
        {{-- ORDER STATUS MINI OVERVIEW --}}
        {{-- ════════════════════════════════════════ --}}
        <div class="grid gap-3 mb-4" style="grid-template-columns: repeat(3, 1fr);">
            @php
                $total = max($totalOrders, 1);
                $completedPct = round(($completedOrders / $total) * 100);
                $processingPct = round(($processingOrders / $total) * 100);
                $pendingPct = round(($pendingOrders / $total) * 100);
            @endphp

            @foreach ([['label' => 'COMPLETED', 'count' => $completedOrders, 'pct' => $completedPct, 'color' => 'var(--color-success)', 'bg' => 'var(--color-success-bg)', 'badge' => 'badge-completed'], ['label' => 'IN PROGRESS', 'count' => $processingOrders, 'pct' => $processingPct, 'color' => 'var(--color-info)', 'bg' => 'var(--color-info-bg)', 'badge' => 'badge-processing'], ['label' => 'PENDING', 'count' => $pendingOrders, 'pct' => $pendingPct, 'color' => 'var(--color-warning)', 'bg' => 'var(--color-warning-bg)', 'badge' => 'badge-pending']] as $item)
                <div class="card" style="padding:16px;">
                    <div class="flex items-center justify-between mb-3">
                        <span class="badge {{ $item['badge'] }}"><span
                                class="badge-dot"></span>{{ $item['label'] }}</span>
                        <span class="font-display"
                            style="font-size:28px; color:{{ $item['color'] }};">{{ $item['count'] }}</span>
                    </div>
                    <div style="height:6px; background:var(--color-ink-700); border-radius:3px; overflow:hidden;">
                        <div
                            style="height:100%; width:{{ $item['pct'] }}%; background:{{ $item['color'] }}; border-radius:3px; transition:width 0.8s ease;">
                        </div>
                    </div>
                    <p class="font-mono" style="font-size:10px; color:var(--color-ink-400); margin-top:5px;">
                        {{ $item['pct'] }}% of total orders</p>
                </div>
            @endforeach
        </div>

        {{-- ════════════════════════════════════════ --}}
        {{-- YEARLY ARCHIVE --}}
        {{-- ════════════════════════════════════════ --}}
        @if (count($yearlyStats) > 0)
            <div class="card" style="padding:20px;">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="font-display"
                            style="font-size:18px; letter-spacing:0.04em; color:var(--color-text-primary);">YEARLY
                            ARCHIVE</h3>
                        <p class="font-mono"
                            style="font-size:10px; color:var(--color-ink-400); text-transform:uppercase; letter-spacing:0.1em; margin-top:2px;">
                            All-time revenue history</p>
                    </div>
                    <span class="badge badge-lime">{{ count($yearlyStats) }} year(s)</span>
                </div>

                <div style="overflow-x:auto;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Year</th>
                                <th>Total Revenue</th>
                                <th>Total Orders</th>
                                <th>Avg Order Value</th>
                                <th>Performance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $maxYearRevenue = collect($yearlyStats)->max('revenue') ?: 1; @endphp
                            @foreach ($yearlyStats as $ys)
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <span class="font-display"
                                                style="font-size:20px; {{ $ys['year'] == now()->year ? 'color:var(--color-lime-400)' : 'color:var(--color-ink-400)' }};">{{ $ys['year'] }}</span>
                                            @if ($ys['year'] == now()->year)
                                                <span class="sticker sticker-lime"
                                                    style="font-size:8px; padding:2px 6px;">NOW</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="price price-sm">Rp
                                            {{ number_format($ys['revenue'], 0, ',', '.') }}</span>
                                    </td>
                                    <td>
                                        <span class="font-mono"
                                            style="font-size:13px; color:var(--color-ink-100);">{{ number_format($ys['orders']) }}</span>
                                    </td>
                                    <td>
                                        <span class="font-mono" style="font-size:12px; color:var(--color-ink-300);">
                                            Rp
                                            {{ $ys['orders'] > 0 ? number_format(round($ys['revenue'] / $ys['orders']), 0, ',', '.') : '0' }}
                                        </span>
                                    </td>
                                    <td style="min-width:200px;">
                                        <div
                                            style="height:6px; background:var(--color-ink-700); border-radius:3px; overflow:hidden; width:100%;">
                                            <div
                                                style="height:100%; width:{{ round(($ys['revenue'] / $maxYearRevenue) * 100) }}%; background:{{ $ys['year'] == now()->year ? '#bef200' : 'var(--color-ink-500)' }}; border-radius:3px;">
                                            </div>
                                        </div>
                                        <span class="font-mono"
                                            style="font-size:10px; color:var(--color-ink-500); margin-top:3px; display:block;">{{ round(($ys['revenue'] / $maxYearRevenue) * 100) }}%
                                            of peak</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>{{-- end .page-content --}}

</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush
