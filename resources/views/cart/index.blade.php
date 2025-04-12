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

    <!-- Ürün Ekleme Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('locale.add_product') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" id="productSearch" placeholder="{{ __('locale.search_products') }}">
                    </div>
                    <div class="row" id="productGrid">
                        @foreach($products as $product)
                            <div class="col-md-4 mb-3 product-item" data-name="{{ strtolower($product->name) }}">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $product->name }}</h5>
                                        <p class="card-text">
                                            <small class="text-muted">{{ $product->category->name }}</small>
                                        </p>
                                        <p class="card-text">
                                            <strong>{{ number_format($product->price, 2) }} ₺</strong>
                                            <small class="text-muted">({{ __('locale.stock') }}: {{ $product->stock }})</small>
                                        </p>
                                        <button type="button" class="btn btn-primary btn-sm add-to-cart" 
                                                data-product-id="{{ $product->id }}"
                                                data-product-name="{{ $product->name }}"
                                                data-product-price="{{ $product->price }}"
                                                data-product-stock="{{ $product->stock }}">
                                            <i class="fas fa-plus"></i> {{ __('locale.add_to_cart') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .card {
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
            margin-bottom: 1rem;
        }
        .modal-lg {
            max-width: 90%;
        }
        .product-item .card {
            transition: all 0.3s ease;
        }
        .product-item .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,.1);
        }
    </style>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Ürün arama
    $('#productSearch').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('.product-item').filter(function() {
            $(this).toggle($(this).data('name').indexOf(value) > -1)
        });
    });

    // Sepete ürün ekleme
    $('.add-to-cart').click(function() {
        var button = $(this);
        var productId = button.data('product-id');
        var productName = button.data('product-name');
        var productStock = button.data('product-stock');

        // Miktar seçimi için prompt
        var quantity = prompt("{{ __('locale.enter_quantity') }} ({{ __('locale.max') }}: " + productStock + ")", "1");
        
        if (quantity === null) return;
        
        quantity = parseInt(quantity);
        if (isNaN(quantity) || quantity < 1 || quantity > productStock) {
            alert("{{ __('locale.invalid_quantity') }}");
            return;
        }

        // AJAX ile sepete ekle
        $.ajax({
            url: '/cart/add/' + productId,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                quantity: quantity
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert("{{ __('locale.error_occurred') }}");
            }
        });
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
            input.val(currentValue + 1).trigger('change');
        } else if (action === 'decrease' && currentValue > 1) {
            input.val(currentValue - 1).trigger('change');
        }
    });

    // Miktar input değişikliği
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

        // AJAX ile miktar güncelle
        $.ajax({
            url: '/cart/update/' + itemId,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                quantity: quantity
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert("{{ __('locale.error_occurred') }}");
            }
        });
    });
});
</script>
@stop