@extends('adminlte::page')

@section('title', __('locale.users'))

@section('plugins.Datatables', true)
@section('plugins.Select2', true)

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>{{ __('locale.users') }}</h1>
        </div>
        <div class="col-sm-6">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary float-right">
                <i class="fas fa-plus"></i> {{ __('locale.create_user') }}
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('locale.user_list') }}</h3>
                    <div class="card-tools">
                        <form action="{{ route('admin.users.index') }}" method="GET" class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="search" class="form-control float-right" placeholder="{{ __('locale.search') }}" value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>{{ __('locale.id') }}</th>
                                <th>{{ __('locale.name') }}</th>
                                <th>{{ __('locale.email') }}</th>
                                <th>{{ __('locale.role') }}</th>
                                <th>{{ __('locale.status') }}</th>
                                <th>{{ __('locale.created_at') }}</th>
                                <th>{{ __('locale.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @foreach($user->roles as $role)
                                            <span class="badge badge-info">{{ $role->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if($user->status === 'active')
                                            <span class="badge badge-success">{{ __('locale.active') }}</span>
                                        @elseif($user->status === 'pending')
                                            <span class="badge badge-warning">{{ __('locale.pending') }}</span>
                                        @else
                                            <span class="badge badge-danger">{{ __('locale.blocked') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-edit"></i> {{ __('locale.edit') }}
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('{{ __('locale.are_you_sure') }}')">
                                                <i class="fas fa-trash"></i> {{ __('locale.delete') }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($users->hasPages())
                    <div class="card-footer clearfix">
                        {{ $users->links() }}
                    </div>
                @endif
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