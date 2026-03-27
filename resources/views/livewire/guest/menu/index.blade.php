{{-- ═══════════════════════════════════════════
     WARSO COFFEE — Guest Menu Page
     Mobile-first, Street Drop Theme
     ═══════════════════════════════════════════ --}}

<div x-data="{ showDetail: @entangle('showDetail'), showCart: @entangle('showCart') }">

    {{-- ── TOP BAR ── --}}
    <div
        style="position:sticky;top:0;z-index:30;background:var(--color-ink-950);border-bottom:1px solid var(--color-ink-700);padding:12px 16px">
        <div style="display:flex;align-items:center;justify-content:space-between;max-width:480px;margin:0 auto">

            {{-- Brand --}}
            <div>
                <div class="font-display" style="font-size:22px;letter-spacing:0.06em;color:#fff;line-height:1">
                    WARSO<span style="color:var(--color-lime-500)">.</span>
                </div>
                @if ($this->table)
                    <div class="table-indicator" style="margin-top:4px;font-size:10px;padding:2px 8px">
                        <svg style="width:8px;height:8px" fill="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="5" />
                        </svg>
                        MEJA {{ strtoupper($this->table->table_number) }}
                    </div>
                @else
                    <div class="badge badge-unpaid" style="margin-top:4px;font-size:9px">⚠ Meja tidak dikenal</div>
                @endif
            </div>

            {{-- Cart Button --}}
            <button wire:click="$set('showCart', true)"
                style="position:relative;background:var(--color-lime-500);border:none;color:var(--color-ink-950);width:44px;height:44px;border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:var(--shadow-hard-sm);flex-shrink:0">
                <svg style="width:20px;height:20px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                @if ($this->cartCount > 0)
                    <span
                        style="position:absolute;top:-6px;right:-6px;background:var(--color-orange-500);color:#fff;font-family:var(--font-mono);font-size:10px;font-weight:700;min-width:18px;height:18px;border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;padding:0 4px;border:2px solid var(--color-ink-950)">
                        {{ $this->cartCount }}
                    </span>
                @endif
            </button>
        </div>
    </div>

    {{-- ── MEJA NOT FOUND ── --}}
    @if (!$this->table)
        <div style="max-width:480px;margin:60px auto;padding:24px;text-align:center">
            <svg style="width:48px;height:48px;margin:0 auto 16px;color:var(--color-error)" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <h2 class="font-display" style="font-size:28px;color:#fff;margin-bottom:8px">MEJA TIDAK DITEMUKAN</h2>
            <p class="font-mono" style="font-size:12px;color:var(--color-ink-400)">QR Code ini mungkin sudah tidak
                aktif.<br>Minta staf untuk membantu.</p>
        </div>
    @else
        {{-- ── CATEGORY FILTER ── --}}
        <div style="max-width:480px;margin:0 auto;padding:16px 16px 0">
            <div
                style="display:flex;gap:8px;overflow-x:auto;padding-bottom:4px;-ms-overflow-style:none;scrollbar-width:none">
                <button wire:click="$set('activeCategoryId', null)"
                    class="{{ is_null($activeCategoryId) ? 'btn-primary' : 'btn-secondary' }} btn btn-sm"
                    style="white-space:nowrap;flex-shrink:0">
                    Semua
                </button>
                @foreach ($categories as $cat)
                    @if ($cat->menus_count > 0)
                        <button wire:click="$set('activeCategoryId', {{ $cat->id }})"
                            class="{{ $activeCategoryId === $cat->id ? 'btn-primary' : 'btn-secondary' }} btn btn-sm"
                            style="white-space:nowrap;flex-shrink:0">
                            {{ $cat->name }}
                        </button>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- ── MENU GRID ── --}}
        <div style="max-width:480px;margin:0 auto;padding:16px">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                @forelse($menus as $menu)
                    <div class="card-menu animate-fade-up" wire:click="openDetail({{ $menu->id }})"
                        style="cursor:pointer">

                        {{-- Gambar --}}
                        <div style="aspect-ratio:4/3;overflow:hidden;background:var(--color-ink-700);position:relative">
                            @if ($menu->image)
                                <img src="{{ Storage::url($menu->image) }}" alt="{{ $menu->name }}"
                                    style="width:100%;height:100%;object-fit:cover" loading="lazy">
                            @else
                                <div
                                    style="width:100%;height:100%;display:flex;align-items:center;justify-content:center">
                                    <svg style="width:32px;height:32px;color:var(--color-ink-500)" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif

                            {{-- Category badge --}}
                            <span style="position:absolute;top:6px;left:6px" class="badge badge-lime"
                                style="font-size:9px">
                                {{ $menu->category->name }}
                            </span>
                        </div>

                        {{-- Info --}}
                        <div style="padding:10px">
                            <p
                                style="font-weight:600;font-size:13px;color:var(--color-text-primary);margin-bottom:4px;line-height:1.3">
                                {{ $menu->name }}
                            </p>
                            @if ($menu->description)
                                <p
                                    style="font-size:11px;color:var(--color-ink-400);margin-bottom:6px;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">
                                    {{ $menu->description }}
                                </p>
                            @endif
                            <p class="price price-sm">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @empty
                    <div style="grid-column:1/-1;text-align:center;padding:48px 0">
                        <p class="font-mono" style="color:var(--color-ink-400);font-size:12px">Menu tidak tersedia saat
                            ini.</p>
                    </div>
                @endforelse
            </div>
        </div>

    @endif

    {{-- ════════════════════════════════════════
             MODAL DETAIL MENU + KUSTOMISASI
             ════════════════════════════════════════ --}}
    @if ($showDetail && $selectedMenu)
        <div class="modal-backdrop" style="align-items:flex-end;padding:0" x-show="showDetail"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div style="background:var(--color-ink-800);border:1.5px solid var(--color-ink-500);border-radius:var(--radius-xl) var(--radius-xl) 0 0;width:100%;max-width:480px;margin:0 auto;max-height:90vh;overflow-y:auto;padding-bottom:24px"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="transform translate-y-full" x-transition:enter-end="transform translate-y-0"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="transform translate-y-0"
                x-transition:leave-end="transform translate-y-full">

                {{-- Drag handle --}}
                <div style="display:flex;justify-content:center;padding:12px 0 4px">
                    <div style="width:36px;height:4px;border-radius:2px;background:var(--color-ink-500)"></div>
                </div>

                {{-- Gambar --}}
                @if ($selectedMenu->image)
                    <div style="height:200px;overflow:hidden">
                        <img src="{{ Storage::url($selectedMenu->image) }}" alt="{{ $selectedMenu->name }}"
                            style="width:100%;height:100%;object-fit:cover">
                    </div>
                @endif

                <div style="padding:20px">
                    {{-- Nama & harga --}}
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:8px">
                        <h3 class="font-display" style="font-size:26px;color:#fff;line-height:1">
                            {{ strtoupper($selectedMenu->name) }}
                        </h3>
                        <span class="price price-md" style="flex-shrink:0;margin-left:12px">
                            Rp {{ number_format($selectedMenu->price, 0, ',', '.') }}
                        </span>
                    </div>

                    @if ($selectedMenu->description)
                        <p style="font-size:13px;color:var(--color-ink-300);margin-bottom:16px;line-height:1.5">
                            {{ $selectedMenu->description }}
                        </p>
                    @endif

                    <hr class="divider">

                    {{-- Suhu (hanya kalau bukan Snack) --}}
                    @if ($selectedMenu->category->name !== 'Snack')
                        <div style="margin-bottom:16px">
                            <p class="form-label" style="margin-bottom:8px">Suhu</p>
                            <div class="option-group">
                                @foreach (['Hot', 'Ice'] as $temp)
                                    <button wire:click="$set('temperature', '{{ $temp }}')"
                                        class="option-pill {{ $temperature === $temp ? 'selected' : '' }}">
                                        {{ $temp === 'Hot' ? '🔥' : '🧊' }} {{ $temp }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        {{-- Ice Level (hanya kalau Ice) --}}
                        @if ($temperature === 'Ice')
                            <div style="margin-bottom:16px">
                                <p class="form-label" style="margin-bottom:8px">Tingkat Es</p>
                                <div class="option-group">
                                    @foreach (['Normal', 'Less Ice', 'No Ice'] as $level)
                                        <button wire:click="$set('ice_level', '{{ $level }}')"
                                            class="option-pill {{ $ice_level === $level ? 'selected' : '' }}">
                                            {{ $level }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Sugar Level --}}
                        <div style="margin-bottom:16px">
                            <p class="form-label" style="margin-bottom:8px">Tingkat Gula</p>
                            <div class="option-group">
                                @foreach (['Normal', 'Less Sugar', 'No Sugar'] as $level)
                                    <button wire:click="$set('sugar_level', '{{ $level }}')"
                                        class="option-pill {{ $sugar_level === $level ? 'selected' : '' }}">
                                        {{ $level }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Add-ons / Topping --}}
                    @if ($addons->count() > 0)
                        <div style="margin-bottom:16px">
                            <p class="form-label" style="margin-bottom:8px">Topping Tambahan</p>
                            <div style="display:flex;flex-direction:column;gap:8px">
                                @foreach ($addons as $addon)
                                    <button wire:click="toggleAddon({{ $addon->id }})"
                                        style="display:flex;align-items:center;justify-content:space-between;padding:10px 12px;border-radius:var(--radius-sm);border:1.5px solid {{ isset($selectedAddons[$addon->id]) ? 'var(--color-lime-500)' : 'var(--color-ink-600)' }};background:{{ isset($selectedAddons[$addon->id]) ? 'rgba(190,242,0,0.06)' : 'var(--color-ink-900)' }};cursor:pointer;transition:all 0.15s;width:100%">
                                        <div style="display:flex;align-items:center;gap:8px">
                                            <div
                                                style="width:16px;height:16px;border-radius:3px;border:1.5px solid {{ isset($selectedAddons[$addon->id]) ? 'var(--color-lime-500)' : 'var(--color-ink-500)' }};background:{{ isset($selectedAddons[$addon->id]) ? 'var(--color-lime-500)' : 'transparent' }};display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                                @if (isset($selectedAddons[$addon->id]))
                                                    <svg style="width:10px;height:10px;color:var(--color-ink-950)"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="3" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                @endif
                                            </div>
                                            <span
                                                style="font-size:13px;color:{{ isset($selectedAddons[$addon->id]) ? 'var(--color-lime-400)' : 'var(--color-ink-200)' }};font-weight:500">
                                                {{ $addon->name }}
                                            </span>
                                        </div>
                                        <span class="font-mono"
                                            style="font-size:12px;color:{{ isset($selectedAddons[$addon->id]) ? 'var(--color-lime-400)' : 'var(--color-ink-400)' }}">
                                            +Rp {{ number_format($addon->price, 0, ',', '.') }}
                                        </span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Catatan --}}
                    <div style="margin-bottom:20px">
                        <p class="form-label" style="margin-bottom:8px">Catatan (opsional)</p>
                        <textarea wire:model="notes" class="form-input form-textarea"
                            placeholder="Contoh: jangan terlalu manis, extra panas..." rows="2"></textarea>
                    </div>

                    {{-- Quantity + Add to Cart --}}
                    <div style="display:flex;align-items:center;gap:12px">
                        <div class="qty-control">
                            <button wire:click="decrementQty" class="qty-btn">−</button>
                            <span class="qty-value">{{ $quantity }}</span>
                            <button wire:click="incrementQty" class="qty-btn">+</button>
                        </div>

                        <button wire:click="addToCart" class="btn btn-primary btn-lg" style="flex:1">
                            <svg style="width:16px;height:16px" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah — Rp {{ number_format($this->subtotal, 0, ',', '.') }}
                        </button>
                    </div>

                </div>

                {{-- Close --}}
                <button wire:click="closeDetail"
                    style="position:absolute;top:16px;right:16px;background:var(--color-ink-700);border:1px solid var(--color-ink-500);color:var(--color-ink-300);width:32px;height:32px;border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;cursor:pointer">
                    <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    {{-- ════════════════════════════════════════
             CART DRAWER
             ════════════════════════════════════════ --}}
    @if ($showCart)
        <div class="modal-backdrop" style="align-items:flex-end;padding:0" x-show="showCart"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100">

            <div style="background:var(--color-ink-800);border:1.5px solid var(--color-ink-500);border-radius:var(--radius-xl) var(--radius-xl) 0 0;width:100%;max-width:480px;margin:0 auto;max-height:85vh;display:flex;flex-direction:column"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="transform translate-y-full"
                x-transition:enter-end="transform translate-y-0">

                {{-- Drag handle --}}
                <div style="display:flex;justify-content:center;padding:12px 0 4px;flex-shrink:0">
                    <div style="width:36px;height:4px;border-radius:2px;background:var(--color-ink-500)"></div>
                </div>

                {{-- Header --}}
                <div
                    style="display:flex;align-items:center;justify-content:space-between;padding:8px 20px 12px;border-bottom:1px solid var(--color-ink-700);flex-shrink:0">
                    <h3 class="font-display" style="font-size:24px;color:#fff;letter-spacing:0.04em">PESANAN KU</h3>
                    <button wire:click="$set('showCart', false)"
                        style="background:transparent;border:1px solid var(--color-ink-600);color:var(--color-ink-400);width:30px;height:30px;border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;cursor:pointer">
                        <svg style="width:14px;height:14px" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Cart Items --}}
                <div style="flex:1;overflow-y:auto;padding:0 20px">
                    @forelse($cart as $item)
                        <div class="cart-item">
                            {{-- Gambar --}}
                            @if ($item['image'])
                                <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['name'] }}"
                                    class="cart-item-img">
                            @else
                                <div class="cart-item-img"
                                    style="display:flex;align-items:center;justify-content:center">
                                    <svg style="width:20px;height:20px;color:var(--color-ink-500)" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14" />
                                    </svg>
                                </div>
                            @endif

                            {{-- Info --}}
                            <div style="flex:1;min-width:0">
                                <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px">
                                    <p style="font-weight:600;font-size:13px;color:var(--color-text-primary)">
                                        {{ $item['name'] }}</p>
                                    <button wire:click="removeFromCart('{{ $item['key'] }}')"
                                        style="background:transparent;border:none;color:var(--color-error);cursor:pointer;flex-shrink:0;padding:0">
                                        <svg style="width:14px;height:14px" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                {{-- Tags --}}
                                <div style="display:flex;flex-wrap:wrap;gap:4px;margin-top:4px">
                                    @if ($item['temperature'])
                                        <span class="badge badge-lime"
                                            style="font-size:9px">{{ $item['temperature'] }}</span>
                                    @endif
                                    @if ($item['ice_level'] && $item['temperature'] === 'Ice')
                                        <span class="badge"
                                            style="font-size:9px;background:var(--color-info-bg);color:var(--color-info);border-color:rgba(0,217,245,0.3)">{{ $item['ice_level'] }}</span>
                                    @endif
                                    @if ($item['sugar_level'])
                                        <span class="badge"
                                            style="font-size:9px;background:var(--color-warning-bg);color:var(--color-warning);border-color:rgba(245,204,0,0.3)">{{ $item['sugar_level'] }}</span>
                                    @endif
                                    @foreach ($item['addons'] as $addon)
                                        <span class="badge badge-orange"
                                            style="font-size:9px">+{{ $addon['name'] }}</span>
                                    @endforeach
                                </div>

                                @if ($item['notes'])
                                    <p
                                        style="font-size:11px;color:var(--color-ink-400);margin-top:4px;font-style:italic">
                                        "{{ $item['notes'] }}"
                                    </p>
                                @endif

                                <div
                                    style="display:flex;align-items:center;justify-content:space-between;margin-top:6px">
                                    <span class="font-mono"
                                        style="font-size:11px;color:var(--color-ink-400)">×{{ $item['quantity'] }}</span>
                                    <span class="price price-sm">
                                        Rp
                                        {{ number_format(($item['price'] + array_sum(array_column($item['addons'], 'price'))) * $item['quantity'], 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div style="text-align:center;padding:48px 0">
                            <svg style="width:40px;height:40px;margin:0 auto 12px;color:var(--color-ink-600)"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <p class="font-mono" style="font-size:12px;color:var(--color-ink-400)">Keranjang masih
                                kosong.<br>Yuk pesan sesuatu! ☕</p>
                        </div>
                    @endforelse
                </div>

                {{-- Footer Cart --}}
                @if (count($cart) > 0)
                    <div style="padding:16px 20px;border-top:1px solid var(--color-ink-700);flex-shrink:0">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px">
                            <span class="font-mono"
                                style="font-size:12px;color:var(--color-ink-400);text-transform:uppercase;letter-spacing:0.08em">Total</span>
                            <span class="price price-lg">Rp {{ number_format($this->cartTotal, 0, ',', '.') }}</span>
                        </div>
                        <button wire:click="checkout" class="btn btn-cta btn-xl btn-block">
                            <svg style="width:18px;height:18px" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Pesan Sekarang
                        </button>
                    </div>
                @endif

            </div>
        </div>
    @endif

    {{-- ── Floating Cart Button (jika cart tidak kosong & drawer tutup) ── --}}
    @if (count($cart) > 0 && !$showCart && !$showDetail)
        <div
            style="position:fixed;bottom:24px;left:50%;transform:translateX(-50%);z-index:20;width:calc(100% - 32px);max-width:448px">
            <button wire:click="$set('showCart', true)" class="btn btn-cta btn-xl btn-block"
                style="box-shadow:0 8px 24px rgba(240,82,0,0.4)">
                <svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Lihat Pesanan ({{ $this->cartCount }} item)
                <span style="margin-left:auto" class="font-mono">Rp
                    {{ number_format($this->cartTotal, 0, ',', '.') }}</span>
            </button>
        </div>
    @endif

</div>
