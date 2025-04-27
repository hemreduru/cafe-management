<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Sale;
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

        $categories = \App\Models\Category::with(['products' => function($query) {
            $query->where('stock', '>', 0)->orderBy('name');
        }])->orderBy('name')->get();

        return view('cart.index', compact('cart', 'total', 'products', 'recentSales', 'categories'));
    }

    public function addToCart(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        if ($product->stock < $request->quantity) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => __('locale.insufficient_stock', ['product' => $product->name])
                ]);
            }
            return back()->with('error', __('locale.insufficient_stock', ['product' => $product->name]));
        }

        try {
            DB::beginTransaction();

            $cart = Cart::firstOrCreate(
                ['user_id' => Auth::id(), 'is_checkedout' => false],
                ['user_id' => Auth::id()]
            );
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $product->id)
                ->first();

            $totalQuantity = $request->quantity;
            if ($cartItem) {
                $totalQuantity += $cartItem->quantity;
            }

            if ($totalQuantity > $product->stock) {
                DB::rollBack();
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => __('locale.total_quantity_exceeds_stock', [
                            'product' => $product->name,
                            'available' => $product->stock
                        ])
                    ]);
                }
                return back()->with('error', __('locale.total_quantity_exceeds_stock', [
                    'product' => $product->name,
                    'available' => $product->stock
                ]));
            }

            if ($cartItem) {
                $cartItem->quantity = $totalQuantity;
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

            return redirect()->route('cart.index')->with('success', __('locale.product_added_to_cart'));
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => __('locale.error_adding_to_cart') . ': ' . $e->getMessage()
                ]);
            }

            return back()->with('error', __('locale.error_adding_to_cart') . ': ' . $e->getMessage());
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
                ]);
            }
            return back()->with('error', __('locale.unauthorized'));
        }

        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        // Stoktan fazla ürün eklenemez
        if ($request->quantity > $cartItem->product->stock) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => __('locale.insufficient_stock', ['product' => $cartItem->product->name])
                ]);
            }
            return back()->with('error', __('locale.insufficient_stock', ['product' => $cartItem->product->name]));
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        if ($request->ajax()) {
            // Toplamları güncelle
            $cart = $cartItem->cart->load('cartItems.product');
            $cartTotal = 0;
            foreach ($cart->cartItems as $item) {
                if ($item->product) {
                    $cartTotal += $item->product->price * $item->quantity;
                }
            }
            return response()->json([
                'success' => true,
                'item_total' => $cartItem->product->price * $cartItem->quantity,
                'cart_total' => $cartTotal
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
                if ($item->quantity > $item->product->stock) {
                    throw new \Exception(__('locale.insufficient_stock', ['product' => $item->product->name]));
                }
                $totalPrice += $item->product->price * $item->quantity;
            }

            $sale = Sale::create([
                'cart_id' => $cart->id,
                'user_id' => Auth::id(),
                'total_price' => $totalPrice
            ]);

            // Sepet öğelerini satış detaylarına ekle
            foreach ($cart->cartItems as $item) {
                // Sadece SaleDetail tablosuna kaydet (sales_details tablosu)
                $sale->saleDetails()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price * $item->quantity
                ]);

                // Stok azaltma işlemleri
                $this->stockService->decreaseStock($item->product_id, $item->quantity);
            }

            $cart->is_checkedout = true;
            $cart->save();

            DB::commit();
            return redirect()->route('cart.index')->with('success', __('locale.checkout_successful'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
