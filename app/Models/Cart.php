<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cart extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'is_checkedout',
    ];

    protected $casts = [
        'is_checkedout' => 'boolean',
    ];

    /**
     * Sepetin ait olduğu kullanıcı ilişkisi
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Sepetteki ürünler ilişkisi
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Sepet bir satışa dönüştüyse bu ilişki
     */
    public function sale(): HasOne
    {
        return $this->hasOne(Sale::class);
    }
}
