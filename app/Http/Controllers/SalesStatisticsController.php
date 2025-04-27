<?php

namespace App\Http\Controllers;

use App\Models\SaleDetail;
use Illuminate\Http\Request;
use App\Models\Product;
use Yajra\DataTables\Facades\DataTables;

class SalesStatisticsController extends Controller
{
    public function index()
    {
        // Toplam satılan ürün adedi
        $totalSales = SaleDetail::sum('quantity');
        // Toplam ciro
        $totalAmount = SaleDetail::sum(\DB::raw('quantity * price'));
        // Son 14 gün için günlük satışlar
        $salesByDay = SaleDetail::selectRaw('DATE(created_at) as date, SUM(quantity) as count, SUM(quantity * price) as total')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->take(14)
            ->get();

        return view('sales.statistics', compact('totalSales', 'totalAmount', 'salesByDay'));
    }

    public function datatable(Request $request)
    {
        $query = SaleDetail::with('product')
            ->selectRaw('product_id, SUM(quantity) as total_quantity, SUM(quantity * price) as total_amount, MIN(created_at) as first_sale, MAX(created_at) as last_sale')
            ->groupBy('product_id');

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        return DataTables::of($query)
            ->addColumn('product_name', function($row) {
                return $row->product ? $row->product->name : '-';
            })
            ->editColumn('total_quantity', function($row) {
                return $row->total_quantity;
            })
            ->editColumn('total_amount', function($row) {
                return number_format($row->total_amount, 2);
            })
            ->editColumn('first_sale', function($row) {
                return $row->first_sale ? date('Y-m-d', strtotime($row->first_sale)) : '-';
            })
            ->editColumn('last_sale', function($row) {
                return $row->last_sale ? date('Y-m-d', strtotime($row->last_sale)) : '-';
            })
            ->make(true);
    }

    public function details(Request $request, $productId)
    {
        $query = SaleDetail::with('product')
            ->where('product_id', $productId);

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        $details = $query->orderBy('created_at', 'desc')->get();

        $rows = $details->map(function($detail) {
            return [
                'date' => $detail->created_at->format('Y-m-d'),
                'quantity' => $detail->quantity,
                'amount' => number_format($detail->quantity * $detail->price, 2),
                'price' => number_format($detail->price, 2),
            ];
        });

        return response()->json(['rows' => $rows]);
    }
}
