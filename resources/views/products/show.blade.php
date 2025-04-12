@extends('adminlte::page')

@section('title', __('locale.product_details'))

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>{{ __('locale.product_details') }}</h1>
        <div>
            <a href="{{ route('products.edit', $product) }}" class="btn btn-info mr-2">
                <i class="fas fa-edit"></i> {{ __('locale.edit') }}
            </a>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> {{ __('locale.back') }}
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $product->name }}</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>{{ __('locale.name') }}:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $product->name }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>{{ __('locale.category') }}:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $product->category->name ?? '-' }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>{{ __('locale.description') }}:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $product->description ?? '-' }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>{{ __('locale.price') }}:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ number_format($product->price, 2) }} TL
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>{{ __('locale.stock') }}:</strong>
                        </div>
                        <div class="col-md-8">
                            <span class="badge badge-{{ $product->stock > 0 ? 'success' : 'danger' }}">
                                {{ $product->stock }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>{{ __('locale.created_at') }}:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $product->created_at->format('d.m.Y H:i') }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>{{ __('locale.updated_at') }}:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $product->updated_at->format('d.m.Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('locale.stock_management') }}</h3>
                </div>
                <div class="card-body">
                    <p class="mb-2">{{ __('locale.current_stock') }}: <strong>{{ $product->stock }}</strong></p>

                    <div class="d-flex justify-content-between mb-3 mt-4">
                        <a href="#" class="btn btn-success update-stock mr-2" data-toggle="modal" data-target="#increaseStockModal">
                            <i class="fas fa-plus-circle"></i> {{ __('locale.increase_stock') }}
                        </a>
                        <a href="#" class="btn btn-danger update-stock" data-toggle="modal" data-target="#decreaseStockModal">
                            <i class="fas fa-minus-circle"></i> {{ __('locale.decrease_stock') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Increase Stock Modal -->
    <div class="modal fade" id="increaseStockModal" tabindex="-1" role="dialog" aria-labelledby="increaseStockModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('products.increase-stock', $product) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="increaseStockModalLabel">{{ __('locale.increase_stock') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="quantity">{{ __('locale.quantity') }}</label>
                            <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('locale.close') }}</button>
                        <button type="submit" class="btn btn-success">{{ __('locale.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Decrease Stock Modal -->
    <div class="modal fade" id="decreaseStockModal" tabindex="-1" role="dialog" aria-labelledby="decreaseStockModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('products.decrease-stock', $product) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="decreaseStockModalLabel">{{ __('locale.decrease_stock') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="quantity">{{ __('locale.quantity') }}</label>
                            <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('locale.close') }}</button>
                        <button type="submit" class="btn btn-danger">{{ __('locale.save') }}</button>
                    </div>
                </form>
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
    </style>
@stop
