@extends('adminlte::page')

@section('title', __('locale.sales_history'))

@section('content_header')
    <h1>{{ __('locale.sales_history') }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('locale.sales_history') }}</h3>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="start_date">{{ __('locale.start_date') }}</label>
                        <input type="date" class="form-control" id="start_date" name="start_date"
                               value="{{ request('start_date') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="end_date">{{ __('locale.end_date') }}</label>
                        <input type="date" class="form-control" id="end_date" name="end_date"
                               value="{{ request('end_date') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="button" id="filterButton" class="btn btn-primary btn-block">
                            <i class="fas fa-filter"></i> {{ __('locale.filter') }}
                        </button>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped" id="salesTable">
                    <thead>
                        <tr>
                            <th>{{ __('locale.date') }}</th>
                            <th>{{ __('locale.users') }}</th>
                            <th>{{ __('locale.total') }}</th>
                            <th>{{ __('locale.quantity') }}</th>
                            <th>{{ __('locale.actions') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            var table = $('#salesTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: '{{ route("sales.index") }}',
                    data: function(d) {
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                    }
                },
                columns: [
                    {data: 'created_at', name: 'created_at', searchable: true},
                    {data: 'user_name', name: 'user_name', searchable: true},
                    {data: 'total_price', name: 'total_price', searchable: true},
                    {data: 'total_items', name: 'total_items', searchable: true},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                order: [[0, 'desc']],
                pageLength: 10,
                language: {
                    url: "{{ asset('js/localization/' . (app()->getLocale() === 'tr' ? 'Turkish.json' : 'English.json')) }}"
                }
            });

            $('#filterButton').on('click', function() {
                table.ajax.reload();
            });
        });
    </script>
@stop
