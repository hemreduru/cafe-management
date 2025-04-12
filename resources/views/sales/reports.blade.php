@extends('adminlte::page')

@section('title', __('locale.sales_reports'))

@section('content_header')
    <h1>{{ __('locale.sales_reports') }}</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('locale.sales_reports') }}</h3>
                    <div class="card-tools">
                        <form action="{{ route('sales.export') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="period" value="{{ $period }}">
                            @if($period == 'custom')
                                <input type="hidden" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
                                <input type="hidden" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
                            @endif
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-file-excel"></i> {{ __('locale.export_excel') }}
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Tarih Filtresi -->
                    <form action="{{ route('sales.reports') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="period">{{ __('locale.date_range') }}</label>
                                    <select name="period" id="period" class="form-control" onchange="toggleCustomDateRange(this.value)">
                                        <option value="today" {{ $period == 'today' ? 'selected' : '' }}>{{ __('locale.today') }}</option>
                                        <option value="yesterday" {{ $period == 'yesterday' ? 'selected' : '' }}>{{ __('locale.yesterday') }}</option>
                                        <option value="last_week" {{ $period == 'last_week' ? 'selected' : '' }}>{{ __('locale.last_week') }}</option>
                                        <option value="last_month" {{ $period == 'last_month' ? 'selected' : '' }}>{{ __('locale.last_month') }}</option>
                                        <option value="last_3months" {{ $period == 'last_3months' ? 'selected' : '' }}>{{ __('locale.last_3months') }}</option>
                                        <option value="last_6months" {{ $period == 'last_6months' ? 'selected' : '' }}>{{ __('locale.last_6months') }}</option>
                                        <option value="last_year" {{ $period == 'last_year' ? 'selected' : '' }}>{{ __('locale.last_year') }}</option>
                                        <option value="custom" {{ $period == 'custom' ? 'selected' : '' }}>{{ __('locale.custom') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 custom-date-range" style="display: {{ $period == 'custom' ? 'block' : 'none' }}">
                                <div class="form-group">
                                    <label for="start_date">{{ __('locale.start_date') }}</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="col-md-3 custom-date-range" style="display: {{ $period == 'custom' ? 'block' : 'none' }}">
                                <div class="form-group">
                                    <label for="end_date">{{ __('locale.end_date') }}</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-block">{{ __('locale.filter') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Genel İstatistikler -->
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ number_format($totalSales, 2) }} ₺</h3>
                                    <p>{{ __('locale.total_sales') }}</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ number_format($totalItems) }}</h3>
                                    <p>{{ __('locale.total_items') }}</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-box"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ number_format($totalOrders) }}</h3>
                                    <p>{{ __('locale.total_orders') }}</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-receipt"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ number_format($averageOrderValue, 2) }} ₺</h3>
                                    <p>{{ __('locale.average_order_value') }}</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Günlük Ortalamalar -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-calendar-day"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ __('locale.average_daily_sales') }}</span>
                                    <span class="info-box-number">{{ number_format($averageDailySales, 2) }} ₺</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-shopping-basket"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ __('locale.average_daily_orders') }}</span>
                                    <span class="info-box-number">{{ number_format($averageDailyOrders, 1) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-box"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ __('locale.average_items_per_order') }}</span>
                                    <span class="info-box-number">{{ number_format($averageItemsPerOrder, 1) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Grafikler -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">{{ __('locale.daily_sales') }}</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="dailySalesChart" style="min-height: 300px;"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">{{ __('locale.category_sales') }}</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="categorySalesChart" style="min-height: 300px;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kategori Bazlı Satışlar -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('locale.category_sales') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>{{ __('locale.category') }}</th>
                                            <th>{{ __('locale.order_count') }}</th>
                                            <th>{{ __('locale.total_quantity') }}</th>
                                            <th>{{ __('locale.total_amount') }}</th>
                                            <th>{{ __('locale.average_price') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($categorySales as $category)
                                        <tr>
                                            <td>{{ $category['category'] }}</td>
                                            <td>{{ number_format($category['order_count']) }}</td>
                                            <td>{{ number_format($category['total_quantity']) }}</td>
                                            <td>{{ number_format($category['total_amount'], 2) }} ₺</td>
                                            <td>{{ number_format($category['average_price'], 2) }} ₺</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- En Çok Satan Ürünler -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('locale.top_selling_products') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>{{ __('locale.product') }}</th>
                                            <th>{{ __('locale.category') }}</th>
                                            <th>{{ __('locale.total_quantity') }}</th>
                                            <th>{{ __('locale.total_amount') }}</th>
                                            <th>{{ __('locale.average_price') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topSellingProducts as $product)
                                        <tr>
                                            <td>{{ $product['product'] }}</td>
                                            <td>{{ $product['category'] }}</td>
                                            <td>{{ number_format($product['total_quantity']) }}</td>
                                            <td>{{ number_format($product['total_amount'], 2) }} ₺</td>
                                            <td>{{ number_format($product['average_price'], 2) }} ₺</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- En Çok Kazandıran Ürünler -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('locale.top_earning_products') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>{{ __('locale.product') }}</th>
                                            <th>{{ __('locale.category') }}</th>
                                            <th>{{ __('locale.total_quantity') }}</th>
                                            <th>{{ __('locale.total_amount') }}</th>
                                            <th>{{ __('locale.average_price') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topEarningProducts as $product)
                                        <tr>
                                            <td>{{ $product['product'] }}</td>
                                            <td>{{ $product['category'] }}</td>
                                            <td>{{ number_format($product['total_quantity']) }}</td>
                                            <td>{{ number_format($product['total_amount'], 2) }} ₺</td>
                                            <td>{{ number_format($product['average_price'], 2) }} ₺</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function toggleCustomDateRange(value) {
    const customDateRange = document.querySelectorAll('.custom-date-range');
    customDateRange.forEach(el => {
        el.style.display = value === 'custom' ? 'block' : 'none';
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Tarih filtresi değiştiğinde
    document.getElementById('period').addEventListener('change', function() {
        toggleCustomDateRange(this.value);
    });

    // Günlük satışlar grafiği
    const dailySalesCtx = document.getElementById('dailySalesChart').getContext('2d');
    new Chart(dailySalesCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($dailySales->pluck('date')) !!},
            datasets: [{
                label: '{{ __("locale.daily_sales") }}',
                data: {!! json_encode($dailySales->pluck('total_amount')) !!},
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Kategori satışları grafiği
    const categorySalesCtx = document.getElementById('categorySalesChart').getContext('2d');
    new Chart(categorySalesCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($categorySales->pluck('category')) !!},
            datasets: [{
                label: '{{ __("locale.category_sales") }}',
                data: {!! json_encode($categorySales->pluck('total_amount')) !!},
                backgroundColor: 'rgb(75, 192, 192)'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush