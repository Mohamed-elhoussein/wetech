@extends('layout.partials.app')

@section('title', 'قائمة خدمات المزودين')

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
                        <h4 class="mb-sm-0 font-size-18 ">قائمة خدمات المزودين</h4>

                        <div class="">
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
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">

                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <div class="col-12 mb-4">
                                <form action="" method="GET" class="d-flex align-items-end">
                                    <input type="text" name="q" placeholder="إبحث عن الخدمة او مزود الخدمة"
                                        class="form-control" value="{{ request()->get('q') }}">

                                    <button class="btn btn-primary ms-2">إبحث</button>
                                </form>
                            </div>
                            <div class="table-responsive">
                                <table class="table align-middle table-nowrap table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">مزود الخدمة</th>
                                            <th scope="col">الخدمة</th>
                                            <th scope="col"
                                                style=" overflow: hidden;text-overflow: ellipsis;white-space: nowrap;">
                                                الوصف</th>
                                            <th scope="col">الحالة</th>
                                            <th scope="col">وقت الانشاء</th>
                                            <th scope="col">تعديل</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($providersServices as $service)
                                            <tr>
                                                <td>
                                                    <h5 class="font-size-14 mb-1"><a
                                                            class="text-dark">{{ $service->provider->username }}</a>
                                                    </h5>
                                                </td>
                                                <td>
                                                    <h5 class="font-size-14 mb-1"><a
                                                            class="text-dark">{{ $service->title }}</a>
                                                    </h5>
                                                </td>
                                                <td style="white-space: inherit">
                                                    {{ $service->description ?: 'لا يوجد' }}</td>
                                                <td style="font-weight: bold">

                                                    @if ($service->status == 'ACCEPTED')
                                                        <span
                                                            class=" border  badge-soft-success px-1 rounded border-2 border-success ">
                                                            {{ ' ثم قبولها' }}
                                                        @elseif ($service->status == 'REJECTED')
                                                            <span
                                                                class=" border  badge-soft-danger px-1 rounded border-2 border-danger ">
                                                                {{ ' ثم رفظها' }}
                                                            @else()
                                                                <span
                                                                    class=" border  badge-soft-warning px-1 rounded border-2 border-warning ">
                                                                    {{ ' في الانتظار' }}
                                                    @endif
                                                    </span>
                                                </td>
                                                <td>
                                                    <div>
                                                        <a
                                                            class="badge badge-soft-primary font-size-11 m-1">{{ date('d-y-M', strtotime($service->created_at)) }}</a>

                                                    </div>
                                                </td>

                                                <td>
                                                    <ul class="list-inline font-size-20 contact-links mb-0">
                                                        <li class="list-inline-item px-2">
                                                            <a class="delete"
                                                                href="/providers/services/delete/{{ $service->id }}"
                                                                title="Delete"><i class="bx bx-trash-alt "></i></a>
                                                        </li>
                                                        <li class="list-inline-item px-2">
                                                            <a class="accept"
                                                                href="/providers/services/accept/{{ $service->id }}"
                                                                title="Accept"><i class="fas fa-check "></i></a>
                                                        </li>
                                                        <li class="list-inline-item px-2">
                                                            <a class="Reject"
                                                                href="/providers/services/reject/{{ $service->id }}"
                                                                title="Reject"><i class="bx bx-block"></i></a>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>


                            </div>
                            {{ $providersServices->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
@endsection
