@extends('layout.partials.app')

@section('title', 'قائمة المدن')

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
                        <h4 class="mb-sm-0 font-size-18 ">قائمة المدن</h4>

                        <div class="d-flex align-items-center">
                            <form action="{{ route('cities.assign') }}" method="POST"
                                class="w-100 d-flex align-items-center me-2">
                                @csrf
                                <select name="country_id" class="form-select" id="">
                                    @foreach (App\Helpers\Loader::getCountries() as $country)
                                        <option value="{{ $country['id'] }}">{{ $country['name'] }}</option>
                                    @endforeach
                                    <input type="hidden" name="ids" id="ids">
                                </select>
                                <button type="submit" class="btn btn-success ms-2 w-md">تعيين الدولة</button>
                            </form>
                            <div class="position-relative me-2">
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
                            @if (!$hasLoadedCities)
                                <form action="{{ route('cities.load') }}" method="POST" class="me-2">
                                    @csrf
                                    <button type="submit" class="btn btn-success d-block" style="width: 155px">تحميل
                                        المدن
                                        السعودية</button>
                                </form>
                            @endif
                            <a href="/cities/create" class="btn btn-success w-md fs-5">أضف مدينة</a>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">

                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">

                            <div class="mb-2 d-flex align-items-end">
                                <div class="me-2" style="position: relative">
                                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        تعديل
                                        <img src="{{ asset('/assets/icons/expand.svg') }}" alt="">
                                    </button>
                                    <div class="dropdown-menu">
                                        <button data-bulk-url="{{ route('cities.bulk-action') }}"
                                            class="dropdown-item bulk__submit" data-action="delete">حذف</button>
                                    </div>
                                    <input type="hidden" name="ids" id="ids">
                                </div>
                                @include('partials.search-input', [
                                    'placeholder' => 'إبحث عن المدينة عبر الإسم أو إسم الدولة',
                                    'hide' => true,
                                ])
                            </div>

                            <div class="table-responsive">
                                <table class="table align-middle table-nowrap table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">
                                                <input type="checkbox" class="form-check" id="checkAll">
                                            </th>
                                            <th scope="col">الإسم</th>
                                            <th scope="col">الإسم بالإنجليزي</th>
                                            <th scope="col">الدولة</th>
                                            <th scope="col">وقت الانشاء</th>
                                            <th scope="col">تعديل</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cities as $city)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="bulk__check form-check"
                                                        value="{{ $city->id }}">
                                                </td>
                                                <td>
                                                    <h5 class="font-size-14 mb-1"><a href="javascript: void(0);"
                                                            class="text-dark">{{ $city->name }}</a>
                                                    </h5>
                                                </td>
                                                <td>
                                                    <h5 class="font-size-14 mb-1"><a href="javascript: void(0);"
                                                            class="text-dark">{{ $city->name_en ?: 'لم يسجل' }}</a>
                                                    </h5>
                                                </td>
                                                <td>
                                                    <h5 class="font-size-14 mb-1"><a href="javascript: void(0);"
                                                            class="text-dark">{{ $city->country->name }}</a>
                                                    </h5>
                                                </td>
                                                <td>
                                                    <div>
                                                        <a href="javascript: void(0);"
                                                            class="badge badge-soft-primary font-size-11 m-1">{{ date('d-y-M', strtotime($city->created_at)) }}</a>

                                                    </div>
                                                </td>

                                                <td>
                                                    <ul class="list-inline font-size-20 contact-links mb-0">
                                                        <li class="list-inline-item px-2">
                                                            <a class="delete"
                                                                href="/cities/delete/{{ $city->id }}" title="Delete"><i
                                                                    class="bx bx-trash-alt "></i></a>
                                                        </li>
                                                        <li class="list-inline-item px-2">
                                                            <a href="/cities/edit/{{ $city->id }}" title="Edit"><i
                                                                    class="bx bx-pencil"></i></a>
                                                        </li>


                                                    </ul>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>


                            </div>
                            {{ $cities->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
@endsection
