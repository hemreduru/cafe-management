<div class="btn-group">
    <a href="{{ route('products.edit', $id) }}" class="btn btn-xs btn-primary">
        <i class="fas fa-edit"></i> {{ __('locale.edit') }}
    </a>
    
    <a href="{{ route('products.show', $id) }}" class="btn btn-xs btn-info">
        <i class="fas fa-eye"></i> {{ __('locale.show') }}
    </a>
    
    <form action="{{ route('products.destroy', $id) }}" method="POST" class="d-inline" data-confirm="delete">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-xs btn-danger">
            <i class="fas fa-trash"></i> {{ __('locale.delete') }}
        </button>
    </form>
</div>