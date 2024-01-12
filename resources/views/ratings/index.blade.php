@extends('layout.partials.app')

@section('title', 'قائمة تقييمات الخدمات')

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
                        <h4 class="mb-sm-0 font-size-18 ">قائمة تقييمات الخدمات</h4>

                        <div class="d-flex align-items-end">
                            <form action="" method="GET" class="ms-2 d-flex align-items-end">
                                <select class="form-select" id="stars" name="stars" required>
                                    <option value="">التقييم</option>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <option @if (request()->get('stars') == $i) selected @endif
                                            value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>

                                <button class="btn btn-success ms-2">فرز</button>
                            </form>
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
                                            <th scope="col">صاحب التقييم</th>
                                            <th scope="col"> التعليق</th>
                                            <th scope="col"> التقييم</th>
                                            <th scope="col"> في</th>
                                            <th scope="col">تعديل</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rates as $rate)
                                            <tr>
                                                <td>
                                                    {{ $rate->user->username }}
                                                </td>
                                                <td style="white-space: inherit">
                                                    {{ $rate->comment }}
                                                </td>
                                                <td>
                                                    @if (isset($rate->stars))
                                                        @for ($i = 1; $i < 6; $i++)
                                                            @if ($i <= number_format($rate->stars))
                                                                <span class="bx bxs-star text-warning"></span>
                                                            @else()
                                                                <span class="bx bxs-star "></span>
                                                            @endif()
                                                        @endfor
                                                        <strong
                                                            class="ms-1 ">{{ number_format($rate->stars, 2) }}</strong>
                                                    @endif()
                                                </td>
                                                <td style="font-weight: bold">
                                                    <div>
                                                        <a href="javascript: void(0);"
                                                            class="badge badge-soft-primary font-size-11 m-1">{{ date('d-y-M', strtotime($rate->created_at)) }}</a>

                                                    </div>
                                                </td>

                                                <td>
                                                    <ul class="list-inline font-size-20 contact-links mb-0">
                                                        <li class="list-inline-item px-2">
                                                            <a class="delete"
                                                                href="{{ route('rate.delete', ['id' => $rate->id]) }}"
                                                                title="Delete"><i class="bx bx-trash-alt "></i></a>
                                                        </li>
                                                        <li class="list-inline-item px-2">
                                                            <a href="{{ route('rate.edit', ['id' => $rate->id]) }}"
                                                                title="Edit"><i class="bx bx-pencil"></i></a>
                                                        </li>

                                                    </ul>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{ $rates->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
@endsection
