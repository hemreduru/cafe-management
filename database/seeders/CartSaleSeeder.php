<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\User;
use App\Services\StockService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartSaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // İlk kullanıcıyı al (admin kullanıcısı olduğunu varsayıyoruz)
        $user = User::first();
        
        if (!$user) {
            // Eğer kullanıcı yoksa, devam edemeyiz
            $this->command->info('Önce bir kullanıcı oluşturmalısınız!');
            return;
        }
        
        // Rastgele ürünleri alabilmek için
        $products = Product::all();
        
        // 1. Sepet örneği - Tamamlanmış satış (is_checkedout = true)
        $cart1 = Cart::create([
            'user_id' => $user->id,
            'is_checkedout' => true
        ]);
        
        // Sepet 1'e ürün ekle
        $cartItems1 = [
            ['product_id' => 1, 'quantity' => 2], // Türk Kahvesi
            ['product_id' => 5, 'quantity' => 1], // Çay
            ['product_id' => 16, 'quantity' => 1], // Cheesecake
        ];
        
        $totalPrice1 = 0;
        
        foreach ($cartItems1 as $item) {
            $product = $products->find($item['product_id']);
            $totalPrice1 += $product->price * $item['quantity'];
            
            CartItem::create([
                'cart_id' => $cart1->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity']
            ]);
        }
        
        // Satış kaydı oluştur
        $sale1 = Sale::create([
            'cart_id' => $cart1->id,
            'user_id' => $user->id,
            'total_price' => $totalPrice1
        ]);
        
        // Satış detaylarını oluştur
        foreach ($cartItems1 as $item) {
            $product = $products->find($item['product_id']);
            
            SaleDetail::create([
                'sale_id' => $sale1->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $product->price
            ]);
        }
        
        // 2. Sepet örneği - Tamamlanmış başka bir satış (is_checkedout = true)
        $cart2 = Cart::create([
            'user_id' => $user->id,
            'is_checkedout' => true
        ]);
        
        // Sepet 2'ye ürün ekle
        $cartItems2 = [
            ['product_id' => 9, 'quantity' => 1], // Serpme Kahvaltı
            ['product_id' => 7, 'quantity' => 2], // Limonata
            ['product_id' => 19, 'quantity' => 1], // Patates Kızartması
        ];
        
        $totalPrice2 = 0;
        
        foreach ($cartItems2 as $item) {
            $product = $products->find($item['product_id']);
            $totalPrice2 += $product->price * $item['quantity'];
            
            CartItem::create([
                'cart_id' => $cart2->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity']
            ]);
        }
        
        // Satış kaydı oluştur
        $sale2 = Sale::create([
            'cart_id' => $cart2->id,
            'user_id' => $user->id,
            'total_price' => $totalPrice2
        ]);
        
        // Satış detaylarını oluştur
        foreach ($cartItems2 as $item) {
            $product = $products->find($item['product_id']);
            
            SaleDetail::create([
                'sale_id' => $sale2->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $product->price
            ]);
        }
        
        // 3. Sepet örneği - Aktif sepet (is_checkedout = false)
        $cart3 = Cart::create([
            'user_id' => $user->id,
            'is_checkedout' => false
        ]);
        
        // Sepet 3'e ürün ekle (henüz satışa dönüşmemiş aktif sepet)
        $cartItems3 = [
            ['product_id' => 2, 'quantity' => 1], // Filtre Kahve
            ['product_id' => 18, 'quantity' => 2], // Sufle
        ];
        
        foreach ($cartItems3 as $item) {
            CartItem::create([
                'cart_id' => $cart3->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity']
            ]);
        }
        
        // StockService kullanarak stokları güncelle
        $stockService = app(StockService::class);
        $stockService->updateStockAfterSale($sale1);
        $stockService->updateStockAfterSale($sale2);
        
        $this->command->info('Sepet ve satış verileri başarıyla oluşturuldu.');
    }
}
