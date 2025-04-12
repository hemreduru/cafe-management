<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    /**
     * Ürün listesini görüntüle
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $products = Product::with('category');
            
            return DataTables::of($products)
                ->addColumn('action', function($product) {
                    return view('products.action', compact('product'))->render();
                })
                ->editColumn('created_at', function($product) {
                    return $product->created_at->format('d.m.Y H:i');
                })
                ->editColumn('price', function($product) {
                    return number_format($product->price, 2) . ' TL';
                })
                ->editColumn('category_id', function($product) {
                    return $product->category ? $product->category->name : '';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        return view('products.index');
    }

    /**
     * Ürün oluşturma formunu göster
     */
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        return redirect()->route('products.index')
            ->with('success', __('locale.product_created_successfully'));
    }

    /**
     * Ürün detayını göster
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Ürün düzenleme formunu göster
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        return redirect()->route('products.index')
            ->with('success', __('locale.product_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            // Ürünün satışlarda veya sepette kullanılıp kullanılmadığını kontrol edebilirsiniz
            $product->delete();
            
            return redirect()->route('products.index')
                ->with('success', __('locale.product_deleted_successfully'));
        } catch (\Exception $e) {
            return redirect()->route('products.index')
                ->with('error', __('locale.error') . ': ' . $e->getMessage());
        }
    }
}
