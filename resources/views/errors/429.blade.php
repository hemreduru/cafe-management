@extends('errors::minimal')

@section('title', __('locale.too_many_requests'))
@section('code', '429')
@section('message', __('locale.too_many_requests_message'))

@section('content_header')
    <h1>{{ __('locale.error_429') }}</h1>
@stop

@section('content')
<div class="error-page">
    <h2 class="headline text-warning">429</h2>
    <div class="error-content">
        <h3>
            <i class="fas fa-exclamation-triangle text-warning"></i> 
            {{ __('locale.error_429') }}
        </h3>
        <p>{{ __('locale.error_429') }}</p>
        <a href="{{ route('dashboard') }}" class="btn btn-primary">{{ __('locale.back') }}</a>
    </div>
</div>
@stop