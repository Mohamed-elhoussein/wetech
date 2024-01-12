@extends('layout.partials.modal')

@section('title', 'إضافة نوع جديدة')

@section('content')

<form class="m-5" action="{{ route('types.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <input type="hidden" name="url" value="{{ request()->url ?? route('main.create') }}">

    <div class="row mb-4">
        <label class="col-sm-3 col-form-label fs-4">النوع</label>
        <div class="col-sm-9">
            <input type="text" name="name" placeholder="أدخل النوع" class="form-control">
        </div>
    </div>

    <div class="row d-flex justify-content-end mb-4">
        <button type="submit" class=" col-sm-6 col-md-2 btn btn-primary w-md fs-4 p-1">أضف النوع</button>
    </div>
</form>

@endsection
