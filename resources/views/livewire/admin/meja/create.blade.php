<div class="page-content animate-fade-up">

    {{-- Header --}}
    <div class="page-header flex items-start gap-4">
        <a href="{{ route('meja.index') }}" wire:navigate class="btn btn-ghost btn-icon mt-1" title="Kembali">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h1 class="page-title">TAMBAH<br><span class="accent">MEJA</span></h1>
            <p class="page-subtitle">// Daftarkan meja baru & generate QR Code</p>
        </div>
    </div>

    <div class="grid gap-6" style="grid-template-columns: 1fr 340px; align-items: start;" x-data="mejaCreate()">

        {{-- Form Card --}}
        <div class="card-hard">
            <div class="flex items-center gap-2 mb-5">
                <span class="sticker sticker-lime">NEW TABLE</span>
            </div>

            <form wire:submit="save" class="flex flex-col gap-5">

                {{-- Nomor Meja --}}
                <div class="form-group">
                    <label class="form-label">
                        Nomor / Nama Meja <span class="required">*</span>
                    </label>
                    <input type="text" wire:model.live="table_number" x-model="tableNumber"
                        class="form-input @error('table_number') error @enderror" placeholder="Contoh: 01, A-02, VIP-1"
                        autocomplete="off" autofocus>
                    @error('table_number')
                        <span class="form-error">⚠ {{ $message }}</span>
                    @enderror
                    <span class="form-hint">
                        Nomor ini akan jadi identitas meja di setiap pesanan.
                    </span>
                </div>

                {{-- QR Link Preview --}}
                <div class="form-group">
                    <label class="form-label">Preview Link QR Code</label>
                    <div class="form-input"
                        style="background:var(--color-ink-800);cursor:default;min-height:44px;display:flex;align-items:center">
                        <span class="font-mono text-xs" style="color:var(--color-lime-500)"
                            x-text="tableNumber ? '{{ url('/order/') }}/' + slugify(tableNumber) : '— Isi nomor meja dulu —'">
                        </span>
                    </div>
                    <span class="form-hint">URL ini yang akan di-encode ke dalam QR Code.</span>
                </div>

                {{-- Submit --}}
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn btn-primary btn-lg flex-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan & Generate QR
                    </button>
                    <a href="{{ route('meja.index') }}" wire:navigate class="btn btn-secondary">Batal</a>
                </div>

            </form>
        </div>

        {{-- QR Preview Card --}}
        <div class="card-lime sticky top-6">
            <div class="sidebar-section px-0 pt-0 mb-3">// QR PREVIEW</div>

            <div class="flex flex-col items-center gap-4" x-show="tableNumber">
                <div
                    style="background:#ffffff;padding:12px;border-radius:var(--radius-md);border:2px solid var(--color-lime-500);box-shadow:var(--shadow-lime)">
                    <img :src="`https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=${encodeURIComponent('{{ url('/order/') }}/' + slugify(tableNumber))}`"
                        alt="QR Preview" width="180" height="180" x-show="tableNumber">
                </div>

                <div class="text-center">
                    <p class="font-display text-2xl" style="color:var(--color-lime-400)"
                        x-text="'MEJA ' + tableNumber.toUpperCase()"></p>
                    <p class="font-mono text-xs mt-1" style="color:var(--color-ink-400)">Scan untuk memesan</p>
                </div>

                <div class="table-indicator w-full justify-center">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="5" />
                    </svg>
                    Preview Realtime
                </div>
            </div>

            <div x-show="!tableNumber" class="flex flex-col items-center gap-3 py-8">
                <svg class="w-12 h-12" style="color:var(--color-ink-600)" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                </svg>
                <p class="font-mono text-xs text-center" style="color:var(--color-ink-500)">
                    QR akan muncul<br>saat nomor meja diisi
                </p>
            </div>
        </div>

    </div>
</div>

<script>
    function mejaCreate() {
        return {
            tableNumber: '',
            slugify(text) {
                return text.toString().toLowerCase()
                    .replace(/\s+/g, '-')
                    .replace(/[^\w\-]+/g, '')
                    .replace(/\-\-+/g, '-')
                    .replace(/^-+/, '')
                    .replace(/-+$/, '');
            }
        }
    }
</script>
