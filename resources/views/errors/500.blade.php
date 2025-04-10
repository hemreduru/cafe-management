@extends('errors::minimal')

@section('title', __('locale.server_error'))
@section('code', '500')
@section('message', __('locale.server_error_message'))

@section('content_header')
    <h1>{{ __('locale.error_500') }}</h1>
@stop

@section('content')
<div class="error-page">
    <h2 class="headline text-danger">500</h2>
    <div class="error-content">
        <h3>
            <i class="fas fa-exclamation-triangle text-danger"></i> 
            {{ __('locale.error_500') }}
        </h3>
        <p>{{ __('locale.error_500') }}</p>
        <a href="{{ route('dashboard') }}" class="btn btn-primary">{{ __('locale.back') }}</a>
    </div>
</div>
@stop