@extends('adminlte::auth.auth-page', ['authType' => 'password_reset'])

@php( $password_reset_url = View::getSection('password_reset_url') ?? config('adminlte.password_reset_url', 'password/reset') )

@if (config('adminlte.use_route_url', false))
    @php( $password_reset_url = $password_reset_url ? route($password_reset_url) : '' )
@else
    @php( $password_reset_url = $password_reset_url ? url($password_reset_url) : '' )
@endif

@section('auth_header', __('locale.reset_password'))

@section('auth_body')
    <form action="{{ $password_reset_url }}" method="post">
        @csrf

        {{-- Email field --}}
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" placeholder="{{ __('locale.email') }}" autofocus>

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Password field --}}
        <div class="input-group mb-3">
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                   placeholder="{{ __('locale.password') }}">

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Password confirmation field --}}
        <div class="input-group mb-3">
            <input type="password" name="password_confirmation" class="form-control"
                   placeholder="{{ __('locale.confirm_password') }}">

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>
        </div>

        {{-- Reset password button --}}
        <button type="submit" class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}">
            {{ __('locale.reset_password') }}
        </button>
    </form>
@stop

@section('auth_footer')
    {{-- Password reset link --}}
    @if (config('adminlte.login_url', 'login'))
        <p class="my-0">
            <a href="{{ route(config('adminlte.login_url', 'login')) }}">
                {{ __('locale.i_already_have_a_membership') }}
            </a>
        </p>
    @endif
@stop 