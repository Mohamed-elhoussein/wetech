<?php
return [
    [
        'icon'  => 'bx bx-home',
        'title' =>  'نظرة عامة',
        'route' => '/dashbord',

    ],
    [
        'image'  => '/assets/icons/skill.svg',
        'title' =>  'المهارات',
        'route' => '/skills',

    ],
    [
        'image'  => '/assets/icons/identity.svg',
        'title' =>  'الهويات',
        'route' => '/identities',

    ],

    [
        'icon'  => 'bx bx-user',
        'title' =>  'الاعظاء',
        'children' => [
            [
                'title'  => 'المشرفين',
                'route'  => '/user/admins',
            ],
            [
                'title'  => 'مزودي الخدمات',
                'route'  => '/user/providers',
            ],
            [
                'title'  => 'المستخدمين',
                'route'  => '/user/users',
            ],
            [
                'title'  => 'مراقبوا الرسائل',
                'route'  => '/user/chat/review',
            ],
        ],
        'policy' => 'access_to_users',
        'name'  => 'الولوج للأعضاء'
    ],
    [
        'icon'  => 'bx bx-share-alt',
        'title' =>  'الشرائح',
        'route' => '/slider',
        'policy' => 'access_to_sliders',
        'name'  => 'الولوج للشرائح'
    ],
    [
        'icon'  => 'bx bxl-joomla  ',
        'title' =>  'المنتوجات',
        'children' => [
            [
                'title'  => 'جميع المنتوجات',
                'route'  => '/product',
            ],
            [
                'title'  => 'تصنيفات المنتجات',
                'route'  => '/product-categories',
            ],
            [
                'title'  => 'انواع المنتجات',
                'route'  => '/product-types',
            ],
        ],
        'policy' => 'access_to_products',
        'name'  => 'الولوج للمنتوجات'
    ],
    [
        'icon'  => ' bx bx-world ',
        'title' =>  'الدول',
        'route' => '/countries',
        'policy' => 'access_to_countries',
        'name'  => 'الولوج للدول'
    ],

    [
        'icon'  => 'bx bxs-city',
        'title' =>  'المدن',
        'route' => '/cities',
        'policy' => 'access_to_cities',
        'name'  => 'الولوج للمدن'
    ],
    [
        'icon'  => 'bx bx-map',
        'title' =>  'الأحياء',
        'route' => '/street',
        'policy' => 'access_to_street',
        'name'  => 'الولوج للأحياء'
    ],
    [
        'icon'  => 'bx bx-message-alt-dots',
        'title' =>  'رسائل الترحيب',
        'children' => [
            [
                'title'  => 'رسائل الترحيب الزبناء',
                'route'  => '/welcome/users',
            ],
            [
                'title'  => 'رسائل الترحيب المزودين',
                'route'  => '/welcome/providers',
            ],
        ],
        'policy' => 'access_to_welcome',
        'name'  => 'الولوج لرسائل الترحيب'
    ],
    [
        'icon'  => 'bx bx-slideshow',
        'title' =>  'العرض السريع',
        'route' => '/quick_offers',
        'policy' => 'access_to_quick_offers',
        'name'  => 'الولوج للعروض السريعة'
    ],
    [
        'icon'  => 'bx bx-star',
        'title' =>  ' تقييمات التطبيق ',
        'route' => '/app/rates',
        'policy' => 'access_to_app_rates',
        'name'  => 'الولوج لتقييمات التطبيق'
    ],
    [
        'icon'  => 'bx bx-star',
        'title' =>  ' تقييمات الخدمات ',
        'route' => '/provider/service/rates',
        'policy' => 'access_to_service_rates',
        'name'  => 'الولوج لتقييمات الخدمات'
    ],
    [
        'icon'  => 'bx bx-table',
        'title' =>  'الخدمات',
        'children' => [
            [
                'title'  => 'الخدمات',
                'route'  => '/services',
            ],
            [
                'title'  => 'أنواع الخدمة',
                'route'  => '/service-types',
            ],
            [
                'title'  => 'تصنيف الخدمات',
                'route'  => '/service/category',
            ],
            [
                'title'  => 'التصنيف الفرعي للخدمات',
                'route'  => '/service/subcategories',
            ],
            [
                'title'  => 'التصنيف الفرعي الثاني للخدمات',
                'route'  => '/service/sub2',
            ],
            [
                'title'  => ' التصنيف الفرعي الثالث للخدمات',
                'route'  => '/service/sub3',
            ],
            [
                'title'  => '  التصنيف الفرعي الرابع للخدمات',
                'route'  => '/service/sub4',
            ],
        ],

        'policy' => 'access_to_services',
        'name'  => 'الولوج للخدمات وتصنيفاتها'
    ],





    [
        'icon'  => 'bx bx-globe',
        'title' =>  'خدمات المزودين',
        'route' => '/providers/services',
        'policy' => 'access_to_providers_services',
        'name'  => 'الولوج لخدمات المزودين'
    ],
    [
        'icon'  => 'bx bx-notepad',
        'title' =>  'الطلبات',
        'route' => '/orders',
        'policy' => 'access_to_orders',
        'name'  => 'الولوج للطلبات'
    ],
    [
        'icon'  => 'bx bxs-folder-open',
        'title' =>  'البلاغات',
        'route' => '/reports',
        'policy' => 'access_to_reports',
        'name'  => 'الولوج للبلاغات'
    ],
    [
        'icon'  => 'bx bx-money',
        'title' =>  'التحويلات',
        'route' => '/transactions',
        'policy' => 'access_to_transactions',
        'name'  => 'الولوج للتحويلات'
    ],
    [
        'icon'  => 'bx bx-money',
        'title' =>  'المدفوعات',
        'route' => '/payments',
        'policy' => 'access_to_payments',
        'name'  => 'الولوج للمدفوعات'
    ],

    [
        'icon'  => 'bx bx-money',
        'title' =>  'الإشتراكات',
        'route' => '/subscribers',
        'policy' => 'access_to_subscribers',
        'name'  => 'الولوج للإشتراكات '
    ],
    [
        'icon'  => 'bx bx-money',
        'title' =>  'باقات الإشتراك',
        'route' => '/subscribes/packes',
        'policy' => 'access_to_subscriber_packes',
        'name'  => 'الولوج لباقات الإشتراك '
    ],
    [
        'icon'  => 'bx bx-money',
        'title' =>  'طلبات السحب ',
        'route' => '/withdraw',
        'policy' => 'access_to_withdraws',
        'name'  => 'الولوج لطلبات السحب '
    ],    [
        'icon'  => 'bx bx-directions',
        'title' =>  'الأسئلة الشائعة',
        'route' => '/faq',
        'policy' => 'access_to_faq',
        'name'  => 'الولوج للأسئلة الشائعة'
    ],
    [
        'icon'  => 'bx bxs-cog',
        'title' =>  'الاعدادات',
        'route' => '/settings',
        'policy' => 'access_to_settings',
        'name'  => 'الولوج للاعدادات'
    ],
    [
        'icon'  => 'bx bx-pulse',
        'title' =>  'الإحصائيات',
        'route' => '/statistic',
        'policy' => 'access_to_statistic',
        'name'  => 'الولوج للإحصائيات'
    ],
    [
        'icon'  => 'bx bx-customize ',
        'title' =>  'النسخ الإحتياطية',
        'route' => '/backups',
        'policy' => 'access_to_backup',
        'name'  => 'الولوج للنسخ الإحتياطية'
    ],
    [
        'icon'  => 'bx bx-data  ',
        'title' =>  ' إرسال الإشعارات',
        'route' => '/notify',
        'policy' => 'access_to_app_notificattion',
        'name'  => 'الولوج لإرسال الإشعارات'
    ],
    [
        'icon'  => 'bx bx-data  ',
        'title' =>  'الترجمة',
        'route' => '/translate',
        'policy' => 'access_to_translate',
        'name'  => 'الولوج للترجمة'
    ],
    [
        'icon'  => 'bx bx-data  ',
        'title' =>  'متجر الصيانة',
        'route' => '/maintenance-store',
        // 'policy' => 'access_to_translate',
        'name'  => 'الولوج للترجمة'
    ]
];
