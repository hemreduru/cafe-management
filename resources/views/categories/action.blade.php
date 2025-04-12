<div class="btn-group">
    <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-info">
        <i class="fas fa-edit"></i>
    </a>
    <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('{{ __('locale.confirm_delete') }}');" style="display: inline-block;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger">
            <i class="fas fa-trash"></i>
        </button>
    </form>
</div>