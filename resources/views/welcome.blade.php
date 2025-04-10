@extends('adminlte::page')

@section('title', __('locale.welcome'))

@section('content_header')
    <h1>{{ __('locale.welcome') }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ __('locale.welcome_to_cafe_management') }}</h5>
                    <p class="card-text">{{ __('locale.welcome_description') }}</p>
                    
                    @if (Route::has('login'))
                        <div class="mt-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="btn btn-primary">{{ __('locale.dashboard') }}</a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary">{{ __('locale.login') }}</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="btn btn-success">{{ __('locale.register') }}</a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        console.log('Hi!');
    </script>
@stop
