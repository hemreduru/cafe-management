@extends('adminlte::page')

@section('title', __('locale.new_sale'))

@section('content_header')
    <h1>{{ __('locale.new_sale') }}</h1>
@stop

@section('content')
    <div class="row">
        <!-- Mevcut Satış -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('locale.current_sale') }}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addProductModal">
                            <i class="fas fa-plus"></i> {{ __('locale.add_product') }}
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($cart && $cart->cartItems->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ __('locale.product') }}</th>
                                        <th>{{ __('locale.quantity') }}</th>
                                        <th>{{ __('locale.price') }}</th>
                                        <th>{{ __('locale.total') }}</th>
                                        <th>{{ __('locale.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart->cartItems as $item)
                                        <tr>
                                            <td>{{ $item->product->name }}</td>
                                            <td>
                                                <div class="input-group input-group-sm">
                                                    <button type="button" class="btn btn-default btn-sm quantity-btn" data-action="decrease" data-item-id="{{ $item->id }}">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <input type="number" class="form-control form-control-sm text-center quantity-input"
                                                           value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}"
                                                           data-item-id="{{ $item->id }}">
                                                    <button type="button" class="btn btn-default btn-sm quantity-btn" data-action="increase" data-item-id="{{ $item->id }}">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td>{{ number_format($item->product->price, 2) }} ₺</td>
                                            <td>{{ number_format($item->product->price * $item->quantity, 2) }} ₺</td>
                                            <td>
                                                <form action="{{ route('cart.remove-item', $item) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-right">{{ __('locale.total') }}:</th>
                                        <th colspan="2">{{ number_format($total, 2) }} ₺</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="card-footer">
                            <form action="{{ route('cart.checkout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check"></i> {{ __('locale.complete_sale') }}
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="p-3 text-center">
                            <p class="text-muted">{{ __('locale.empty_cart') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Son Satışlar -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('locale.recent_sales') }}</h3>
                </div>
                <div class="card-body p-0">
                    @if($recentSales->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>{{ __('locale.date') }}</th>
                                        <th>{{ __('locale.total') }}</th>
                                        <th>{{ __('locale.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentSales as $sale)
                                        <tr>
                                            <td>{{ $sale->created_at->format('d.m.Y H:i') }}</td>
                                            <td>{{ number_format($sale->total_price, 2) }} ₺</td>
                                            <td>
                                                <a href="{{ route('sales.show', $sale) }}" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm delete-sale" data-id="{{ $sale->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-3 text-center">
                            <p class="text-muted">{{ __('locale.no_recent_sales') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document" style="max-width: 98vw;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">{{ __('locale.add_product') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" id="productSearch" placeholder="{{ __('locale.search_products') }}">
                    </div>
                    <div class="row flex-wrap justify-content-center" id="categoryAccordion" style="gap: 0.5rem;">
                        @foreach($categories as $i => $category)
                            <div class="col-5 col-md-5 mb-3 d-flex align-items-stretch">
                                <div class="card w-100">
                                    <div class="card-header p-2 category-header d-flex align-items-center" id="headingCat{{ $category->id }}" style="background: linear-gradient(90deg, #232526 0%, #414345 100%); border-radius: 8px 8px 0 0; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                                        @php
                                            $shapes = ['square', 'triangle', 'circle'];
                                            $shape = $shapes[$i % 3];
                                            $colors = ['#ff7675', '#74b9ff', '#55efc4', '#fdcb6e', '#a29bfe', '#fab1a0', '#00b894', '#fd79a8'];
                                            $color = $colors[$i % count($colors)];
                                        @endphp
                                        <span class="category-shape-icon mr-2" style="display:inline-block; width:28px; height:28px; background:{{ $shape == 'circle' ? $color : 'transparent' }}; border-radius:{{ $shape == 'circle' ? '50%' : '4px' }}; position:relative; vertical-align:middle;">
                                            @if($shape == 'square')
                                                <span style="display:block; width:100%; height:100%; background:{{ $color }}; border-radius:4px;"></span>
                                            @elseif($shape == 'triangle')
                                                <span style="display:block; width:0; height:0; border-left:14px solid transparent; border-right:14px solid transparent; border-bottom:28px solid {{ $color }}; position:absolute; left:0; top:0;"></span>
                                            @endif
                                        </span>
                                        <button class="btn btn-link w-100 text-left text-white font-weight-bold category-header-btn" type="button" data-toggle="collapse" data-target="#collapseCat{{ $category->id }}" aria-expanded="false" aria-controls="collapseCat{{ $category->id }}" style="font-size: 1.15rem; letter-spacing: 0.5px; text-shadow: 0 2px 8px rgba(0,0,0,0.25); background: transparent;">
                                            {{ $category->name }}
                                        </button>
                                    </div>
                                    <div id="collapseCat{{ $category->id }}" class="collapse" aria-labelledby="headingCat{{ $category->id }}" data-parent="#categoryAccordion">
                                        <div class="card-body p-2">
                                            <div class="row">
                                                @forelse($category->products as $product)
                                                    <div class="col-md-12 mb-3 product-item" data-name="{{ strtolower($product->name) }}">
                                                        <div class="card h-100 product-card-bg add-to-cart-trigger"
                                                            style="background: url('{{ $product->image ? asset('storage/' . $product->image) : asset('storage/products/1.jpg') }}') center center/cover no-repeat; min-height: 140px; position: relative; cursor:pointer;"
                                                            data-product-id="{{ $product->id }}"
                                                            data-product-name="{{ $product->name }}"
                                                            data-product-price="{{ $product->price }}"
                                                            data-product-stock="{{ $product->stock }}">
                                                            <div class="card-body p-2 product-card-content" style="background: rgba(0,0,0,0.75); border-radius: 0 0 8px 8px; position: absolute; left: 0; bottom: 0; width: 100%; z-index: 2; color: #fff;">
                                                                <h5 class="card-title mb-1" style="font-weight:bold; font-size:1.1rem; margin-bottom:0.25rem;">{{ $product->name }}</h5>
                                                                <p class="card-text mb-0" style="font-size:0.95rem;">
                                                                    <strong>{{ number_format($product->price, 2) }} ₺</strong>
                                                                    <small class="text-white">({{ __('locale.stock') }}: {{ $product->stock }})</small>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <div class="col-12 text-muted">{{ __('locale.no_products') }}</div>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('locale.close') }}</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .modal-xl {
            max-width: 98vw !important;
        }
        #categoryAccordion {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            justify-content: center;
        }
        #categoryAccordion > .col-5.col-md-5 {
            flex: 0 0 32%;
            max-width: 32%;
            min-width: 320px;
            box-sizing: border-box;
        }
        @media (max-width: 1200px) {
            #categoryAccordion > .col-5.col-md-5 {
                flex: 0 0 48%;
                max-width: 48%;
            }
        }
        @media (max-width: 767.98px) {
            #categoryAccordion > .col-5.col-md-5 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
    </style>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Ürün arama
    let searchTimeout;
    let lastQuery = '';
    $('#productSearch').on('input', function() {
        clearTimeout(searchTimeout);
        var input = $(this);
        var value = input.val().toLocaleLowerCase('tr-TR');
        searchTimeout = setTimeout(function() {
            // Eğer arada yeni bir sorgu geldiyse, bu sorguyu iptal et ve yenisini bekle
            if (value !== lastQuery) {
                lastQuery = value;
                $('.product-item').each(function() {
                    var productName = $(this).data('name').toLocaleLowerCase('tr-TR');
                    if (productName.includes(value)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }
        }, 300);
    });

    // Satış silme
    $('.delete-sale').click(function() {
        var button = $(this);
        var saleId = button.data('id');

        if (confirm('{{ __("locale.confirm_delete") }}')) {
            $.ajax({
                url: '/sales/' + saleId,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        button.closest('tr').fadeOut(300, function() {
                            $(this).remove();
                        });
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('{{ __("locale.error_occurred") }}');
                }
            });
        }
    });

    // Miktar güncelleme
    $('.quantity-btn').click(function() {
        var button = $(this);
        var action = button.data('action');
        var itemId = button.data('item-id');
        var input = $('.quantity-input[data-item-id="' + itemId + '"]');
        var currentValue = parseInt(input.val());
        var maxValue = parseInt(input.attr('max'));

        if (action === 'increase' && currentValue < maxValue) {
            updateQuantity(itemId, currentValue + 1);
        } else if (action === 'decrease' && currentValue > 1) {
            updateQuantity(itemId, currentValue - 1);
        }
    });

    $('.quantity-input').change(function() {
        var input = $(this);
        var itemId = input.data('item-id');
        var quantity = parseInt(input.val());
        var maxValue = parseInt(input.attr('max'));

        if (isNaN(quantity) || quantity < 1) {
            input.val(1);
            quantity = 1;
        } else if (quantity > maxValue) {
            input.val(maxValue);
            quantity = maxValue;
        }
        updateQuantity(itemId, quantity);
    });

    function updateQuantity(itemId, quantity) {
        $.ajax({
            url: '/cart/items/' + itemId,
            method: 'PATCH',
            data: {
                _token: '{{ csrf_token() }}',
                quantity: quantity
            },
            success: function(response) {
                if (response.success) {
                    // Sadece satırı güncelle, sayfayı yenileme
                    var row = $('.quantity-input[data-item-id="' + itemId + '"]').closest('tr');
                    row.find('td:eq(3)').text((response.item_total).toFixed(2) + ' ₺');
                    // Toplamı güncelle
                    if (response.cart_total !== undefined) {
                        $('tfoot th[colspan="2"]:last').text((response.cart_total).toFixed(2) + ' ₺');
                    }
                    // Input değerini de güncelle
                    $('.quantity-input[data-item-id="' + itemId + '"]').val(quantity);
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert("{{ __('locale.error_occurred') }}");
            }
        });
    }

    // Ürün ekleme (modal içindeki ürün kartına tıklama)
    $('.add-to-cart-trigger').click(function() {
        var productId = $(this).data('product-id');
        var maxStock = $(this).data('product-stock') || 1;
        var quantity = 1; // Varsayılan olarak 1 adet eklenir

        $.ajax({
            url: '/cart/add/' + productId,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                quantity: quantity
            },
            success: function(response) {
                if (response.success) {
                    $('#addProductModal').modal('hide');
                    // Sayfayı yenile veya bildirim göster
                    location.reload();
                } else {
                    alert(response.message || 'Hata oluştu');
                }
            },
            error: function(xhr) {
                alert("{{ __('locale.error_occurred') }}");
            }
        });
    });
});
</script>
@stop
