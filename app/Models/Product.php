<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'price',
        'stock',
        'image',
    ];

    /**
     * Ürünün ait olduğu kategoriyi getiren ilişki
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Ürünün sepet öğeleriyle ilişkisi
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Ürünün satış detaylarıyla ilişkisi
     */
    public function saleDetails(): HasMany
    {
        return $this->hasMany(SaleDetail::class);
    }
}
