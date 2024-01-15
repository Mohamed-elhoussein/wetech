<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         \App\Models\User::create([

        'email' => 'mohamed.elhoussein.a@gmail.com',
        'username' => 'admin',
        'number_phone' => '00000000',
        'role' => 'admin',
        'password' => Hash::make('admin'),
        'permissions'=>[
                        'access_to_users',
                        'access_to_sliders',
                        'access_to_countries',
                        'access_to_cities',
                        'access_to_street',
                        'access_to_welcome',
                        'access_to_quick_offers',
                        'access_to_services',
                        'access_to_providers_services',
                        'access_to_orders',
                        'access_to_reports',
                        'access_to_transactions',
                        'access_to_faq',
                        'access_to_settings',
                        'access_to_statistic',
                        'access_to_backup',
                        ]
        ]);
    }
}
