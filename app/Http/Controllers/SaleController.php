<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $sales = Sale::with(['user', 'saleDetails.product'])
                        ->orderBy('created_at', 'desc');

            // Tarih aralığı filtreleme
            if ($request->has('start_date') && $request->has('end_date') && $request->start_date && $request->end_date) {
                $start = Carbon::createFromFormat('d.m.Y', $request->start_date)->startOfDay();
                $end = Carbon::createFromFormat('d.m.Y', $request->end_date)->endOfDay();

                $sales->whereBetween('created_at', [$start, $end]);
            }

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
     * Satışı sil
     */
    public function destroy(Sale $sale)
    {
        try {
            DB::beginTransaction();

            // Satış detaylarını sil
            $sale->saleDetails()->delete();
            
            // Satışı sil
            $sale->delete();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => __('locale.sale_deleted_successfully')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => __('locale.error') . ': ' . $e->getMessage()
            ], 500);
        }
    }
}
