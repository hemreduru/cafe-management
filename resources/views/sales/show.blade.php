@extends('adminlte::page')

@section('title', __('locale.sale_details'))

@section('content_header')
    <h1>{{ __('locale.sale_details') }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('locale.sale_info') }}</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>{{ __('locale.date') }}:</strong> {{ $sale->created_at->format('d.m.Y H:i') }}</p>
                    <p><strong>{{ __('locale.total') }}:</strong> {{ number_format($sale->total_price, 2) }} ₺</p>
                </div>
                <div class="col-md-6">
                    <p><strong>{{ __('locale.total_items') }}:</strong> {{ $sale->saleDetails->sum('quantity') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('locale.items') }}</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('locale.product') }}</th>
                            <th>{{ __('locale.quantity') }}</th>
                            <th>{{ __('locale.price') }}</th>
                            <th>{{ __('locale.total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->saleDetails as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->price, 2) }} ₺</td>
                                <td>{{ number_format($item->price * $item->quantity, 2) }} ₺</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-right">{{ __('locale.total') }}:</th>
                            <th>{{ number_format($sale->total_price, 2) }} ₺</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('sales.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('locale.back') }}
        </a>
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