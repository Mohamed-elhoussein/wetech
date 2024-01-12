@extends('layout.partials.app')

@section('title', 'قائمة باقات الإشتراك')

@section('dashbord_content')
    <div class="page-content">
        <div class="container-fluid">
            @if (session('created'))
                <div class=" w-50 m-auto rounded p-2 bg-success text-white bg-gradient text-center zindex-fixed fs-4">
                    {{ session('created') }}</div>
            @endif
            @if (session('deleted'))
                <div class=" w-50 m-auto rounded p-2 bg-danger text-white bg-gradient text-center zindex-fixed fs-4">
                    {{ session('deleted') }}</div>
            @endif
            @if (session('updated'))
                <div class=" w-50 m-auto rounded p-2 bg-warning text-white bg-gradient text-center zindex-fixed fs-4">
                    {{ session('updated') }}</div>
            @endif
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18 ">قائمة باقات الإشتراك</h4>

                        <div class="">
                            <a href="{{ route('subscribe.pack.create') }}" class="btn btn-success w-md fs-5">أضف
                                إشتراك</a>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">

                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">


                            <div class="table-responsive">
                                <table class="table align-middle table-nowrap table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">الإسم</th>
                                            <th scope="col">الإسم بالإنجليزي</th>
                                            <th scope="col">عدد الأيام</th>
                                            <th scope="col">الثمن بالريال </th>
                                            <th scope="col">الثمن بالدولار</th>
                                            <th scope="col">وقت الانشاء</th>
                                            <th scope="col">تعديل</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($subscribesPackes as $subscribe)
                                            <tr>
                                                <td>
                                                    <h5 class="font-size-14 mb-1"><a
                                                            class="text-dark">{{ $subscribe->name }}</a>
                                                    </h5>
                                                </td>
                                                <td>
                                                    <h5 class="font-size-14 mb-1"><a
                                                            class="text-dark">{{ $subscribe->name_en ?: 'لم يسجل' }}</a>
                                                    </h5>
                                                </td>
                                                <td>
                                                    <h5 class="font-size-14 mb-1"><a
                                                            class="text-dark">{{ $subscribe->days }}</a>
                                                    </h5>
                                                </td>
                                                <td>
                                                    <h5 class="font-size-14 mb-1"><a
                                                            class="text-dark">{{ $subscribe->price_sar }}</a>
                                                    </h5>
                                                </td>
                                                <td>
                                                    <h5 class="font-size-14 mb-1"><a
                                                            class="text-dark">{{ $subscribe->price_usd }}</a>
                                                    </h5>
                                                </td>
                                                <td>
                                                    <div>
                                                        <a
                                                            class="badge badge-soft-primary font-size-11 m-1">{{ date('d-y-M', strtotime($subscribe->created_at)) }}</a>

                                                    </div>
                                                </td>

                                                <td>
                                                    <ul class="list-inline font-size-20 contact-links mb-0">
                                                        <li class="list-inline-item px-2">
                                                            <a class="delete"
                                                                href="{{ route('subscribe.pack.delete', ['id' => $subscribe->id]) }}"
                                                                title="Delete"><i class="bx bx-trash-alt "></i></a>
                                                        </li>
                                                        <li class="list-inline-item px-2">
                                                            <a href="{{ route('subscribe.pack.edit', ['id' => $subscribe->id]) }}"
                                                                title="Edit"><i class="bx bx-pencil"></i></a>
                                                        </li>


                                                    </ul>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>


                            </div>
                            {{ $subscribesPackes->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
@endsection
