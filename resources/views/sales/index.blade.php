@extends('adminlte::page')

@section('title', __('locale.sales_history'))

@section('content_header')
    <h1>{{ __('locale.sales_history') }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('locale.sales_history') }}</h3>
            <div class="card-tools">
                <a href="{{ route('cart.index') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> {{ __('locale.new_sale') }}
                </a>
            </div>
        </div>
        <div class="card-body">
    <!-- Tarih Seçici (AdminLTE input-group ile) -->
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="form-group">
                <label for="start_date">{{ __('locale.start_date') }}</label>
                <div class="input-group date" id="startDateGroup">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    </div>
                    <input type="text" id="start_date" class="form-control" placeholder="{{ __('locale.start_date') }}" autocomplete="off" readonly data-toggle="datetimepicker" data-target="#startDateGroup">
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="end_date">{{ __('locale.end_date') }}</label>
                <div class="input-group date" id="endDateGroup">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    </div>
                    <input type="text" id="end_date" class="form-control" placeholder="{{ __('locale.end_date') }}" autocomplete="off" readonly data-toggle="datetimepicker" data-target="#endDateGroup">
                </div>
            </div>
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button id="filterBtn" class="btn btn-primary mr-2"><i class="fas fa-filter"></i> {{ __('locale.filter') }}</button>
            <button id="resetFilterBtn" class="btn btn-secondary"><i class="fas fa-undo"></i> {{ __('locale.cancel') }}</button>
        </div>
    </div>
    <!-- DataTable -->
    <table class="table table-hover" id="sales-table">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('locale.date') }}</th>
                <th>{{ __('locale.users') }}</th>
                <th>{{ __('locale.total') }}</th>
                <th>{{ __('locale.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            <!-- DataTables will populate this -->
        </tbody>
    </table>
</div>
    </div>

    <!-- Delete Sale Modal -->
    <div class="modal fade" id="delete-sale-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('locale.confirm_delete') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>{{ __('locale.confirm_delete') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('locale.cancel') }}</button>
                    <button type="button" class="btn btn-danger" id="confirm-delete-sale">{{ __('locale.delete') }}</button>
                </div>
            </div>
        </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css" />
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js"></script>
    <script>
    $(function () {
        // AdminLTE datepicker entegrasyonu
        $('#startDateGroup').datetimepicker({
            format: 'DD.MM.YYYY',
            locale: 'tr',
            icons: { time: 'far fa-clock', date: 'far fa-calendar-alt', up: 'fas fa-arrow-up', down: 'fas fa-arrow-down', previous: 'fas fa-chevron-left', next: 'fas fa-chevron-right', today: 'far fa-calendar-check', clear: 'far fa-trash-alt', close: 'far fa-times-circle' }
        });
        $('#endDateGroup').datetimepicker({
            format: 'DD.MM.YYYY',
            locale: 'tr',
            useCurrent: false,
            icons: { time: 'far fa-clock', date: 'far fa-calendar-alt', up: 'fas fa-arrow-up', down: 'fas fa-arrow-down', previous: 'fas fa-chevron-left', next: 'fas fa-chevron-right', today: 'far fa-calendar-check', clear: 'far fa-trash-alt', close: 'far fa-times-circle' }
        });

        // Tarih seçildiğinde input değerlerini güncelle
        $('#startDateGroup').on('change.datetimepicker', function(e) {
            $('#start_date').val(e.date ? e.date.format('DD.MM.YYYY') : '');
            if (e.date) {
                $('#endDateGroup').datetimepicker('minDate', e.date);
            }
        });

        $('#endDateGroup').on('change.datetimepicker', function(e) {
            $('#end_date').val(e.date ? e.date.format('DD.MM.YYYY') : '');
            if (e.date) {
                $('#startDateGroup').datetimepicker('maxDate', e.date);
            }
        });

        let saleToDelete = null;

        // Initialize DataTable
        var table = $('#sales-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('sales.index') }}",
                data: function (d) {
                    // Tarih filtrelerini DD.MM.YYYY formatında gönder
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                }
            },
              language: {
                    "url": "{{ asset('js/localization/' . (app()->getLocale() === 'tr' ? 'Turkish.json' : 'English.json')) }}"
                },
            columns: [
                { data: 'id', name: 'id', orderable: false },
                { data: 'created_at', name: 'created_at', orderable: false },
                { data: 'user_id', name: 'user_id', orderable: false },
                { data: 'total_price', name: 'total_price', orderable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            dom: 'Brtip'  // Butonları eklemek için
        });

        // Filtreleme butonuna tıklanma durumu
        $('#filterBtn').on('click', function () {
            if ($('#start_date').val() && $('#end_date').val()) {
                table.ajax.reload(); // DataTable'ı yeniden yükle
            }
        });

        // Filtreyi sıfırlama butonuna tıklanma durumu
        $('#resetFilterBtn').on('click', function () {
            // Tarih seçicileri sıfırla
            $('#startDateGroup, #endDateGroup').datetimepicker('clear');
            // Input değerlerini temizle
            $('#start_date, #end_date').val('');
            // DataTable'ı yeniden yükle
            table.ajax.reload();
        });

        // Set sale ID when delete button clicked
        $(document).on('click', '.delete-sale', function () {
            saleToDelete = $(this).data('id');
        });

        // Delete sale when confirmed
        $('#confirm-delete-sale').on('click', function () {
            if (saleToDelete) {
                $.ajax({
                    url: `/sales/${saleToDelete}`,
                    type: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        $('#delete-sale-modal').modal('hide');
                        $('#sales-table').DataTable().ajax.reload();

                        // Show success alert
                        Swal.fire({
                            title: '{{ __("locale.success") }}',
                            text: response.message,
                            icon: 'success',
                            timer: 2000
                        });
                    },
                    error: function (xhr) {
                        $('#delete-sale-modal').modal('hide');

                        // Show error alert
                        Swal.fire({
                            title: '{{ __("locale.error") }}',
                            text: xhr.responseJSON ? xhr.responseJSON.message : '{{ __("locale.error_occurred") }}',
                            icon: 'error'
                        });
                    }
                });
            }
        });
    });
    </script>
@stop
