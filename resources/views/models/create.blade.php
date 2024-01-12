@extends('maintenance')


@section('content')
    <h2 class="mb-4">موديل جديد</h2>
    <div class="card">
        <div class="card-body">

            @if ($errors->any())
                <div class="col-md-8 offset-2 text-center badge-soft-danger rounded border border-danger">
                    {!! implode('', $errors->all('<div>:message</div>')) !!}
                </div>
            @endif

            <form class="m-5" action="{{ route('main.models.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row mb-4">
                    <label for="brand_id" class="col-sm-3 col-form-label fs-4">
                        الجهاز
                    </label>
                    <div class="col-sm-9">
                        <select name="brand_id" id="brand_id" class="form-select">
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-4">
                    <label for="name" class="col-sm-3 col-form-label fs-4">
                        الموديل
                    </label>
                    <div class="col-sm-9">
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="الموديل" class="form-control">
                    </div>
                </div>

                <button class="btn btn-primary">إضافة</button>
            </form>
        </div>
    </div>
@endsection
