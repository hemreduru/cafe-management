@extends('errors::minimal')

@section('title', __('locale.forbidden'))
@section('code', '403')
@section('message', __('locale.forbidden_message'))

@section('content_header')
    <h1>{{ __('locale.error_403') }}</h1>
@stop

@section('content')
<div class="error-page">
    <h2 class="headline text-danger">403</h2>
    <div class="error-content">
        <h3>
            <i class="fas fa-exclamation-triangle text-danger"></i> 
            {{ __('locale.error_403') }}
        </h3>
        <p>{{ __('locale.error_403') }}</p>
        <a href="{{ route('dashboard') }}" class="btn btn-primary">{{ __('locale.back') }}</a>
    </div>
</div>
@stop