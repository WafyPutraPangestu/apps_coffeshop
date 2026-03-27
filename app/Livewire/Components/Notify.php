<?php

namespace App\Livewire\Components;

use Livewire\Component;

class Notify extends Component
{
    // --- Toast ---
    public array $toasts = [];
    private int $nextId = 0;

    // --- Confirm Modal ---
    public bool $confirmOpen    = false;
    public string $confirmMessage = '';
    public string $confirmAction  = '';
    public mixed $confirmPayload  = null;

    protected $listeners = [
        'toast',
        'confirm',
        'confirmResolved',
    ];

    // Dipanggil dari component manapun:
    // $this->dispatch('toast', type: 'success', message: 'Berhasil!');
    // type: success | error | warning | info
    public function toast(string $type, string $message, int $duration = 4000): void
    {
        $id = ++$this->nextId;

        $this->toasts[] = [
            'id'       => $id,
            'type'     => $type,
            'message'  => $message,
            'duration' => $duration,
        ];

        // Auto-dismiss via JS (lihat blade)
        $this->dispatch('toast-added', id: $id, duration: $duration);
    }

    public function dismissToast(int $id): void
    {
        $this->toasts = array_values(
            array_filter($this->toasts, fn($t) => $t['id'] !== $id)
        );
    }

    // Dipanggil dari component manapun:
    // $this->dispatch('confirm',
    //     message: 'Yakin hapus ini?',
    //     action: 'deleteMenu',       // nama method di component pemanggil
    //     payload: $id                // data yang dikirim balik saat confirm
    // );
    public function confirm(string $message, string $action, mixed $payload = null): void
    {
        $this->confirmMessage = $message;
        $this->confirmAction  = $action;
        $this->confirmPayload = $payload;
        $this->confirmOpen    = true;
    }

    public function confirmYes(): void
    {
        // Kirim event ke component pemanggil dengan action & payload
        $this->dispatch($this->confirmAction, payload: $this->confirmPayload);
        $this->closeConfirm();
    }

    public function closeConfirm(): void
    {
        $this->confirmOpen    = false;
        $this->confirmMessage = '';
        $this->confirmAction  = '';
        $this->confirmPayload = null;
    }

    public function render()
    {
        return view('livewire.components.notify');
    }
}
