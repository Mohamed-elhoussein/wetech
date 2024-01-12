@extends('layout.partials.app')

@section('title', 'قائمة الزبناء')

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
                        <h4 class="mb-sm-0 font-size-18"> قائمة الزبناء</h4>
                        <div class="d-flex align-items-center">
                            <span onclick="exportTasks(event.target);" data-href="{{ route('user.users.export') }}"
                                id="export" class="btn btn-primary">
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
                            <div class="d-flex align-items-end col-lg-3 mb-4">
                                <div class="me-2" style="position: relative">
                                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <span>تعديل</span>
                                        <img src="{{ asset('/assets/icons/expand.svg') }}" alt="">
                                    </button>
                                    <div class="dropdown-menu">
                                        <button data-bulk-url="{{ route('user.bulk-action') }}"
                                            class="dropdown-item bulk__submit" data-value="active"
                                            data-action="status">تفعيل</button>
                                        <button data-bulk-url="{{ route('user.bulk-action') }}"
                                            class="dropdown-item bulk__submit" data-value="blocked" data-action="status">
                                            حظر
                                        </button>
                                        <button data-bulk-url="{{ route('user.bulk-action') }}"
                                            class="dropdown-item bulk__submit" data-action="delete">حذف</button>
                                    </div>
                                    <input type="hidden" name="ids" id="ids">
                                </div>

                                <form class="table_users w-100" action="">
                                    <div class="position-relative">
                                        <input @if (request()->get('key_search')) autofocus @endif id="" name="key_search"
                                            value="{{ request()->get('key_search') }}"
                                            placeholder=" خانة البحث  كمثال (الهاتف)"
                                            class=" _search mt-2  form-control px-4" type="text">
                                        <span class="position-absolute fs-3 px-1"
                                            style="right:0; top:3px; bottom: 0;color: #74788d;"><i
                                                class="bx bx-search-alt-2 fs-5"></i></span>
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
                                            <th scope="col" style="width: 70px;">#</th>
                                            <th scope="col">الإسم</th>
                                            <th scope="col">الهاتف</th>
                                            <th scope="col">البلد</th>
                                            <th scope="col">البريد الإلكتروني</th>
                                            <th scope="col">نوع الهاتف</th>
                                            <th scope="col">وقت الإنشاء</th>
                                            <th scope="col">الحالة</th>
                                            <th scope="col">تعديل</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($users as $user)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="bulk__check form-check"
                                                        value="{{ $user->id }}">
                                                </td>
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
                                                    <p class="text-muted mb-0">{{ Str::lower($user->role) }}</p>
                                                </td>
                                                <td style="text-align: right" dir="ltr">
                                                    {{ '(' . optional($user->country)->country_code . ')' . $user->number_phone }}
                                                </td>
                                                <td>{{ optional($user->country)->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->x_os ?? 'لم يسجل بعد' }}</td>
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
                                                            <a href="{{ route('user.users.edit', compact('user')) }}"
                                                                title="Edit">
                                                                <i class="bx bx-pencil "></i></a>
                                                        </li>
                                                        <li class="list-inline-item px-2">
                                                            <a class="delete"
                                                                href="/user/delete/{{ $user->id }}" title="Delete"><i
                                                                    class="bx bx-trash-alt "></i></a>
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
