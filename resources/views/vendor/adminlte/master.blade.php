<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    {{-- Base Meta Tags --}}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Flash Mesajları için Meta Etiketleri --}}
    @if(session('success'))
        <meta name="session-success" content="{{ session('success') }}">
    @endif
    @if(session('error'))
        <meta name="session-error" content="{{ session('error') }}">
    @endif
    @if(session('warning'))
        <meta name="session-warning" content="{{ session('warning') }}">
    @endif
    @if(session('info'))
        <meta name="session-info" content="{{ session('info') }}">
    @endif

    {{-- Custom Meta Tags --}}
    @yield('meta_tags')

    {{-- Title --}}
    <title>
        @yield('title_prefix', config('adminlte.title_prefix', ''))
        @yield('title', config('adminlte.title', 'AdminLTE 3'))
        @yield('title_postfix', config('adminlte.title_postfix', ''))
    </title>

    {{-- Custom stylesheets (pre AdminLTE) --}}
    @yield('adminlte_css_pre')

    {{-- Base Stylesheets (depends on Laravel asset bundling tool) --}}
    @if(config('adminlte.enabled_laravel_mix', false))
        <link rel="stylesheet" href="{{ mix(config('adminlte.laravel_mix_css_path', 'css/app.css')) }}">
    @else
        @switch(config('adminlte.laravel_asset_bundling', false))
            @case('mix')
                <link rel="stylesheet" href="{{ mix(config('adminlte.laravel_css_path', 'css/app.css')) }}">
            @break

            @case('vite')
                @vite([config('adminlte.laravel_css_path', 'resources/css/app.css'), config('adminlte.laravel_js_path', 'resources/js/app.js')])
            @break

            @case('vite_js_only')
                @vite(config('adminlte.laravel_js_path', 'resources/js/app.js'))
            @break

            @default
                <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
                <link rel="stylesheet" href="{{ asset('vendor/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
                <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
                <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.4/css/responsive.bootstrap5.css">

                @if(config('adminlte.google_fonts.allowed', true))
                    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
                @endif
        @endswitch
    @endif

    {{-- Extra Configured Plugins Stylesheets --}}
    @include('adminlte::plugins', ['type' => 'css'])

    {{-- Livewire Styles --}}
    @if(config('adminlte.livewire'))
        @if(intval(app()->version()) >= 7)
            @livewireStyles
        @else
            <livewire:styles />
        @endif
    @endif

    {{-- Custom Stylesheets (post AdminLTE) --}}
    @yield('adminlte_css')

    {{-- Favicon --}}
    @if(config('adminlte.use_ico_only'))
        <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}" />
    @elseif(config('adminlte.use_full_favicon'))
        <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}" />
        <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicons/apple-icon-57x57.png') }}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicons/apple-icon-60x60.png') }}">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicons/apple-icon-72x72.png') }}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicons/apple-icon-76x76.png') }}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicons/apple-icon-114x114.png') }}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicons/apple-icon-120x120.png') }}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicons/apple-icon-144x144.png') }}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicons/apple-icon-152x152.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicons/apple-icon-180x180.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicons/favicon-16x16.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicons/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicons/favicon-96x96.png') }}">
        <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicons/android-icon-192x192.png') }}">
        <link rel="manifest" crossorigin="use-credentials" href="{{ asset('favicons/manifest.json') }}">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="{{ asset('favicon/ms-icon-144x144.png') }}">
    @endif

    {{-- Toastr ve SweetAlert2 için CSS --}}
    <link rel="stylesheet" href="{{ asset('vendor/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/sweetalert2/sweetalert2.min.css') }}">

</head>

<body class="@yield('classes_body')" @yield('body_data')>

    {{-- Body Content --}}
    @yield('body')

    {{-- Base Scripts (depends on Laravel asset bundling tool) --}}
    @if(config('adminlte.enabled_laravel_mix', false))
        <script src="{{ mix(config('adminlte.laravel_mix_js_path', 'js/app.js')) }}"></script>
    @else
        @switch(config('adminlte.laravel_asset_bundling', false))
            @case('mix')
                <script src="{{ mix(config('adminlte.laravel_js_path', 'js/app.js')) }}"></script>
            @break

            @case('vite')
            @case('vite_js_only')
            @break

            @default
                <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
                <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
                <script src="{{ asset('vendor/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
                <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
                <script src="https://cdn.datatables.net/responsive/3.0.4/js/dataTables.responsive.js"></script>
        @endswitch
    @endif

    {{-- Extra Configured Plugins Scripts --}}
    @include('adminlte::plugins', ['type' => 'js'])

    {{-- Livewire Script --}}
    @if(config('adminlte.livewire'))
        @if(intval(app()->version()) >= 7)
            @livewireScripts
        @else
            <livewire:scripts />
        @endif
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('darkmode', false))
                document.body.classList.add('dark-mode');
                const widgetIcon = document.querySelector('li.adminlte-darkmode-widget i');
                if (widgetIcon) {
                    widgetIcon.classList.remove('fa-sun');
                    widgetIcon.classList.add('fa-moon');
                }
            @endif
        });
    </script>

    {{-- Custom Scripts --}}
    @yield('adminlte_js')

    {{-- SweetAlert2 ve Toastr JavaScript --}}
    <script src="{{ asset('vendor/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>

    {{-- Çeviriler ve flash mesajları için global değişkenler --}}
    <script>
        window.trans = window.trans || {};
        window.flashMessages = window.flashMessages || {};

        @if(isset($jsTranslations) && is_array($jsTranslations))
            @foreach($jsTranslations as $key => $value)
                window.trans.{{ $key }} = "{{ $value }}";
            @endforeach
        @endif

        @if(isset($jsFlashMessages) && is_array($jsFlashMessages))
            @foreach($jsFlashMessages as $type => $message)
                window.flashMessages.{{ $type }} = "{{ $message }}";
            @endforeach
        @endif
    </script>

    {{-- SweetAlert ve Toastr için genel ayarlar --}}
    <script>
        // Toastr ayarları
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": true,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        // Session mesajlarını Toastr ile gösterme
        document.addEventListener('DOMContentLoaded', function() {
            // Success mesajları
            const successMsg = document.querySelector('meta[name="session-success"]');
            if (successMsg) {
                toastr.success(successMsg.getAttribute('content'));
            }

            // Error mesajları
            const errorMsg = document.querySelector('meta[name="session-error"]');
            if (errorMsg) {
                toastr.error(errorMsg.getAttribute('content'));
            }

            // Warning mesajları
            const warningMsg = document.querySelector('meta[name="session-warning"]');
            if (warningMsg) {
                toastr.warning(warningMsg.getAttribute('content'));
            }

            // Info mesajları
            const infoMsg = document.querySelector('meta[name="session-info"]');
            if (infoMsg) {
                toastr.info(infoMsg.getAttribute('content'));
            }

            // SweetAlert2 ile form onayları
            document.querySelectorAll('form[data-confirm]').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    let title, text, confirmButtonText, cancelButtonText, icon;
                    const confirmType = this.getAttribute('data-confirm');
                    
                    if (confirmType === 'delete') {
                        title = "{{ __('locale.are_you_sure_delete') ?? 'Silmek istediğinize emin misiniz?' }}";
                        text = "{{ __('locale.delete_warning') ?? 'Bu işlemi geri alamazsınız!' }}";
                        confirmButtonText = "{{ __('locale.yes_delete') ?? 'Evet, sil!' }}";
                        cancelButtonText = "{{ __('locale.cancel') ?? 'İptal' }}";
                        icon = 'warning';
                    } else {
                        title = "{{ __('locale.are_you_sure') ?? 'Emin misiniz?' }}";
                        text = "{{ __('locale.action_warning') ?? 'Bu işlemi gerçekleştirmek istediğinize emin misiniz?' }}";
                        confirmButtonText = "{{ __('locale.confirm') ?? 'Onayla' }}";
                        cancelButtonText = "{{ __('locale.cancel') ?? 'İptal' }}";
                        icon = 'question';
                    }

                    Swal.fire({
                        title: title,
                        text: text,
                        icon: icon,
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: confirmButtonText,
                        cancelButtonText: cancelButtonText
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });
            });

            // AJAX işlemleri için Toastr ve SweetAlert2 entegrasyonu
            $(document).on('click', '.ajax-action', function(e) {
                e.preventDefault();
                
                const url = $(this).data('url');
                const method = $(this).data('method') || 'POST';
                const confirmType = $(this).data('confirm');
                const successMessage = $(this).data('success-message') || 'İşlem başarıyla tamamlandı.';
                const errorMessage = $(this).data('error-message') || 'İşlem sırasında bir hata oluştu.';
                
                if (confirmType) {
                    let title, text, confirmButtonText, cancelButtonText, icon;
                    
                    if (confirmType === 'delete') {
                        title = "{{ __('locale.are_you_sure_delete') ?? 'Silmek istediğinize emin misiniz?' }}";
                        text = "{{ __('locale.delete_warning') ?? 'Bu işlemi geri alamazsınız!' }}";
                        confirmButtonText = "{{ __('locale.yes_delete') ?? 'Evet, sil!' }}";
                        cancelButtonText = "{{ __('locale.cancel') ?? 'İptal' }}";
                        icon = 'warning';
                    } else {
                        title = "{{ __('locale.are_you_sure') ?? 'Emin misiniz?' }}";
                        text = "{{ __('locale.action_warning') ?? 'Bu işlemi gerçekleştirmek istediğinize emin misiniz?' }}";
                        confirmButtonText = "{{ __('locale.confirm') ?? 'Onayla' }}";
                        cancelButtonText = "{{ __('locale.cancel') ?? 'İptal' }}";
                        icon = 'question';
                    }

                    Swal.fire({
                        title: title,
                        text: text,
                        icon: icon,
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: confirmButtonText,
                        cancelButtonText: cancelButtonText
                    }).then((result) => {
                        if (result.isConfirmed) {
                            sendAjaxRequest(url, method, successMessage, errorMessage);
                        }
                    });
                } else {
                    sendAjaxRequest(url, method, successMessage, errorMessage);
                }
            });

            function sendAjaxRequest(url, method, successMessage, errorMessage) {
                $.ajax({
                    url: url,
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        toastr.success(response.message || successMessage);
                        
                        // DataTables'ı yeniden çizmek için
                        if ($.fn.DataTable) {
                            $('.dataTable').DataTable().ajax.reload();
                        }
                        
                        // Başarılı bir işlem sonrası sayfa yenileme gerekiyorsa
                        if (response.reload) {
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        }
                        
                        // Yönlendirme gerekiyorsa
                        if (response.redirect) {
                            setTimeout(function() {
                                window.location.href = response.redirect;
                            }, 1000);
                        }
                    },
                    error: function(xhr) {
                        let message = errorMessage;
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        toastr.error(message);
                    }
                });
            }
        });
    </script>

</body>

</html>
