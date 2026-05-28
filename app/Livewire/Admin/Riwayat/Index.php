<?php

namespace App\Livewire\Admin\Riwayat;

use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Riwayat Pesanan')]
class Index extends Component
{
    use WithPagination;

    public string $search        = '';
    public string $filterStatus  = '';
    public string $filterPayment = '';
    public string $filterDate    = '';

    protected $listeners = ['deleteOrder'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }
    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }
    public function updatingFilterPayment(): void
    {
        $this->resetPage();
    }
    public function updatingFilterDate(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $this->dispatch(
            'confirm',
            message: 'Yakin ingin menghapus riwayat pesanan ini?',
            action: 'deleteOrder',
            payload: $id,
        );
    }

    public function deleteOrder(mixed $payload): void
    {
        Order::findOrFail((int) $payload)->delete();
        $this->dispatch('toast', type: 'success', message: 'Pesanan berhasil dihapus dari riwayat.');
    }

    public function render()
    {
        $orders = Order::query()
            ->with(['table', 'items.menu'])
            ->when($this->search, fn($q) => $q->where('order_code', 'like', "%{$this->search}%"))
            ->when($this->filterStatus,  fn($q) => $q->where('order_status',  $this->filterStatus))
            ->when($this->filterPayment, fn($q) => $q->where('payment_status', $this->filterPayment))
            ->when($this->filterDate,    fn($q) => $q->whereDate('created_at', $this->filterDate))
            ->orderByDesc('created_at')
            ->paginate(15);

        // Stats ringkasan
        $baseQuery = Order::query()
            ->when($this->filterDate, fn($q) => $q->whereDate('created_at', $this->filterDate));

        $stats = [
            'total'     => $baseQuery->clone()->count(),
            'completed' => $baseQuery->clone()->where('order_status', 'Completed')->count(),
            'pending'   => $baseQuery->clone()->where('order_status', 'Pending')->count(),
            'revenue'   => $baseQuery->clone()->where('payment_status', 'Paid')->sum('total_price'),
        ];

        return view('livewire.admin.riwayat.index', compact('orders', 'stats'));
    }
}
