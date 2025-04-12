<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $sales = Sale::with(['saleDetails.product', 'user'])
                ->orderBy('created_at', 'desc');

            // Tarih filtresi
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $sales->whereBetween('created_at', [
                    $request->start_date . ' 00:00:00',
                    $request->end_date . ' 23:59:59'
                ]);
            }

            return DataTables::of($sales)
                ->addColumn('total_items', function($sale) {
                    return $sale->saleDetails->sum('quantity');
                })
                ->addColumn('action', function($sale) {
                    return '<a href="'.route('sales.show', $sale).'" class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i> '.__('locale.view').'
                    </a>';
                })
                ->editColumn('total_price', function($sale) {
                    return number_format($sale->total_price, 2) . ' ₺';
                })
                ->editColumn('created_at', function($sale) {
                    return $sale->created_at->format('d.m.Y H:i');
                })
                ->addColumn('user_name', function($sale) {
                    return $sale->user ? $sale->user->name : '';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('sales.index');
    }

    public function show(Sale $sale)
    {
        $sale->load(['saleDetails.product', 'user']);
        return view('sales.show', compact('sale'));
    }

    public function reports(Request $request)
    {
        $query = Sale::with(['saleDetails.product', 'user']);

        // Tarih filtresi
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        // Toplam satış
        $totalSales = $query->sum('total_price');

        // Toplam ürün sayısı
        $totalItems = SaleDetail::whereIn('sale_id', $query->pluck('id'))->sum('quantity');

        // En çok satan ürünler
        $topProducts = SaleDetail::whereIn('sale_id', $query->pluck('id'))
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id')
            ->with('product')
            ->orderBy('total_quantity', 'desc')
            ->take(5)
            ->get();

        return view('sales.reports', compact('totalSales', 'totalItems', 'topProducts'));
    }
}
