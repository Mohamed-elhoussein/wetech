<form method="GET" class="d-flex align-items-center w-100">
    <input type="text" class="w-100 form-control" value="{{ request()->get('q') }}" name="q"
        placeholder="{{ $placeholder }}" />
    <button class="btn btn-success ms-2">بحث</button>
</form>

{{-- @if (!isset($hide))
    <div class="position-relative mt-2">
        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
            <span>عرض</span>
            <img src="{{ asset('/assets/icons/expand.svg') }}" alt="">
        </button>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="?limit=5">5</a>
            <a class="dropdown-item" href="?limit=10">10</a>
            <a class="dropdown-item" href="?limit=15">15</a>
            <a class="dropdown-item" href="?limit=20">20</a>
            <a class="dropdown-item" href="?limit=50">50</a>
        </div>
    </div>
@endif --}}
