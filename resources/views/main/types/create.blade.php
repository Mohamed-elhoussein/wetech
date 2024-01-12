@extends('maintenance')

@section('content')
    <h2 class="mb-4">إضافة عرض صيانة جديد</h2>
    <div class="card">
        <div class="card-body">

            @if ($errors->any())
                <div class="col-md-8 offset-2 text-center badge-soft-danger rounded border border-danger">
                    {!! implode('', $errors->all('<div>:message</div>')) !!}
                </div>
            @endif

            <form class="m-5" action="{{ route('main.types.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row mb-4">
                    <label for="name" class="col-sm-3 col-form-label fs-4">
                        النوع
                    </label>
                    <div class="col-sm-9">
                        <input type="text" name="name" class="form-control" required>
                    </div>
                </div>

                <button class="btn btn-primary">إضافة</button>
            </form>
        </div>
    </div>

    <div id="ajax-result">

    </div>
@endsection
