<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockService
{
    /**
     * Bir satış gerçekleştiğinde stok miktarlarını günceller
     *
     * @param Sale $sale
     * @return bool
     */
    public function updateStockAfterSale(Sale $sale): bool
    {
        try {
            DB::beginTransaction();

            foreach ($sale->saleDetails as $detail) {
                $product = Product::findOrFail($detail->product_id);
                
                // Yeterli stok kontrolü
                if ($product->stock < $detail->quantity) {
                    throw new Exception("Ürün stokta yetersiz: {$product->name}");
                }
                
                // Stok miktarını düşür
                $product->stock -= $detail->quantity;
                $product->save();
            }

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Stok güncellenirken hata: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Stok miktarını manuel olarak artır
     *
     * @param int $productId
     * @param int $quantity
     * @return bool
     */
    public function increaseStock(int $productId, int $quantity): bool
    {
        try {
            $product = Product::findOrFail($productId);
            $product->stock += $quantity;
            $product->save();
            
            return true;
        } catch (Exception $e) {
            Log::error('Stok artırılırken hata: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Stok miktarını manuel olarak azalt
     *
     * @param int $productId
     * @param int $quantity
     * @return bool
     */
    public function decreaseStock(int $productId, int $quantity): bool
    {
        try {
            $product = Product::findOrFail($productId);
            
            if ($product->stock < $quantity) {
                throw new Exception("Yetersiz stok: {$product->name}");
            }
            
            $product->stock -= $quantity;
            $product->save();
            
            return true;
        } catch (Exception $e) {
            Log::error('Stok azaltılırken hata: ' . $e->getMessage());
            return false;
        }
    }
}