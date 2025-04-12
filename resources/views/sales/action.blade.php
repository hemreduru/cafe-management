<div class="btn-group">
    <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-sm btn-info">
        <i class="fas fa-eye"></i> {{ __('locale.view') }}
    </a>
{{--    <a href="#" class="btn btn-sm btn-danger delete-sale" data-id="{{ $sale->id }}" data-toggle="modal" data-target="#delete-sale-modal">--}}
{{--        <i class="fas fa-trash"></i> {{ __('locale.delete') }}--}}
{{--    </a>--}}
</div>
