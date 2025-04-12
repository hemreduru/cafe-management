@extends('adminlte::page')

@section('title', __('locale.dashboard'))

@section('content_header')
    <h1>{{ __('locale.dashboard') }}</h1>
@stop

@section('content')
    <!-- Üst İstatistik Kartları -->
    <div class="row">
        <!-- Toplam Kullanıcı Kartı -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalUsers }}</h3>
                    <p>{{ __('locale.total_users') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="#" class="small-box-footer">
                    {{ __('locale.more_info') }} <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Toplam Ürün Kartı -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalProducts }}</h3>
                    <p>{{ __('locale.products') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-box"></i>
                </div>
                <a href="{{ route('products.index') }}" class="small-box-footer">
                    {{ __('locale.more_info') }} <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Toplam Kategori Kartı -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $totalCategories }}</h3>
                    <p>{{ __('locale.categories') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-tags"></i>
                </div>
                <a href="{{ route('categories.index') }}" class="small-box-footer">
                    {{ __('locale.more_info') }} <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Toplam Satışlar Kartı -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ number_format($totalSales, 2) }} TL</h3>
                    <p>{{ __('locale.total_sales') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <a href="{{ route('sales.index') }}" class="small-box-footer">
                    {{ __('locale.more_info') }} <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Satış İstatistikleri Kartı -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-1"></i>
                        {{ __('locale.sales_statistics') }}
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>

        <!-- Hızlı İstatistik Özeti -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-1"></i>
                        {{ __('locale.sales_summary') }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                        <p class="text-success text-xl">
                            <i class="fas fa-calendar-day"></i>
                        </p>
                        <p class="d-flex flex-column text-right">
                            <span class="font-weight-bold">
                                {{ number_format($todaySales, 2) }} TL
                            </span>
                            <span class="text-muted">{{ __('locale.today_sales') }}</span>
                        </p>
                    </div>
                    <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                        <p class="text-primary text-xl">
                            <i class="fas fa-calendar-week"></i>
                        </p>
                        <p class="d-flex flex-column text-right">
                            <span class="font-weight-bold">
                                {{ number_format($thisWeekSales, 2) }} TL
                            </span>
                            <span class="text-muted">{{ __('locale.this_week_sales') }}</span>
                        </p>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="text-warning text-xl">
                            <i class="fas fa-calendar-alt"></i>
                        </p>
                        <p class="d-flex flex-column text-right">
                            <span class="font-weight-bold">
                                {{ number_format($thisMonthSales, 2) }} TL
                            </span>
                            <span class="text-muted">{{ __('locale.this_month_sales') }}</span>
                        </p>
                    </div>
                    <!-- Yeni Sipariş Butonu -->
                    <div class="mt-4">
                        <a href="{{ route('cart.index') }}" class="btn btn-primary btn-lg btn-block">
                            <i class="fas fa-plus"></i> {{ __('locale.new_order') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Son Satışlar -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('locale.recent_sales') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('sales.index') }}" class="btn btn-tool">
                            <i class="fas fa-star"></i> {{ __('locale.view_all') }}
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>{{ __('locale.date') }}</th>
                                    <th>{{ __('locale.users') }}</th>
                                    <th>{{ __('locale.total') }}</th>
                                    <th style="width: 40px">{{ __('locale.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentSales as $sale)
                                    <tr>
                                        <td>{{ $sale->id }}</td>
                                        <td>{{ $sale->created_at->format('d.m.Y H:i') }}</td>
                                        <td>{{ $sale->user->name ?? 'N/A' }}</td>
                                        <td>{{ number_format($sale->total_price, 2) }} TL</td>
                                        <td>
                                            <a href="{{ route('sales.show', $sale) }}" class="btn btn-xs btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">{{ __('locale.no_sales_found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

       <!-- Popüler Ürünler ve Stok Uyarısı -->
                <div class="col-md-6">
                    <div class="row">
                        <!-- Popüler Ürünler -->
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-star mr-1"></i> {{ __('locale.popular_products') }}
                                    </h3>
                                </div>
                                <div class="card-body p-0">
                                    <ul class="products-list product-list-in-card pl-2 pr-2">
                                        @forelse($popularProducts as $product)
                                            <li class="item">
                                                <div class="product-info">
                                                    <a href="{{ route('products.show', $product->id) }}" class="product-title text-info">
                                                        {{ $product->name }}
                                                        <span class="badge badge-info float-right">
                                                            <i class="fas fa-shopping-cart"></i> {{ $product->total_quantity }} {{ __('locale.sold') }}
                                                        </span>
                                                    </a>
                                                </div>
                                            </li>
                                        @empty
                                            <li class="item">
                                                <div class="product-info text-center text-muted">
                                                    <i class="fas fa-exclamation-circle"></i> {{ __('locale.no_sales_found') }}
                                                </div>
                                            </li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stok Uyarısı -->
{{--                <div class="col-md-12 mt-3">--}}
{{--                    <div class="card">--}}
{{--                        <div class="card-header bg-warning">--}}
{{--                            <h3 class="card-title">{{ __('locale.low_stock_warning') }}</h3>--}}
{{--                        </div>--}}
{{--                        <div class="card-body p-0">--}}
{{--                            <ul class="products-list product-list-in-card pl-2 pr-2">--}}
{{--                                @forelse($lowStockProducts as $product)--}}
{{--                                    <li class="item">--}}
{{--                                        <div class="product-info">--}}
{{--                                            <a href="{{ route('products.show', $product->id) }}" class="product-title">--}}
{{--                                                {{ $product->name }}--}}
{{--                                                <span class="badge {{ $product->stock <= 5 ? 'badge-danger' : 'badge-warning' }} float-right">--}}
{{--                                                    {{ $product->stock }} {{ __('locale.in_stock') }}--}}
{{--                                                </span>--}}
{{--                                            </a>--}}
{{--                                        </div>--}}
{{--                                    </li>--}}
{{--                                @empty--}}
{{--                                    <li class="item">--}}
{{--                                        <div class="product-info text-center">--}}
{{--                                            {{ __('locale.all_products_in_stock') }}--}}
{{--                                        </div>--}}
{{--                                    </li>--}}
{{--                                @endforelse--}}
{{--                            </ul>--}}
{{--                        </div>--}}
{{--                        <div class="card-footer text-center">--}}
{{--                            <a href="{{ route('products.index') }}" class="uppercase">--}}
{{--                                {{ __('locale.view_all_products') }}--}}
{{--                            </a>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .small-box {
            transition: transform 0.3s ease;
        }

        .small-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .products-list .item {
            padding: 10px;
            border-bottom: 1px solid rgba(0,0,0,0.1);
        }

        .products-list .item:last-child {
            border-bottom: none;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Satış grafiği
        $(function () {
            var ctx = document.getElementById('salesChart').getContext('2d');
            var salesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($labels),
                    datasets: [{
                        label: '{{ __("locale.daily_sales") }}',
                        data: @json($salesData),
                        borderColor: '#17a2b8',
                        backgroundColor: 'rgba(23, 162, 184, 0.1)',
                        borderWidth: 2,
                        pointBackgroundColor: '#17a2b8',
                        pointRadius: 4,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    var label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('tr-TR', {
                                            style: 'currency',
                                            currency: 'TRY'
                                        }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        },
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value, index, values) {
                                    return value.toFixed(2) + ' TL';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@stop
