@extends('layout.partials.app')

@section('title', '')

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
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-5">
                                    <div class="product-detai-imgs">
                                        <div class="row">

                                            <div class="col-md-8 offset-md-1 col-sm-10 col-8">
                                                <div class="tab-content" id="v-pills-tabContent">
                                                    <div class="fade  show">
                                                        <div>
                                                            <img src="{{ url($provider->avatar) }}" alt=""
                                                                class="img-fluid mx-auto d-block">
                                                        </div>
                                                    </div>


                                                </div>

                                                {{-- <div class="text-center">
                                                    <button type="button"
                                                        class="btn btn-primary waves-effect waves-light mt-2 me-1">
                                                        <i class="bx bx-cart me-2"></i> Add to cart
                                                    </button>
                                                    <button type="button"
                                                        class="btn btn-success waves-effect  mt-2 waves-light">
                                                        <i class="bx bx-shopping-bag me-2"></i>Buy now
                                                    </button>
                                                </div> --}}

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-6">
                                    <div class="mt-4 mt-xl-3">
                                        <span class="text-primary font-size-18">{{ $provider->username }}</span>

                                        <h4 class="mt-1 mb-3">
                                            {{ $provider->first_name . ' ' . $provider->second_name . ' ' . $provider->last_name }}
                                            <span class="text-muted font-size-13" dir="ltr">

                                                ( {{ $provider->country->country_code . $provider->number_phone }})
                                            </span>
                                        </h4>

                                        <p class="text-muted float-start me-2">
                                            @if (isset($provider->rate[0]))
                                                @for ($i = 1; $i < 6; $i++)
                                                    @if ($i <= number_format($provider->rate[0]['rating']))
                                                        <span class="bx bxs-star text-warning"></span>
                                                    @else()
                                                        <span class="bx bxs-star "></span>
                                                    @endif()
                                                @endfor
                                                <strong
                                                    class="ms-1 ">{{ number_format($provider->rate[0]['rating'], 2) }}</strong>
                                            @else()
                                                <span class="bx bxs-star text-warning"></span>
                                                <span class="bx bxs-star text-warning"></span>
                                                <span class="bx bxs-star text-warning"></span>
                                                <span class="bx bxs-star text-warning"></span>
                                                <span class="bx bxs-star text-warning"></span>
                                            @endif()



                                        </p>
                                        <p class="text-muted mb-4">( عدد التقييمات : {{ $provider->rate_count ?? '0' }} )
                                        </p>



                                        <p class="text-muted mb-4">{{ $provider->about }}</p>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div>
                                                    <p class="text-muted"><i
                                                            class="bx bx-world font-size-16 align-middle text-primary me-1"></i>
                                                        {{ $provider->country->name }}</p>
                                                    <p class="text-muted"><i
                                                            class="bx bxs-city font-size-16 align-middle text-primary me-1"></i>
                                                        {{ $provider->city->name ?? 'المدينة' }}</p>
                                                    <p class="text-muted"><i
                                                            class="bx bx-map font-size-16 align-middle text-primary me-1"></i>
                                                        {{ $provider->street->name ?? 'الحي' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div>
                                                    <p class="text-muted"><i
                                                            class="bx bx-dollar  font-size-16 align-middle text-primary me-1"></i>
                                                        <span dir="ltr" id="balance_id">
                                                            {{ $provider->balance }}
                                                        </span>
                                                        @if ($provider->country->unit)
                                                            <span class="ms-1 font-size-11">
                                                                {{ '(' . $provider->country->unit . ')' }}
                                                            </span>
                                                        @endif

                                                    </p>
                                                    <p class="text-muted"><i
                                                            class="bx bx-disc  font-size-16 align-middle text-primary me-1"></i>
                                                        {{ $provider->is_blocked ? 'محظور' : 'يعمل' }} </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="rounded border col-6  p-2 bg-light bg-soft d-flex justify-content-between align-items-center">
                                            <span class="border-e h-full ">

                                                <i class="bx bx-cog bx-spin h-100 font-size-20 text-primary"></i>


                                            </span>
                                            <strong id="provider_key"
                                                class="font-size-20 bg-white  px-2 text-center rounded shadow"
                                                style="letter-spacing: 0.2em;width: 7em;  ">{{ cache('provider-key-' . $provider->id) ?? ' - - - - ' }}</strong>
                                            <a data-provider="{{ $provider->id }}" id="newProviderKey"
                                                class="  btn btn-success  bg-soft p-1 px-2  rounded  "
                                                style="cursor: pointer">
                                                <strong class="">أضف</strong>
                                            </a>
                                        </div>


                                    </div>
                                </div>
                            </div>
                            <!-- end row -->
                            <hr class="m-5">
                            <div class="mt-5 px-3">
                                <h5 class="mb-3 fs-4">معلومات المزود :</h5>


                                <div class="mb-4 row">
                                    <label for="example-number-input"
                                        class="col-sm-3 col-form-label font-size-16">الهوية</label>
                                    <div class="col-sm-8">

                                        @if (!empty($provider->identity))
                                            <span>اضفط على الصورة لرؤيتها بشكل أكبر</span> <br>
                                            <a href='{{ $provider->identity }}' target='_blank'>
                                                <img src="{{ $provider->identity }}"
                                                    style=" max-height: 240px; width: 400px; object-fit: cover; margin-bottom:15px;" />
                                            </a>
                                        @endif
                                    </div>


                                    <label for="example-number-input"
                                        class="col-sm-3 col-form-label font-size-16">العمولة</label>
                                    <div class="col-sm-6">
                                        <input class="form-control " type="number"
                                            value="{{ $provider->commission->commission ?? '0' }}" id="commission_value"
                                            style="text-align: end;" id="example-number-input">
                                    </div>
                                    <div class="col-sm-1 ps-0">
                                        <div class="form-control bg-primary bg-soft ">
                                            <div class="form-check ">
                                                <input class="form-check-input px-1" type="checkbox"
                                                    id="Commission_percentage"
                                                    @if ($provider->commission->percentage ?? 0) checked @endif>
                                                <label class="form-check-label" for="Commission_percentage">
                                                    <strong>النسبة</strong>
                                                </label>
                                            </div>
                                        </div>
                                    </div>





                                    <div class="col-sm-2 ">
                                        @if (!$provider->commission)
                                            <button data-provider="{{ $provider->id }}" id="newCommission"
                                                class=" newCommission btn btn-success btn-sm">
                                                <strong class="font-size-14">تسجيل</strong>
                                            </button>
                                        @else
                                            <button data-provider="{{ $provider->id }}" id="updateCommission"
                                                class=" newCommission btn btn-warning btn-sm">
                                                <strong class="font-size-14">تحديث</strong>
                                            </button>
                                            <button id="deleteCommission" data-provider="{{ $provider->id }}"
                                                class="newCommission btn btn-danger btn-sm">
                                                <strong class="font-size-14">إزالة</strong>
                                            </button>
                                        @endif
                                    </div>
                                </div>



                                <div class="mb-4 row">
                                    <label for="example-number-input" class="col-sm-3 col-form-label font-size-16"> سقف
                                        الدين</label>
                                    <div class="col-sm-7">
                                        <input class="form-control " type="number"
                                            value="{{ $provider->debt_ceiling }}" id="debt_ceiling_value"
                                            style="text-align: end;" id="example-number-input">
                                    </div>
                                    <div class="col-sm-2 ">
                                        <button data-provider="{{ $provider->id }}" id="debt_ceiling"
                                            class="btn btn-success btn-sm">
                                            <strong class="font-size-14">تسجيل</strong>
                                        </button>
                                    </div>
                                </div>

                                <div class="mb-4 row">
                                    <label for="example-transaction-input" class="col-sm-3 col-form-label font-size-16">
                                        التحويلات</label>
                                    <div class="col-sm-6">
                                        <input class="form-control " type="number" value="0"
                                            id="transaction_value" style="text-align: end;"
                                            id="example-transaction-input">
                                    </div>
                                    <div class="col-sm-1 ps-0">
                                        <div class="form-control bg-primary bg-soft ">
                                            <div class="form-check ">
                                                <input class="form-check-input px-1" type="checkbox" id="is_usd">
                                                <label class="form-check-label" for="is_usd">
                                                    <strong>دولار</strong>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 ">
                                        <button data-provider="{{ $provider->id }}" data-type="WITHDRAWAL"
                                            data-provider="{{ $provider->id }}"
                                            class=" newTransaction btn btn-success btn-sm">
                                            <strong class="font-size-14">دفع</strong>
                                        </button>
                                        <button data-provider="{{ $provider->id }}" data-type="DEPOSIT"
                                            data-provider="{{ $provider->id }}"
                                            class="newTransaction btn btn-warning btn-sm">
                                            <strong class="font-size-14">سحب</strong>
                                        </button>
                                    </div>
                                </div>

                            </div>
                            <!-- end Specifications -->
                            <div class="mt-5">
                                <h5>التقيمات :</h5>
                                @foreach ($provider->rates as $rating)
                                    <div class="d-flex py-3 border-bottom">
                                        <div class="flex-shrink-0 me-3">
                                            <img src="{{ url($rating->user->avatar) }}" class="avatar-md rounded-circle"
                                                alt="img">
                                        </div>

                                        <div class="flex-grow-1">
                                            <h5 class="mb-1 font-size-15">{{ $rating->user->username }}</h5>
                                            <p class="text-muted">{{ $rating->comment ?? 'لا تعليق' }}</p>

                                            <div class="text-muted font-size-12"><i class=" text-primary me-1"></i>

                                                @if (isset($rating->stars))
                                                    @for ($i = 1; $i < 6; $i++)
                                                        @if ($i <= number_format($rating->stars))
                                                            <span class="bx bxs-star text-warning"></span>
                                                        @else()
                                                            <span class="bx bxs-star "></span>
                                                        @endif()
                                                    @endfor
                                                    <strong class="ms-1 ">{{ number_format($rating->stars, 2) }}</strong>
                                                @endif()
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-5">
                                <form method="POST">
                                    @csrf
                                    <div class="row align-items-start">
                                        <div class="col-sm-3">
                                            <h5>روابط مواقع التواصل الإجتماعي :</h5>
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="form-group">
                                                <input
                                                    value="{{ $provider->social_media_links['facebook'] ?? old('social_media_links.facebook') }}"
                                                    placeholder="رابط الفايسبوك" name="social_media_links[facebook]"
                                                    type="text"
                                                    class="form-control @error('social_media_links.facebook') is-invalid @enderror">
                                                @error('social_media_links.facebook')
                                                    <span style="font-size: 95%;font-weight: bold"
                                                        class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-1">
                                                <input placeholder="رابط تويتر" name="social_media_links[twitter]"
                                                    type="text"
                                                    value="{{ $provider->social_media_links['twitter'] ?? old('social_media_links.twitter') }}"
                                                    class="form-control @error('social_media_links.twitter') is-invalid @enderror">

                                                @error('social_media_links.twitter')
                                                    <span style="font-size: 95%;font-weight: bold"
                                                        class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-1">
                                                <input placeholder="رابط الإنسغرام" name="social_media_links[instagram]"
                                                    type="text"
                                                    value="{{ $provider->social_media_links['instagram'] ?? old('social_media_links.instagram') }}"
                                                    class="form-control @error('social_media_links.instagram') is-invalid @enderror">

                                                @error('social_media_links.instagram')
                                                    <span style="font-size: 95%;font-weight: bold"
                                                        class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group mt-1">
                                                <input placeholder="رابط التيليغرام" name="social_media_links[telegram]"
                                                    type="text"
                                                    value="{{ $provider->social_media_links['telegram'] ?? old('social_media_links.telegram') }}"
                                                    class="form-control @error('social_media_links.telegram') is-invalid @enderror">

                                                @error('social_media_links.telegram')
                                                    <span style="font-size: 95%;font-weight: bold"
                                                        class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <button class="btn btn-success btn-sm">
                                                <strong class="font-size-14">تحديث</strong>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="mt-5">
                                <div class="row align-items-start">
                                    <div class="col-sm-3">
                                        <h5>هل تم التحقق من رقم هاتف المزود :</h5>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="bg-primary bg-soft form-control">
                                            <div class="form-check ">
                                                <input class="form-check-input px-1" type="checkbox" id="verified_phone"
                                                    data-provider="{{ $provider->id }}"
                                                    @if ($provider->verified) checked @endif>
                                                <label class="form-check-label" for="verified_phone">
                                                    <strong>تم التحقق</strong>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="mt-5">
                                <div class="row align-items-start">
                                    <div class="col-sm-3">
                                        <h5>هل تم التحقق من الإيميل :</h5>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="bg-primary bg-soft form-control">
                                            <div class="form-check ">
                                                <input class="form-check-input px-1" type="checkbox" id="verified_email"
                                                    data-provider="{{ $provider->id }}"
                                                    @if ($provider->email_verified) checked @endif>
                                                <label class="form-check-label" for="verified_email">
                                                    <strong>تم التحقق</strong>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="mt-5">
                                <div class="row align-items-start">
                                    <div class="col-sm-3">
                                        <h5>هل تم التحقق من هوية المزود :</h5>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="bg-primary bg-soft form-control">
                                            <div class="form-check ">
                                                <input class="form-check-input px-1" type="checkbox"
                                                    id="verified_identity" data-provider="{{ $provider->id }}"
                                                    @if ($provider->identity_verified) checked @endif>
                                                <label class="form-check-label" for="verified_identity">
                                                    <strong>تم التحقق</strong>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5">
                                <form action="{{ route('provider.skills', ['id' => $provider->id]) }}" method="POST"
                                    id="skill_form">
                                    @csrf
                                    <div class="row align-items-start">
                                        <div class="col-sm-3">
                                            <h5>المهارات :</h5>
                                        </div>
                                        <div class="col-sm-7 form_body">
                                            @foreach ($provider->provider_skills as $provider_skill)
                                                <div class="select-row-container mb-2">
                                                    @if ($loop->index == 0)
                                                        <button type="button"
                                                            class="add-select-row btn btn-primary btn-sm">
                                                            <i class="bx bx-plus"></i>
                                                        </button>
                                                    @else
                                                        <button type="button"
                                                            class="remove-select-row btn btn-danger btn-sm">
                                                            <i class="bx bx-minus"></i>
                                                        </button>
                                                    @endif
                                                    <select name="skills[]" class="form-select">
                                                        <option value disabled>إختر مهارة</option>
                                                        @foreach ($skills as $skill)
                                                            <option @if ($skill->id === $provider_skill->id) selected @endif
                                                                value="{{ $skill->id }}">{{ $skill->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endforeach
                                            @if ($provider->provider_skills->count() === 0)
                                                <div class="select-row-container mb-2">
                                                    <button type="button" class="add-select-row btn btn-primary btn-sm">
                                                        <i class="bx bx-plus"></i>
                                                    </button>
                                                    <select name="skills[]" class="form-select">

                                                        <option value disabled selected>إختر مهارة</option>

                                                        @foreach ($skills as $skill)
                                                            <option value="{{ $skill->id }}">{{ $skill->name }}
                                                            </option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-sm-2">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <strong class="font-size-14">تحديث</strong>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const skills = {!! json_encode($skills->toArray()) !!}
    </script>
@endsection
