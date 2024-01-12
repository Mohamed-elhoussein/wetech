@extends('layout.partials.app')

@section('title', 'قائمة العروض السريعة')

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
                        <h4 class="mb-sm-0 font-size-18 ">قائمة العروض السريعة</h4>

                        <div class="">
                            <a href="/quick_offers/create" class="btn btn-success w-md fs-5">أضف عرض سريع </a>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-4 d-flex">
                                <div class="me-2">
                                    <select id="offer_setting" class="form-select">
                                        <option value="">المرجو الإختيار</option>
                                        <option @if(optional($setting)->full_order === "created-at_asc") selected @endif value="created-at_asc">من الأقدم للأحدث</option>
                                        <option @if(optional($setting)->full_order === "created-at_desc") selected @endif value="created-at_desc">من الأحدث للأقدم</option>
                                        <option @if(optional($setting)->full_order === "price_asc") selected @endif value="price_asc">من الأقل سعر إلى الأعلى</option>
                                        <option @if(optional($setting)->full_order === "price_desc") selected @endif value="price_desc">من الأعلى سعر إلى الأقل</option>
                                    </select>
                                </div>
                                @include('partials.search-input', [
                                    'placeholder' => 'إبحث عن العرض السرع عبر العنوان او الوصف',
                                ])
                            </div>
                            <div class="table-responsive">
                                <table class="table align-middle table-nowrap table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">الصورة</th>
                                            <th scope="col">العنوان</th>
                                            <th scope="col">الوصف</th>
                                            <th scope="col">وقت الانشاء</th>
                                            <th scope="col">تعديل</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($quickOffers as $quickOffer)
                                            <tr>
                                                <td>
                                                    <div class="avatar-xs">

                                                        <img src="{{ $quickOffer->image ?: default_image() }}"
                                                            alt="slider image" class=""
                                                            style="height: 40px;width: 40px">

                                                    </div>
                                                </td>
                                                <td>
                                                    <h5 class="font-size-14 mb-1"><a href="javascript: void(0);"
                                                            class="text-dark">{{ $quickOffer->title }}</a>
                                                    </h5>
                                                </td>
                                                <td>
                                                    <h5 class="font-size-14 mb-1"><a href="javascript: void(0);"
                                                            class="text-dark">{{ $quickOffer->body }}</a>
                                                    </h5>
                                                </td>


                                                <td>
                                                    <div>
                                                        <a href="javascript: void(0);"
                                                            class="badge badge-soft-primary font-size-11 m-1">{{ date('d-y-M', strtotime($quickOffer->created_at)) }}</a>

                                                    </div>
                                                </td>

                                                <td>
                                                    <ul class="list-inline font-size-20 contact-links mb-0">
                                                        <li class=" list-inline-item px-2">
                                                            <a class="delete"
                                                                href="/quick_offers/delete/{{ $quickOffer->id }}"
                                                                title="Delete"><i class="bx bx-trash-alt "></i></a>
                                                        </li>
                                                        <li class="list-inline-item px-2">
                                                            <a href="/quick_offers/edit/{{ $quickOffer->id }}"
                                                                title="Edit"><i class="bx bx-pencil"></i></a>
                                                        </li>
                                                        <li class="list-inline-item px-2">
                                                            <a href="/quick_offers/provider/service/{{ $quickOffer->id }}"
                                                                title="Edit"><i class="bx bx-wrench "></i></a>
                                                        </li>


                                                    </ul>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{ $quickOffers->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
@endsection


@section('scripts')

<script defer>

    $('#offer_setting').on('change', function (e) {
        console.log(e.target.value)

        $.ajax({
            url: '{{ route("quick_offers.settings.update") }}',
            method: "PATCH",
            data: {
                setting: e.target.value
            },
            success: r => {
                console.log(r)
                alertSucces('تم التحديث')
            }
        })

    })

</script>


@endsection
