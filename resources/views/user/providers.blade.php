@extends('layout.partials.app')

@section('title', 'قائمة مزودي الخدمات')

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
                        <h4 class="mb-sm-0 font-size-18">قائمة مزودي الخدمات</h4>
                        <div class="d-flex align-items-center">
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
                            <a href="/provider/create" data-bs-toggle="modal" data-bs-target="#composemodal"
                                class="btn ms-2 btn-primary w-md  btn-block waves-effect waves-light">إنشاء
                                كود </a>
                            <a href="{{ route('provider.create') }}" class="btn ms-2 btn-success w-md">اضافة مزود خدمة
                            </a>
                            <span onclick="exportTasks(event.target);" data-href="{{ route('user.providers.export') }}"
                                id="export" class="btn ms-2 btn-primary">
                                إستخراج
                            </span>
                            <button data-bs-toggle="modal" data-bs-target="#importModal" id="import"
                                class="btn ms-2 btn-primary">
                                رفع
                            </button>

                            <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('provider.import') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">رفع المزودين</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <label for="file">إختر الملف (csv)</label>
                                                <input type="file"
                                                    class="form-control @error('file') is-invalid mt-1 @enderror"
                                                    style="display: block" name="file" accept="" required>
                                                @error('file')
                                                    <span class="invalid-feedback">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                                <a class="mt-2" style="display: block" download
                                                    href="{{ asset('csv/providers.csv') }}">تحميل مثال الملف</a>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">إغلاق</button>
                                                <button type="submit" class="btn btn-primary">رفع</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <form method="GET" class="d-flex align-items-center ms-2">

                                <select name="status" class="form-select">
                                    <option value="" @if (!request()->has('status')) selected @endif>إختر حالة</option>
                                    <option value="active" @if (request()->get('status') === 'active') selected @endif>يعمل</option>
                                    <option value="inactive" @if (request()->get('status') === 'inactive') selected @endif>محظور
                                    </option>
                                </select>

                                <button class="btn btn-success ms-2">
                                    فرز
                                </button>
                                @if (request()->has('status'))
                                    <a href="{{ route('user.providers') }}" class="btn btn-danger ms-2"
                                        style="white-space: nowrap">إعادة التعيين</a>
                                @endif
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="col-sm-3 d-flex align-items-end w-100 mb-4 ">
                                <div class="me-2" style="position: relative">
                                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <span>تعديل</span>
                                        <img src="{{ asset('/assets/icons/expand.svg') }}" alt="">
                                    </button>
                                    <div class="dropdown-menu">
                                        <button data-bulk-url="{{ route('provider.bulk-action') }}"
                                            class="dropdown-item bulk__submit" data-value="blocked"
                                            data-action="status">حظر</button>
                                        <button data-bulk-url="{{ route('provider.bulk-action') }}"
                                            class="dropdown-item bulk__submit" data-value="active" data-action="status">
                                            رفع الحظر
                                        </button>
                                        <button data-bulk-url="{{ route('provider.bulk-action') }}"
                                            class="dropdown-item bulk__submit" data-action="delete">حذف</button>
                                    </div>
                                    <input type="hidden" name="ids" id="ids">
                                </div>
                                <form class="table_users w-100" action="">
                                    <div class="position-relative">
                                        <input @if (request()->get('q')) autofocus @endif id="" name="q"
                                            value="{{ request()->get('q') }}" placeholder=" خانة البحث  كمثال (الهاتف)"
                                            class="w-100 _search mt-2  form-control px-4" type="text">
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
                                            <th scope="col">الإسم الأول</th>
                                            <th scope="col">الإسم الثاني</th>
                                            <th scope="col">الإسم الأخير</th>
                                            <th scope="col">البلد</th>
                                            <th scope="col">وقت الإنشاء</th>
                                            <th scope="col">الحالة</th>
                                            <th scope="col">العمولة</th>
                                            <th scope="col">الحساب</th>
                                            <th scope="col">التحقق</th>
                                            <th scope="col">تعديل</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr class="commission" data-info="{{ $user->id }}">
                                                <td class="has__bulk__check">
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

                                                </td>
                                                <td>{{ $user->first_name }}</td>
                                                <td>{{ $user->second_name }}</td>
                                                <td>{{ $user->last_name }}</td>
                                                <td>{{ optional($user->country)->name ?: 'لا توجد' }}</td>
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
                                                <td style="font-weight: bold">
                                                    {{ optional($user->commission)->commission ?: 'الإعتيادية' }}</td>
                                                <td style="font-weight: bold">{{ $user->balance ?: 0 }}</td>

                                                <td>
                                                    @if ($user->verified)
                                                        <span
                                                            class=" border  badge-soft-primary px-1 rounded border-2 border-primary ">
                                                            {{ 'تم التحقق' }}
                                                        @else
                                                            <span
                                                                class=" border  badge-soft-warning px-1 rounded border-2 border-warning ">
                                                                {{ 'ليس بعد ' }}
                                                    @endif
                                                    </span>
                                                </td>
                                                <td class='action'>
                                                    <ul class="list-inline font-size-20 contact-links mb-0">
                                                        <li class="list-inline-item px-2">
                                                            <a class="delete"
                                                                href="/provider/delete/{{ $user->id }}"
                                                                title="Delete"><i class="bx bx-trash-alt "></i></a>
                                                        </li>
                                                        <li class="list-inline-item px-2">
                                                            <a href="/provider/edit/{{ $user->id }}" title="edit"><i
                                                                    class="bx bx-user-circle "></i></a>
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
                                                        @if ($user->verified)
                                                            <li class="list-inline-item px-2">
                                                                <a data-href="/user/verified/{{ $user->id }}"
                                                                    class="verified" title="verified">
                                                                    <i class=" bx bx-user-check "></i></a>
                                                            </li>
                                                        @else
                                                            <li class="list-inline-item px-2">
                                                                <a data-href="/user/verified/{{ $user->id }}"
                                                                    class="verified" title="unverified">
                                                                    <i class='bx bx-user-x '></i></a>
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
    <div class="modal fade" id="composemodal" tabindex="-1" aria-labelledby="composemodalTitle" style="display: none;"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-center" id="composemodalTitle"> أضافة قن سري للمزويدين الجدد </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="py-5 px-3">
                        <div class="my-3">
                            <input name="country_code" type="text" class="form-control country_code"
                                placeholder="رمز الدولة  (94+)">
                        </div>

                        <div class="mb-3">
                            <input name="number_phone" type="text" class="form-control number_phone"
                                placeholder="رقم الهاتف">
                        </div>
                        <div class="mb-3 fade">
                            <div style="font-weight: bold" id="key_by_number"
                                class="p-2 rouded shadow  bg-white fs-4 text-center bold"></div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button id="ProviderKey" type="button" class="btn btn-success">أضف </button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    @if ($errors->any())
        <script>
            $('#importModal').modal('show');
        </script>
    @endif
@endsection
