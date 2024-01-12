@extends('layout.partials.app', [
    'hide_footer' => true,
])

@section('title', 'الإعدادت')

@section('dashbord_content')
    <div class="page-content">
        <div class="container-fluid">

            <div class="row flex-row-reverse">
                <div class="col-xl-2 col-sm-3 d-flex flex-column">
                    <button id="settings"
                        class="mb-2 btn-active fs-5 mx-2 btn btn-outline-info d-flex flex-column align-items-center py-3">
                        <i class="bx bx-cog fs-1 mb-2"></i>
                        الإعدادات
                        العامة </button>
                    <button id="settings_store"
                        class="fs-5 mb-2 mx-2 btn btn-outline-info d-flex flex-column align-items-center py-3">
                        <i class="bx bx-link-alt fs-1 mb-2"></i>
                        روابط
                        التطبيقات </button>
                    <button id="settings_media"
                        class="fs-5 mb-2 mx-2 btn btn-outline-info d-flex flex-column align-items-center py-3">
                        <i class="bx bxl-facebook fs-1 mb-2"></i>
                        روابط
                        وسائل التواصل</button>
                    <button id="oder_settings"
                        class="fs-5 mb-2 mx-2 btn btn-outline-info d-flex flex-column align-items-center py-3">
                        <i class="bx bx-slider fs-1 mb-2"></i>
                        إعدادات
                        أخرى</button>
                    <button id="payment_settings"
                        class="fs-5 mb-2 mx-2 btn btn-outline-info d-flex flex-column align-items-center py-3">
                        <i class="bx bx-money fs-1 mb-2"></i>
                        إعدادات
                        الدفع
                    </button>
                    <button id="email_settings"
                        class="fs-5 mb-2 mx-2 btn btn-outline-info d-flex flex-column align-items-center py-3">
                        <i class="bx bx-mail-send  fs-1 mb-2"></i>
                        إعدادات
                        الإميل</button>
                </div>
                <div class="col-xl-10 col-sm-9">
                    <div class="row settings active">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-4 fs-4">الإعدادات</h4>
                                <hr class="mb-4  my-2">


                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body">

                                                <h4 class="card-title mb-2"></h4>


                                                <form action="" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="row mb-4">
                                                        @if ($settings['logo'])
                                                            <div class="col-12" align="center">
                                                                <img class="avatar-xl "
                                                                    src="{{ url($settings['logo']) }}" alt="">
                                                            </div>
                                                        @endif

                                                        <label for="horizontal-email-input"
                                                            class=" fs-4 col-sm-3 col-form-label">الأيقونة</label>
                                                        <div class="col-sm-9">
                                                            <input type="file" name="logo" class="  form-control">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        @if ($settings['icon'])
                                                            <div class="col-12" align="center">
                                                                <img class="avatar-xl "
                                                                    src="{{ url($settings['icon']) }}" alt="">
                                                            </div>
                                                        @endif
                                                        <label for="horizontal-email-input"
                                                            class=" fs-4 col-sm-3 col-form-label">شعار الموقع</label>
                                                        <div class="col-sm-9">
                                                            <input type="file" name="icon" class="  form-control" src="">
                                                        </div>
                                                    </div>

                                                    <div class="row mb-4">
                                                        <label for="horizontal-name-input"
                                                            class="col-sm-3 col-form-label fs-4">اسم التطبيق</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" name="site_name"
                                                                value="@isset($settings['site_name']) {{ $settings['site_name'] }} @endisset"
                                                                class="form-control" id="horizontal-name-input">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="horizontal-description-input"
                                                            class="col-sm-3 col-form-label fs-4">سطر الوصف</label>
                                                        <div class="col-sm-9">
                                                            <textarea class="form-control" name="description" id="horizontal-description-input" rows="4">
    @isset($settings['description'])
{{ $settings['description'] }}
@endisset
    </textarea>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-4">
                                                        <label for="horizontal-lang-input"
                                                            class="col-sm-3 col-form-label fs-4">لغة التطبيق</label>
                                                        <div class="col-sm-9 fs-1">
                                                            <select name="lang" class="form-select">
                                                                <option @if (isset($settings['lang']) && $settings['lang'] == 'en,US') selected @endif
                                                                    value="en,US">english</option>
                                                                <option @if (isset($settings['lang']) && $settings['lang'] == 'ar,SA') selected @endif
                                                                    value="ar,SA">عربي</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-4">
                                                        <label for="horizontal-mobile-input"
                                                            class="col-sm-3 col-form-label fs-4">رقم الهاتف</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" name="phone"
                                                                value="@isset($settings['phone']) {{ $settings['phone'] }} @endisset"
                                                                class="form-control" id="horizontal-mobile-input">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="horizontal-email-input"
                                                            class="col-sm-3 col-form-label fs-4">البريد الالكتروني </label>
                                                        <div class="col-sm-9">
                                                            <input style="text-align: right" type="email"
                                                                name="contact_email"
                                                                value="@isset($settings['contact_email']) {{ $settings['contact_email'] }} @endisset"
                                                                class="form-control" id="horizontal-email-input">
                                                        </div>
                                                    </div>

                                                    <div class="row mb-4">
                                                        <label for="horizontal-Copyrights-input"
                                                            class="col-sm-3 col-form-label fs-4">نص حقوق النشر </label>
                                                        <div class="col-sm-9">
                                                            <input type="text" name="site_copyright"
                                                                value="@isset($settings['site_copyright']) {{ $settings['site_copyright'] }} @endisset"
                                                                class="form-control" id="horizontal-Copyrights-input">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="horizontal-description-input"
                                                            class="col-sm-3 col-form-label fs-4">سياسة الخصوصية</label>
                                                        <div class="col-sm-9">
                                                            <textarea class="form-control" name="privacy_policy" id="horizontal-description-input" rows="4">
    @isset($settings['privacy_policy'])
{{ $settings['privacy_policy'] }}
@endisset
    </textarea>
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-9 mx-auto my-md-5">
                            <button class="btn btn-primary w-100  fs-4">حفظ التغييرات</button>
                        </div>
                    </div>

                    <div class="row settings_store d-none">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-4 fs-3"> إعدادات روابط تطبيقات دكتور تك </h4>
                                    <hr class="mb-4  my-2">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-body">

                                                    <div class="row mb-4">
                                                        <label for="horizontal-play_store-input"
                                                            class="col-sm-3 col-form-label fs-4">
                                                            رابط متجر كوكل لتطبيق
                                                            المستخدم</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" name="client_play_store"
                                                                value="@isset($settings['client_play_store']) {{ $settings['client_play_store'] }} @endisset"
                                                                class="form-control" id="horizontal-play_store-input">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="horizontal-apple-input"
                                                            class="col-sm-3 col-form-label fs-4">
                                                            رابط متجر أبل لتطبيق المستخدم</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" name="client_apple_store"
                                                                value="@isset($settings['client_apple_store']) {{ urldecode($settings['client_apple_store']) }} @endisset"
                                                                class="form-control" id="horizontal-apple-input">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="horizontal-play_store-input"
                                                            class="col-sm-3 col-form-label fs-4">
                                                            رابط متجر كوكل لتطبيق
                                                            المزود</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" name="provider_play_store"
                                                                value="@isset($settings['provider_play_store']) {{ urldecode($settings['provider_play_store']) }} @endisset"
                                                                class="form-control" id="horizontal-play_store-input">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="horizontal-apple-input"
                                                            class="col-sm-3 col-form-label fs-4">
                                                            رابط متجر أبل لتطبيق المزود</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" name="provider_apple_store"
                                                                value="@isset($settings['provider_apple_store']) {{ urldecode($settings['provider_apple_store']) }} @endisset"
                                                                class="form-control" id="horizontal-apple-input">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="horizontal-play_store-input"
                                                            class="col-sm-3 col-form-label fs-4">
                                                            رابط متجر كوكل لتطبيق
                                                            المراقب</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" name="monitor_play_store"
                                                                value="@isset($settings['monitor_play_store']) {{ $settings['monitor_play_store'] }} @endisset"
                                                                class="form-control" id="horizontal-play_store-input">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="horizontal-apple-input"
                                                            class="col-sm-3 col-form-label fs-4">
                                                            رابط متجر أبل لتطبيق المراقب</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" name="monitor_apple_store"
                                                                value="@isset($settings['monitor_apple_store']) {{ $settings['monitor_apple_store'] }} @endisset"
                                                                class="form-control" id="horizontal-apple-input">
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                        </div> <!-- end col -->
                                    </div> <!-- end row -->
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-9 mx-auto my-md-5">
                                <button class="btn btn-primary w-100  fs-4">حفظ التغييرات</button>
                            </div>
                        </div>
                    </div>

                    <div class="row settings_media d-none">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-4 fs-3"> إعدادات وسائل التواصل الاجتماعي</h4>
                                    <hr class="mb-4  my-2">


                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-body">

                                                    <div class="row mb-4">
                                                        <label for="horizontal-facebook-input"
                                                            class="col-sm-3 col-form-label fs-4">فايسبوك</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" name="facebook_url"
                                                                value="@isset($settings['facebook_url']) {{ $settings['facebook_url'] }} @endisset"
                                                                class="form-control" id="horizontal-facebook-input">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="horizontal-email-input"
                                                            class=" fs-4 col-sm-3 col-form-label">أيقونة فايسبوك</label>
                                                        <div class="col-sm-9">
                                                            <input type="file" name="facebook_logo" class="  form-control">
                                                        </div>
                                                    </div>

                                                    <div class="row mb-4">
                                                        <label for="horizontal-twiter-input"
                                                            class="col-sm-3 col-form-label fs-4">تويتر</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" name="twiter_url"
                                                                value="@isset($settings['twiter_url']) {{ $settings['twiter_url'] }} @endisset"
                                                                class="form-control" id="horizontal-twiter-input">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="horizontal-email-input"
                                                            class=" fs-4 col-sm-3 col-form-label">أيقونة تويتر</label>
                                                        <div class="col-sm-9">
                                                            <input type="file" name="twiter_logo" class="  form-control">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="horizontal-instagram-input"
                                                            class="col-sm-3 col-form-label fs-4">انستغرام </label>
                                                        <div class="col-sm-9">
                                                            <input type="text" name="instagram_url"
                                                                value="@isset($settings['instagram_url']) {{ $settings['instagram_url'] }} @endisset"
                                                                class="form-control" id="horizontal-instagram-input">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="horizontal-email-input"
                                                            class=" fs-4 col-sm-3 col-form-label">أيقونة انستغرام</label>
                                                        <div class="col-sm-9">
                                                            <input type="file" name="instagram_logo"
                                                                class="  form-control">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="horizontal-linkden-input"
                                                            class="col-sm-3 col-form-label fs-4">لينكدن</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" name="linkden_url"
                                                                value="@isset($settings['linkden_url']) {{ $settings['linkden_url'] }} @endisset"
                                                                class="form-control" id="horizontal-linkden-input">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="horizontal-email-input"
                                                            class=" fs-4 col-sm-3 col-form-label">أيقونة لينكدن</label>
                                                        <div class="col-sm-9">
                                                            <input type="file" name="linkden_logo" class="  form-control">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="horizontal-youtobe-input"
                                                            class="col-sm-3 col-form-label fs-4">يوتوب</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" name="youtobe_url"
                                                                value="@isset($settings['youtobe_url']) {{ $settings['youtobe_url'] }} @endisset"
                                                                class="form-control" id="horizontal-youtobe-input">
                                                        </div>
                                                    </div>

                                                    <div class="row mb-4">
                                                        <label for="horizontal-email-input"
                                                            class=" fs-4 col-sm-3 col-form-label">أيقونة يوتوب</label>
                                                        <div class="col-sm-9">
                                                            <input type="file" name="youtobe_logo" class="  form-control">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="horizontal-whatsapp-input"
                                                            class="col-sm-3 col-form-label fs-4">واتساب</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" name="whatsapp_url"
                                                                value="@isset($settings['whatsapp_url']) {{ $settings['whatsapp_url'] }} @endisset"
                                                                class="form-control" id="horizontal-whatsapp-input">
                                                        </div>
                                                    </div>

                                                    <div class="row mb-4">
                                                        <label for="horizontal-email-input"
                                                            class=" fs-4 col-sm-3 col-form-label">أيقونة واتساب</label>
                                                        <div class="col-sm-9">
                                                            <input type="file" name="whatsapp_logo" class="  form-control">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="horizontal-whatsapp-input"
                                                            class="col-sm-3 col-form-label fs-4">
                                                            واتساب التواصل مع
                                                            المزودين</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" name="whatsapp_provider_url"
                                                                value="@isset($settings['whatsapp_provider_url']) {{ $settings['whatsapp_provider_url'] }} @endisset"
                                                                class="form-control" id="horizontal-whatsapp-input">
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div> <!-- end col -->
                                    </div> <!-- end row -->
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-9 mx-auto my-md-3">
                                <button class="btn btn-primary w-100  fs-4 my-md-5  ">حفظ التغييرات</button>
                            </div>
                        </div>
                    </div>

                    <div class="row oder_settings d-none">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-4 fs-3"> إعدادات أخرى </h4>
                                    <hr class="mb-4  my-2">


                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-body">

                                                    <div class="row mb-4">
                                                        <label for="horizontal-facebook-input"
                                                            class="col-sm-4 col-form-label fs-4">الضريبة على القيمة
                                                            المضافة</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" name="default_commission"
                                                                value="{{ $settings['default_commission'] ?? '' }}"
                                                                class="form-control" id="horizontal-facebook-input">
                                                        </div>
                                                    </div>


                                                    <div class="row mb-4">
                                                        <label for="horizontal-twiter-input"
                                                            class="col-sm-4 col-form-label fs-4">مدة
                                                            اعادة الإرسال بالثواني
                                                        </label>
                                                        <div class="col-sm-8">
                                                            <input type="text" name="resend_time"
                                                                value="{{ $settings['resend_time'] ?? '' }}"
                                                                class="form-control" id="horizontal-twiter-input">
                                                        </div>
                                                    </div>

                                                    <div class="row mb-4">
                                                        <label for="horizontal-instagram-input"
                                                            class="col-sm-4 col-form-label fs-4">تطبيق المستخدم تحت الصيانة
                                                        </label>
                                                        <div class="col-sm-8">
                                                            <select name="client_under_maintenance_show_webview"
                                                                class="form-select">
                                                                <option @if (isset($settings['client_under_maintenance_show_webview']) && $settings['client_under_maintenance_show_webview'] == '1') selected @endif
                                                                    value="1">
                                                                    نعم</option>
                                                                <option @if (isset($settings['client_under_maintenance_show_webview']) && $settings['client_under_maintenance_show_webview'] == '0') selected @endif
                                                                    value="0">لا
                                                                </option>
                                                            </select>

                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="horizontal-instagram-input"
                                                            class="col-sm-4 col-form-label fs-4">تطبيق المراقب تحت الصيانة
                                                        </label>
                                                        <div class="col-sm-8">
                                                            <select name="monitor_under_maintenance_show_webview"
                                                                class="form-select">
                                                                <option @if (isset($settings['monitor_under_maintenance_show_webview']) && $settings['monitor_under_maintenance_show_webview'] == '1') selected @endif
                                                                    value="1">
                                                                    نعم</option>
                                                                <option @if (isset($settings['monitor_under_maintenance_show_webview']) && $settings['monitor_under_maintenance_show_webview'] == '0') selected @endif
                                                                    value="0">لا
                                                                </option>
                                                            </select>

                                                        </div>
                                                    </div>

                                                    <div class="row mb-4">
                                                        <label for="horizontal-linkden-input"
                                                            class="col-sm-4 col-form-label fs-4">تطبيق مزود الخدمة تحت
                                                            الصيانة</label>
                                                        <div class="col-sm-8">

                                                            <select name="provider_under_maintenance_show_webview"
                                                                class="form-select">
                                                                <option @if (isset($settings['provider_under_maintenance_show_webview']) && $settings['provider_under_maintenance_show_webview'] == '1') selected @endif
                                                                    value="1">
                                                                    نعم</option>
                                                                <option @if (isset($settings['provider_under_maintenance_show_webview']) && $settings['provider_under_maintenance_show_webview'] == '0') selected @endif
                                                                    value="0">لا
                                                                </option>
                                                            </select>


                                                        </div>
                                                    </div>

                                                    <div class="row mb-4">
                                                        <label for="horizontal-youtobe-input"
                                                            class="col-sm-4 col-form-label fs-4">رابط
                                                            الصيانة في تطبيق المستخدم
                                                        </label>
                                                        <div class="col-sm-8">
                                                            <input type="text" name="webview_url_client"
                                                                value="{{ $settings['webview_url_client'] ?? '' }}"
                                                                class="form-control" id="horizontal-youtobe-input">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="horizontal-youtobe-input"
                                                            class="col-sm-4 col-form-label fs-4">رابط
                                                            الصيانة في تطبيق المراقب
                                                        </label>
                                                        <div class="col-sm-8">
                                                            <input type="text" name="webview_url_monitor"
                                                                value="{{ $settings['webview_url_monitor'] ?? '' }}"
                                                                class="form-control" id="horizontal-youtobe-input">
                                                        </div>
                                                    </div>


                                                    <div class="row mb-4">
                                                        <label for="horizontal-whatsapp-input"
                                                            class="col-sm-4 col-form-label fs-4">رابط الصيانة في تطبيق
                                                            مزودي
                                                            الخدمات </label>
                                                        <div class="col-sm-8">
                                                            <input type="text" name="webview_url_provider"
                                                                value="{{ $settings['webview_url_provider'] ?? '' }}"
                                                                class="form-control" id="horizontal-whatsapp-input">
                                                        </div>
                                                    </div>

                                                    <div class="row mb-4">
                                                        <label for="horizontal-whatsapp-input"
                                                            class="col-sm-4 col-form-label fs-4">تطبيق المستخدم آخر نسخة
                                                            أندرويد
                                                        </label>
                                                        <div class="col-sm-8">
                                                            <input type="text" name="client_last_version_android"
                                                                value="{{ $settings['client_last_version_android'] ?? '' }}"
                                                                class="form-control" id="horizontal-whatsapp-input">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="horizontal-whatsapp-input"
                                                            class="col-sm-4 col-form-label fs-4">تطبيق المستخدم آخر نسخة
                                                            أيفون
                                                        </label>
                                                        <div class="col-sm-8">
                                                            <input type="text" name="client_last_version_ios"
                                                                value="{{ $settings['client_last_version_ios'] ?? '' }}"
                                                                class="form-control" id="horizontal-whatsapp-input">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="horizontal-whatsapp-input"
                                                            class="col-sm-4 col-form-label fs-4">تطبيق المراقب آخر نسخة
                                                            أندرويد
                                                        </label>
                                                        <div class="col-sm-8">
                                                            <input type="text" name="monitor_last_version_android"
                                                                value="{{ $settings['monitor_last_version_android'] ?? '' }}"
                                                                class="form-control" id="horizontal-whatsapp-input">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="horizontal-whatsapp-input"
                                                            class="col-sm-4 col-form-label fs-4">تطبيق المراقب آخر نسخة
                                                            أيفون
                                                        </label>
                                                        <div class="col-sm-8">
                                                            <input type="text" name="monitor_last_version_ios"
                                                                value="{{ $settings['monitor_last_version_ios'] ?? '' }}"
                                                                class="form-control" id="horizontal-whatsapp-input">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="horizontal-whatsapp-input"
                                                            class="col-sm-4 col-form-label fs-4">تطبيق مزود الخدمة آخر نسخة
                                                            أندرويد</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" name="provider_last_version_android"
                                                                value="{{ $settings['provider_last_version_android'] ?? '' }}"
                                                                class="form-control" id="horizontal-whatsapp-input">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="horizontal-whatsapp-input"
                                                            class="col-sm-4 col-form-label fs-4">تطبيق مزود الخدمة آخر نسخة
                                                            أيفون</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" name="provider_last_version_ios"
                                                                value="{{ $settings['provider_last_version_ios'] ?? '' }}"
                                                                class="form-control" id="horizontal-whatsapp-input">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="horizontal-whatsapp-input"
                                                            class="col-sm-4 col-form-label fs-4">فرض
                                                            تحديث تطبيق المستخدم </label>
                                                        <div class="col-sm-8">
                                                            <select name="is_force_update_client" class="form-select">
                                                                <option @if (isset($settings['is_force_update_client']) && $settings['is_force_update_client'] == '1') selected @endif
                                                                    value="1">
                                                                    نعم</option>
                                                                <option @if (isset($settings['is_force_update_client']) && $settings['is_force_update_client'] == '0') selected @endif
                                                                    value="0">لا
                                                                </option>
                                                            </select>

                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="horizontal-whatsapp-input"
                                                            class="col-sm-4 col-form-label fs-4">فرض
                                                            تحديث تطبيق مزودي الخدمات
                                                        </label>
                                                        <div class="col-sm-8">
                                                            <select name="is_force_update_provider" class="form-select">
                                                                <option @if (isset($settings['is_force_update_provider']) && $settings['is_force_update_provider'] == '1') selected @endif
                                                                    value="1">
                                                                    نعم</option>
                                                                <option @if (isset($settings['is_force_update_provider']) && $settings['is_force_update_provider'] == '0') selected @endif
                                                                    value="0">لا
                                                                </option>
                                                            </select>

                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="horizontal-whatsapp-input"
                                                            class="col-sm-4 col-form-label fs-4">فرض
                                                            تحديث تطبيق مراقبي الرسائل
                                                        </label>
                                                        <div class="col-sm-8">
                                                            <select name="is_force_update_monitor" class="form-select">
                                                                <option @if (isset($settings['is_force_update_monitor']) && $settings['is_force_update_monitor'] == '1') selected @endif
                                                                    value="1">
                                                                    نعم</option>
                                                                <option @if (isset($settings['is_force_update_monitor']) && $settings['is_force_update_monitor'] == '0') selected @endif
                                                                    value="0">لا
                                                                </option>
                                                            </select>

                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                        </div> <!-- end col -->
                                    </div> <!-- end row -->
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-9 mx-auto my-md-3">
                                <button class="btn btn-primary w-100  fs-4">حفظ التغييرات</button>
                            </div>
                        </div>
                    </div>

                    </form>
                    {{-- Here you goo --}}


                    <form action="{{ route('payment.setting') }}" method="POST">
                        @csrf
                        <div class="row payment_settings d-none">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4 fs-3"> إعدادات الدفع </h4>
                                        <hr class="mb-4  my-2">


                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card">
                                                    <div class="card-body">

                                                        <div class="m-auto text-center my-5 p-2 ">
                                                            <svg viewBox="0 0 526.77502 140.375" height="30px"
                                                                xml:space="preserve" version="1.1" id="svg2">

                                                                <defs id="defs6" />
                                                                <g transform="matrix(1.25,0,0,-1.25,0,140.375)" id="g10">
                                                                    <g transform="scale(0.1,0.1)" id="g12">
                                                                        <path id="path14"
                                                                            style="fill:#283b82;fill-opacity:1;fill-rule:nonzero;stroke:none"
                                                                            d="m 505.703,1122.93 -327.781,0 c -22.434,0 -41.508,-16.3 -45.008,-38.45 L 0.34375,243.961 C -2.29297,227.383 10.5547,212.426 27.375,212.426 l 156.488,0 c 22.43,0 41.504,16.293 45.004,38.484 l 35.754,226.699 c 3.453,22.196 22.574,38.493 44.957,38.493 l 103.766,0 c 215.918,0 340.531,104.484 373.078,311.535 14.664,90.586 0.621,161.758 -41.797,211.603 -46.586,54.74 -129.215,83.69 -238.922,83.69 z M 543.52,815.941 C 525.594,698.324 435.727,698.324 348.832,698.324 l -49.461,0 34.699,219.656 c 2.063,13.278 13.563,23.055 26.985,23.055 l 22.668,0 c 59.191,0 115.031,0 143.882,-33.738 17.208,-20.133 22.481,-50.039 15.915,-91.356" />
                                                                        <path id="path16"
                                                                            style="fill:#283b82;fill-opacity:1;fill-rule:nonzero;stroke:none"
                                                                            d="m 1485.5,819.727 -156.96,0 c -13.37,0 -24.92,-9.778 -26.99,-23.055 l -6.94,-43.902 -10.98,15.914 c -33.98,49.32 -109.76,65.804 -185.39,65.804 -173.451,0 -321.599,-131.371 -350.451,-315.656 -15,-91.926 6.328,-179.828 58.473,-241.125 47.832,-56.363 116.273,-79.848 197.708,-79.848 139.76,0 217.26,89.86 217.26,89.86 l -7,-43.614 c -2.64,-16.679 10.21,-31.632 26.94,-31.632 l 141.38,0 c 22.48,0 41.46,16.297 45.01,38.484 l 84.83,537.234 c 2.69,16.536 -10.11,31.536 -26.89,31.536 z M 1266.71,514.23 c -15.14,-89.671 -86.32,-149.875 -177.09,-149.875 -45.58,0 -82.01,14.622 -105.401,42.325 -23.196,27.511 -32.016,66.668 -24.633,110.285 14.137,88.906 86.514,151.066 175.894,151.066 44.58,0 80.81,-14.808 104.68,-42.746 23.92,-28.23 33.4,-67.629 26.55,-111.055" />
                                                                        <path id="path18"
                                                                            style="fill:#283b82;fill-opacity:1;fill-rule:nonzero;stroke:none"
                                                                            d="m 2321.47,819.727 -157.73,0 c -15.05,0 -29.19,-7.477 -37.72,-19.989 L 1908.47,479.289 1816.26,787.23 c -5.8,19.27 -23.58,32.497 -43.71,32.497 l -155,0 c -18.84,0 -31.92,-18.403 -25.93,-36.137 L 1765.36,273.727 1602.02,43.1406 C 1589.17,24.9805 1602.11,0 1624.31,0 l 157.54,0 c 14.95,0 28.95,7.28906 37.43,19.5586 L 2343.9,776.828 c 12.56,18.121 -0.33,42.899 -22.43,42.899" />
                                                                        <path id="path20"
                                                                            style="fill:#469bdb;fill-opacity:1;fill-rule:nonzero;stroke:none"
                                                                            d="m 2843.7,1122.93 -327.83,0 c -22.38,0 -41.46,-16.3 -44.96,-38.45 L 2338.34,243.961 c -2.63,-16.578 10.21,-31.535 26.94,-31.535 l 168.23,0 c 15.62,0 29,11.402 31.44,26.933 l 37.62,238.25 c 3.45,22.196 22.58,38.493 44.96,38.493 l 103.72,0 c 215.96,0 340.53,104.484 373.12,311.535 14.72,90.586 0.58,161.758 -41.84,211.603 -46.54,54.74 -129.12,83.69 -238.83,83.69 z m 37.82,-306.989 C 2863.64,698.324 2773.78,698.324 2686.83,698.324 l -49.41,0 34.75,219.656 c 2.06,13.278 13.46,23.055 26.93,23.055 l 22.67,0 c 59.15,0 115.03,0 143.88,-33.738 17.21,-20.133 22.43,-50.039 15.87,-91.356" />
                                                                        <path id="path22"
                                                                            style="fill:#469bdb;fill-opacity:1;fill-rule:nonzero;stroke:none"
                                                                            d="m 3823.46,819.727 -156.87,0 c -13.47,0 -24.93,-9.778 -26.94,-23.055 l -6.95,-43.902 -11.02,15.914 c -33.98,49.32 -109.71,65.804 -185.34,65.804 -173.46,0 -321.55,-131.371 -350.41,-315.656 -14.95,-91.926 6.28,-179.828 58.43,-241.125 47.93,-56.363 116.27,-79.848 197.7,-79.848 139.76,0 217.26,89.86 217.26,89.86 l -7,-43.614 c -2.63,-16.679 10.21,-31.632 27.04,-31.632 l 141.34,0 c 22.38,0 41.46,16.297 44.96,38.484 l 84.88,537.234 c 2.58,16.536 -10.26,31.536 -27.08,31.536 z M 3604.66,514.23 c -15.05,-89.671 -86.32,-149.875 -177.09,-149.875 -45.49,0 -82.01,14.622 -105.4,42.325 -23.19,27.511 -31.92,66.668 -24.63,110.285 14.23,88.906 86.51,151.066 175.9,151.066 44.57,0 80.8,-14.808 104.67,-42.746 24.01,-28.23 33.5,-67.629 26.55,-111.055" />
                                                                        <path id="path24"
                                                                            style="fill:#469bdb;fill-opacity:1;fill-rule:nonzero;stroke:none"
                                                                            d="M 4008.51,1099.87 3873.97,243.961 c -2.63,-16.578 10.21,-31.535 26.94,-31.535 l 135.25,0 c 22.48,0 41.56,16.293 45.01,38.484 l 132.66,840.47 c 2.64,16.59 -10.2,31.59 -26.93,31.59 l -151.46,0 c -13.37,-0.04 -24.87,-9.83 -26.93,-23.1" />
                                                                    </g>
                                                                </g>
                                                            </svg>
                                                        </div>
                                                        <div class="row mb-4">
                                                            <label for="horizontal-facebook-input"
                                                                class="col-sm-4 col-form-label fs-4">PAYPAL_CLIENT_ID</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" name="PAYPAL_CLIENT_ID"
                                                                    value="{{ env('PAYPAL_CLIENT_ID') }}"
                                                                    class="form-control" id="horizontal-facebook-input">
                                                            </div>
                                                        </div>
                                                        <div class="row mb-4">
                                                            <label for="horizontal-twiter-input"
                                                                class="col-sm-4 col-form-label fs-4">PAYPAL_SECRET</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" name="PAYPAL_SECRET"
                                                                    value="{{ env('PAYPAL_SECRET') }}"
                                                                    class="form-control" id="horizontal-twiter-input">
                                                            </div>
                                                        </div>
                                                        <div class="row mb-4">
                                                            <label for="horizontal-payapal-input"
                                                                class="col-sm-4 col-form-label fs-4">
                                                                الدفع بواسطة بايبال
                                                                مفعل</label>
                                                            <div class="col-sm-8">
                                                                <select name="PAYPAL_ACTIVE" class="form-select"
                                                                    id="payapal">
                                                                    <option
                                                                        @if ($paymet_methods->where('method', 'paypal')->first()->active) selected @endif
                                                                        value="1">نعم</option>
                                                                    <option
                                                                        @if ($paymet_methods->where('method', 'paypal')->first()->active) selected @endif
                                                                        value="0">لا</option>
                                                                </select>

                                                            </div>
                                                        </div>
                                                        <div class="row mb-4">
                                                            <label for="horizontal-payapal-input"
                                                                class="col-sm-4 col-form-label fs-4">
                                                                بايبال ميتود</label>
                                                            <div class="col-sm-8">
                                                                <select name="PAYPAL_MODE" class="form-select"
                                                                    id="payapal">
                                                                    <option
                                                                        @if (env('PAYPAL_MODE') == 'live') selected @endif
                                                                        value="live">Live</option>
                                                                    <option
                                                                        @if (env('PAYPAL_MODE') == 'sandbox') selected @endif
                                                                        value="sandbox">Sandbox</option>
                                                                </select>

                                                            </div>
                                                        </div>
                                                        <div class="m-auto text-center my-5 p-2 ">
                                                            <svg xmlns="http://www.w3.org/2000/svg" height="50px"
                                                                viewBox="0 0 201.143 96.952">
                                                                <defs>
                                                                    <style>
                                                                        .a,
                                                                        .b {
                                                                            fill: #4cb4e7;
                                                                        }

                                                                        .b {
                                                                            font-size: 9px;
                                                                            font-family: Graphik-Medium, Graphik;
                                                                            font-weight: 500;
                                                                        }

                                                                    </style>
                                                                </defs>
                                                                <g transform="translate(-892.327 -960.8)">
                                                                    <g transform="translate(892.327 960.8)">
                                                                        <path class="a"
                                                                            d="M636.3,645.342l27.908-.212.038,5.083-27.908.212"
                                                                            transform="translate(-635.853 -585.615)" />
                                                                        <path class="a"
                                                                            d="M636.288,643.3l27.908-.212.038,5.083-27.908.212"
                                                                            transform="translate(-635.902 -592.046)" />
                                                                        <path class="a"
                                                                            d="M636.272,641.261l27.908-.212.039,5.083-27.908.212"
                                                                            transform="translate(-635.951 -598.483)" />
                                                                        <path class="a"
                                                                            d="M636.257,639.3l41.951-.318.039,5.083-41.951.318"
                                                                            transform="translate(-636 -604.994)" />
                                                                        <path class="a"
                                                                            d="M636.241,637.264l41.951-.318.038,5.083-41.951.318"
                                                                            transform="translate(-636.048 -611.424)" />
                                                                        <path class="a"
                                                                            d="M636.225,635.3l55.914-.424.038,5.083-55.914.424"
                                                                            transform="translate(-636.097 -617.936)" />
                                                                        <path class="a"
                                                                            d="M636.21,633.265l55.914-.424.038,5.083-55.914.424"
                                                                            transform="translate(-636.146 -624.369)" />
                                                                        <path class="a"
                                                                            d="M636.195,631.225l55.914-.424.039,5.083-55.914.424"
                                                                            transform="translate(-636.195 -630.801)" />
                                                                        <g transform="translate(56.167 33.392)">
                                                                            <path
                                                                                d="M649.717,639.364l4.926-.037.013,1.923a5.992,5.992,0,0,1,4.8-2.321,6.053,6.053,0,0,1,5.6,2.824,6.875,6.875,0,0,1,5.628-2.912c4.6-.034,6.9,2.708,6.936,7.393l.083,11.074-5.034.037-.079-10.421c-.017-2.43-.968-3.842-3.178-3.826-1.885.017-3.248,1.333-3.227,4.382l.075,9.914-5.034.037-.079-10.421c-.017-2.43-.968-3.842-3.178-3.825-1.881.017-3.248,1.333-3.223,4.382l.075,9.915-4.964.037Z"
                                                                                transform="translate(-649.717 -638.84)" />
                                                                            <path
                                                                                d="M663.88,656.323l-7.337-17.3,5.214-.04,4.538,11.148,4.079-11.214,5.142-.039-10.3,26.62-4.961.038Z"
                                                                                transform="translate(-628.191 -638.714)" />
                                                                        </g>
                                                                        <g transform="translate(56.53 56.942)">
                                                                            <path
                                                                                d="M649.8,644.834l14.954-.114.036,4.829-9.921.075.045,5.954,9.921-.075.036,4.829-9.921.075.082,10.892-5.033.038Z"
                                                                                transform="translate(-649.805 -643.848)" />
                                                                            <path
                                                                                d="M668.142,655.987a4.68,4.68,0,0,0-4.673-4.83,4.891,4.891,0,1,0,4.673,4.83m-14.338.146c-.05-6.426,4.635-9.474,8.727-9.507a7.243,7.243,0,0,1,5.412,2.1l-.013-1.778,4.959-.037.137,18.151-4.959.038-.017-2.031a7.056,7.056,0,0,1-5.449,2.438c-3.838.029-8.748-2.986-8.8-9.374"
                                                                                transform="translate(-637.196 -637.844)" />
                                                                            <path
                                                                                d="M658.852,651.158l3.078-.021-.042-5.823,4.96-.042.046,5.827,3.4-.029.033,4.469-3.4.025.042,5.773c.021,2.829.386,3.265,3.431,3.24l.033,4.648-.723,0c-5.757.046-7.655-1.9-7.7-7.821l-.046-5.807-3.078.021Z"
                                                                                transform="translate(-621.277 -642.107)" />
                                                                            <path
                                                                                d="M675.753,655.97a4.547,4.547,0,1,0-4.523,4.751,4.645,4.645,0,0,0,4.523-4.751m-13.939.1a9.379,9.379,0,1,1,9.449,9.37,9.382,9.382,0,0,1-9.449-9.37"
                                                                                transform="translate(-611.932 -638.04)" />
                                                                            <path
                                                                                d="M680.6,655.93a4.651,4.651,0,0,0-4.6-4.685,4.725,4.725,0,0,0,.071,9.441,4.656,4.656,0,0,0,4.527-4.756m-13.943.108a9.379,9.379,0,1,1,9.453,9.366,9.38,9.38,0,0,1-9.453-9.366"
                                                                                transform="translate(-596.676 -638.152)" />
                                                                            <path
                                                                                d="M671.561,646.663l4.926-.038.021,2.978a5.1,5.1,0,0,1,4.976-3.016l1.811-.017.033,4.831-2.965.021c-2.646.021-3.792,1.412-3.767,4.785l.067,8.569-4.964.038Z"
                                                                                transform="translate(-581.195 -638.011)" />
                                                                            <path
                                                                                d="M688.907,655.825A4.679,4.679,0,0,0,684.234,651a4.889,4.889,0,1,0,4.673,4.826m-14.338.146c-.05-6.426,4.636-9.474,8.727-9.5a7.228,7.228,0,0,1,5.412,2.1l-.016-1.782,4.964-.037.137,18.155-4.959.037-.017-2.035a7.045,7.045,0,0,1-5.449,2.438c-3.838.029-8.752-2.983-8.8-9.375"
                                                                                transform="translate(-571.713 -638.339)" />
                                                                            <g transform="translate(123.578)">
                                                                                <path
                                                                                    d="M679.761,671.064l-.2-26.5,6.367-.05.083,10.891a6.63,6.63,0,0,1,6.094-3.531c3.555-.029,6.1,2.226,6.135,6.957l.091,12.1-6.368.046-.083-10.812c-.013-2.1-.843-3.123-2.692-3.107-1.882.013-3.157,1.163-3.14,3.543l.079,10.421Z"
                                                                                    transform="translate(-679.557 -644.51)" />
                                                                            </g>
                                                                        </g>
                                                                    </g>
                                                                </g>
                                                            </svg>
                                                        </div>
                                                        <div class="row mb-4">
                                                            <label for="horizontal-facebook-input"
                                                                class="col-sm-4 col-form-label fs-4">MYFATOORAH_URL</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" name="MYFATOORAH_URL"
                                                                    value="{{ env('MYFATOORAH_URL') }}"
                                                                    class="form-control" id="horizontal-facebook-input">
                                                            </div>
                                                        </div>
                                                        <div class="row mb-4">
                                                            <label for="horizontal-twiter-input"
                                                                class="col-sm-4 col-form-label fs-4">MYFATOORAH_KEY
                                                            </label>
                                                            <div class="col-sm-8">
                                                                <input type="text" name="MYFATOORAH_KEY"
                                                                    value="{{ env('MYFATOORAH_KEY') }}"
                                                                    class="form-control" id="horizontal-twiter-input">
                                                            </div>
                                                        </div>
                                                        <div class="row mb-4">
                                                            <label for="horizontal-payapal-input"
                                                                class="col-sm-4 col-form-label fs-4">
                                                                الدفع بواسطة ماي فاتوراه
                                                                مفعل</label>
                                                            <div class="col-sm-8">
                                                                <select name="MYFATOORAH_ACTIVE" class="form-select"
                                                                    id="payapal">
                                                                    <option
                                                                        @if ($paymet_methods->where('method', 'myfatoorah')->first()->active) selected @endif
                                                                        value="1">نعم</option>
                                                                    <option
                                                                        @if (!$paymet_methods->where('method', 'myfatoorah')->first()->active) selected @endif
                                                                        value="0">لا</option>
                                                                </select>

                                                            </div>
                                                        </div>



                                                    </div>

                                                </div>
                                            </div> <!-- end col -->
                                        </div> <!-- end row -->
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-9 mx-auto my-md-3">
                                    <button class="btn btn-primary w-100  fs-4">حفظ التغييرات</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <form action="{{ route('email.setting') }}" method="POST">
                        @csrf
                        <div class="row email_settings d-none">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4 fs-3"> إعدادات الإميل </h4>
                                        <hr class="mb-4  my-2">


                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card">
                                                    <div class="card-body">

                                                        <div class="row mb-4">
                                                            <label for="horizontal-facebook-input"
                                                                class="col-sm-4 col-form-label fs-4">MAIL_HOST</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" name="MAIL_HOST"
                                                                    value="{{ env('MAIL_HOST') }}"
                                                                    class="form-control" id="horizontal-facebook-input">
                                                            </div>
                                                        </div>
                                                        <div class="row mb-4">
                                                            <label for="horizontal-twiter-input"
                                                                class="col-sm-4 col-form-label fs-4">MAIL_USERNAME</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" name="MAIL_USERNAME"
                                                                    value="{{ env('MAIL_USERNAME') }}"
                                                                    class="form-control" id="horizontal-twiter-input">
                                                            </div>
                                                        </div>
                                                        <div class="row mb-4">
                                                            <label for="horizontal-facebook-input"
                                                                class="col-sm-4 col-form-label fs-4">MAIL_PASSWORD</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" name="MAIL_PASSWORD"
                                                                    value="{{ env('MAIL_PASSWORD') }}"
                                                                    class="form-control" id="horizontal-facebook-input">
                                                            </div>
                                                        </div>
                                                        <div class="row mb-4">
                                                            <label for="horizontal-twiter-input"
                                                                class="col-sm-4 col-form-label fs-4">MAIL_PORT</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" name="MAIL_PORT"
                                                                    value="{{ env('MAIL_PORT') }}"
                                                                    class="form-control" id="horizontal-twiter-input">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-9 mx-auto my-md-3">
                                                            <button class="btn btn-primary w-100  fs-4">حفظ
                                                                التغييرات</button>
                                                        </div>
                                                    </div>
                                                </div>
                    </form>

                </div>
            </div>
        </div>

    </div> <!-- container-fluid -->
    </div>
@endsection
