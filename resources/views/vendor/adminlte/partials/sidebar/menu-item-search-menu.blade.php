<li>

    <div class="form-inline my-2">
        <div class="input-group" data-widget="sidebar-search" data-arrow-sign="&raquo;">

            {{-- Search input --}}
            <input class="form-control form-control-sidebar" type="search"
                @isset($item['id']) id="{{ $item['id'] }}" @endisset
                placeholder="{{ __('locale.search_placeholder') }}"
                aria-label="{{ $item['text'] }}tttt">

            {{-- Search button --}}
            <div class="input-group-append">
                <button class="btn btn-sidebar">
                    <i class="fas fa-fw fa-search"></i>
                </button>
            </div>

        </div>
    </div>

</li>
