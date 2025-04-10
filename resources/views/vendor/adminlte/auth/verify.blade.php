@extends('adminlte::auth.auth-page', ['authType' => 'verify'])

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@stop

@php
    $verifyUrl = View::getSection('verify_url') ?? config('adminlte.verify_url', 'verification.notice');
    $verifySendUrl = View::getSection('verify_send_url') ?? config('adminlte.verify_send_url', 'verification.send');
    $verifyResendUrl = View::getSection('verify_resend_url') ?? config('adminlte.verify_resend_url', 'verification.resend');
    $verifyEmailUrl = View::getSection('verify_email_url') ?? config('adminlte.verify_email_url', 'verification.verify');
    $verifyLogoutUrl = View::getSection('verify_logout_url') ?? config('adminlte.verify_logout_url', 'verification.logout');

    if (config('adminlte.use_route_url', false)) {
        $verifyUrl = $verifyUrl ? route($verifyUrl) : '';
        $verifySendUrl = $verifySendUrl ? route($verifySendUrl) : '';
        $verifyResendUrl = $verifyResendUrl ? route($verifyResendUrl) : '';
        $verifyEmailUrl = $verifyEmailUrl ? route($verifyEmailUrl) : '';
        $verifyLogoutUrl = $verifyLogoutUrl ? route($verifyLogoutUrl) : '';
    } else {
        $verifyUrl = $verifyUrl ? url($verifyUrl) : '';
        $verifySendUrl = $verifySendUrl ? url($verifySendUrl) : '';
        $verifyResendUrl = $verifyResendUrl ? url($verifyResendUrl) : '';
        $verifyEmailUrl = $verifyEmailUrl ? url($verifyEmailUrl) : '';
        $verifyLogoutUrl = $verifyLogoutUrl ? url($verifyLogoutUrl) : '';
    }
@endphp

@section('auth_header', __('locale.verify_message'))

@section('auth_body')
    @if(session('resent'))
        <div class="alert alert-success">
            {{ __('locale.verification_link_sent') }}
        </div>
    @endif

    {{ __('locale.check_your_email') }}
    <form action="{{ $verifyResendUrl }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">
            {{ __('locale.click_here_to_request_another') }}
        </button>
    </form>
@stop

@section('auth_footer')
    <form action="{{ $verifyLogoutUrl }}" method="POST" class="d-none" id="logout-form">
        @csrf
    </form>
    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        {{ __('locale.logout') }}
    </a>
@stop
