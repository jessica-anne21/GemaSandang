<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    
    // Relasi: Satu pesanan dimiliki oleh satu User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Relasi: Satu pesanan memiliki banyak Item
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}