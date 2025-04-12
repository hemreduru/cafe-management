<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Temel sayılar
        $totalUsers = User::count();
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        
        // Satış istatistikleri
        $today = Carbon::now()->startOfDay();
        $todaySales = Sale::whereDate('created_at', $today)->sum('total_price');
        
        $thisWeekStart = Carbon::now()->startOfWeek();
        $thisWeekSales = Sale::where('created_at', '>=', $thisWeekStart)->sum('total_price');
        
        $thisMonthStart = Carbon::now()->startOfMonth();
        $thisMonthSales = Sale::where('created_at', '>=', $thisMonthStart)->sum('total_price');
        
        $totalSales = Sale::sum('total_price');
        
        // Son 7 günün satışları (grafik için)
        $lastSevenDays = Carbon::now()->subDays(6)->startOfDay();
        $dailySales = Sale::where('created_at', '>=', $lastSevenDays)
            ->selectRaw('DATE(created_at) as date, SUM(total_price) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('total', 'date')
            ->toArray();
            
        // Son 7 günün günlerini oluştur
        $salesData = [];
        $labels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $labels[] = Carbon::now()->subDays($i)->format('d.m');
            $salesData[] = $dailySales[$date] ?? 0;
        }
        
        // Son 5 satış
        $recentSales = Sale::with(['user', 'saleDetails.product'])
            ->latest()
            ->take(5)
            ->get();
            
        // Popüler ürünler
        $popularProducts = DB::table('sales_details')
            ->join('products', 'sales_details.product_id', '=', 'products.id')
            ->select('products.id', 'products.name', DB::raw('SUM(sales_details.quantity) as total_quantity'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_quantity', 'desc')
            ->take(5)
            ->get();
        
        // Stok durumu kritik olan ürünler
        $lowStockProducts = Product::where('stock', '<=', 10)
            ->orderBy('stock')
            ->take(5)
            ->get();
            
        return view('dashboard.index', compact(
            'totalUsers', 
            'totalProducts', 
            'totalCategories',
            'todaySales',
            'thisWeekSales',
            'thisMonthSales',
            'totalSales',
            'labels',
            'salesData',
            'recentSales',
            'popularProducts',
            'lowStockProducts'
        ));
    }
}
