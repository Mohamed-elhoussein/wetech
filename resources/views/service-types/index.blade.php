@extends('layout.partials.app')

@section('title', 'قائمة الخدمات')

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
                    <h4 class="mb-sm-0 font-size-18 ">قائمة أنواع الخدمات</h4>

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
                        <a href="{{ route('service-types.create') }}" class="btn btn-success w-md fs-5">أضف نوع خدمة</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">

            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <div class="col-lg-3 mb-4 d-flex align-items-end">
                            <div class="me-2" style="position: relative">
                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span>تعديل</span>
                                    <img src="{{ asset('/assets/icons/expand.svg') }}" alt="">
                                </button>
                                <div class="dropdown-menu">
                                    <button data-bulk-url="{{ route('service-types.bulk-action') }}" class="dropdown-item bulk__submit" data-action="delete">حذف</button>
                                </div>
                                <input type="hidden" name="ids" id="ids">
                            </div>
                            <form class="table_users w-100" action="">
                                <div class="position-relative">
                                    <input @if (request()->get('q')) autofocus @endif id="" name="q"
                                    value="{{ request()->get('q') }}"
                                    placeholder=" خانة البحث كمثال (الإسم)"
                                    class=" _search mt-2 form-control px-4" type="text">
                                    <span class="position-absolute fs-3 px-1" style="right:0; top:3px; bottom: 0;color: #74788d;"><i class="bx bx-search-alt-2 fs-5"></i></span>
                                </div>
                            </form>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle table-nowrap table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">
                                            <input type="checkbox" class="form-check" id="checkAll">
                                        </th>
                                        <th scope="col">الإسم</th>
                                        <th scope="col">الإسم (en)</th>
                                        <th scope="col">تعديل</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($serviceTypes as $service_type)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="bulk__check form-check" value="{{ $service_type->id }}">
                                        </td>
                                        <td>
                                            {{ $service_type->name }}
                                        </td>
                                        <td>
                                            {{ $service_type->name_en }}
                                        </td>
                                        <td>
                                            <ul class="list-inline font-size-20 contact-links mb-0">
                                                <li class="list-inline-item px-2">
                                                    <form id="delete-form-{{ $service_type->id }}" action="{{ route('service-types.destroy', compact('service_type')) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                    <a class="delete" data-delete-form="#delete-form-{{ $service_type->id }}" href="javascript:;"><i class="bx bx-trash-alt "></i></a>
                                                </li>
                                                <li class="list-inline-item px-2">
                                                    <a href="{{ route('service-types.edit', compact('service_type')) }}" title="Edit"><i class="bx bx-pencil"></i></a>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>


                        </div>
                        {{ $serviceTypes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- container-fluid -->
</div>
<!-- End Page-content -->
@endsection