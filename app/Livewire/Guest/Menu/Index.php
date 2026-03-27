<?php

namespace App\Livewire\Guest\Menu;

use App\Models\AddOn;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemAddon;
use App\Models\Table;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest.guest')]
class Index extends Component
{
    // ── Meja ──
    public ?Table $table = null;
    public string $tableSlug = '';

    // ── Filter ──
    public ?int $activeCategoryId = null;

    // ── Modal Detail Menu ──
    public bool $showDetail = false;
    public ?Menu $selectedMenu = null;

    // ── Kustomisasi ──
    public string $temperature = '';
    public string $ice_level   = 'Normal';
    public string $sugar_level = 'Normal';
    public array  $selectedAddons = [];   // [addon_id => true/false]
    public int    $quantity = 1;
    public string $notes    = '';

    // ── Cart ──
    public array $cart = [];   // [{menu_id, name, price, qty, temp, ice, sugar, addons:[{id,name,price}], notes}]

    // ── Cart Drawer ──
    public bool $showCart = false;

    public function mount(string $tableSlug): void
    {
        $this->tableSlug = $tableSlug;

        $this->table = Table::where('qr_code_link', url("/order/{$tableSlug}"))->first();

        $this->cart = session("cart_{$tableSlug}", []);
    }
    // ── Buka detail menu ──
    public function openDetail(int $menuId): void
    {
        $this->selectedMenu   = Menu::with('category')->findOrFail($menuId);
        $this->temperature    = $this->selectedMenu->category->name === 'Snack' ? '' : 'Ice';
        $this->ice_level      = 'Normal';
        $this->sugar_level    = 'Normal';
        $this->selectedAddons = [];
        $this->quantity       = 1;
        $this->notes          = '';
        $this->showDetail     = true;
    }

    public function closeDetail(): void
    {
        $this->showDetail   = false;
        $this->selectedMenu = null;
    }

    // ── Toggle addon ──
    public function toggleAddon(int $addonId): void
    {
        if (isset($this->selectedAddons[$addonId])) {
            unset($this->selectedAddons[$addonId]);
        } else {
            $this->selectedAddons[$addonId] = true;
        }
    }

    public function incrementQty(): void
    {
        if ($this->quantity < 20) $this->quantity++;
    }

    public function decrementQty(): void
    {
        if ($this->quantity > 1) $this->quantity--;
    }

    // ── Hitung subtotal di modal ──
    public function getSubtotalProperty(): int
    {
        if (!$this->selectedMenu) return 0;

        $addonTotal = 0;
        if (!empty($this->selectedAddons)) {
            $addonTotal = AddOn::whereIn('id', array_keys($this->selectedAddons))->sum('price');
        }

        return ($this->selectedMenu->price + $addonTotal) * $this->quantity;
    }

    // ── Tambah ke cart ──
    public function addToCart(): void
    {
        if (!$this->selectedMenu) return;

        $addons = [];
        if (!empty($this->selectedAddons)) {
            $addons = AddOn::whereIn('id', array_keys($this->selectedAddons))
                ->get(['id', 'name', 'price'])
                ->toArray();
        }

        $this->cart[] = [
            'key'         => Str::uuid()->toString(),
            'menu_id'     => $this->selectedMenu->id,
            'name'        => $this->selectedMenu->name,
            'image'       => $this->selectedMenu->image,
            'price'       => $this->selectedMenu->price,
            'temperature' => $this->temperature,
            'ice_level'   => $this->ice_level,
            'sugar_level' => $this->sugar_level,
            'addons'      => $addons,
            'quantity'    => $this->quantity,
            'notes'       => $this->notes,
        ];

        // Simpan ke session
        session(["cart_{$this->tableSlug}" => $this->cart]);

        $this->closeDetail();
        $this->showCart = true;
        $this->dispatch('cart-updated');
    }

    // ── Hapus item dari cart ──
    public function removeFromCart(string $key): void
    {
        $this->cart = array_values(array_filter($this->cart, fn($i) => $i['key'] !== $key));
        session(["cart_{$this->tableSlug}" => $this->cart]);
    }

    // ── Total cart ──
    public function getCartTotalProperty(): int
    {
        return array_sum(array_map(function ($item) {
            $addonTotal = array_sum(array_column($item['addons'], 'price'));
            return ($item['price'] + $addonTotal) * $item['quantity'];
        }, $this->cart));
    }

    public function getCartCountProperty(): int
    {
        return array_sum(array_column($this->cart, 'quantity'));
    }

    // ── Checkout — buat Order & redirect ke payment ──
    public function checkout(): void
    {
        if (empty($this->cart) || !$this->table) return;

        $orderCode = 'WC-' . strtoupper(Str::random(8));

        $order = Order::create([
            'order_code'     => $orderCode,
            'table_id'       => $this->table->id,
            'total_price'    => $this->cartTotal,
            'payment_status' => 'Unpaid',
            'order_status'   => 'Pending',
        ]);

        foreach ($this->cart as $item) {
            $addonTotal = array_sum(array_column($item['addons'], 'price'));
            $itemPrice  = ($item['price'] + $addonTotal) * $item['quantity'];

            $orderItem = OrderItem::create([
                'order_id'    => $order->id,
                'menu_id'     => $item['menu_id'],
                'temperature' => $item['temperature'] ?: null,
                'ice_level'   => $item['ice_level'] ?: null,
                'sugar_level' => $item['sugar_level'] ?: null,
                'quantity'    => $item['quantity'],
                'price'       => $itemPrice,
                'notes'       => $item['notes'] ?: null,
            ]);

            foreach ($item['addons'] as $addon) {
                OrderItemAddon::create([
                    'order_item_id' => $orderItem->id,
                    'add_on_id'     => $addon['id'],
                    'price'         => $addon['price'],
                ]);
            }
        }

        // ── TAMBAHAN MIDTRANS ──
        $order->load('items.menu', 'items.addons.addOn', 'table');

        $paymentService = new \App\Services\PaymentService();
        $snapToken      = $paymentService->createSnapToken($order);

        $order->update([
            'snap_token'  => $snapToken,
            'payment_url' => config('services.midtrans.is_production')
                ? "https://app.midtrans.com/snap/v2/vtweb/{$snapToken}"
                : "https://app.sandbox.midtrans.com/snap/v2/vtweb/{$snapToken}",
        ]);
        // ── END MIDTRANS ──

        session()->forget("cart_{$this->tableSlug}");
        $this->cart     = [];
        $this->showCart = false;

        $this->redirect(route('guest.order.track', $order->order_code));
    }

    public function render()
    {
        $categories = Category::withCount(['menus' => fn($q) => $q->where('is_available', true)])->get();

        $menus = Menu::with('category')
            ->where('is_available', true)
            ->when($this->activeCategoryId, fn($q) => $q->where('category_id', $this->activeCategoryId))
            ->get();

        $addons = AddOn::where('is_available', true)->get();

        return view('livewire.guest.menu.index', compact('categories', 'menus', 'addons'));
    }
}
