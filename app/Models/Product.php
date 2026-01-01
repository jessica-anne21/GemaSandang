<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'nama_produk',
        'deskripsi',
        'harga',
        'stok',
        'foto_produk', 
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItem() 
    {
        return $this->hasOne(OrderItem::class); 
    }

    public function bargains()
    {
        // Satu produk bisa memiliki banyak tawaran
        return $this->hasMany(Bargain::class);
    }
}