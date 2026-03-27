<div wire:poll.4s="refreshOrders" x-data="{
    newOrderFlash: false,
    soundEnabled: localStorage.getItem('wc_sound') !== 'false',

    playBeep() {
        if (!this.soundEnabled) return;
        try {
            const ctx = new(window.AudioContext || window.webkitAudioContext)();
            [880, 660, 990].forEach((freq, i) => {
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.type = 'sine';
                osc.frequency.value = freq;
                gain.gain.setValueAtTime(0.25, ctx.currentTime + i * 0.12);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + i * 0.12 + 0.25);
                osc.start(ctx.currentTime + i * 0.12);
                osc.stop(ctx.currentTime + i * 0.12 + 0.3);
            });
        } catch (e) {}
    }
}"
    @new-order-arrived.window="
        newOrderFlash = true;
        playBeep();
        setTimeout(() => newOrderFlash = false, 3000);
    ">
    <div class="page-content" style="padding-bottom:40px;">

        {{-- ══════════════════════════════════ --}}
        {{-- PAGE HEADER --}}
        {{-- ══════════════════════════════════ --}}
        <div class="flex flex-col gap-4 mb-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="page-header mb-0">
                <div class="flex items-center gap-3 mb-1">
                    {{-- LIVE indicator --}}
                    <span class="badge badge-processing">
                        <span class="badge-dot"></span>
                        LIVE
                    </span>
                    <span class="font-mono text-xs"
                        style="color:var(--color-ink-400); letter-spacing:0.12em;">AUTO-REFRESH 4S</span>
                </div>
                <h1 class="page-title">LIVE <span class="accent">ORDERS</span></h1>
                <p class="page-subtitle">Pesanan yang sudah dibayar — siap diracik</p>
            </div>

            {{-- Filter tabs --}}
            <div class="flex gap-1"
                style="background:var(--color-ink-800); border:1px solid var(--color-ink-600); border-radius:var(--radius-md); padding:3px;">
                @foreach ([
        'all' => ['label' => 'ALL', 'count' => $counts['all']],
        'pending' => ['label' => 'PENDING', 'count' => $counts['pending']],
        'processing' => ['label' => 'MAKING', 'count' => $counts['processing']],
        'completed' => ['label' => 'DONE', 'count' => $counts['completed']],
    ] as $key => $tab)
                    <button wire:click="setFilter('{{ $key }}')"
                        class="btn btn-xs font-mono {{ $filterStatus === $key ? 'btn-primary' : 'btn-ghost' }}"
                        style="{{ $filterStatus !== $key ? 'border:none;' : '' }}">
                        {{ $tab['label'] }}
                        @if ($tab['count'] > 0)
                            <span
                                style="
                                background:{{ $filterStatus === $key ? 'rgba(0,0,0,0.25)' : 'var(--color-ink-700)' }};
                                padding:0 5px; border-radius:3px; font-size:10px; margin-left:2px;
                            ">{{ $tab['count'] }}</span>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>

        {{-- ══════════════════════════════════ --}}
        {{-- NEW ORDER TOAST FLASH --}}
        {{-- ══════════════════════════════════ --}}
        <div x-show="newOrderFlash" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-end="opacity-0" x-cloak
            style="
                position:fixed; top:20px; right:20px; z-index:200;
                background:var(--color-ink-800);
                border:1.5px solid var(--color-orange-500);
                border-radius:var(--radius-lg);
                padding:14px 18px;
                display:flex; align-items:center; gap:10px;
                box-shadow: 4px 4px 0px var(--color-orange-600);
                min-width:260px;
            ">
            <div
                style="
                width:36px; height:36px;
                background:rgba(240,82,0,0.15);
                border-radius:var(--radius-sm);
                display:flex; align-items:center; justify-content:center;
                flex-shrink:0;
                animation: order-pulse 1s ease-in-out infinite;
            ">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="var(--color-orange-400)" stroke-width="2">
                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                    <line x1="3" y1="6" x2="21" y2="6" />
                    <path d="M16 10a4 4 0 0 1-8 0" />
                </svg>
            </div>
            <div>
                <p class="font-display" style="font-size:15px; letter-spacing:0.05em; color:#fff;">PESANAN BARU!</p>
                <p class="font-mono" style="font-size:11px; color:var(--color-ink-400);">Segera diproses ☕</p>
            </div>
        </div>

        {{-- ══════════════════════════════════ --}}
        {{-- EMPTY STATE --}}
        {{-- ══════════════════════════════════ --}}
        @if ($orders->isEmpty())
            <div
                style="
                text-align:center;
                padding:80px 20px;
                background:var(--color-ink-800);
                border:1.5px dashed var(--color-ink-600);
                border-radius:var(--radius-lg);
            ">
                <div style="font-size:48px; margin-bottom:12px;">☕</div>
                <p class="font-display" style="font-size:22px; letter-spacing:0.05em; color:var(--color-ink-300);">
                    @if ($filterStatus === 'all')
                        BELUM ADA PESANAN
                    @elseif($filterStatus === 'pending')
                        TIDAK ADA YANG PENDING
                    @elseif($filterStatus === 'processing')
                        TIDAK ADA YANG SEDANG DIBUAT
                    @else
                        TIDAK ADA YANG SELESAI
                    @endif
                </p>
                <p class="font-mono"
                    style="font-size:11px; color:var(--color-ink-500); margin-top:8px; text-transform:uppercase; letter-spacing:0.1em;">
                    Halaman refresh otomatis tiap 4 detik
                </p>
            </div>
        @else
            {{-- ══════════════════════════════════ --}}
            {{-- ORDER CARDS GRID --}}
            {{-- ══════════════════════════════════ --}}
            <div class="grid-orders">
                @foreach ($orders as $order)
                    @php
                        $cardClass = match ($order->order_status) {
                            'Pending' => 'new',
                            'Processing' => 'making',
                            'Completed' => 'done',
                            default => '',
                        };
                    @endphp

                    <div class="order-card {{ $cardClass }} animate-fade-up" wire:key="order-{{ $order->id }}">
                        {{-- Card Header --}}
                        <div
                            style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:12px;">
                            <div>
                                <div style="display:flex; align-items:center; gap:6px; margin-bottom:4px;">
                                    <span class="order-tag">#{{ $order->order_code }}</span>
                                    <span class="table-indicator" style="font-size:10px; padding:2px 8px;">
                                        Meja {{ $order->table?->table_number ?? '?' }}
                                    </span>
                                </div>
                                <p class="font-mono" style="font-size:10px; color:var(--color-ink-400);">
                                    {{ $order->created_at->format('H:i') }} —
                                    {{ $order->created_at->diffForHumans() }}
                                </p>
                            </div>

                            {{-- Status badge --}}
                            @php
                                $badgeClass = match ($order->order_status) {
                                    'Pending' => 'badge-pending',
                                    'Processing' => 'badge-processing',
                                    'Completed' => 'badge-completed',
                                    default => '',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">
                                <span class="badge-dot"></span>
                                {{ $order->order_status }}
                            </span>
                        </div>

                        {{-- Order Items --}}
                        <div
                            style="
                            background:var(--color-ink-900);
                            border:1px solid var(--color-ink-700);
                            border-radius:var(--radius-md);
                            padding:10px;
                            margin-bottom:12px;
                        ">
                            @foreach ($order->items as $item)
                                <div
                                    style="
                                    padding:8px 0;
                                    border-bottom:1px solid var(--color-ink-800);
                                    {{ $loop->last ? 'border-bottom:none;' : '' }}
                                ">
                                    <div
                                        style="display:flex; align-items:center; justify-content:space-between; gap:8px;">
                                        <div style="display:flex; align-items:center; gap:8px; flex:1; min-width:0;">
                                            <span
                                                style="
                                                background:var(--color-lime-500);
                                                color:var(--color-ink-950);
                                                font-family:var(--font-mono);
                                                font-size:10px;
                                                font-weight:700;
                                                width:20px; height:20px;
                                                display:flex; align-items:center; justify-content:center;
                                                border-radius:var(--radius-sm);
                                                flex-shrink:0;
                                            ">{{ $item->quantity }}x</span>
                                            <span
                                                style="font-size:13px; font-weight:500; color:var(--color-ink-100); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                                {{ $item->menu?->name ?? 'Item' }}
                                            </span>
                                        </div>
                                        <span class="price price-sm" style="font-size:12px; flex-shrink:0;">
                                            Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                        </span>
                                    </div>

                                    {{-- Variant tags --}}
                                    <div
                                        style="display:flex; flex-wrap:wrap; gap:4px; margin-top:5px; padding-left:28px;">
                                        @if ($item->temperature)
                                            <span class="badge badge-lime"
                                                style="font-size:9px; padding:2px 6px;">{{ $item->temperature }}</span>
                                        @endif
                                        @if ($item->ice_level && $item->ice_level !== 'Normal')
                                            <span class="badge"
                                                style="font-size:9px; padding:2px 6px; background:rgba(0,217,245,0.08); color:var(--color-spray-cyan); border:1px solid rgba(0,217,245,0.25);">{{ $item->ice_level }}</span>
                                        @endif
                                        @if ($item->sugar_level && $item->sugar_level !== 'Normal')
                                            <span class="badge"
                                                style="font-size:9px; padding:2px 6px; background:rgba(245,204,0,0.08); color:var(--color-spray-yellow); border:1px solid rgba(245,204,0,0.25);">{{ $item->sugar_level }}</span>
                                        @endif
                                        @foreach ($item->addons as $addon)
                                            <span class="badge badge-orange"
                                                style="font-size:9px; padding:2px 6px;">+{{ $addon->addOn?->name }}</span>
                                        @endforeach
                                        @if ($item->notes)
                                            <span class="badge"
                                                style="font-size:9px; padding:2px 6px; background:rgba(255,255,255,0.05); color:var(--color-ink-300); border:1px solid var(--color-ink-600);">📝
                                                {{ $item->notes }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Total --}}
                        <div
                            style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px;">
                            <div>
                                <p class="font-mono"
                                    style="font-size:10px; color:var(--color-ink-400); text-transform:uppercase; letter-spacing:0.08em;">
                                    Total</p>
                                <p class="price price-md">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                            </div>
                            <div style="text-align:right;">
                                <p class="font-mono"
                                    style="font-size:10px; color:var(--color-ink-400); text-transform:uppercase; letter-spacing:0.08em;">
                                    Pembayaran</p>
                                <span class="badge badge-paid" style="margin-top:3px;">
                                    <span class="badge-dot"></span>
                                    {{ $order->payment_method ?? 'Paid' }}
                                </span>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div style="display:flex; gap:6px;">
                            @if ($order->order_status === 'Pending')
                                <button wire:click="updateStatus({{ $order->id }}, 'Processing')"
                                    wire:loading.attr="disabled"
                                    wire:target="updateStatus({{ $order->id }}, 'Processing')"
                                    class="btn btn-cta btn-sm" style="flex:1;">
                                    <span wire:loading.remove
                                        wire:target="updateStatus({{ $order->id }}, 'Processing')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2.5">
                                            <polygon points="5 3 19 12 5 21 5 3" />
                                        </svg>
                                        MULAI BUAT
                                    </span>
                                    <span wire:loading wire:target="updateStatus({{ $order->id }}, 'Processing')">
                                        <div class="spinner spinner-orange"
                                            style="width:14px;height:14px;border-width:2px;"></div>
                                    </span>
                                </button>
                            @elseif($order->order_status === 'Processing')
                                <button wire:click="updateStatus({{ $order->id }}, 'Pending')"
                                    class="btn btn-secondary btn-sm" style="flex:0 0 auto;">↩</button>
                                <button wire:click="updateStatus({{ $order->id }}, 'Completed')"
                                    wire:loading.attr="disabled"
                                    wire:target="updateStatus({{ $order->id }}, 'Completed')"
                                    class="btn btn-primary btn-sm" style="flex:1;">
                                    <span wire:loading.remove
                                        wire:target="updateStatus({{ $order->id }}, 'Completed')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2.5">
                                            <polyline points="20 6 9 17 4 12" />
                                        </svg>
                                        SELESAI / ANTAR
                                    </span>
                                    <span wire:loading wire:target="updateStatus({{ $order->id }}, 'Completed')">
                                        <div class="spinner" style="width:14px;height:14px;border-width:2px;"></div>
                                    </span>
                                </button>
                            @else
                                <div
                                    style="flex:1; display:flex; align-items:center; justify-content:center; padding:8px; background:var(--color-success-bg); border:1px solid rgba(50,224,16,0.2); border-radius:var(--radius-md);">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                        viewBox="0 0 24 24" fill="none" stroke="var(--color-success)"
                                        stroke-width="2.5">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                    <span class="font-mono"
                                        style="font-size:11px; color:var(--color-success); margin-left:6px; text-transform:uppercase; letter-spacing:0.08em;">Sudah
                                        Diantar</span>
                                </div>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>
        @endif

    </div>{{-- end .page-content --}}
</div>
