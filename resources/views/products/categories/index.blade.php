@extends('layout.partials.app')

@section('title', 'قائمة تصنيفات المنتجات')

@section('dashbord_content')
<div class="page-content">
    <div class="container-fluid">
        @if (session('created'))
        <div class=" w-50 m-auto rounded p-2 bg-success text-white bg-gradient text-center zindex-fixed fs-4">
            {{ session('created') }}
        </div>
        @endif
        @if (session('deleted'))
        <div class=" w-50 m-auto rounded p-2 bg-danger text-white bg-gradient text-center zindex-fixed fs-4">
            {{ session('deleted') }}
        </div>
        @endif
        @if (session('updated'))
        <div class=" w-50 m-auto rounded p-2 bg-warning text-white bg-gradient text-center zindex-fixed fs-4">
            {{ session('updated') }}
        </div>
        @endif
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18 ">قائمة تصنيفات المنتجات</h4>
                    <div class="d-flex align-items-center">
                        <div class="position-relative me-2">
                            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                        <a href="{{ route('product-categories.create') }}" class="btn btn-success w-md">أضف تصنيف جديد</a>
                        <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">رفع المزودين</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <label for="file">إختر الملف (csv)</label>
                                            <input type="file" class="form-control @error('file') is-invalid mt-1 @enderror" style="display: block" name="file" accept=".csv" required>
                                            @error('file')
                                            <span class="invalid-feedback">
                                                {{ $message }}
                                            </span>
                                            @enderror
                                            <a class="mt-2" style="display: block" download href="{{ asset('csv/categorys.csv') }}">تحميل مثال الملف</a>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                                            <button type="submit" class="btn btn-primary">رفع</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">

            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            @include('partials.search-input', [
                                'placeholder' => 'إبحث من تصنيف عبر الإسم',
                                'hide' => true,
                            ])
                        </div>

                        <div class="col-sm-4 offset-sm-8 mb-4">
                            <form action="" id="search">
                                <label class="visually-hidden" for="inlineFormInputGroupUsername"></label>
                            </form>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle table-nowrap table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">الإسم</th>
                                        <th scope="col">الإسم (en)</th>
                                        <th scope="col">اﻷيقونة</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $category)
                                    <tr>
                                        <td>
                                            {{ $category->name }}
                                        </td>
                                        <td>
                                            {{ $category->name_en }}
                                        </td>
                                        <td>
                                            <img width="40" height="40" src="{{ $category->icon }}" alt="">
                                        </td>
                                        <td>

                                            <ul class="list-inline font-size-20 contact-links mb-0">
                                                <li class="list-inline-item px-2">
                                                    <a href="{{ route('product-categories.edit', $category) }}" title="Edit"><i class="bx bx-pencil"></i></a>
                                                </li>
                                                <li class="list-inline-item px-2">
                                                    <form id="delete-form-{{ $category->id }}" action="{{ route('product-categories.destroy', $category) }}" method="POST">
                                                        @csrf
                                                        @method("DELETE")
                                                    </form>
                                                    <a data-delete-form="#delete-form-{{ $category->id }}" class="delete" href="javascript:;" title="Delete">
                                                        <i class="bx bx-trash-alt "></i>
                                                    </a>
                                                </li>
                                            </ul>

                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>


                        </div>
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- container-fluid -->
</div>
<!-- End Page-content -->
@endsection
