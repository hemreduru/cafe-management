@extends('adminlte::page')

@section('title', __('locale.sale_details'))

@section('content_header')
    <h1>{{ __('locale.sale_details') }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('locale.sale_info') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('sales.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> {{ __('locale.back') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <p><strong>{{ __('locale.id') }}:</strong> #{{ $sale->id }}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>{{ __('locale.date') }}:</strong> {{ $sale->created_at->format('d.m.Y H:i') }}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>{{ __('locale.users') }}:</strong> {{ $sale->user->name }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-12 mt-3">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('locale.items') }}</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('locale.product') }}</th>
                                <th>{{ __('locale.quantity') }}</th>
                                <th>{{ __('locale.price') }}</th>
                                <th>{{ __('locale.subtotal') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sale->saleDetails as $index => $detail)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $detail->product->name }}</td>
                                    <td>{{ $detail->quantity }}</td>
                                    <td>{{ number_format($detail->price / $detail->quantity, 2) }} TL</td>
                                    <td>{{ number_format($detail->price, 2) }} TL</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-right"><strong>{{ __('locale.total') }}:</strong></td>
                                <td><strong>{{ number_format($sale->total_price, 2) }} TL</strong></td>
                            </tr>
                        </tfoot>
                    </table>
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
    </style>
@stop