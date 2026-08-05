<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TestingSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Pastikan ada meja
        $table = Table::first();
        if (!$table) {
            $table = Table::create([
                'table_number' => 'Meja 1',
                'slug' => 'meja-1',
                'status' => 'Available',
            ]);
        }

        // 2. Pastikan ada kategori & menu
        $menu = Menu::first();
        if (!$menu) {
            $category = Category::firstOrCreate([
                'name' => 'Coffee',
                'slug' => 'coffee',
            ]);

            $menu = Menu::create([
                'category_id' => $category->id,
                'name' => 'Kopi Susu Gula Aren',
                'slug' => 'kopi-susu-gula-aren',
                'description' => 'Kopi susu dengan gula aren asli',
                'price' => 25000,
                'is_active' => true,
                'is_available' => true,
            ]);

            $menu2 = Menu::create([
                'category_id' => $category->id,
                'name' => 'Americano',
                'slug' => 'americano',
                'description' => 'Espresso dengan air mineral',
                'price' => 15000,
                'is_active' => true,
                'is_available' => true,
            ]);
        }

        $menus = Menu::all();

        // 3. Buat Order tersebar selama 365 hari ke belakang
        $totalOrdersToCreate = 1000; // Jumlah pesanan dummy yang akan dibuat

        for ($i = 0; $i < $totalOrdersToCreate; $i++) {
            // Sebar tanggal order secara acak antara hari ini dan 365 hari lalu
            $randomDaysAgo = rand(0, 365);
            $randomHour = rand(16, 23); // Jam operasional (16:00 - 23:00)
            $randomMinute = rand(0, 59);

            $createdAt = Carbon::now()
                ->subDays($randomDaysAgo)
                ->setHour($randomHour)
                ->setMinute($randomMinute);

            // Buat order
            $order = Order::create([
                'order_code' => 'WC-TEST-' . strtoupper(Str::random(6)),
                'table_id' => $table->id,
                'total_price' => 0, // Akan di-update setelah items dibuat
                'payment_status' => 'Paid', // Agar masuk ke revenue
                'order_status' => 'Completed',
                'payment_method' => ['qris', 'gopay', 'shopeepay'][rand(0, 2)],
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            // Tambah 1 - 3 item per order
            $numItems = rand(1, 3);
            $totalPrice = 0;

            for ($j = 0; $j < $numItems; $j++) {
                $selectedMenu = $menus->random();
                $qty = rand(1, 4);
                $subtotal = $selectedMenu->price * $qty;

                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $selectedMenu->id,
                    'temperature' => 'Ice',
                    'ice_level' => 'Normal',
                    'sugar_level' => 'Normal',
                    'quantity' => $qty,
                    'price' => $selectedMenu->price,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                $totalPrice += $subtotal;
            }

            // Update total price order
            $order->update(['total_price' => $totalPrice]);
        }
    }
}
