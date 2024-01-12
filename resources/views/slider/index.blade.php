@extends('layout.partials.app')

@section('title', 'قائمة الشرائح')

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
                        <h4 class="mb-sm-0 font-size-18 ">قائمة الشرائح</h4>

                        <div class="d-flex align-items-center">
                            <a href="/slider/create" class="btn btn-success w-md fs-5">أضف شريحة</a>

                            <form method="GET" class="d-flex align-items-center ms-2">
                                <select name="status" class="form-select">
                                    <option value="" @if (!request()->has('status')) selected @endif>إختر حالة</option>
                                    <option value="active" @if (request()->get('status') === 'active') selected @endif>مفعل</option>
                                    <option value="inactive" @if (request()->get('status') === 'inactive') selected @endif>غير مفعل
                                    </option>
                                </select>

                                <button class="btn btn-success ms-2">
                                    فرز
                                </button>
                                @if (request()->has('status'))
                                    <a href="/slider" class="btn btn-danger ms-2" style="white-space: nowrap">إعادة
                                        التعيين</a>
                                @endif

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

                            <div class="d-flex justify-content-between">
                                <div class="mb-2">
                                    <div class="me-2" style="position: relative">
                                        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            تعديل
                                            <img src="{{ asset('/assets/icons/expand.svg') }}" alt="">
                                        </button>
                                        <div class="dropdown-menu">
                                            <button data-bulk-url="{{ route('slider.bulk-action') }}"
                                                class="dropdown-item bulk__submit" data-value="active"
                                                data-action="status">مفعل</button>
                                            <button data-bulk-url="{{ route('slider.bulk-action') }}"
                                                class="dropdown-item bulk__submit" data-value="inactive"
                                                data-action="status">
                                                غير مفعل
                                            </button>
                                            <button data-bulk-url="{{ route('slider.bulk-action') }}"
                                                class="dropdown-item bulk__submit" data-action="delete">حذف</button>
                                        </div>
                                        <input type="hidden" name="ids" id="ids">
                                    </div>
                                </div>

                                <form class="table_users w-100" action="">
                                    <div class="col-sm-3 offset-sm-9 mb-4 position-relative">
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
                                            <th scope="col">الصورة</th>
                                            <th scope="col">الاسم</th>
                                            {{-- <th scope="col">الرابط</th> --}}
                                            <th scope="col">الهاتف</th>
                                            <th scope="col">زر الإتصال</th>
                                            <th scope="col">الحالة</th>
                                            <th scope="col">موجه إلى</th>
                                            <th scope="col">وقت الانشاء</th>
                                            <th scope="col">تعديل</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sliders as $slider)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="bulk__check form-check"
                                                        value="{{ $slider->id }}">
                                                </td>
                                                <td>
                                                    <div class="avatar-xs">

                                                        <img src="{{ $slider->image ?: default_image() }}"
                                                            alt="slider image" class=""
                                                            style="height: 40px;width: 40px">

                                                    </div>
                                                </td>
                                                <td>
                                                    <h5 class="font-size-14 mb-1"><a href="javascript: void(0);"
                                                            class="text-dark">{{ $slider->name }}</a>
                                                    </h5>
                                                </td>

                                                <td
                                                    @if ($slider->phone) style="text-align: right;" dir="ltr" @endif>
                                                    {{ $slider->phone ?: 'لا يوجد' }}
                                                </td>
                                                <td style="font-weight: bold">
                                                    @if ($slider->visitableBtn)
                                                        <span
                                                            class=" border  badge-soft-primary px-1 rounded border-2 border-primary ">
                                                            {{ 'يظهر' }}
                                                        @else
                                                            <span
                                                                class=" border  badge-soft-warning px-1 rounded border-2 border-warning ">
                                                                {{ 'لا يظهر' }}
                                                    @endif
                                                    </span>
                                                </td>
                                                <td style="font-weight: bold">
                                                    @if ($slider->active)
                                                        <span
                                                            class=" border  badge-soft-success px-1 rounded border-2 border-success ">
                                                            {{ 'مفعل' }}
                                                        @else
                                                            <span
                                                                class=" border  badge-soft-danger px-1 rounded border-2 border-danger ">
                                                                {{ ' غير مفعل' }}
                                                    @endif
                                                    </span>
                                                </td>
                                                <td>{{ $slider->target }}</td>
                                                <td>
                                                    <div>
                                                        <a href="javascript: void(0);"
                                                            class="badge badge-soft-primary font-size-11 m-1">{{ date('d-y-M', strtotime($slider->created_at)) }}</a>

                                                    </div>
                                                </td>

                                                <td>
                                                    <ul class="list-inline font-size-20 contact-links mb-0">
                                                        <li class=" list-inline-item px-2">
                                                            <a href="/slider/delete/{{ $slider->id }}" title="Delete"
                                                                class="delete"><i class="bx bx-trash-alt "></i></a>
                                                        </li>
                                                        <li class="list-inline-item px-2">
                                                            <a href="/slider/edit/{{ $slider->id }}" title="Edit"><i
                                                                    class="bx bx-pencil"></i></a>
                                                        </li>
                                                        @if ($slider->active)
                                                            <li class="list-inline-item px-2">
                                                                <a href="/slider/block/{{ $slider->id }}"
                                                                    title="Block"><i class="bx bx-block"></i></a>
                                                            </li>
                                                        @else
                                                            <li class="list-inline-item px-2">
                                                                <a href="/slider/block/{{ $slider->id }}"
                                                                    title="active"><i class='bx bx-check-square'></i></a>
                                                            </li>
                                                        @endif
                                                        <li class="list-inline-item px-2">
                                                            <a href="{{ route('slider.newBtn', $slider->id) }}"
                                                                title="add new button to slider"><i
                                                                    class='bx bx-add-to-queue '></i></a>
                                                        </li>

                                                    </ul>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{ $sliders->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
@endsection
