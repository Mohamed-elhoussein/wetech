@extends('layout.partials.app')

@section('title', '')

@section('dashbord_content')


<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18"></h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4"></h4>
                        <div>
                            <div class="w-75 mx-auto ">
                                <form class="search" action="">
                                    <select name="service" id="service" class="form-select">
                                        <option value="">تصنيف بواسطة خدمة </option>
                                        @foreach ($services as $sercive)
                                        <option value="{{ $sercive->id }}" @if (Request::get('service')==$sercive->id) selected @endif>
                                            {{ $sercive->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <div class="d-flex align-items-end">
                                        <input type="text" class="w-100 form-control mt-2" value="{{ request()->get('provider') }}" name="provider" placeholder="إبحث عن طريق المزود" />

                                        <button class="btn btn-success ms-2">بحث</button>

                                    </div>
                                </form>
                            </div>
                            <ul class="list-unstyled chat-list p-5 border w-75 mx-auto shadow-xl mt-5 " data-simplebar="init" style="max-height: 500px; ">
                                <div class="simplebar-wrapper" style="margin: 0px;">
                                    <div class="simplebar-height-auto-observer-wrapper">
                                        <div class="simplebar-height-auto-observer"></div>
                                    </div>
                                    <div class="simplebar-mask">
                                        <div class="simplebar-offset" style="right: -17px; bottom: 0px;">
                                            <div class="simplebar-content-wrapper" style="height: auto; overflow: hidden scroll;">
                                                <div class="simplebar-content" style="padding: 0px;">
                                                    @foreach ($providerservices as $service)
                                                    <li class="active">
                                                        <a href="javascript: void(0);">
                                                            <div class="d-flex">

                                                                <div class="flex-shrink-0 align-self-center me-3">
                                                                    <img src="{{ $service->thumbnail ?: default_image() }}" onerror="this.onerror=null;this.src='/images/avatars/default.png';" class="rounded-circle avatar-sm" alt="">
                                                                </div>

                                                                <div class="flex-grow-1 overflow-hidden">
                                                                    <h5 class="text-truncate font-size-16 mb-2">
                                                                        {{ $service->title }}
                                                                        <span class="font-size-11">({{ optional($service->city)->name ?? '' }})</span>
                                                                    </h5>
                                                                    <h3 class="text-truncate font-size-14 mb-1">
                                                                        {{ $service->provider->username }} <span class="font-size-12" dir="ltr">({{ $service->provider->country->country_code . $service->provider->number_phone ?? '' }})</span>
                                                                    </h3>
                                                                    <p class="text-truncate mb-0">
                                                                        {{ $service->description }}
                                                                    </p>
                                                                </div>
                                                                <div class="font-size-11">
                                                                    @if (in_array($service->id, $serviceIds))
                                                                    <button data-provider-service="{{ $service->id }}" data-method="remove" class="addQuickOffer btn btn-sm btn-danger"><strong>-</strong>
                                                                    </button>
                                                                    @else
                                                                    <button data-provider-service="{{ $service->id }}" data-method="add" class="addQuickOffer btn btn-sm btn-success"><strong>+</strong>
                                                                    </button>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="simplebar-placeholder" style="width: auto; height: 485px;"></div>
                                </div>
                                <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                                    <div class="simplebar-scrollbar" style="transform: translate3d(0px, 0px, 0px); display: none;"></div>
                                </div>
                                <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
                                    <div class="simplebar-scrollbar" style="height: 346px; transform: translate3d(0px, 0px, 0px); display: block;">
                                    </div>
                                </div>
                            </ul>
                            <div class="row mt-5">
                                <button data-quick-offer-id="{{ $id }}" id="saveQuick" class="btn btn-success w-50 mx-auto"><strong>حفظ</strong> </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection