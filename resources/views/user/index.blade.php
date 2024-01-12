@extends('layout.partials.app')

@section('title', 'قائمة المشرفين')

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
                        <h4 class="mb-sm-0 font-size-18"> قائمة المشرفين</h4>

                        <div class="d-flex align-items-end">
                            <a href="/user/create" class="btn btn-success w-md">اضافة مشرف</a>

                            <span onclick="exportTasks(event.target);" data-href="{{ route('user.admins.export') }}"
                                id="export" class="btn btn-primary ms-2">
                                إستخراج
                            </span>

                            <div class="position-relative ms-2">
                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
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
                                            <th scope="col" style="width: 70px;">#</th>
                                            <th scope="col">الإسم</th>
                                            <th scope="col">البريد الإلكتروني</th>
                                            <th scope="col">وقت الإنشاء</th>
                                            <th scope="col">الحالة</th>
                                            <th scope="col">تعديل</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($users as $user)
                                            <tr>
                                                <td>
                                                    <div class="avatar-xs">
                                                        <span class="avatar-title avatar-sm rounded-circle">
                                                            <img class="avatar-sm rounded" src="{{ url($user->avatar) }}"
                                                                alt="avatar">
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <h5 class="font-size-14 mb-1"><a href="javascript: void(0);"
                                                            class="text-dark">{{ $user->username }}</a>
                                                    </h5>
                                                    <p class="text-muted mb-0">{{ $user->role }}</p>
                                                </td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    <div>
                                                        <a href="javascript: void(0);"
                                                            class="badge badge-soft-primary font-size-11 m-1">{{ date('d-y-M', strtotime($user->created_at)) }}</a>

                                                    </div>
                                                </td>
                                                <td>
                                                    @if (!$user->is_blocked)
                                                        <span
                                                            class=" border  badge-soft-success px-1 rounded border-2 border-success ">
                                                            {{ 'يعمل' }}
                                                        @else
                                                            <span
                                                                class=" border  badge-soft-danger px-1 rounded border-2 border-danger ">
                                                                {{ 'تم حظره' }}
                                                    @endif
                                                    </span>
                                                </td>
                                                <td>
                                                    <ul class="list-inline font-size-20 contact-links mb-0">
                                                        <li class="list-inline-item px-2">
                                                            <a class="delete"
                                                                href="/user/admins/delete/{{ $user->id }}"
                                                                title="Delete"><i class="bx bx-trash-alt "></i></a>
                                                        </li>
                                                        <li class="list-inline-item px-2">
                                                            <a href="/user/edit/{{ $user->id }}" title="Edite"><i
                                                                    class="bx bx-user-circle"></i></a>
                                                        </li>
                                                        @if (!$user->is_blocked)
                                                            <li class="list-inline-item px-2">
                                                                <a href="/user/block/{{ $user->id }}"
                                                                    title="Block    "><i class="bx bx-block"></i></a>
                                                            </li>
                                                        @else
                                                            <li class="list-inline-item px-2">
                                                                <a href="/user/block/{{ $user->id }}" title="active"><i
                                                                        class='bx bx-check-square'></i></a>
                                                            </li>
                                                        @endif

                                                    </ul>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
@endsection
