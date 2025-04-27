<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    /**
     * Kategori listesini görüntüle
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $categories = Category::query();

            return DataTables::of($categories)
                ->addColumn('action', function($category) {
                    return view('categories.action', compact('category'))->render();
                })
                ->editColumn('created_at', function($category) {
                    return $category->created_at->format('d.m.Y H:i');
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('categories.index');
    }

    /**
     * Kategori oluşturma formunu göster
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $data = [
            'name' => $request->name,
        ];
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }
        Category::create($data);
        return redirect()->route('categories.index')
            ->with('success', __('locale.category_created_successfully'));
    }

    /**
     * Kategori düzenleme formunu göster
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $data = [
            'name' => $request->name,
        ];
        if ($request->hasFile('image')) {
            // Eski resmi sil
            if ($category->image) {
                \Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')->store('categories', 'public');
        }
        $category->update($data);
        return redirect()->route('categories.index')
            ->with('success', __('locale.category_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            // Kategori ile ilişkili ürünler olup olmadığını kontrol et
            if ($category->products()->exists()) {
                return redirect()->route('categories.index')
                    ->with('error', __('locale.cannot_delete_category_with_products'));
            }

            $category->delete();

            return redirect()->route('categories.index')
                ->with('success', __('locale.category_deleted_successfully'));
        } catch (\Exception $e) {
            return redirect()->route('categories.index')
                ->with('error', __('locale.error') . ': ' . $e->getMessage());
        }
    }
}
