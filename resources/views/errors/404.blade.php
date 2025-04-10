@extends('errors::minimal')

@section('title', __('locale.not_found'))
@section('code', '404')
@section('message', __('locale.not_found_message'))

@section('content_header')
    <h1>{{ __('locale.error_404') }}</h1>
@stop

@section('content')
<div class="error-page">
    <h2 class="headline text-warning">404</h2>
    <div class="error-content">
        <h3>
            <i class="fas fa-exclamation-triangle text-warning"></i> 
            {{ __('locale.error_404') }}
        </h3>
        <p>{{ __('locale.error_404') }}</p>
        <a href="{{ route('dashboard') }}" class="btn btn-primary">{{ __('locale.back') }}</a>
    </div>
</div>
@stop