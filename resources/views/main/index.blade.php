@extends('maintenance')


@section('content')
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h2 class="mb-sm-0">متجر الصيانة</h2>

        <a href="{{ route('main.create') }}" class="btn btn-success w-md fs-5">إضافة عرض طلب جديد</a>
    </div>


    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <form class="w-100" action="">
                    <div class="col-sm-3 offset-sm-9 mb-4 position-relative">
                        <input name="q" value="{{ request()->q }}" placeholder=" خانة البحث  كمثال (الهاتف)"
                            class="mt-2  form-control px-4" type="text">
                        <span class="position-absolute fs-3 px-1" style="right:0; top:3px; bottom: 0;color: #74788d;">
                            <i class="bx bx-search-alt-2 fs-5"></i>
                        </span>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table align-middle table-nowrap table-hover">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">نوع الخدمة</th>
                            <th scope="col">نوع الجهاز</th>
                            <th scope="col">موديل الجهاز</th>
                            <th scope="col">اللون</th>
                            <th scope="col">المشكلة</th>
                            <th scope="col">الدول</th>
                            <th scope="col">المدن</th>
                            <th scope="col">اﻷحياء</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requests as $request)
                            <tr>
                                <td>{{ $request->service->name }}</td>
                                <td>{{ $request->brand->name }}</td>
                                <td>{{ $request->model->name }}</td>
                                <td>{{ $request->color->name }}</td>
                                <td>{{ $request->issue->name }}</td>
                                <td>{{ $request->country_names }}</td>
                                <td>{{ $request->city_names }}</td>
                                <td>{{ $request->street_names }}</td>
                                <td>
                                    <ul class="list-inline font-size-20 contact-links mb-0">
                                        <li class=" list-inline-item px-2">
                                            <form action="{{ route('main.destroy', $request) }}" method="POST" id="delete-request-{{ $request->id }}">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <a data-delete-form="#delete-request-{{ $request->id }}" href="javascript:;" title="Delete" class="delete">
                                                <i class="bx bx-trash-alt"></i>
                                            </a>
                                        </li>
                                        <li class="list-inline-item px-2">
                                            <a href="{{ route('main.edit', $request) }}" title="Edit">
                                                <i class="bx bx-pencil"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $requests->links() }}
            </div>
        </div>
    </div>
@endsection
