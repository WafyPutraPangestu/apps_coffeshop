<div class="page-content animate-fade-up">

    {{-- Flash Message --}}
    @if (session('success'))
        <div class="toast-container" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3500)"
            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-2">
            <div class="toast toast-success">
                <svg class="w-4 h-4 flex-shrink-0" style="color:var(--color-success)" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span style="color:var(--color-text-primary)">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="page-header flex items-start justify-between gap-4 flex-wrap">
        <div>
            <h1 class="page-title">MANA<span class="accent">JE</span>MEN<br>MEJA</h1>
            <p class="page-subtitle">// Kelola meja & QR Code pemesanan</p>
        </div>
        <a href="{{ route('meja.create') }}" wire:navigate class="btn btn-primary btn-lg mt-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Meja
        </a>
    </div>

    {{-- Search & Stats Row --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 mb-5">
        <div class="relative flex-1 w-full">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4" style="color:var(--color-ink-400)"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0" />
            </svg>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nomor meja..."
                class="form-input pl-9" style="max-width:320px">
        </div>
        <div class="table-indicator">
            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="5" />
            </svg>
            {{ $tables->total() }} Meja Terdaftar
        </div>
    </div>

    {{-- Table --}}
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>No. Meja</th>
                    <th>QR Code</th>
                    <th>Link Pemesanan</th>
                    <th>Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tables as $table)
                    <tr class="stagger-{{ ($loop->index % 6) + 1 }} animate-fade-up">
                        <td>
                            <span class="font-mono text-xs" style="color:var(--color-ink-400)">
                                {{ $tables->firstItem() + $loop->index }}
                            </span>
                        </td>
                        <td>
                            <span class="font-display text-xl" style="color:var(--color-text-primary)">
                                {{ $table->table_number }}
                            </span>
                        </td>
                        <td>
                            {{-- QR Code render via Google Chart API --}}
                            @if ($table->qr_code_link)
                                <div class="flex items-center gap-3">
                                    <div
                                        style="background:#fff;padding:4px;border-radius:4px;display:inline-block;border:1.5px solid var(--color-ink-500)">
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=64x64&data={{ urlencode($table->qr_code_link) }}"
                                            alt="QR Meja {{ $table->table_number }}" width="56" height="56"
                                            loading="lazy">
                                    </div>
                                </div>
                            @else
                                <span class="badge badge-unavailable">Belum ada</span>
                            @endif
                        </td>
                        <td>
                            @if ($table->qr_code_link)
                                <a href="{{ $table->qr_code_link }}" target="_blank"
                                    class="font-mono text-xs truncate block max-w-[200px]"
                                    style="color:var(--color-lime-500);text-decoration:underline;text-underline-offset:3px"
                                    title="{{ $table->qr_code_link }}">
                                    {{ $table->qr_code_link }}
                                </a>
                            @else
                                <span style="color:var(--color-ink-400)" class="font-mono text-xs">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="font-mono text-xs" style="color:var(--color-ink-400)">
                                {{ $table->created_at->format('d M Y') }}
                            </span>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                {{-- Download QR --}}
                                @if ($table->qr_code_link)
                                    <a href="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode($table->qr_code_link) }}"
                                        download="qr-meja-{{ Str::slug($table->table_number) }}.png" target="_blank"
                                        class="btn btn-mono btn-sm btn-icon" title="Download QR Code">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                @endif
                                {{-- Edit --}}
                                <a href="{{ route('meja.edit', $table->id) }}" wire:navigate
                                    class="btn btn-secondary btn-sm btn-icon" title="Edit Meja">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                {{-- Delete --}}
                                <button wire:click="confirmDelete({{ $table->id }})"
                                    class="btn btn-danger btn-sm btn-icon" title="Hapus Meja">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-16">
                            <div class="flex flex-col items-center gap-3">
                                <svg class="w-10 h-10" style="color:var(--color-ink-600)" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="font-mono text-sm" style="color:var(--color-ink-400)">
                                    {{ $search ? 'Meja tidak ditemukan.' : 'Belum ada meja. Tambah sekarang!' }}
                                </p>
                                @if (!$search)
                                    <a href="{{ route('meja.create') }}" wire:navigate
                                        class="btn btn-primary btn-sm mt-1">+ Tambah Meja</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if ($tables->hasPages())
        <div class="mt-4 flex justify-end">
            {{ $tables->links() }}
        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    @if ($confirmingDelete)
        <div class="modal-backdrop" x-data x-show="true" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="modal" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                <div class="flex items-start justify-between mb-5">
                    <h2 class="modal-title" style="color:var(--color-error)">HAPUS<br>MEJA?</h2>
                    <button wire:click="cancelDelete" class="modal-close">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <p class="font-mono text-sm mb-6" style="color:var(--color-ink-300)">
                    Aksi ini <span style="color:var(--color-error)">tidak bisa dibatalkan</span>.<br>
                    Semua data pesanan terkait meja ini juga akan terhapus.
                </p>
                <div class="flex gap-3">
                    <button wire:click="delete" class="btn btn-danger flex-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Ya, Hapus Meja
                    </button>
                    <button wire:click="cancelDelete" class="btn btn-secondary">Batal</button>
                </div>
            </div>
        </div>
    @endif

</div>
