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
                            @if ($errors->any())
                                <div class="col-md-8 offset-2 text-center badge-soft-danger rounded border border-danger">
                                    {!! implode('', $errors->all('<div>:message</div>')) !!}
                                </div>
                            @endif
                            <form class="m-5" action="" method="POST">
                                @csrf
                                <div class="mb-4 row">
                                    <label for="horizontal-select-user" class="col-sm-3 col-form-label fs-4">صاحب
                                        الخدمة</label>
                                    <div class="col-sm-9">
                                        <select name="user_id" id="horizontal-select-user" class="form-control form-select">
                                            @foreach ($users as $user)
                                                <option @if ($user->id == $order->user_id) selected="true" @endif
                                                    value="{{ $user->id }}">
                                                    {{ $user->username }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-4 row">
                                    <label for="providers" class="col-sm-3 col-form-label fs-4">مقدم
                                        الخدمة</label>
                                    <div class="col-sm-9">
                                        <select name="provider_id" id="providers" onchange="getProviderServices(this)"
                                            class="form-control form-select">
                                            @foreach ($providers as $provider)
                                                <option @if ($provider->id == $order->provider_id) selected="true" @endif
                                                    value="{{ $provider->id }}">
                                                    {{ $provider->username }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-4 row">
                                    <label for="provider_services" class="col-sm-3 col-form-label fs-4">
                                        الخدمة</label>
                                    <div class="col-sm-9">
                                        <select name="service_id" id="provider_services" class="form-control form-select">
                                            @foreach ($order->provider->services as $service)
                                                <option @if ($service->id == $order->service->id) selected="true" @endif
                                                    value="{{ $service->id }}">
                                                    {{ $service->title }}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-title-input" class="col-sm-3 col-form-label fs-4">
                                        الثمن</label>
                                    <div class="col-sm-9">
                                        <input type="text" value="{{ $order->price }}" name="price"
                                            placeholder="أدخل الثمن" class="form-control" id="horizontal-title-input">
                                    </div>
                                </div>

                                <div class="row d-flex justify-content-end mb-4">
                                    <button type="submit" class=" col-sm-6 col-md-2 btn btn-warning w-md fs-4 p-1">تحديث
                                        الطلب</button>
                                </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>






@endsection




@section('footer_scripts')



    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        services_select = document.getElementById('provider_services');


        function getProviderServices() {

            _provider_id = event.target.value;

            axios.post('/orders/services', {
                    provider_id: _provider_id,
                })
                .then(function(response) {
                    let services_option = "";
                    services = response.data;

                    for (let index = 0; index < services.length; index++) {

                        services_option += '<option value="' + services[index].id + '">' + services[index].title +
                            '</option>';

                    }


                    services_select.innerHTML = services_option;

                })
                .catch(function(error) {

                });
        }
    </script>
@endsection
