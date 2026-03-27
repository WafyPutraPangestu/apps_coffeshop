<div class="page-content animate-fade-up">

    {{-- Flash --}}
    @if (session('success'))
        <div class="toast-container" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3500)"
            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            <div class="toast toast-success">
                <svg class="w-4 h-4 flex-shrink-0" style="color:var(--color-success)" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span style="color:var(--color-text-primary)">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    {{-- Header --}}
    <div class="page-header flex items-start gap-4">
        <a href="{{ route('meja.index') }}" wire:navigate class="btn btn-ghost btn-icon mt-1" title="Kembali">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h1 class="page-title">EDIT <span class="accent">MEJA</span></h1>
            <p class="page-subtitle">// Perbarui data meja {{ $tableModel->table_number }}</p>
        </div>
    </div>

    <div class="grid gap-6" style="grid-template-columns: 1fr 340px; align-items: start;" x-data="mejaEdit('{{ $table_number }}', '{{ $tableModel->qr_code_link }}')">

        {{-- Form Card --}}
        <div class="card-hard">
            <div class="flex items-center gap-2 mb-5">
                <span class="sticker sticker-orange">EDIT MODE</span>
                <span class="font-mono text-xs ml-auto" style="color:var(--color-ink-400)">
                    ID #{{ $tableModel->id }}
                </span>
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
                </div>

                {{-- QR Link Preview --}}
                <div class="form-group">
                    <label class="form-label">Preview Link QR Code Baru</label>
                    <div class="form-input"
                        style="background:var(--color-ink-800);cursor:default;min-height:44px;display:flex;align-items:center">
                        <span class="font-mono text-xs" style="color:var(--color-lime-500)"
                            x-text="tableNumber ? '{{ url('/order/') }}/' + slugify(tableNumber) : '—'">
                        </span>
                    </div>
                    <span class="form-hint">Link QR akan diperbarui saat kamu simpan.</span>
                </div>

                {{-- Submit --}}
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn btn-primary btn-lg flex-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('meja.index') }}" wire:navigate class="btn btn-secondary">Batal</a>
                </div>

            </form>
        </div>

        {{-- QR Code Panel --}}
        <div class="flex flex-col gap-4 sticky top-6">

            {{-- Current QR --}}
            <div class="card-lime">
                <div class="sidebar-section px-0 pt-0 mb-3">// QR SAAT INI</div>

                <div class="flex flex-col items-center gap-3">
                    @if ($tableModel->qr_code_link)
                        <div
                            style="background:#ffffff;padding:10px;border-radius:var(--radius-md);border:2px solid var(--color-lime-500);box-shadow:var(--shadow-lime)">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data={{ urlencode($tableModel->qr_code_link) }}"
                                alt="QR Meja {{ $tableModel->table_number }}" width="160" height="160">
                        </div>
                        <div class="text-center">
                            <p class="font-display text-xl" style="color:var(--color-lime-400)">
                                MEJA {{ strtoupper($tableModel->table_number) }}
                            </p>
                            <p class="font-mono text-xs mt-1 break-all" style="color:var(--color-ink-400)">
                                {{ $tableModel->qr_code_link }}
                            </p>
                        </div>
                        {{-- Download --}}
                        <a href="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode($tableModel->qr_code_link) }}"
                            download="qr-meja-{{ Str::slug($tableModel->table_number) }}.png" target="_blank"
                            class="btn btn-mono btn-sm w-full">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Download QR (300×300)
                        </a>
                    @else
                        <p class="font-mono text-xs text-center py-4" style="color:var(--color-ink-400)">
                            Belum ada QR Code.
                        </p>
                    @endif
                </div>
            </div>

            {{-- Regenerate QR --}}
            <div class="card" style="border-color:var(--color-ink-500)">
                <p class="font-mono text-xs mb-3" style="color:var(--color-ink-300)">
                    Jika QR Code lama sudah tersebar dan ingin diganti, klik tombol di bawah.
                </p>
                <button wire:click="regenerateQr"
                    wire:confirm="Yakin mau regenerate QR Code? Link lama akan tidak valid."
                    class="btn btn-secondary btn-sm w-full">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Regenerate QR Code
                </button>
            </div>

        </div>
    </div>
</div>

<script>
    function mejaEdit(initialName, currentQr) {
        return {
            tableNumber: initialName,
            currentQr: currentQr,
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
