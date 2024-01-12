@extends('layout.partials.app')

@section('title', 'تعديل التصنيف الفرعي للخدمات')

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

                            <form class="m-5" action="" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-4">
                                    <label for="horizontal-title-input" class="col-sm-3 col-form-label fs-4">
                                        العنوان</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="name" value="{{ $serviceSubcategories->name }}"
                                            placeholder="أدخل العنوان" class="form-control" id="horizontal-title-input">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-title-input" class="col-sm-3 col-form-label fs-4">
                                        الخدمة</label>
                                    <div class="col-sm-9">
                                        <select name="service_id" class="form-select" id="service">


                                            @foreach ($services as $sercive)
                                                <option value="{{ $sercive->id }}"
                                                    @if ($sercive->id == $ids['service_id']) selected @endif>
                                                    {{ $sercive->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-title-input" class="col-sm-3 col-form-label fs-4">
                                        تصنيف الخدمة</label>
                                    <div class="col-sm-9">
                                        <select name="service_categories_id" class="form-select" id="service_category">
                                            @foreach ($service_category as $sercive)
                                                <option value="{{ $sercive->id }}"
                                                    @if ($sercive->id == $ids['service_categories_id']) selected @endif>
                                                    {{ $sercive->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-image-input" class="col-sm-3 col-form-label fs-4">
                                        صورة التصنيف الفرعي للخدمة </label>
                                    <div class="col-sm-9">
                                        <input type="file" name="image" class="form-control" id="horizontal-image-input">
                                    </div>
                                </div>

                                <div class="row mb-4 justify-end">
                                    <label class=" col-sm-3 form-check-label fs-4" for="service">
                                        تفعيل التصنيف الفرعي للخدمة
                                    </label>

                                    <div class=" ps-5 col-sm-9 form-check form-switch form-switch-lg mb-3">
                                        <input class="form-check-input" type="checkbox" name="active" id="service" checked>
                                    </div>
                                </div>


                                <div class="row d-flex justify-content-end mb-4">
                                    <button type="submit" class=" col-sm-6 col-md-2 btn btn-warning w-md fs-4 p-1">تحديث
                                        التصنيف
                                        الفرعي</button>
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
        category_select = document.getElementById('service_category');




        function getCategoriesServices() {

            service_id = event.target.value ? event.target.value : category_select.value;

            axios.get('/api/services/categories/' + service_id)
                .then(function(response) {
                    let services_option = "";
                    services = response.data;

                    for (let index = 0; index < services.length; index++) {

                        services_option += '<option value="' + services[index].id + '">' + services[index].name +
                            '</option>';

                    }


                    category_select.innerHTML = services_option;

                })
                .catch(function(error) {

                });
        }
    </script>
@endsection
