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
            <a href="{{ route('cart.index') }}" class="btn btn-primary btn-lg btn-block">

                <div class="inner">
              <!--   <p>{{ __('locale.total_user2') }} : ( {{ $totalUsers }} ) </p>-->
              <h1> <i class="fas fa-plus"></i> </h1>
                <h1>  {{ __('locale.new_order') }}</h1>

                </div>



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
                    <h3>{{ number_format($todaySales, 2) }} TL</h3>
                    <p>{{ __('locale.bugun_satilan_toplam') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <a href="{{ route('sales.index') }}" class="small-box-footer">
                    {{ __('locale.tum_satis_gecmisi') }} <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
       <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-1"></i>
                        {{ __('locale.son10satis') }}
                    </h3>
                </div>
                <div class="card-body">
                <table class="table table-hover" id="sales-table">
                <thead>
                    <tr>

                        <th>{{ __('locale.date') }}</th>

                        <th>{{ __('locale.total') }}</th>
                        <th>{{ __('locale.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- DataTables will populate this -->
                </tbody>
            </table>
                </div>
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
        $('#sales-table').DataTable({
                processing: true,
                serverSide: true,

                searching: false,      // Arama kutusu yok
                ordering: false,       // Sıralama devre dışı
                info: false,
                ajax: "{{ route('sales.index') }}",
                language: {
                    "url": "{{ asset('js/localization/' . (app()->getLocale() === 'tr' ? 'Turkish.json' : 'English.json')) }}"
                },
                columns: [

                    { data: 'created_at', name: 'created_at' },

                    { data: 'total_price', name: 'total_price' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });

    </script>




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
