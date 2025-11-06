<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    // Relasi: Satu item adalah milik satu Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}