<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SaleController extends Controller
{
    /**
     * Satış listesini görüntüle
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $sales = Sale::with(['user', 'saleDetails.product']);
            
            return DataTables::of($sales)
                ->addColumn('action', function($sale) {
                    return view('sales.action', compact('sale'))->render();
                })
                ->editColumn('created_at', function($sale) {
                    return $sale->created_at->format('d.m.Y H:i');
                })
                ->editColumn('total_price', function($sale) {
                    return number_format($sale->total_price, 2) . ' TL';
                })
                ->editColumn('user_id', function($sale) {
                    return $sale->user ? $sale->user->name : '';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        return view('sales.index');
    }

    /**
     * Satış detaylarını görüntüle
     */
    public function show(Sale $sale)
    {
        $sale->load(['user', 'saleDetails.product']);
        return view('sales.show', compact('sale'));
    }

    /**
     * Satış raporlarını görüntüle
     */
    public function reports(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfDay());
        $endDate = $request->get('end_date', now()->endOfDay());

        $sales = Sale::with(['saleDetails.product'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $totalSales = $sales->sum('total_price');
        $totalItems = $sales->sum(function($sale) {
            return $sale->saleDetails->sum('quantity');
        });

        $topProducts = SaleDetail::with('product')
            ->whereHas('sale', function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->select('product_id', \DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();

        return view('sales.reports', compact('sales', 'totalSales', 'totalItems', 'topProducts', 'startDate', 'endDate'));
    }
} 