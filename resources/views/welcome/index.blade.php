@extends('layout.partials.app')

@section('title', 'قائمة الترحيبات')

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
                        <h4 class="mb-sm-0 font-size-18 ">قائمة الترحيبات</h4>

                        <div class="">
                            <a href="/welcome/create" class="btn btn-success w-md fs-5">أضف ترحيب</a>
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
                                            <th scope="col">الصورة</th>
                                            <th scope="col">العنوان</th>
                                            <th scope="col">الترحيب</th>

                                            <th scope="col">وقت الانشاء</th>
                                            <th scope="col">تعديل</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($welcomes as $welcome)
                                            <tr>
                                                <td>
                                                    <div class="avatar-xs">

                                                        <img src="{{ $welcome->image ?: default_image() }}"
                                                            alt="slider image" class=""
                                                            style="height: 40px;width: 40px">

                                                    </div>
                                                </td>
                                                <td
                                                    style="white-space: nowrap; text-overflow:ellipsis; overflow: hidden; max-width:100px;">
                                                    <h5 class="font-size-14 mb-1"><a
                                                            class="text-dark">{{ $welcome->titel }}</a>
                                                    </h5>
                                                </td>
                                                <td style="overflow: hidden; text-overflow: ellipsis">
                                                    <h5 class="font-size-14 mb-1"><a
                                                            class="text-dark">{{ $welcome->body }}</a>
                                                    </h5>
                                                </td>


                                                <td>
                                                    <div>
                                                        <a href="javascript: void(0);"
                                                            class="badge badge-soft-primary font-size-11 m-1">{{ date('d-y-M', strtotime($welcome->created_at)) }}</a>

                                                    </div>
                                                </td>

                                                <td>
                                                    <ul class="list-inline font-size-20 contact-links mb-0">
                                                        <li class=" list-inline-item px-2">
                                                            <a class="delete"
                                                                href="/welcome/delete/{{ $welcome->id }}"
                                                                title="Delete"><i class="bx bx-trash-alt "></i></a>
                                                        </li>
                                                        <li class="list-inline-item px-2">
                                                            <a href="/welcome/edit/{{ $welcome->id }}" title="Edit"><i
                                                                    class="bx bx-pencil"></i></a>
                                                        </li>


                                                    </ul>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{ $welcomes->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
@endsection
