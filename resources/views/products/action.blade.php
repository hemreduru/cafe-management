<div class="btn-group">
    <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-primary mr-1">
        <i class="fas fa-eye"></i>
    </a>
    <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-info mr-1">
        <i class="fas fa-edit"></i>
    </a>
    <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('{{ __('locale.confirm_delete') }}');" style="display: inline-block;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger">
            <i class="fas fa-trash"></i>
        </button>
    </form>
</div>