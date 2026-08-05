<?php

namespace App\Livewire\Admin\Report;

use App\Exports\OrdersExport;
use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('layouts.app')]
#[Title('Laporan Penjualan')]
class Index extends Component
{
    use WithPagination;

    public string $startDate     = '';
    public string $endDate       = '';
    public string $filterStatus  = '';
    public string $filterPayment = '';

    public function mount(): void
    {
        // Default range: bulan ini
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate   = now()->endOfMonth()->format('Y-m-d');
    }

    public function updatingStartDate(): void
    {
        $this->resetPage();
    }
    public function updatingEndDate(): void
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

    public function resetFilter(): void
    {
        $this->startDate    = now()->startOfMonth()->format('Y-m-d');
        $this->endDate      = now()->endOfMonth()->format('Y-m-d');
        $this->filterStatus  = '';
        $this->filterPayment = '';
        $this->resetPage();
    }

    public function downloadExcel()
    {
        $filename = 'laporan-penjualan-' . now()->format('Ymd-His') . '.xlsx';

        return Excel::download(
            new OrdersExport(
                startDate: $this->startDate ?: null,
                endDate: $this->endDate ?: null,
                filterStatus: $this->filterStatus ?: null,
                filterPayment: $this->filterPayment ?: null,
            ),
            $filename
        );
    }

    private function baseQuery()
    {
        return Order::query()
            ->when($this->startDate, fn($q) => $q->whereDate('created_at', '>=', $this->startDate))
            ->when($this->endDate,   fn($q) => $q->whereDate('created_at', '<=', $this->endDate))
            ->when($this->filterStatus,  fn($q) => $q->where('order_status',  $this->filterStatus))
            ->when($this->filterPayment, fn($q) => $q->where('payment_status', $this->filterPayment));
    }

    public function render()
    {
        $orders = $this->baseQuery()
            ->with(['table', 'items.menu'])
            ->orderByDesc('created_at')
            ->paginate(15);

        $base = $this->baseQuery();

        $stats = [
            'total_order'   => (clone $base)->count(),
            'total_revenue' => (clone $base)->where('payment_status', 'Paid')->sum('total_price'),
            'completed'     => (clone $base)->where('order_status', 'Completed')->count(),
            'pending'       => (clone $base)->where('order_status', 'Pending')->count(),
            'avg_order'     => (clone $base)->where('payment_status', 'Paid')->avg('total_price') ?? 0,
            'total_items'   => (clone $base)->withCount('items')->get()->sum('items_count'),
        ];

        // Chart data: pendapatan per hari dalam range
        $dailyRevenueDb = (clone $base)
            ->where('payment_status', 'Paid')
            ->selectRaw('DATE(created_at) as date, SUM(total_price) as revenue, COUNT(*) as orders')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $dailyRevenue = collect();
        if ($this->startDate && $this->endDate) {
            $start = \Carbon\Carbon::parse($this->startDate);
            $end = \Carbon\Carbon::parse($this->endDate);
            
            // Generate kalender penuh (maksimal 366 hari untuk mencegah overload)
            if ($start->diffInDays($end) <= 366) {
                for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
                    $dateStr = $d->format('Y-m-d');
                    if ($dailyRevenueDb->has($dateStr)) {
                        $dailyRevenue->push($dailyRevenueDb->get($dateStr));
                    } else {
                        $dailyRevenue->push((object)[
                            'date' => $dateStr,
                            'revenue' => 0,
                            'orders' => 0
                        ]);
                    }
                }
            } else {
                $dailyRevenue = $dailyRevenueDb->values();
            }
        } else {
            $dailyRevenue = $dailyRevenueDb->values();
        }

        return view('livewire.admin.report.index', compact('orders', 'stats', 'dailyRevenue'));
    }
}
