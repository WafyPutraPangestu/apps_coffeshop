<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'menu_id',
        'temperature',
        'ice_level',
        'sugar_level',
        'quantity',
        'price',
        'notes'
    ];

    // Relasi: Item ini milik satu transaksi pesanan
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relasi: Item ini merujuk pada satu menu fisik
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    // Relasi: Satu minuman di keranjang bisa punya banyak topping tambahan
    public function addons()
    {
        return $this->hasMany(OrderItemAddon::class);
    }
}
