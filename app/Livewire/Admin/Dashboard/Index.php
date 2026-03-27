<?php

namespace App\Livewire\Admin\Dashboard;

use Livewire\Component;
use App\Models\Order;
use App\Models\Menu;
use App\Models\Table;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Index extends Component
{
    // Period selector
    public string $period = 'today'; // today | week | month | year
    public int $selectedYear;
    public int $selectedMonth;
    public array $availableYears = [];

    // Live stats
    public int $totalRevenue = 0;
    public int $totalOrders = 0;
    public int $completedOrders = 0;
    public int $pendingOrders = 0;
    public int $processingOrders = 0;
    public int $totalMenuItems = 0;
    public int $activeTables = 0;

    // Chart data
    public array $revenueChart = [];
    public array $orderStatusChart = [];
    public array $topMenus = [];
    public array $hourlyChart = [];
    public array $categoryRevenue = [];
    public array $paymentMethodChart = [];

    // Comparison
    public int $revenueGrowth = 0;
    public int $orderGrowth = 0;

    // Recent orders
    public array $recentOrders = [];

    // Yearly archive
    public array $yearlyStats = [];

    public function mount()
    {
        $this->selectedYear = now()->year;
        $this->selectedMonth = now()->month;
        $this->availableYears = $this->getAvailableYears();
        $this->loadData();
    }

    public function updatedPeriod()
    {
        $this->loadData();
    }

    public function updatedSelectedYear()
    {
        $this->loadData();
    }

    public function updatedSelectedMonth()
    {
        $this->loadData();
    }

    public function setPeriod(string $period)
    {
        $this->period = $period;
        $this->loadData();
    }

    private function getAvailableYears(): array
    {
        $years = Order::selectRaw('YEAR(created_at) as year')
            ->groupBy('year')
            ->orderByDesc('year')
            ->pluck('year')
            ->toArray();

        if (empty($years)) {
            $years = [now()->year];
        }

        // Always include current year
        if (!in_array(now()->year, $years)) {
            array_unshift($years, now()->year);
        }

        return $years;
    }

    private function getDateRange(): array
    {
        return match ($this->period) {
            'today' => [
                Carbon::today(),
                Carbon::today()->endOfDay(),
            ],
            'week' => [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek(),
            ],
            'month' => [
                Carbon::create($this->selectedYear, $this->selectedMonth)->startOfMonth(),
                Carbon::create($this->selectedYear, $this->selectedMonth)->endOfMonth(),
            ],
            'year' => [
                Carbon::create($this->selectedYear)->startOfYear(),
                Carbon::create($this->selectedYear)->endOfYear(),
            ],
            default => [Carbon::today(), Carbon::today()->endOfDay()],
        };
    }

    private function getPreviousDateRange(): array
    {
        return match ($this->period) {
            'today' => [
                Carbon::yesterday(),
                Carbon::yesterday()->endOfDay(),
            ],
            'week' => [
                Carbon::now()->subWeek()->startOfWeek(),
                Carbon::now()->subWeek()->endOfWeek(),
            ],
            'month' => [
                Carbon::create($this->selectedYear, $this->selectedMonth)->subMonth()->startOfMonth(),
                Carbon::create($this->selectedYear, $this->selectedMonth)->subMonth()->endOfMonth(),
            ],
            'year' => [
                Carbon::create($this->selectedYear - 1)->startOfYear(),
                Carbon::create($this->selectedYear - 1)->endOfYear(),
            ],
            default => [Carbon::yesterday(), Carbon::yesterday()->endOfDay()],
        };
    }

    public function loadData()
    {
        [$start, $end] = $this->getDateRange();
        [$prevStart, $prevEnd] = $this->getPreviousDateRange();

        $paidOrders = Order::where('payment_status', 'Paid')
            ->whereBetween('created_at', [$start, $end]);

        $allOrders = Order::whereBetween('created_at', [$start, $end]);

        // Core stats
        $this->totalRevenue = (clone $paidOrders)->sum('total_price');
        $this->totalOrders = (clone $allOrders)->count();
        $this->completedOrders = (clone $allOrders)->where('order_status', 'Completed')->count();
        $this->pendingOrders = (clone $allOrders)->where('order_status', 'Pending')->count();
        $this->processingOrders = (clone $allOrders)->where('order_status', 'Processing')->count();
        $this->totalMenuItems = Menu::where('is_available', true)->count();
        $this->activeTables = Table::count();

        // Growth comparison
        $prevRevenue = Order::where('payment_status', 'Paid')
            ->whereBetween('created_at', [$prevStart, $prevEnd])
            ->sum('total_price');
        $prevOrders = Order::whereBetween('created_at', [$prevStart, $prevEnd])->count();

        $this->revenueGrowth = $prevRevenue > 0
            ? round((($this->totalRevenue - $prevRevenue) / $prevRevenue) * 100)
            : ($this->totalRevenue > 0 ? 100 : 0);

        $this->orderGrowth = $prevOrders > 0
            ? round((($this->totalOrders - $prevOrders) / $prevOrders) * 100)
            : ($this->totalOrders > 0 ? 100 : 0);

        // Revenue chart
        $this->revenueChart = $this->buildRevenueChart($start, $end);

        // Order status breakdown
        $this->orderStatusChart = [
            'completed' => $this->completedOrders,
            'processing' => $this->processingOrders,
            'pending' => $this->pendingOrders,
        ];

        // Top menus
        $this->topMenus = OrderItem::select('menu_id', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(price * quantity) as revenue'))
            ->whereHas('order', fn($q) => $q->whereBetween('created_at', [$start, $end])->where('payment_status', 'Paid'))
            ->with('menu:id,name,price')
            ->groupBy('menu_id')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get()
            ->map(fn($item) => [
                'name' => $item->menu?->name ?? 'Unknown',
                'sold' => $item->total_sold,
                'revenue' => $item->revenue,
            ])
            ->toArray();

        // Hourly orders (today / this period)
        $this->hourlyChart = $this->buildHourlyChart($start, $end);

        // Category revenue
        $this->categoryRevenue = $this->buildCategoryRevenue($start, $end);

        // Payment methods
        $this->paymentMethodChart = Order::where('payment_status', 'Paid')
            ->whereBetween('created_at', [$start, $end])
            ->select('payment_method', DB::raw('count(*) as total'), DB::raw('sum(total_price) as revenue'))
            ->groupBy('payment_method')
            ->get()
            ->map(fn($item) => [
                'method' => $item->payment_method ?? 'Unknown',
                'total' => $item->total,
                'revenue' => $item->revenue,
            ])
            ->toArray();

        // Recent orders
        $this->recentOrders = Order::with(['table', 'items.menu'])
            ->latest()
            ->limit(8)
            ->get()
            ->map(fn($order) => [
                'id' => $order->id,
                'order_code' => $order->order_code,
                'table' => $order->table?->table_number ?? '-',
                'total' => $order->total_price,
                'payment_status' => $order->payment_status,
                'order_status' => $order->order_status,
                'items_count' => $order->items->count(),
                'created_at' => $order->created_at->diffForHumans(),
            ])
            ->toArray();

        // Yearly archive stats
        $this->yearlyStats = $this->buildYearlyStats();
    }

    private function buildRevenueChart(Carbon $start, Carbon $end): array
    {
        $labels = [];
        $values = [];

        if ($this->period === 'today' || $this->period === 'week') {
            // Daily breakdown
            $days = $start->diffInDays($end) + 1;
            for ($i = 0; $i < $days; $i++) {
                $day = $start->copy()->addDays($i);
                $labels[] = $day->format('D d');
                $values[] = Order::where('payment_status', 'Paid')
                    ->whereDate('created_at', $day)
                    ->sum('total_price');
            }
        } elseif ($this->period === 'month') {
            // Daily in month
            $daysInMonth = $start->daysInMonth;
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $labels[] = sprintf('%02d', $d);
                $values[] = Order::where('payment_status', 'Paid')
                    ->whereYear('created_at', $this->selectedYear)
                    ->whereMonth('created_at', $this->selectedMonth)
                    ->whereDay('created_at', $d)
                    ->sum('total_price');
            }
        } else {
            // Monthly in year
            for ($m = 1; $m <= 12; $m++) {
                $labels[] = Carbon::create($this->selectedYear, $m)->format('M');
                $values[] = Order::where('payment_status', 'Paid')
                    ->whereYear('created_at', $this->selectedYear)
                    ->whereMonth('created_at', $m)
                    ->sum('total_price');
            }
        }

        return ['labels' => $labels, 'values' => $values];
    }

    private function buildHourlyChart(Carbon $start, Carbon $end): array
    {
        $labels = [];
        $values = [];

        for ($h = 7; $h <= 22; $h++) {
            $labels[] = sprintf('%02d:00', $h);
            $values[] = Order::where('payment_status', 'Paid')
                ->whereBetween('created_at', [$start, $end])
                ->whereRaw('HOUR(created_at) = ?', [$h])
                ->count();
        }

        return ['labels' => $labels, 'values' => $values];
    }

    private function buildCategoryRevenue(Carbon $start, Carbon $end): array
    {
        return DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->join('categories', 'menus.category_id', '=', 'categories.id')
            ->where('orders.payment_status', 'Paid')
            ->whereBetween('orders.created_at', [$start, $end])
            ->select('categories.name', DB::raw('SUM(order_items.price * order_items.quantity) as revenue'), DB::raw('SUM(order_items.quantity) as sold'))
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('revenue')
            ->get()
            ->map(fn($row) => ['name' => $row->name, 'revenue' => $row->revenue, 'sold' => $row->sold])
            ->toArray();
    }

    private function buildYearlyStats(): array
    {
        return collect($this->availableYears)->map(function ($year) {
            $revenue = Order::where('payment_status', 'Paid')
                ->whereYear('created_at', $year)
                ->sum('total_price');
            $orders = Order::whereYear('created_at', $year)->count();
            return [
                'year' => $year,
                'revenue' => $revenue,
                'orders' => $orders,
            ];
        })->toArray();
    }

    public function render()
    {
        return view('livewire.admin.dashboard.index')
            ->with(['title' => 'Dashboard — Warso Coffee']);
    }
}
