<?php

namespace App\Livewire\Guest\Order;

use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.guest.guest')]
#[Title('Status Pesanan')]
class Track extends Component
{
    public string $orderCode = '';
    public ?Order $order = null;

    public function mount(string $orderCode): void
    {
        $this->orderCode = $orderCode;
        $this->loadOrder();
    }

    public function loadOrder(): void
    {
        $this->order = Order::with(['items.menu', 'items.addons.addOn', 'table'])
            ->where('order_code', $this->orderCode)
            ->firstOrFail();
    }

    // Polling setiap 5 detik untuk update status realtime
    public function getListeners(): array
    {
        return [
            'refresh' => '$refresh',
        ];
    }

    public function render()
    {
        // Reload order setiap render (dipicu polling)
        $this->loadOrder();

        return view('livewire.guest.order.track');
    }
}
