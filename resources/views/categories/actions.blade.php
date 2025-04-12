<div class="btn-group">
    <a href="{{ route('categories.edit', $id) }}" class="btn btn-xs btn-primary">
        <i class="fas fa-edit"></i> {{ __('locale.edit') }}
    </a>

    <a href="{{ route('categories.show', $id) }}" class="btn btn-xs btn-info">
        <i class="fas fa-eye"></i> {{ __('locale.show') }}
    </a>

    <form action="{{ route('categories.destroy', $id) }}" method="POST" class="d-inline" data-confirm="delete">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-xs btn-danger">
            <i class="fas fa-trash"></i> {{ __('locale.delete') }}
        </button>
    </form>
</div>
