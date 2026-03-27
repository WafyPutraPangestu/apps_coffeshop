<?php

namespace App\Livewire\Components;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Sidebar extends Component
{
    public int $pendingCount = 0;
    public array $latestOrders = [];

    public function mount(): void
    {
        $this->loadCounts();
    }

    // Dipanggil wire:poll setiap 5 detik
    public function refreshCounts(): void
    {
        $this->loadCounts();
    }

    public function logout(): void
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        $this->js("window.showLoader && window.showLoader('Sampai jumpa...')");
        $this->redirect(route('login'), navigate: true);
    }

    private function loadCounts(): void
    {
        $this->pendingCount = Order::where('order_status', 'Pending')
            ->where('payment_status', 'Paid')
            ->count();

        $this->latestOrders = Order::with('table')
            ->where('order_status', 'Pending')
            ->where('payment_status', 'Paid')
            ->latest()
            ->limit(3)
            ->get()
            ->map(fn($o) => [
                'id'         => $o->id,
                'order_code' => $o->order_code,
                'table'      => $o->table?->table_number ?? '-',
                'total'      => $o->total_price,
                'ago'        => $o->created_at->diffForHumans(),
            ])
            ->toArray();
    }

    public function render()
    {
        return view('livewire.components.sidebar');
    }
}
