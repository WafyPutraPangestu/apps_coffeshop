<?php

namespace App\Livewire\Guest;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Home extends Component
{
    /**
     * ID kategori yang lagi aktif difilter di grid menu.
     * null = tampilkan semua kategori.
     */
    public ?int $activeCategory = null;

    /**
     * Dipanggil dari pill kategori: wire:click="selectCategory(...)"
     */
    public function selectCategory(?int $categoryId): void
    {
        $this->activeCategory = $categoryId;
    }

    public function render()
    {
        $categories = Category::orderBy('name')->get();

        $menus = Menu::query()
            ->where('is_active', true)
            ->where('is_available', true)
            ->when(
                $this->activeCategory,
                fn($query) => $query->where('category_id', $this->activeCategory)
            )
            ->latest()
            ->take(8)
            ->get();

        $categoryNames = $categories->pluck('name', 'id');

        $cupsToday = (int) DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereDate('orders.created_at', today())
            ->sum('order_items.quantity');

        // Jumlah pesanan (transaksi) yang masuk hari ini.
        $ordersToday = (int) DB::table('orders')
            ->whereDate('created_at', today())
            ->count();

        // Estimasi pendapatan hari ini dari item pesanan x harga menu saat ini.
        // Sesuaikan nama kolom (menu_id, quantity) kalau berbeda di skema kamu.
        $revenueToday = (int) DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('menus', 'menus.id', '=', 'order_items.menu_id')
            ->whereDate('orders.created_at', today())
            ->selectRaw('COALESCE(SUM(order_items.quantity * menus.price), 0) as total')
            ->value('total');

        return view('livewire.guest.home', [
            'categories' => $categories,
            'menus' => $menus,
            'categoryNames' => $categoryNames,
            'cupsToday' => $cupsToday,
            'ordersToday' => $ordersToday,
            'revenueToday' => $revenueToday,
        ]);
    }
}
