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
}
