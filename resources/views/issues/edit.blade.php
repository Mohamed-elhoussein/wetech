@extends('maintenance')


@section('content')
    <h2 class="mb-4">تعديل المشكلة</h2>
    <div class="card">
        <div class="card-body">

            @if ($errors->any())
                <div class="col-md-8 offset-2 text-center badge-soft-danger rounded border border-danger">
                    {!! implode('', $errors->all('<div>:message</div>')) !!}
                </div>
            @endif

            <form class="m-5" action="{{ route('main.issues.update', $issue) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row mb-4">
                    <label for="service_id" class="col-sm-3 col-form-label fs-4">
                        المشكلة
                    </label>
                    <div class="col-sm-9">
                        <input type="text" name="name" value="{{ old('name', $issue->name) }}" placeholder="المشكلة" class="form-control">
                    </div>
                </div>

                <button class="btn btn-primary">تعديل</button>
            </form>
        </div>
    </div>
@endsection
