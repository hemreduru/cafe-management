<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleDetail extends Model
{
    use SoftDeletes;
    
    protected $table = 'sales_details';

    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'price',
    ];

    /**
     * Bu detayın ait olduğu satış ilişkisi
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * Bu satış detayının ürün ilişkisi
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
