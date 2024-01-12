<?php

return  [

"config" => [
"services" => [
[
"id" => "1",
"name" => "خدمات الصيانة",
"name_en" => "Maintenance Services",
"target" => "5",
"join_option" => "1",
"icon" => "https://server.drtechapp.com/storage/images/tablet-phone-and-browser.svg",
"order_index" => "0",
"status" => "active",
"active" => false
],
[
"id" => "2",
"name" => "صيانة الكمبيوترات",
"name_en" => "Computer maintenance",
"target" => "4",
"join_option" => "0",
"icon" => "https://server.drtechapp.com/storage/images/laptop.svg",
"order_index" => "0",
"status" => "active",
"active" => false
],
[
"id" => "3",
"name" => "صيانة الماك",
"name_en" => "Mac maintenance",
"target" => "3",
"join_option" => "0",
"icon" => "https://server.drtechapp.com/storage/images/apple.svg",
"order_index" => "0",
"status" => "active",
"active" => false
],
[
"id" => "4",
"name" => "الانظمة و التقنية",
"name_en" => "systems and technology ",
"target" => "2",
"join_option" => "0",
"icon" => "https://server.drtechapp.com/storage/images/camera.svg",
"order_index" => "0",
"status" => "inactive",
"active" => false
],
],
"welcome" => [
[
"id" => "1",
"titel" => "احجز بسهولة",
"titel_en" => "",
"body" => "بامكانك الان الحجز باستخدام التطبيق لكل الخدمات التقنية و التكنلوجيا 1",
"body_en" => "",
"image" => "https://server.drtechapp.com/storage/images/intro1.jpg"
],
[
"id" => "2",
"titel" => "احجز بسهولة",
"titel_en" => "",
"body" => "بامكانك الان الحجز باستخدام التطبيق لكل الخدمات التقنية و التكنلوجيا 2",
"body_en" => "",
"image" => "https://server.drtechapp.com/storage/images/intro2.jpg"
],
[
"id" => "3",
"titel" => "احجز بسهولة",
"titel_en" => "",
"body" => "بامكانك الان الحجز باستخدام التطبيق لكل الخدمات التقنية و التكنلوجيا 3",
"body_en" => "",
"image" => "https://server.drtechapp.com/storage/images/intro4.png"
]
],
"countries" => [
[
"id" => "4",
"name" => "الجزائر",
"code" => "DZ",
"country_code" => "+213",
"status" => "ACTIVE",
"message" => "للاسف الخدمة غير متوفرة بعد في دولتك . يمكنك التوصال معنا لمعلومات اكثر حول توفير خدمت دكتور تك في دولتك",
"pin" => "PINED",
"cities" => [
[
"id" => "1",
"name" => "الجزائر",
"name_en" => "",
"country_id" => "4"
],
[
"id" => "2",
"name" => "البويرة",
"name_en" => "",
"country_id" => "4"
],
[
"id" => "8357",
"name" => "الجزائر",
"name_en" => "",
"country_id" => "4"
],
[
"id" => "8358",
"name" => "البويرة",
"name_en" => "",
"country_id" => "4"
],
[
"id" => "8359",
"name" => "الجزائر",
"name_en" => "",
"country_id" => "4"
],
[
"id" => "8360",
"name" => "البويرة",
"name_en" => "",
"country_id" => "4"
],
[
"id" => "8361",
"name" => "الجزائر",
"name_en" => "",
"country_id" => "4"
],
[
"id" => "8362",
"name" => "البويرة",
"name_en" => "",
"country_id" => "4"
]
]
],
[
"id" => "63",
"name" => "مصر",
"code" => "EG",
"country_code" => "+20",
"status" => "ACTIVE",
"message" => "للاسف الخدمة غير متوفرة بعد في دولتك . ",
"pin" => "PINED",
"cities" => [
]
],
[
"id" => "109",
"name" => "الأردن",
"code" => "JO",
"country_code" => "+962",
"status" => "ACTIVE",
"message" => "للاسف الخدمة غير متوفرة بعد في دولتك . ",
"pin" => "PINED",
"cities" => [
]
],
[
"id" => "191",
"name" => "السعودية",
"code" => "SA",
"country_code" => "+966",
"status" => "ACTIVE",
"message" => "للاسف الخدمة غير متوفرة بعد في دولتك . ",
"pin" => "PINED",
"cities" => [
[
"id" => "8363",
"name" => "مكة",
"name_en" => "",
"country_id" => "191"
],
[
"id" => "8364",
"name" => "جدة",
"name_en" => "",
"country_id" => "191"
]
]
],
[
"id" => "206",
"name" => "السودان",
"code" => "SD",
"country_code" => "+249",
"status" => "ACTIVE",
"message" => "للاسف الخدمة غير متوفرة بعد في دولتك . ",
"pin" => "UNPINED",
"cities" => [
[
"id" => "8365",
"name" => "الخرطوم",
"name_en" => "",
"country_id" => "206"
]
]
],
[
"id" => "231",
"name" => "الولايات المتحدة",
"code" => "US",
"country_code" => "+1",
"status" => "ACTIVE",
"message" => "للاسف الخدمة غير متوفرة بعد في دولتك . ",
"pin" => "UNPINED",
"cities" => [
]
]
],
"slider" => [
[
"id" => "1",
"image" => "https://server.drtechapp.com/storage/images/slider_2.jpg",
"text" => "إتصل بالمعلن",
"text_en" => "Call advertiser",
"url" => "tel:+966 92 111 8035",
"icon" => "phone_faw",
"visitableBtn" => "true",
"target" => "HOME",
"created_at" => "2021-07-06 10:01:52"
],
[
"id" => "9",
"image" => "https://server.drtechapp.com/storage/images/slide_1.jpg",
"text" => "إتصل بالمعلن",
"text_en" => "Call advertiser",
"url" => "tel:+966 92 000 8035",
"icon" => "phone_fawe",
"visitableBtn" => "false",
"target" => "HOME",
"created_at" => "2021-07-06 10:01:52"
],
],
"shearing" => [




],
"provider_store_app_link" =>
[
"url_ios" =>  "https://apps.apple.com/us/app/%D8%AF%D9%83%D8%AA%D9%88%D8%B1-%D8%AA%D9%83/id1451445517",
"url_android" =>  "https://play.google.com/store/apps/details?id=app.android.doctortecapp.com"
],
"client_store_app_link" =>
[
"url_ios" =>  "https://apps.apple.com/us/app/%D8%AF%D9%83%D8%AA%D9%88%D8%B1-%D8%AA%D9%83/id1451445517",
"url_android" =>  "https://play.google.com/store/apps/details?id=app.android.doctortecapp.com"
],
"contects" => [
[
"id" => "1",
"contect" => "doctortecapp@gmail.com",
"name" => "7",
"icon" => "email_mco"
],
[
"id" => "2",
"contect" => "920008035",
"name" => "6",
"icon" => "mobile_fou"
]
],
"settings" => [
[
"id" => "1",
"name" => "code_to_phone",
"type" => "BOOL",
"value" => "0",
"options" => ""
],
[
"id" => "2",
"name" => "resend_time",
"type" => "INT",
"value" => "60",
"options" => ""
],
[
"id" => "3",
"name" => "tax",
"type" => "INT",
"value" => "15",
"options" => ""
],
[
"id" => "4",
"name" => "provider_under_maintenance_show_webview",
"type" => "BOOL",
"value" => "false",
"options" => ""
],
[
"id" => "5",
"name" => "client_under_maintenance_show_webview",
"type" => "BOOL",
"value" => "false",
"options" => ""
],
[
"id" => "6",
"name" => "webview_url_provider",
"type" => "TEXT",
"value" => "https://docs.google.com/forms/d/e/1FAIpQLScgp5jxM438f2Mk9QaRyS2AsmKDgupB2XcJeVKUe2Y_M1IGIQ/viewform",
"options" => ""
],
[
"id" => "7",
"name" => "webview_url_client",
"type" => "TEXT",
"value" => "https://docs.google.com/forms/d/e/1FAIpQLScgp5jxM438f2Mk9QaRyS2AsmKDgupB2XcJeVKUe2Y_M1IGIQ/viewform",
"options" => ""
],
[
"id" => "8",
"name" => "not_original",
"type" => "BOOL",
"value" => "false",
"options" => ""
],
[
"id" => "9",
"name" => "client_last_version_android",
"type" => "TEXT",
"value" => "1.0.3",
"options" => ""
],
[
"id" => "10",
"name" => "client_last_version_ios",
"type" => "TEXT",
"value" => "75",
"options" => ""
],
[
"id" => "11",
"name" => "provider_last_version_android",
"type" => "TEXT",
"value" => "1.0.3",
"options" => ""
],
[
"id" => "12",
"name" => "provider_last_version_ios",
"type" => "TEXT",
"value" => "75",
"options" => ""
],
[
"id" => "13",
"name" => "default_commission",
"type" => "DOUBLE",
"value" => "20.0",
"options" => ""
],
[
"id" => "14",
"name" => "is_force_update_provider",
"type" => "int",
"value" => "0",
"options" => ""
],
[
"id" => "15",
"name" => "is_force_update_client",
"type" => "int",
"value" => "0",
"options" => ""
],
],
"header" => [
"Cookie" => "PHPSESSID=05c482071b05b1c3321b804e380c5336",
"Connection" => "keep-alive",
"Accept-Encoding" => "gzip, deflate, br",
"Host" => "server.drtechapp.com",
"Postman-Token" => "04a4b86a-9fc5-4734-8899-f1bdc6530e5e",
"Accept" => "*/*",
"User-Agent" => "PostmanRuntime/7.28.4"
],
"os" => "",
"build_number" => "",
"app_version" => ""
],
"localisation" => [
"languages_names" => [
"ar,SA" => "العربية",
"en,US" => "English"
],
"data" => [




],
"default" => "ar,SA"
]
];

