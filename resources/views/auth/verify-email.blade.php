@extends('adminlte::auth.verify')

@section('title', __('locale.verify_your_email_address'))

@section('auth_header', __('locale.verify_your_email_address'))

@section('auth_body')
    @if (session('resent'))
        <div class="alert alert-success" role="alert">
            {{ __('locale.verification_link_sent') }}
        </div>
    @endif

    {{ __('locale.verify_email_text') }}
    {{ __('locale.if_not_receive_email') }},
    <form class="d-inline" method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('locale.click_here_resend') }}</button>.
    </form>
@stop

@section('auth_footer')
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-link">
            {{ __('locale.logout') }}
        </button>
    </form>
@stop
