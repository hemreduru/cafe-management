<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'cart_id',
        'user_id',
        'total_price'
    ];

    protected $casts = [
        'total_price' => 'decimal:2'
    ];

    /**
     * Bu satışın ait olduğu sepet ilişkisi
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Bu satışı yapan kullanıcı ilişkisi
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Bu satışın detayları ilişkisi
     */
    public function saleDetails(): HasMany
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(SaleDetail::class);
    }

}
