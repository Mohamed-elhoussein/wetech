@extends('layout.partials.app')

@section('title', 'مزود خدمة')

@section('dashbord_content')

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18"> مزود خدمة </h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="product-detai-imgs">
                                <div class="row ">
                                    <div class="fade  show ">
                                        <h2 class=" position-relative d-flex justify-content-between "
                                            style="color: #90beec;">
                                            <strong> {{ $service->title }}</strong>
                                            <div>
                                                @if ($service->status == 'PENDING')
                                                    <span class="badge font-size-14 bg-warning">Pending</span>
                                                @endif
                                                @if ($service->status == 'ACCEPTED')
                                                    <span class="badge font-size-14 bg-success">Accepted</span>
                                                @endif
                                                @if ($service->status == 'REJECTED')
                                                    <span class="badge font-size-14 bg-danger">Rejected</span>
                                                @endif
                                            </div>
                                        </h2>
                                        <p class="">{{ $service->description }}</p>
                                        <div class="mx-auto w-100 ">
                                            <img src="{{ url($service->thumbnail ?: '/images/default.png') }}" alt=""
                                                class=" mx-auto d-block w-100" style="height: 34em">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="mt-4 mt-xl-3">
                                <div class="row">
                                    <div class=" col-4">
                                        <img class="w-100 img-thumbnail rounded-circle"
                                            src="{{ url($service->provider->avatar) ?? default_image() }}" alt="avatar">
                                    </div>
                                    <div class="col-8 py-2">

                                        <a href="javascript: void(0);"
                                            class="text-primary">{{ $service->provider->username }}</a>
                                        <h4 class="mt-1 mb-3">
                                            {{ $service->provider->first_name . ' ' . $service->provider->second_name . ' ' . $service->provider->last_name }}
                                        </h4>
                                        {{-- <p class="text-muted float-start me-2">
                                            @if (isset($service->provider->rate[0]))
                                                @for ($i = 1; $i < 6; $i++)

                                                    @if ($i <= number_format($service->provider->rate[0]['rating']))
                                                        <span class="bx bxs-star text-warning"></span>
                                                    @else()
                                                        <span class="bx bxs-star "></span>
                                                    @endif()
                                                @endfor
                                                <strong
                                                    class="ms-1 ">{{ number_format($service->provider->rate[0]['rating'], 2) }}</strong>
                                            @else()
                                                <span class="bx bxs-star text-warning"></span>
                                                <span class="bx bxs-star text-warning"></span>
                                                <span class="bx bxs-star text-warning"></span>
                                                <span class="bx bxs-star text-warning"></span>
                                                <span class="bx bxs-star text-warning"></span>

                                            @endif()



                                        </p> --}}
                                    </div>
                                </div>



                                <p class="text-muted my-4">{{ $service->provider->about }}</p>


                                <div class="product-color">
                                    <h5 class="font-size-15">خدمات أخرى :</h5>
                                    <div class="row">
                                        <a href="javascript: void(0);" class="active col-4">
                                            <div class=" rounded shadow ">
                                                <img src="" alt="" class="avatar-md">
                                            </div>
                                            <p>Black</p>
                                        </a>
                                        <a href="javascript: void(0);" class="col-4">
                                            <div class="-0 border rounded">
                                                <img src="" alt="" class="avatar-md">
                                            </div>
                                            <p>Blue</p>
                                        </a>
                                        <a href="javascript: void(0);" class="col-4">
                                            <div class=" rounded">
                                                <img src="" alt="" class="avatar-md">
                                            </div>
                                            <p>Gray</p>
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    </div>


@endsection
