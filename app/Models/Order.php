<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'table_id',
        'total_price',
        'payment_status',
        'order_status',
        'payment_method',
        'snap_token',   // tambah ini
        'payment_url',  // tambah ini
    ];

    // Relasi: Pesanan ini berasal dari satu meja
    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    // Relasi: Pesanan ini memiliki banyak item (isi keranjang)
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
