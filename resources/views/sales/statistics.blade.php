@extends('adminlte::page')

@section('title', __('locale.sales_statistics'))

@section('content_header')
    <h1>{{ __('locale.sales_statistics') }}</h1>
@stop

@section('content')
    <div class="card">

        <div class="card-body">
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
            <table id="sales-table" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Ürün</th>
                        <th>Toplam Satış (Adet)</th>
                        <th>Toplam Ciro</th>
                        <th>İlk Satış</th>
                        <th>Son Satış</th>
                        <th>Detay</th>
                    </tr>
                </thead>
            </table>

            <!-- Detay Modalı -->
            <div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Ürün Satış Detayları</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="row mb-3">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="modal_start_date">Başlangıç Tarihi</label>
                          <div class="input-group date" id="modalStartDateGroup">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            <input type="text" id="modal_start_date" class="form-control" placeholder="Başlangıç Tarihi" autocomplete="off" readonly data-toggle="datetimepicker" data-target="#modalStartDateGroup">
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="modal_end_date">Bitiş Tarihi</label>
                          <div class="input-group date" id="modalEndDateGroup">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            <input type="text" id="modal_end_date" class="form-control" placeholder="Bitiş Tarihi" autocomplete="off" readonly data-toggle="datetimepicker" data-target="#modalEndDateGroup">
                          </div>
                        </div>
                      </div>
                    </div>
                    <table class="table table-sm table-bordered" id="details-table">
                      <thead>
                        <tr>
                          <th>Tarih</th>
                          <th>Adet</th>
                          <th>Birim Fiyat</th>
                          <th>Tutar</th>
                        </tr>
                      </thead>
                      <tbody></tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
@stop

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css" />
@stop

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js"></script>
<script>
$(function() {
    // Tarih seçiciler (readonly, sadece takvimden seçim)
    $('#startDateGroup').datetimepicker({
        format: 'YYYY-MM-DD',
        useCurrent: false,
        icons: { time: 'far fa-clock' }
    });
    $('#endDateGroup').datetimepicker({
        format: 'YYYY-MM-DD',
        useCurrent: false,
        icons: { time: 'far fa-clock' }
    });
    $('#startDateGroup').on('change.datetimepicker', function(e) {
        if(e.date) $('#start_date').val(e.date.format('YYYY-MM-DD'));
        $('#endDateGroup').datetimepicker('minDate', e.date);
    });
    $('#endDateGroup').on('change.datetimepicker', function(e) {
        if(e.date) $('#end_date').val(e.date.format('YYYY-MM-DD'));
        $('#startDateGroup').datetimepicker('maxDate', e.date);
    });

    // input'a manuel yazmayı tamamen engelle
    $('#start_date, #end_date').on('keydown paste', function(e) { e.preventDefault(); });

    var table = $('#sales-table').DataTable({
        processing: true,
        serverSide: true,
        searching: false, // Arama kutusunu kaldır
        ajax: {
            url: '{{ route('sales.statistics.datatable') }}',
            data: function (d) {
                d.start_date = $('#start_date').val();
                d.end_date = $('#end_date').val();
            }
        },
          language: {
                    "url": "{{ asset('js/localization/' . (app()->getLocale() === 'tr' ? 'Turkish.json' : 'English.json')) }}"
                },
        columns: [
            { data: 'product_name', name: 'product_name' },
            { data: 'total_quantity', name: 'total_quantity' },
            { data: 'total_amount', name: 'total_amount' },
            { data: 'first_sale', name: 'first_sale' },
            { data: 'last_sale', name: 'last_sale' },
            { data: null, orderable: false, searchable: false, render: function(data, type, row) {
                return '<button class="btn btn-sm btn-info btn-details" data-id="'+row.product_id+'">Detay</button>';
            }}
        ]
    });

    $('#filterBtn').on('click', function(e) {
        e.preventDefault();
        table.ajax.reload();
    });
    $('#resetFilterBtn').on('click', function(e) {
        e.preventDefault();
        $('#start_date').val('');
        $('#end_date').val('');
        $('#startDateGroup').datetimepicker('clear');
        $('#endDateGroup').datetimepicker('clear');
        table.ajax.reload();
    });

    // Modal tarih picker
    $('#modalStartDateGroup').datetimepicker({
        format: 'YYYY-MM-DD',
        useCurrent: false,
        icons: { time: 'far fa-clock' }
    });
    $('#modalEndDateGroup').datetimepicker({
        format: 'YYYY-MM-DD',
        useCurrent: false,
        icons: { time: 'far fa-clock' }
    });
    $('#modalStartDateGroup').on('change.datetimepicker', function(e) {
        if(e.date) $('#modal_start_date').val(e.date.format('YYYY-MM-DD'));
        $('#modalEndDateGroup').datetimepicker('minDate', e.date);
    });
    $('#modalEndDateGroup').on('change.datetimepicker', function(e) {
        if(e.date) $('#modal_end_date').val(e.date.format('YYYY-MM-DD'));
        $('#modalStartDateGroup').datetimepicker('maxDate', e.date);
    });
    $('#modal_start_date, #modal_end_date').on('keydown paste', function(e) { e.preventDefault(); });

    // Detay butonu
    $('#sales-table').on('click', '.btn-details', function() {
        var productId = $(this).data('id');
        $('#detailsModal').data('product-id', productId);
        $('#modal_start_date').val('');
        $('#modal_end_date').val('');
        $('#modalStartDateGroup').datetimepicker('clear');
        $('#modalEndDateGroup').datetimepicker('clear');
        loadDetails(productId);
        $('#detailsModal').modal('show');
    });
    // Modal filtre
    $('#modalStartDateGroup, #modalEndDateGroup').on('change.datetimepicker', function() {
        var productId = $('#detailsModal').data('product-id');
        loadDetails(productId);
    });
    function loadDetails(productId) {
        var start = $('#modal_start_date').val();
        var end = $('#modal_end_date').val();
        $.getJSON("{{ url('/sales/statistics/details') }}/"+productId, {start_date: start, end_date: end}, function(data) {
            var tbody = $('#details-table tbody');
            tbody.empty();
            if(data.rows.length === 0) {
                tbody.append('<tr><td colspan="4" class="text-center">Kayıt yok</td></tr>');
            } else {
                data.rows.forEach(function(row) {
                    tbody.append('<tr><td>'+row.date+'</td><td>'+row.quantity+'</td><td>'+row.price+'</td><td>'+row.amount+'</td></tr>');
                });
            }
        });
    }
});
</script>
@stop
