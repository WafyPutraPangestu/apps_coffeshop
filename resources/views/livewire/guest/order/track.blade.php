<div wire:poll.5s style="min-height:100vh;background:var(--color-ink-900)">
    <div
        style="position:sticky;top:0;z-index:30;background:var(--color-ink-950);border-bottom:1px solid var(--color-ink-700);padding:12px 16px">
        <div style="display:flex;align-items:center;justify-content:space-between;max-width:480px;margin:0 auto">
            <div class="font-display" style="font-size:22px;letter-spacing:0.06em;color:#fff;line-height:1">
                WARSO<span style="color:var(--color-lime-500)">.</span>
            </div>
            <div class="table-indicator">
                <svg style="width:8px;height:8px" fill="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="5" />
                </svg>
                MEJA {{ strtoupper($order->table->table_number) }}
            </div>
        </div>
    </div>

    <div style="max-width:480px;margin:0 auto;padding:20px 16px">

        {{-- ── ORDER CODE ── --}}
        <div style="text-align:center;margin-bottom:24px">
            <p class="font-mono"
                style="font-size:10px;color:var(--color-ink-400);letter-spacing:0.12em;text-transform:uppercase;margin-bottom:4px">
                Kode Pesanan</p>
            <h1 class="font-display" style="font-size:32px;color:var(--color-lime-400);letter-spacing:0.08em">
                {{ $order->order_code }}
            </h1>
        </div>

        {{-- ── STATUS STEPS ── --}}
        @php
            $paymentStatus = $order->payment_status;
            $orderStatus = $order->order_status;

            $isPaid = in_array($paymentStatus, ['Paid']);
            $isFailed = in_array($paymentStatus, ['Failed', 'Expired']);
            $isProcessing = $orderStatus === 'Processing';
            $isCompleted = $orderStatus === 'Completed';

            $currentStep = 1;
            if ($isPaid && !$isProcessing && !$isCompleted) {
                $currentStep = 2;
            }
            if ($isProcessing) {
                $currentStep = 3;
            }
            if ($isCompleted) {
                $currentStep = 4;
            }
            if ($isFailed) {
                $currentStep = 0;
            } // error state
        @endphp

        {{-- Status Card Utama --}}
        <div style="margin-bottom:24px">
            @if ($isFailed)
                <div class="card-hot" style="text-align:center;padding:24px">
                    <div style="font-size:40px;margin-bottom:8px">❌</div>
                    <h2 class="font-display" style="font-size:26px;color:var(--color-error);margin-bottom:6px">
                        PEMBAYARAN GAGAL
                    </h2>
                    <p class="font-mono" style="font-size:12px;color:var(--color-ink-400)">
                        {{ $paymentStatus === 'Expired' ? 'Waktu pembayaran habis.' : 'Transaksi gagal diproses.' }}<br>
                        Silakan pesan ulang atau hubungi staf.
                    </p>
                </div>
            @elseif($isCompleted)
                <div class="card-lime" style="text-align:center;padding:24px">
                    <div style="font-size:40px;margin-bottom:8px">✅</div>
                    <h2 class="font-display" style="font-size:26px;color:var(--color-success);margin-bottom:6px">
                        PESANAN SELESAI!
                    </h2>
                    <p class="font-mono" style="font-size:12px;color:var(--color-ink-400)">
                        Pesananmu sudah diantar ke meja.<br>Selamat menikmati! ☕
                    </p>
                </div>
            @elseif($isProcessing)
                <div class="card" style="border-color:var(--color-info);text-align:center;padding:24px">
                    <div style="font-size:40px;margin-bottom:8px">☕</div>
                    <h2 class="font-display" style="font-size:26px;color:var(--color-info);margin-bottom:6px">
                        SEDANG DIRAMU
                    </h2>
                    <p class="font-mono" style="font-size:12px;color:var(--color-ink-400)">
                        Barista sedang menyiapkan pesananmu.<br>Harap tunggu sebentar ya!
                    </p>
                    {{-- Animasi loading --}}
                    <div style="display:flex;justify-content:center;gap:6px;margin-top:14px">
                        @foreach ([0, 1, 2] as $i)
                            <div
                                style="width:8px;height:8px;border-radius:50%;background:var(--color-info);animation:blink-dot 1.2s ease-in-out {{ $i * 0.2 }}s infinite">
                            </div>
                        @endforeach
                    </div>
                </div>
            @elseif($isPaid)
                <div class="card" style="border-color:var(--color-warning);text-align:center;padding:24px">
                    <div style="font-size:40px;margin-bottom:8px">🧾</div>
                    <h2 class="font-display" style="font-size:26px;color:var(--color-warning);margin-bottom:6px">
                        PESANAN DITERIMA
                    </h2>
                    <p class="font-mono" style="font-size:12px;color:var(--color-ink-400)">
                        Pembayaran berhasil!<br>Pesananmu sedang menunggu diproses barista.
                    </p>
                </div>
            @else
                <div class="card" style="border-color:var(--color-ink-500);text-align:center;padding:24px">
                    <div style="font-size:40px;margin-bottom:8px">💳</div>
                    <h2 class="font-display" style="font-size:26px;color:var(--color-text-primary);margin-bottom:6px">
                        MENUNGGU PEMBAYARAN
                    </h2>
                    <p class="font-mono" style="font-size:12px;color:var(--color-ink-400)">
                        Selesaikan pembayaran untuk memproses pesananmu.
                    </p>
                </div>
            @endif
        </div>

        {{-- ── PROGRESS STEPS ── --}}
        @if (!$isFailed)
            <div style="margin-bottom:24px">
                @php
                    $steps = [
                        [
                            'icon' => '💳',
                            'label' => 'Pembayaran',
                            'done' => $currentStep >= 2,
                            'active' => $currentStep === 1,
                        ],
                        [
                            'icon' => '🧾',
                            'label' => 'Diterima',
                            'done' => $currentStep >= 3,
                            'active' => $currentStep === 2,
                        ],
                        [
                            'icon' => '☕',
                            'label' => 'Sedang Dibuat',
                            'done' => $currentStep >= 4,
                            'active' => $currentStep === 3,
                        ],
                        [
                            'icon' => '✅',
                            'label' => 'Selesai',
                            'done' => $currentStep >= 4,
                            'active' => $currentStep === 4,
                        ],
                    ];
                @endphp

                <div style="display:flex;align-items:flex-start;justify-content:space-between;position:relative">
                    {{-- Garis connector --}}
                    <div
                        style="position:absolute;top:20px;left:20px;right:20px;height:2px;background:var(--color-ink-700);z-index:0">
                    </div>
                    <div
                        style="position:absolute;top:20px;left:20px;height:2px;background:var(--color-lime-500);z-index:1;transition:width 0.5s ease;width:{{ (($currentStep - 1) / 3) * 100 }}%">
                    </div>

                    @foreach ($steps as $i => $step)
                        <div style="display:flex;flex-direction:column;align-items:center;gap:6px;z-index:2;flex:1">
                            <div
                                style="width:40px;height:40px;border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;font-size:18px;border:2px solid {{ $step['done'] || $step['active'] ? 'var(--color-lime-500)' : 'var(--color-ink-600)' }};background:{{ $step['done'] ? 'var(--color-lime-500)' : ($step['active'] ? 'rgba(190,242,0,0.1)' : 'var(--color-ink-800)') }};transition:all 0.3s">
                                @if ($step['done'] && !$step['active'])
                                    <svg style="width:18px;height:18px;color:var(--color-ink-950)" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    {{ $step['icon'] }}
                                @endif
                            </div>
                            <span class="font-mono"
                                style="font-size:9px;text-align:center;letter-spacing:0.06em;text-transform:uppercase;color:{{ $step['done'] || $step['active'] ? 'var(--color-lime-400)' : 'var(--color-ink-500)' }};line-height:1.3">
                                {{ $step['label'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ── DETAIL PESANAN ── --}}
        <div class="card" style="margin-bottom:16px">
            <div class="sidebar-section"
                style="padding:0 0 10px;margin-bottom:12px;border-bottom:1px solid var(--color-ink-700)">
                // DETAIL PESANAN
            </div>

            @foreach ($order->items as $item)
                <div
                    style="display:flex;justify-content:space-between;align-items:flex-start;padding:10px 0;border-bottom:1px solid var(--color-ink-700)">
                    <div style="flex:1;min-width:0">
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px">
                            <span style="font-weight:600;font-size:13px;color:var(--color-text-primary)">
                                {{ $item->menu->name }}
                            </span>
                            <span class="font-mono"
                                style="font-size:11px;color:var(--color-ink-400)">×{{ $item->quantity }}</span>
                        </div>

                        {{-- Tags kustomisasi --}}
                        <div style="display:flex;flex-wrap:wrap;gap:4px">
                            @if ($item->temperature)
                                <span class="badge badge-lime" style="font-size:9px">{{ $item->temperature }}</span>
                            @endif
                            @if ($item->ice_level && $item->temperature === 'Ice')
                                <span class="badge"
                                    style="font-size:9px;background:var(--color-info-bg);color:var(--color-info);border-color:rgba(0,217,245,0.3)">{{ $item->ice_level }}</span>
                            @endif
                            @if ($item->sugar_level)
                                <span class="badge"
                                    style="font-size:9px;background:var(--color-warning-bg);color:var(--color-warning);border-color:rgba(245,204,0,0.3)">{{ $item->sugar_level }}</span>
                            @endif
                            @foreach ($item->addons as $addon)
                                <span class="badge badge-orange"
                                    style="font-size:9px">+{{ $addon->addOn->name }}</span>
                            @endforeach
                        </div>

                        @if ($item->notes)
                            <p style="font-size:11px;color:var(--color-ink-400);margin-top:4px;font-style:italic">
                                "{{ $item->notes }}"</p>
                        @endif
                    </div>

                    <span class="price price-sm" style="flex-shrink:0;margin-left:12px">
                        Rp {{ number_format($item->price, 0, ',', '.') }}
                    </span>
                </div>
            @endforeach

            {{-- Total --}}
            <div style="display:flex;justify-content:space-between;align-items:center;padding-top:12px">
                <span class="font-mono"
                    style="font-size:11px;color:var(--color-ink-400);text-transform:uppercase;letter-spacing:0.08em">Total</span>
                <span class="price price-md">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- ── TOMBOL BAYAR ── --}}
        @if ($order->payment_status === 'Unpaid' && $order->snap_token)
            <div style="margin-bottom:20px">
                <button onclick="bayar()" class="btn btn-cta btn-block btn-xl">
                    💳 BAYAR SEKARANG
                </button>
                <p class="font-mono"
                    style="font-size:10px;color:var(--color-ink-500);text-align:center;margin-top:8px">
                    Pembayaran aman via Midtrans
                </p>
            </div>

            <script>
                function bayar() {
                    snap.pay('{{ $order->snap_token }}', {
                        onSuccess: function(result) {
                            // Reload halaman setelah bayar sukses
                            window.location.reload();
                        },
                        onPending: function(result) {
                            window.location.reload();
                        },
                        onError: function(result) {
                            alert('Pembayaran gagal, silakan coba lagi.');
                        },
                        onClose: function() {
                            // User tutup popup tanpa bayar, tidak perlu apa-apa
                        }
                    });
                }
            </script>
        @endif
        {{-- ── PAYMENT STATUS BADGE ── --}}
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px">
            <span class="font-mono" style="font-size:11px;color:var(--color-ink-400)">Status Pembayaran</span>
            <span
                class="badge {{ match ($paymentStatus) {
                    'Paid' => 'badge-paid',
                    'Failed' => 'badge-unpaid',
                    'Expired' => 'badge-unpaid',
                    default => 'badge-pending',
                } }}">
                <span class="badge-dot"></span>
                {{ $paymentStatus }}
            </span>
        </div>

        {{-- ── INFO POLLING ── --}}
        <div style="text-align:center">
            <p class="font-mono" style="font-size:10px;color:var(--color-ink-600);letter-spacing:0.06em">
                ↻ Status diperbarui otomatis setiap 5 detik
            </p>
        </div>

    </div>
</div>
