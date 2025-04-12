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
{{--                <a href="{{ route('sales.reports') }}" class="btn btn-info btn-sm ml-2">--}}
{{--                    <i class="fas fa-chart-bar"></i> {{ __('locale.sales_reports') }}--}}
{{--                </a>--}}
            </div>
        </div>
        <div class="card-body">
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
@stop

@section('js')
{{--    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>--}}
    <script>
        $(function () {
            let saleToDelete = null;

            // Initialize DataTable
            $('#sales-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('sales.index') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'user_id', name: 'user_id' },
                    { data: 'total_price', name: 'total_price' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
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
