@extends('adminlte::page')

@section('title', __('locale.categories'))

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>{{ __('locale.categories') }}</h1>
        <a href="{{ route('categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> {{ __('locale.add_category') }}
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="categories-table">
                    <thead>
                    <tr>
{{--                        <th style="width: 10px">#</th>--}}
                        <th>{{ __('locale.name') }}</th>
{{--                        <th>{{ __('locale.created_at') }}</th>--}}
                        <th style="width: 150px">{{ __('locale.actions') }}</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .card {
            box-shadow: 0 0 1px rgba(0, 0, 0, .125), 0 1px 3px rgba(0, 0, 0, .2);
            margin-bottom: 1rem;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function () {

            // DataTables ayarları
            $('#categories-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('categories.index') }}",
                },
                columns: [
                    // {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    // {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                language: {
                    "url": "{{ asset('js/localization/' . (app()->getLocale() === 'tr' ? 'Turkish.json' : 'English.json')) }}"
                }
            });

            console.log('Kategori DataTable başlatıldı.');
            // Uyarı mesajını 3 saniye sonra otomatik kapat
            setTimeout(function () {
                $('.alert').alert('close');
            }, 3000);
        });
    </script>
@stop
