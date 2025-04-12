<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    private $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function index()
    {
        $cart = Cart::where('user_id', Auth::id())
            ->where('is_checkedout', false)
            ->with(['cartItems.product'])
            ->first();

        $total = 0;
        if ($cart) {
            foreach ($cart->cartItems as $item) {
                if ($item->product) {
                    $total += $item->product->price * $item->quantity;
                }
            }
        }

        // Tüm ürünleri kategorilerine göre gruplandırarak getir
        $products = Product::with('category')
            ->where('stock', '>', 0)
            ->orderBy('category_id')
            ->orderBy('name')
            ->get();

        // Son satışları getir
        $recentSales = Sale::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('cart.index', compact('cart', 'total', 'products', 'recentSales'));
    }

    public function addToCart(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock
        ]);

        try {
            DB::beginTransaction();

            $cart = Cart::firstOrCreate(
                ['user_id' => Auth::id(), 'is_checkedout' => false],
                ['user_id' => Auth::id()]
            );

            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $product->id)
                ->first();

            if ($cartItem) {
                $newQuantity = $cartItem->quantity + $request->quantity;
                if ($newQuantity > $product->stock) {
                    throw new \Exception(__('locale.insufficient_stock', ['product' => $product->name]));
                }
                $cartItem->quantity = $newQuantity;
                $cartItem->save();
            } else {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => $request->quantity
                ]);
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => __('locale.product_added_to_cart')
                ]);
            }

            return back()->with('success', __('locale.product_added_to_cart'));
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }

            return back()->with('error', $e->getMessage());
        }
    }

    public function removeItem(CartItem $cartItem)
    {
        if ($cartItem->cart->user_id !== Auth::id()) {
            return back()->with('error', __('locale.unauthorized'));
        }

        $cartItem->delete();
        return back()->with('success', __('locale.item_removed_from_cart'));
    }

    public function updateQuantity(Request $request, CartItem $cartItem)
    {
        if ($cartItem->cart->user_id !== Auth::id()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => __('locale.unauthorized')
                ], 403);
            }
            return back()->with('error', __('locale.unauthorized'));
        }

        if (!$cartItem->product) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => __('locale.product_not_found')
                ], 404);
            }
            return back()->with('error', __('locale.product_not_found'));
        }

        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $cartItem->product->stock
        ]);

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('locale.cart_updated')
            ]);
        }

        return back()->with('success', __('locale.cart_updated'));
    }

    public function checkout()
    {
        try {
            DB::beginTransaction();

            $cart = Cart::where('user_id', Auth::id())
                ->where('is_checkedout', false)
                ->with(['cartItems.product'])
                ->firstOrFail();

            $totalPrice = 0;
            foreach ($cart->cartItems as $item) {
                if (!$item->product) {
                    throw new \Exception(__('locale.product_not_found'));
                }
                
                if ($item->quantity > $item->product->stock) {
                    throw new \Exception(__('locale.insufficient_stock', ['product' => $item->product->name]));
                }
                $totalPrice += $item->product->price * $item->quantity;
            }

            // Satış kaydı oluştur
            $sale = Sale::create([
                'cart_id' => $cart->id,
                'user_id' => Auth::id(),
                'total_price' => $totalPrice
            ]);

            // Satış detaylarını oluştur
            foreach ($cart->cartItems as $item) {
                if (!$item->product) {
                    throw new \Exception(__('locale.product_not_found'));
                }

                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price
                ]);

                // Stok miktarını güncelle
                $this->stockService->decreaseStock($item->product_id, $item->quantity);
            }

            // Sepeti tamamlandı olarak işaretle
            $cart->is_checkedout = true;
            $cart->save();

            DB::commit();
            return redirect()->route('cart.index')->with('success', __('locale.sale_completed'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}