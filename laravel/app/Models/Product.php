<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'price',
        'stock',
        'description',
        'category_id',
        'is_public',
        'image_path'
    ];

    protected $casts = [
        'price'     => 'integer',
        'stock'     => 'integer',
        'is_public' => 'boolean',
    ];

    protected $attributes = [
        'stock'     => 0,
        'is_public' => true,
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // todo

    /**
     * 在庫があるか
     * 管理画面・API で使える
     */
    // public function getInStockAttribute(): bool
    // {
    //     return $this->stock > 0;
    // }

    /**
     * 在庫減算（注文確定時）
     */
    // public function decreaseStock(int $quantity): void
    // {
    //     $this->decrement('stock', $quantity);
    // }
}
