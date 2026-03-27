<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'image',
        'is_available'
    ];

    // Relasi: Menu ini milik satu kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi: Menu ini bisa ada di banyak keranjang pesanan
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
