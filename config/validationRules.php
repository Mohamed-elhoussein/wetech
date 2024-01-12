<?php

use App\Enum\ReportStatus;

return [

    'user.create' => [
        'email'              => ['required', 'string', 'email', 'max:255'],
        'username'           => ['string', 'max:255'],
        'number_phone'       => ['required', 'string', 'max:255', 'unique:users'],
        'city_id'            => ['int'],
        'street_id'          => ['int'],
        'avatar'             => ['mimes:jpg,png,jpeg'],
        'balance'            => ['float']
    ],

    'user.login' => [
        'role'               => ['required', 'string', 'max:255'],
        'country'            => ['required', 'string', 'max:255'],
        'number_phone'       => ['required', 'string', 'max:255'],

    ],
    'user.update' => [
        'email'              => ['regex:/(.+)@(.+)\.(.+)/i', 'max:200'],
        'username'           => ['string', 'max:255'],
        'number_phone'       => ['string', 'max:255', 'unique:users'],
        'city_id'            => ['int'],
        'street_id'          => ['int'],
        'avatar'             => ['mimes:jpg,png,jpeg'],
        'balance'            => ['float'],
        'role'               => ['string'],
    ],
    'provider.create' => [
        // 'email'               => ['email', 'max:255'],
        'first_name'          => ['required', 'string', 'max:255'],
        'second_name'         => ['required', 'string', 'max:255'],
        'last_name'           => ['required', 'string', 'max:255'],
        // 'friend_number'       => ['required', 'string', 'max:255'],
        'identity.*'          => ['required', 'mimes:jpg,png,jpeg'],
    ],
    'provider.create_' => [
        'email'               => ['email', 'max:255'],
        'first_name'          => ['required', 'string', 'max:255'],
        'second_name'         => ['required', 'string', 'max:255'],
        'last_name'           => ['required', 'string', 'max:255'],
        'friend_number'       => ['required', 'string', 'max:255'],
        'country_id'          => ['required', 'string', 'max:255'],
        'number_phone'        => ['required', 'string', 'max:255'],
        'identity'            => ['required']

    ],
    'service.create' => [
        'name'          => ['required', 'string', 'max:255'],
        'name_en'       => ['string', 'max:255'],
        'description'   => ['string'],
        'order_index'   => ['int'],


    ],
    'chat_review.create' => [
        'username'            => ['required', 'string', 'max:255'],
        'number_phone'        => ['required', 'string', 'max:255'],
        'email'               => ['email', 'max:255'],
        'country_id'          => ['required', 'max:255'],

    ],
    'services.update' => [
        'name'                  => ['string'],
        'image'                 => ['mimes:jpg,png,jpeg'],
        'description'           => ['string'],

    ],
    'service.category.create' => [
        'service_id'        => ['int'],
        'name'              => ['string'],

    ],
    'service.subcategory.create' => [
        'name'                  => ['required', 'string'],
        'service_categories_id' => ['required', 'int'],
    ],
    'service.sub2.create' => [
        'name'                     => ['required', 'string'],
        'service_subcategories_id' => ['required', 'int'],
    ],
    'service.sub3.create' => [
        'name'                  => ['required', 'string'],
        'service_sub2_id'       => ['required', 'int'],
    ],
    'service.sub4.create' => [
        'name'                  => ['required', 'string'],
        'service_sub3_id'       => ['required', 'int'],
        'name'                  => ['string'],

    ],

    'providerservices.create' => [
        // 'title'                         => ['required', 'string'],
        'service_id'                    => ['required', 'int'],
        'service_categories_id'         => ['int'],
        'service_subcategories_id'      => ['int'],
        'image_0'                       => ['mimes:jpg,png,jpeg'],
        'description'                   => ['string'],
    ],
    'providerservices.update' => [
        'title'                         => ['string'],
        'description'                   => ['string'],

        'offers.*.details'              => ['string']

    ],
    'orders.create' => [
        'offer_id'        => ['required', 'int'],
    ],
    'orders.update' => [

        'canceled_by'    => ['int']
    ],
    'chat.create'  => [
        'user_id'        => ['required', 'int'],
        'provider_id'    => ['required', 'int'],
        'send_by'        => ['required', 'int'],
    ],
    'rating.create'  => [
        'order_id'       => ['required', 'int'],
        'rated_by'       => ['required', 'int'],
        'rate'           => ['numeric']
    ],
    'rating.update'  => [
        'order_id'      => ['required', 'int'],
        'rated_by'      => ['required', 'int'],
        'rate'          => ['numeric']
    ],
    'app.rates'   => [
        'user_id'      => ['int'],
        'stars'        => ['numeric'],
        'rate'         => ['numeric']
    ],
    'offer'         => [
        'description'   => ['string'],

    ],
    'offer.status'   => [
        'status'           => ['required', 'in:REJECTED,ACCEPTED,CANCELED'],
    ],
    'report.create' => [
        'title'             => ['required', 'string'],
    ],
    'service.offer' => [
        'service_id'    => ['required', 'int'],
    ],
    'faq.create' => [
        'title' => ['required']
    ],
    'country.create' => [
        'name' => ['required', 'string'],
        'unit' => ['required', 'string'],
        'country_code' => ['required']
    ],
    'cities.create' => [
        'name'          => ['required', 'string'],
        'country_id'    => ['int']
    ],
    'street.create' => [
        'name'          => ['required', 'string'],
        'city_id'    => ['int']
    ],
    'product.api.create' => [
        'user_id'       => ['required', 'string'],
        'city_id'          => ['required', 'string'],
        'catigory'      => ['required', 'string'],
        'type'          => ['required', 'string'],
        'name'          => ['required', 'string'],
        'name_en'       => ['required', 'string'],
        'color'         => ['required', 'string'],
        'price'         => ['required', 'string'],
        'description'   => ['required', 'string'],
    ],
    'product.create' => [
        'user_id' => ['required', 'string'],
        'name' => ['required', 'string'],
    ],
    'reports.status.update' => [
        'status' => [
            'required',
            'string',
            'in:' . implode(',', ReportStatus::toArray())
        ],
    ],
    'providers.import' => [
        'file' => [
            'required',
            'file',
            'max:30000',
            'mimetypes:text/csv,text/plain,application/csv,text/comma-separated-values,text/anytext,application/octet-stream,application/txt'
        ]
    ]
];
