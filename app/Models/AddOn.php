<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddOn extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'is_available'];

    // Relasi: Topping ini bisa menempel di banyak pesanan kopi
    public function orderItemAddons()
    {
        return $this->hasMany(OrderItemAddon::class);
    }
}
